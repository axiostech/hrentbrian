<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Unit;
use App\Operator;
use DB;
use App\Insurance;
use App\Billplz;

class CheckoutController extends Controller
{
    
    // auth access
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function __construct() {
		if(empty($_SERVER['HTTP_HOST'])){
			$_SERVER['HTTP_HOST'] = 'stage';
		}
		//dev
		$callback_dev = 'http://'.$_SERVER['HTTP_HOST'].'/api/v2/invoice-callback/';
		$return_dev = 'http://'.$_SERVER['HTTP_HOST'].'/bilpllz/payinsurance-return/';
		$passback_dev = 'http://stage.intentapp.in';
		//production 
		$callback_pro = 'https://'.$_SERVER['HTTP_HOST'].'/api/v2/invoice-callback/';
		$return_pro = 'https://'.$_SERVER['HTTP_HOST'].'/bilpllz/payinsurance-return/';
		$passback_pro = 'http://app.intentapp.in';



		// if (App::environment('production')) {
		// 	$this->billplz_callback = $callback_pro;
		// 	$this->billplz_return = $return_pro;
		// 	$this->billplz_passback = $passback_pro;
		// }else{
			$this->billplz_callback = $callback_dev;
			$this->billplz_return = $return_dev;
			$this->billplz_passback = $passback_dev;
			
		//}
		$this->month = array("1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August","9" => "September", "10" => "October", "11" => "November", "12" => "December");
	}

    // return checkout index page
    public function index(Request $request)
    {
        return view('checkout.index');

       


    }
    // return checkout index page
    public function getcheckout(Request $request)
    {
        $session_data = $request->session()->get('current_checkout_data');
        
        $response_data = $session_data['response_data'];
        $total_amount   =  $session_data['total'];
        $tax_amount     =  $session_data['tax_amount'];
        
        
        //return $session_data['response_data'];die;
        //return View::make('checkout.index')->with('plan_selected', $plan_selected)->with('total_amount', $total_amount);
       //print_r($response_data);die;
        foreach($response_data as $key=>$val){
           
        $units[] = DB::table('units')
            ->where('unit_number', $key)
            //->leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->select(
                'id', 'block_number', 'unit_number', 'address', 'label', 'remarks',
                'id AS value',
                DB::raw('CONCAT(IFNULL(block_number,""),IF(block_number IS NULL,""," - "),unit_number,IF(address IS NULL,"", " - "),IFNULL(address,"")) AS label')
            )
            ->first();
       }
       $checkout['checkoutlist'] = $units;

       foreach($session_data['plans'] as $key=>$val){
        $details_plans[] = DB::table('happyrent_insurance_plan')
        ->where('insurance_plan_id', $val)
        ->select(
            'happyrent_insurance_plan.insurance_plan_id', 'happyrent_insurance_plan.insurance_plan_name', 'happyrent_insurance_plan.insurance_plan_price', 'happyrent_insurance_plan.insurance_plan_duration','happyrent_insurance_plan.insurance_plan_status'
        )
        ->orderBy('happyrent_insurance_plan.insurance_plan_name')
        ->first();
       }
       $checkout['plans']           = $details_plans;
       $checkout['total_amount']    = $total_amount+$tax_amount;
       $checkout['tax_amount']      = $tax_amount;

       return $checkout;


       


    }

    // insurances api filter(Query query)
    private function filterInsurancesApi($query)
    {
        $block_number = request('block_number');//name
        $unit_number = request('unit_number');//coverages
        $address = request('address');//remarks
        $status = request('status');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($block_number) {
            $query = $query->where('units.block_number', 'LIKE', '%'.$block_number.'%');
        }
        if($unit_number) {
            $query = $query->where('units.unit_number', 'LIKE', '%'.$unit_number.'%');
        }
        if($address) {
            $query = $query->where('units.address', 'LIKE', '%'.$address.'%');
        }
        // if($status) {
        //     $query = $query->where('units.status', $status);
        // }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('units.block_number');
        }

        return $query;
    }
   
    
    
    //create a bill for insulcare units
    public function createinsurance(Request $request){
        $secret_array = $request->all();
        $session_data = $request->session()->get('current_checkout_data');
        //get the setting details
        $setting_details = DB::table('happyrent_site_setting')
        ->select(
            'happyrent_site_setting.billplz_secret_key',
            'happyrent_site_setting.billplz_signature',
            'happyrent_site_setting.billplz_collection_id', 
            'happyrent_site_setting.tax_amount'
        )->first();
        $token = encrypt(json_encode($secret_array));
        $response_data = $session_data['response_data'];
        $total_amount =  $session_data['total'];
        $total_paybale_amout = $total_amount + $setting_details->tax_amount;
        
        $billplz = new Billplz($setting_details->billplz_secret_key, $setting_details->billplz_signature);
        $valid_collection_id = $billplz->check_collection_id($setting_details->billplz_collection_id);


        if($valid_collection_id){
            $name ='happrent_test_stage'.time();//$user->name;
            $billplz->setName($name);
            $billplz->setReference_1_Label('Refrenece no.');
            $billplz->setReference_1(time());
            $billplz->setAmount($total_paybale_amout);
            
            
            $billplz->setMobile('903963110'); 
           
            $billplz->setEmail('jitendra'.time().'@mailinator.com'); 
            
            
            $description ='abc,'.time().', test address'.time();
            
            $billplz->setDescription($description);
            $billplz->setPassbackURL($this->billplz_passback);
            $billplz->setCollection($setting_details->billplz_collection_id);//get from database for onlt one send only one collection for pay
            $billplz->setDue(date('Y-m-d'));
            $billplz->setPassbackURL($this->billplz_callback.$token, $this->billplz_return.$token);
            $billplz->create_bill(); 
            $billid = $billplz->getID();
            if($billid){
                return $call_response = response()->json(['response'=> true, 'bill_url' => $billplz->getURL(), 'bill_id' => $billid ,'callback' => $this->billplz_callback.$token]);
        
            }else{
                return $call_response= false;
            }
           
        }

    }
    //
    public function returnbill($token,Request $request){
        $session_data = $request->session()->get('current_checkout_data');
        $session_response_data = $session_data['plans'];
        $billplz_return = $request->all();
        $toekn_data     = json_decode(decrypt($token),true);
        $bill_id        = $billplz_return['billplz']['id'];
        
        if($bill_id){
            foreach($toekn_data as $key=>$insured_unit){
                foreach($session_response_data as $key => $val){
                    if($key == $insured_unit['unit_number'] ){
                        $plan_ids[] = $val;
                    }
                    
                }
            }
            $plan_ids_arr = json_encode($plan_ids);
            $insurance_transaction_id = DB::table('happyrent_insurance_transaction')->insertGetId([
                'insurance_billplz_id'          => $bill_id,
                'insurance_plan_id'             => $plan_ids_arr,
                'insurance_subtotal_amount'     => $session_data['total'],
                'insurance_taxable_amount'      => $session_data['tax_amount'],
                'insurance_transaction_status'  => 1,
                'insurance_payment_by'          => 'fpx',
                'insurance_transaction_date'    => date('Y-m-d H:i:s'),
                'insurance_total_amount'        => $session_data['total'],
                'created_at'                    => date('Y-m-d H:i:s'),
                'updated_at'                    => date('Y-m-d H:i:s'),
              
            ]);
            
            $transation = DB::table('happyrent_insurance_transaction')
            ->where('insurance_transaction_id', $insurance_transaction_id)
            ->select(
                'happyrent_insurance_transaction.insurance_transaction_status',
                'happyrent_insurance_transaction.insurance_plan_id',
                'happyrent_insurance_transaction.insurance_transaction_date'                     
            )->first();

            //print_r(json_decode($transation->insurance_plan_id));die;
            foreach($toekn_data as $key=>$insured_unit){
                DB::table('happyrent_insurance')->insert([
                    'insurance_unit_id'         => $insured_unit['id'],
                   // 'insurance_plan_id'         => $transation->insurance_plan_id,
                    'insurance_transaction_id'  => $insurance_transaction_id,
                    'insurance_date'            => $transation->insurance_transaction_date,
                    'insurance_status'          => $transation->insurance_transaction_status,
                    'created_at'                => date('Y-m-d H:i:s'),
                    'updated_at'                => date('Y-m-d H:i:s'),
                  
                ]);
            }
           
           
        }
        $request->session()->forget('current_checkout_data');//delete session
        return redirect('/success');
        
        
       
    }
    public function success(){
        return view('checkout.response');

    }

    
}