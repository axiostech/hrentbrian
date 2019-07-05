<?php

namespace App\Traits;

use Carbon\Carbon;

trait Sms {

  	public function sendSms($destination, $message) {
        //   dd($message);
        $message = html_entity_decode($message, ENT_QUOTES, 'utf-8');
        $message = urlencode($message);
        $username = urlencode("happyrent");
        $password = urlencode("happy888!");
        $sender_id = urlencode("happyrent");
        $type = 1;

        $fp = "https://www.isms.com.my/isms_send.php";
        $fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
        $http = curl_init($fp);
        curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
        $http_result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);
        $myArray = explode(' ', $http_result);
       	return $myArray;
    }
}
