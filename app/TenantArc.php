<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;

class TenantArc extends Model
{

	use App\Traits\ShortenUrl;
	use App\Traits\Sms;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenant_arc';

    public function sendArcForm($tenancy){

        $token = encrypt(json_encode([
        	'user_id' => auth()->user()->id,
            'reference_number' => $this->generateReferenceNumber($tenancy->code),
            'tenancy_id' => $tenancy->id
        ]));

        $url = url('arc/form/'.$token);
        $url = $this->get_tiny_url($url);
        // dd($token, $url, $url);

        $message = 'Hi '.$tenancy->tenant->name.', '.auth()->user()->name.' has created Auto Rent Collection (ARC) service for you, please click link to approve. '.$url.'
            Rent details:
	    	'.$tenancy->unit->block_number.', '.$tenancy->unit->unit_number.','.$tenancy->unit->address.', Rental @ '.$tenancy->rental.'
	            Thanks
	            Happy Rent
	            Supported by RHB Bank
	            Insured by Allianz Insurance';

	            print_r($message);die;

        $this->sendMsg(substr($tenancy->tenant->phone_number,1),$message);


        // Mail::send(array(), array(), function ($email) emailuse ($message,$recurring) {
        //   $email->to($recurring->email)
        //     ->subject('Auto Debit Rental Form')
        //     ->setBody($message);
        // });
    }

    public function tenancy(){
    	return $this->belongsTo('App\Tenancy');
    }

    private function generateReferenceNumber($code)
    {
        return 'HR' . $code;
    }


 }
