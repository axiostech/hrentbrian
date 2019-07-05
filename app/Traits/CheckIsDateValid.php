<?php

namespace App\Traits;

use Carbon\Carbon;

trait checkIsDateValid
{
  public function checkIsDateValid($datefrom, $dateto)
  {
    if (Carbon::today()->between(Carbon::parse($datefrom), Carbon::parse($dateto))) {
      return true;
    } else {
      return false;
    }
  }
}
