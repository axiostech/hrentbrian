<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App;
use Log;
use Exception;

class Curlec extends Model {

    var $url,$banks,$batchId,$order_no,$ex_order_no,$mandate_reference;

    protected $fields,$collection_fields;
    public static $merchantId,$employeeId;


    private static $pro_url = array(
        'new_mandate' => 'https://go.curlec.com/curlec-services/mandate',
        'new_mandate_get' => 'https://go.curlec.com/new-mandate',
        'mandate_banks' => 'https://go.curlec.com/curlec-services/banks',
        'new_collection' => 'https://go.curlec.com/curlec-services',
        'new_instapay_get' => 'https://go.curlec.com/new-instant-pay',
    );

    /* Note Change merchantId id when change envirment  */
    private static $dev_url = array(
        'new_mandate' => 'https://demo.curlec.com/curlec-services/mandate',
        'new_mandate_get' => 'https://demo.curlec.com/new-mandate',
        'mandate_banks' => 'https://demo.curlec.com/curlec-services/banks',
        'new_collection' => 'https://demo.curlec.com/curlec-services',
        'new_instapay_get' => 'https://demo.curlec.com/new-instant-pay',

    );

    public function __construct(){

        if(App::environment('production')){
            self::$merchantId = 70150;
            self::$employeeId = 7016;
            $this->url = self::$pro_url;
        }else{
            self::$merchantId = 40020;
            self::$employeeId = 4003;
            $this->url = self::$dev_url;
        }

        $this->fields = array(
            'referenceNumber' => array(
                'required' => true,
                'value' =>null
            ),
            'effectiveDate' => array(
                'required' => false,
                'value' =>null
            ),
            'expiryDate' => array(
                'required' => false,
                'value' =>null
            ),
            'amount' => array(
                'required' => true,
                'value' =>null
            ),
            'frequency' => array(
                'required' => true,
                'value' =>'DAILY'
            ),
            'maximumFrequency' => array(
                'required' => true,
                'value' =>'1'
            ),
            'purposeOfPayment' => array(
                'required' => true,
                'value' =>'RENTAL_AND_UTILITIES'
            ),
            'businessModel' => array(
                'required' => true,
                'value' =>'B2C'
            ),
            'name' => array(
                'required' => true,
                'value' =>null
            ),
            'emailAddress' => array(
                'required' => true,
                'value' =>null
            ),
            'phoneNumber' => array(
                'required' => false,
                'value' =>null
            ),
            'idType' => array(
                'required' => true,
                'value' =>null
            ),
            'idValue' => array(
                'required' => true,
                'value' =>null
            ),
            'bankId' => array(
                'required' => true,
                'value' =>null
            ),
            'linkId' => array(
                'required' => false,
                'value' => null
            ),
            'merchantId' => array(
                'required' => true,
                'value' => self::$merchantId
            ),
            'employeeId' => array(
                'required' => true,
                'value' => self::$employeeId
            ),
            'method' => array(
                'required' => true,
                'value' => '04'
            ),
            'merchantUrl' => array(
                'required' => false,
                'value' => null
            ),
            'merchantCallbackUrl' => array(
                'required' => false,
                'value' => null
            )
        );


        $this->collection_fields = array(
            'method' => array(
                'required' => true,
                'value' =>'04'
            ),
            'merchantId' => array(
                'required' => true,
                'value' => self::$merchantId
            ),
            'date' => array(
                'required' => true,
                'value' => date('d/m/Y H:i:s')
            ),
            'reminder' => array(
                'required' => true,
                'value' => 'true'
            ),
            'upload' => array(
                'required' => true,
                'value' => 'true'
            ),
            'list' => array(
                'required' => true,
                'value' => null
            ),
            'items' => array(
                'required' => false,
                'value' => null
            ),
            'description' => array(
                'required' => false,
                'value' => null
            ),

        );
    }



   /*Development  */
/*    public static $merchantId = 40020;
    public static $employeeId = 4003;*/

    public function getMerchantId()  {
        return self::$merchantId;
    }

    public function getEmployeeId()  {
        return self::$employeeId;
    }

    public function setReferenceNumber($value){
        $this->fields['referenceNumber']['value'] = $value;
        return $this;
    }

    public function setEffectiveDate($value){
        $this->fields['effectiveDate']['value'] = $value;
        return $this;
    }

    public function setExpiryDate($value){
        $this->fields['expiryDate']['value'] = $value;
        return $this;
    }

    public function setAmount($value){
        $this->fields['amount']['value'] = $value;
        return $this;
    }

    public function setFrequency($value){
        $this->fields['frequency']['value'] = $value;
        return $this;
    }

    public function setMaximumFrequency($value){
        $this->fields['maximumFrequency']['value'] = $value;
        return $this;
    }

    public function setPurposeOfPayment($value){
        $this->fields['purposeOfPayment']['value'] = $value;
        return $this;
    }

    public function setBusinessModel($value){
        $this->fields['businessModel']['value'] = $value;
        return $this;
    }

    public function setName($value){
        $this->fields['name']['value'] = $value;
        return $this;
    }

    public function setEmailAddress($value){
        $this->fields['emailAddress']['value'] = $value;
        return $this;
    }

    public function setPhoneNumber($value){
        $this->fields['phoneNumber']['value'] = $value;
        return $this;
    }

    public function setIdType($value){
        $this->fields['idType']['value'] = $value;
        return $this;
    }

    public function setIdValue($value){
        $this->fields['idValue']['value'] = $value;
        return $this;
    }

    public function setBankId($value){
        $this->fields['bankId']['value'] = $value;
        return $this;
    }

    public function setLinkId($value){
        $this->fields['linkId']['value'] = $value;
        return $this;
    }

    public function setMerchantId(){
        $this->fields['merchantId']['value'] = self::$merchantId;
        $this->collection_fields['merchantId']['value'] = self::$merchantId;
        $this->merchantId = self::$merchantId;
        return $this;
    }

    public function setEmployeeId($value){
        $this->fields['employeeId']['value'] = $value;
        return $this;
    }

    public function setMethod($value){
        $this->fields['method']['value'] = $value;
        return $this;
    }

    public function setMerchantUrl($value){
        $this->fields['merchantUrl']['value'] = $value;
        return $this;
    }

    public function setMerchantCallbackUrl($value){
        $this->fields['merchantCallbackUrl']['value'] = $value;
        return $this;
    }

    public function setDate($value){
        $this->collection_fields['date'] = $value;
        return $this;
    }

    public function setRemainder($value){
        $this->collection_fields['reminder'] = $value;
        return $this;
    }

    public function setUpload($value){
        $this->collection_fields['upload'] = $value;
        return $this;
    }

    public function setList($value){
        $this->collection_fields['list']['value'] = $value;
        return $this;
    }

    public function setItems($value){
        $this->collection_fields['items'] = json_encode($value);
        return $this;
    }

    public function setDescription($value){
        $this->collection_fields['description'] = $value;
        return $this;
    }

    public function setBatchId($value){
         $this->batchId = $value;
        return $this;
    }

    public function setMandateReference($value){
         $this->mandate_reference = $value;
        return $this;
    }

    public function setOrderNo($value){
        $this->order_no = $value;
        return $this;
    }

    public function setExOrderNo($value){
        $this->ex_order_no = $value;
        return $this;
    }




    public function createMandate(){
        $params = [];
        foreach ($this->fields as $key => $field) {
            if($field['required'] == true && $field['value'] == null){

                throw new Exception("Missing $key from required fields", 1);
            }else{
                $params[$key] = $field['value'];
            }
        }

        $this->action($this->url['new_mandate'],$params);
        return $this->response;
    }

    public function createInstantPay($params){
    	//$params = [];
        // foreach ($this->fields as $key => $field) {
        //     if($field['required'] == true && $field['value'] == null){

        //         throw new Exception("Missing $key from required fields", 1);
        //     }else{
        //     	$params[$key] = $field['value'];
        //     }
        // }

        $this->action($this->url['new_mandate'],$params);
        return $this->response;
    }

    public function getResponse(){
        return $this->response;
    }


    public function createCollection(){
        foreach ($this->collection_fields as $key => $field) {
            if($field['required'] == true && $field['value'] == null){
                throw new Exception("Missing $key from required fields", 1);
            }
        }

        $data_arr  = array(
            'date' =>   $this->collection_fields['date']['value'],
            'reminder' => $this->collection_fields['reminder']['value'],
            'upload' =>  $this->collection_fields['upload']['value'],
            'list' =>  $this->collection_fields['list']['value'],
            'items' =>  $this->collection_fields['items']['value'],
            'description' =>  $this->collection_fields['description']['value'],
        );

        if($data_arr['items']['value'] == null){
            unset($data_arr['items']);
        }

        if($data_arr['description']['value'] == null){
            unset($data_arr['description']);
        }


        $params =  array(
            'method' => $this->collection_fields['method']['value'],
            'merchantId' => $this->collection_fields['merchantId']['value'],
            'data' => json_encode($data_arr)
        );

        $this->action($this->url['new_collection'],$params);
        return $this->response;
    }

    public function checkCollectionStatus($batch_id){
        $params = array(
            'method' => '05',
            'merchantId' => self::$merchantId,
            'batch' => $batch_id
        );

        Log::info('invoice collection status params ->'.json_encode($params));

        $this->action($this->url['new_collection'],$params);
        return $this->response;
    }

    public function checkEnrpStatus($condition = null){

        $params = array(
            'method' => '03',
            'merchantId' => self::$merchantId
        );

        if(empty($this->order_no) && empty($this->ex_order_no)){
            throw new Exception("Missing order_no or ex order no from required fields", 1);
        }

        if(!empty($this->order_no)){
            $params['order_no'] = $this->order_no;
        }

        if(!empty($this->ex_order_no)){
            $params['ex_order_no'] = $this->ex_order_no;
        }

        if($condition != null){
            $params['condition'] = $condition;
        }

        $this->action($this->url['new_collection'],$params);
        return $this->response;


    }

    public function generateMandateUrl(){

        $params = [];

        foreach ($this->fields as $key => $field) {
            if($field['required'] == true && $field['value'] == null){

                throw new Exception("Missing $key from required fields", 1);
            }else{
                $params[$key] = $field['value'];
            }
        }

        $params['method'] = '03';

        $mandate_url = $this->url['new_mandate_get'].'?'.http_build_query($params);

        return $mandate_url;
    }

    public function generateInstantPayUrl($params){

        // $params = [];

        // foreach ($this->fields as $key => $field) {
        //     if($field['required'] == true && $field['value'] == null){

        //         throw new Exception("Missing $key from required fields", 1);
        //     }else{
        //         $params[$key] = $field['value'];
        //     }
        // }

        $params['method'] = '03';

        $instant_pay_url = $this->url['new_instapay_get'].'?'.http_build_query($params);

        return $instant_pay_url;
    }

    public function checkMandateStatus(){
        $params = array(
            'method' => '00',
            'merchantId' => self::$merchantId
        );

        if(empty($this->order_no) && empty($this->ex_order_no)){
        	throw new Exception("Missing order_no or ex order no from required fields", 1);
        }

        if(!empty($this->order_no)){
        	$params['order_no'] = $this->order_no;
        }

        if(!empty($this->ex_order_no)){
        	$params['ex_order_no'] = $this->ex_order_no;
        }

        $this->action($this->url['new_collection'],$params);
        return $this->response;
    }

    public function getMandatebanks($msgToken = '01'){

        $this->action(sprintf('%s?method=01&msgToken=01',$this->url['mandate_banks']));

        $response = $this->response;

        if($response['Status'][0] == 201){
            $this->banks = $response['Response'][0];
        }else{
            $this->banks = [];
        }

        return $this->banks;
    }


    private function action($url,$params=[]){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $exec = curl_exec($ch);

        $this->response = json_decode($exec,true);

    }


}


