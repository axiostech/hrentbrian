<?php

namespace App\Traits;
use Propaganistas\LaravelPhone\PhoneNumber;

trait SendWhatsapp
{

  public function sendWhatsapp($mobile_number, $content)
  {
    $content = html_entity_decode($content, ENT_QUOTES, 'utf-8');
    $content = urlencode($content);
    $mobile_number = PhoneNumber::make($mobile_number, 'MY')->formatForCountry('MY');

    $url = "https://api.whatsapp.com/send";
    $url .= "?phone=$mobile_number&text=$content";

    return redirect()->away($url);
/*
    $http = curl_init($url);
    curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
    $http_result = curl_exec($http);
    $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
    curl_close($http);
    $myArray = explode(' ', $http_result);
    return $myArray; */
  }
}
