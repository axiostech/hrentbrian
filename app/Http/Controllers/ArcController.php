<?php

namespace App\Http\Controllers;
use App\Arc;
use App\Curlec;
use App\Idtype;
use App\Profile;
use App\Tenancy;
use App\User;
use App\Jobs\SendArcForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\FilterPhoneNumber;
use App\Traits\GenerateArcUrlContent;
use App\Traits\RunningNum;

use Log;

class ArcController extends Controller
{
    use FilterPhoneNumber, GenerateArcUrlContent, RunningNum;

	protected $mandate_return_url, $mandate_callback_url;

    public function __construct(){
        $this->mandate_return_url = url('arc/mandate-return');
        $this->mandate_callback_url = url('arc/mandate-callback');

        $this->middleware('auth', ['except' => ['indexArcForm','createMandate','mandateReturn','mandateCallback']]);
    }

    // return arc api to tenant
    public function indexArcForm($token)
    {
        $token_data = json_decode(decrypt($token), true);
        $arc = Arc::findOrFail($token_data['arc_id']);
        $user_id = $token_data['user_id'];
        $form_return = false;

        // update arc_status
        $arc->status = 2;
        $arc->save();

        $data = [
            'mandate_banks' => $this->getMandateBanks(),
            'arc' => $arc,
            'user_id' => $user_id,
            'form_return' => $form_return
        ];

        return view('arc.form_tenant', compact('data'));
    }

    // trigger arc creation and send arc form(int tenancy_id, String channel ['email', 'whatsapp', 'sms'])
    public function createAndSendArcForm($tenancy_id, $channel = null)
    {
        $tenancy = Tenancy::findOrFail($tenancy_id);
        $search_arc = Arc::where('ref_number', $tenancy->code)->first();

        if($search_arc) {
            $arc = $search_arc;
        }else {
            $arc = new Arc();
            $arc->tenancy_id  = $tenancy->id;
            $arc->ref_number = $tenancy->code;
            $arc->name = $tenancy->tenant->name;
            $arc->email = $tenancy->tenant->email;
            $arc->amount = $tenancy->rental;
            $arc->creator_id = auth()->user()->id;
            $arc->profile_id = auth()->user()->profile->id;
            $arc->idtype_id = $tenancy->tenant->idtype_id;
            $arc->id_value = $tenancy->tenant->id_value;
            $arc->save();
        }

        if($arc) {
            // update tenancy arc status
            if($arc->status <= 1) {
                $arc->status = 1;
                $arc->save();
            }

            SendArcForm::dispatch($arc->id, $channel);
            if($channel == 'whatsapp') {
                $url = $this->generateArcUrl($arc->id);
                $message = $this->generateArcMessage($arc->id, $url);
                $phone_number_noplus = $this->removePhoneNumberPlus($arc->tenancy->tenant->phone_number);
                return [
                    'message' => $message,
                    'phone_number_noplus' => $phone_number_noplus,
                ];
            }
        }
    }

	public function createMandate(){
        request()->validate([
            'ref_number' => 'required',
            'email' => 'required',
            'idtype_id' => 'required',
            'id_value' => 'required',
            'bank_code' => 'required'
        ], [
            'bank_code.required' => 'Please choose a Bank'
        ]);

        $ref_number = request('ref_number');
        $name = request('name');
        $email = request('email');
        $amount = request('amount');
        $idtype_id = request('idtype_id');
        $id_value = request('id_value');
        $bank_code = request('bank_code');
        $user_id = request('user_id');
        $today_date = Carbon::today()->toDateString();

        $arc = Arc::where('ref_number', $ref_number)->first();
        $idtype = Idtype::where('id', $idtype_id)->first();
        $user = User::find($user_id);
        $address = substr($arc->tenancy->tenant->unit_number.'-'.$arc->tenancy->tenant->block_number.'-'.$arc->tenancy->tenant->address, 0, 26);
        $normal_phone_number = $this->filterMalaysiaStandardPhoneNumber($arc->tenancy->tenant->phone_number);

        $curlec = new Curlec();
        $create = $curlec->setReferenceNumber($ref_number)
                ->setAmount($amount)
                ->setEffectiveDate($today_date)
                ->setExpiryDate('')
                ->setName($name)
                ->setEmailAddress($email)
                ->setPhoneNumber($normal_phone_number)
                ->setPurposeOfPayment($address)
                ->setIdType($idtype->value)
                ->setIdValue($id_value)
                ->setBankId($bank_code)
                ->setLinkId($user->name)
                ->setMerchantUrl($this->mandate_return_url)
                ->setMerchantCallbackUrl($this->mandate_callback_url)
                ->generateMandateUrl();

        Arc::where('ref_number', $ref_number)->update([
            'email'=>  $email,
            'bank_code' => $bank_code,
            'idtype_id' => $idtype_id,
            'id_value' => $id_value
        ]);

        return redirect($create);

    }


    public function makeCollection(Request $request){

    	$curlec = new Curlec();

    	$arc = Arc::where('tenancy_id',request('tenancy_id'))->first();

        $create_collection  = $curlec->setList(array(
			                	'refNum' => $arc->ref_number,
			                    'amount' => $arc->amount
			                ))->createCollection();

        Log::info('--current create collection'.json_encode($create_collection));

        if(!empty($create_collection['Status'][0])){
            if($create_collection['Status'][0] == 201 || $create_collection['Status'][0] == '201'){
                $response = $create_collection['Response'][0];
                $batch_id = $response['batch_id'][0];

                return true;

            }

            if($create_collection['Status'][0] == 409 || $create_collection['Status'][0] == '409'){
                $Message = $create_collection['Message'][0];

                return false;
            }

        }

        return false;
    }

   	public function mandateReturn(Request $request){
        //    dd($request->all());
        Log::info('response in mandate return'.json_encode($request->all()));

        $ref_number = request('fpx_sellerOrderNo');
        $mandate_status = request('fpx_debitAuthCode');
        $fpx_transaction_id = request('fpx_fpxTxnId');
        $ex_order_no = request('fpx_sellerExOrderNo');

        $arc = Arc::where('ref_number', $ref_number)->first();
        $arc->update([
            'mandate_status' => $mandate_status,
            'fpx_transaction_id' => $fpx_transaction_id,
            'ex_order_no' => $ex_order_no
        ]);

        // update arc_status
        if($mandate_status == '00') {
            $arc->status = 3;
        }else {
            $arc->status = 4;
        }
        $arc->save();

        $data = [
            'mandate_banks' => $this->getMandateBanks(),
            'arc' => $arc,
            'user_id' => $arc->created_by->id
        ];

        return view('arc.form_tenant', compact('data'));
    }

    public function mandateCallback(Request $request){
        // dd($request->all());
        try{
            Log::info('response in mandate callback'.json_encode($request->all()));

            $ref_number = request('fpx_sellerOrderNo');
            $mandate_status = request('fpx_debitAuthCode');
            $fpx_transaction_id = request('fpx_fpxTxnId');
            $ex_order_no = request('fpx_sellerExOrderNo');
            $enrp_status = request('enrp_status_code');
            $enrp_status_updated_at = request('enrp_status_code') ? Carbon::now() : null;
            $fieldsArr = [];

            $arc = Arc::whereNotNull('created_at');

            if($ref_number) {
                $arc = $arc->where('ref_number', $ref_number);
            }

            if($ex_order_no) {
                $arc = $arc->where('ex_order_no', $ex_order_no);
            }

            $arc = $arc->first();

            if($ref_number or $mandate_status or $fpx_transaction_id) {
                $fieldsArr['mandate_status'] = $mandate_status;
                $fieldsArr['fpx_transaction_id'] = $fpx_transaction_id;
                if($mandate_status == '00') {
                    $fieldsArr['status'] = 3;
                }else {
                    $fieldsArr['status'] = 4;
                }
            }

            if($enrp_status) {
                $fieldsArr['enrp_status'] = $enrp_status;
                $fieldsArr['enrp_status_updated_at'] = $enrp_status_updated_at;
                if($enrp_status == '00') {
                    $fieldsArr['status'] = 5;
                }else {
                    $fieldsArr['status'] = 6;
                }
            }

            $arc->update($fieldsArr);



            if($request->Status[0] == '200') {
                $response = $request->Response[0];
                $arc_find = Arc::where('ex_order_no', $response->transaction_reference[0])->first();

                if($arc_find) {
                    if ($response->enrp_status[0] == 'Approved' or $response->enrp_status[0] == '00') {
                        $arc_find->enrp_status = '00';
                        $arc_find->status = 5;
                    } else {
                        $arc_find->status = 6;
                    }
                    $arc_find->enrp_status_updated_at = Carbon::now();
                    $arc_find->save();
                }
            }

        }catch(Exception $e){
            Log::info('error in mandate callback'.json_encode($e->getMessage()));
        }
    }

    // return banks for mandate
    private function getMandateBanks()
    {
        $curlec = new Curlec();
        $mandate_banks = $curlec->getMandatebanks();
        return $mandate_banks;
    }

    // generate and return ref_number (int profile_id)
    public function generateRefNumber($profile_id)
    {
        $profile = Profile::findOrFail($profile_id);

        return $profile->prefix.$this->getRunningNumByYear($profile->tenancy_running_num);
    }

}
