<?php

namespace App\Traits;

use Propaganistas\LaravelPhone\PhoneNumber;

trait FilterPhoneNumber
{

  public function filterMalaysiaPhoneNumber($value)
  {
    $phone_number = PhoneNumber::make($value)->ofCountry('MY');
    return $phone_number;
  }

  public function removePhoneNumberPlus($value)
  {
    $phone_number = substr($value, 1);
    return $phone_number;
  }

  public function filterMalaysiaStandardPhoneNumber($value)
  {
    $phone_number = substr($value, 2);
    return $phone_number;
  }
}
