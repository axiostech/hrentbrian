<?php

namespace App\Traits;

use Carbon\Carbon;

trait RunningNum {

  public function getRunningNumByDay($code = null)
  {
    $todayDateStr = Carbon::today()->format('ymd');
    $yearStr = Carbon::today()->format('y');
    $runningnum = '';

    $runningnum = $todayDateStr.'01';

    if($code) {
      if(substr($code, 0, 6) == $todayDateStr) {
        $tail = (int)substr($code, -2);
        $tail += 1;
        $tail = sprintf('%02d', $tail);
        $runningnum = $todayDateStr.$tail;
      }
    }

    return $runningnum;
  }

  public function getRunningNumByYear($code = null)
  {
    $yearStr = Carbon::today()->format('y');
    $runningnum = '';

    $runningnum = $yearStr.'000001';

    if($code) {
      $codeint = preg_replace('/[^0-9]/', '', $code);
      if(substr($codeint, 0, 2) == $yearStr) {
        $tail = (int)substr($code, -6);
        $tail += 1;
        $tail = sprintf('%06d', $tail);
        $runningnum = $yearStr.$tail;
      }
    }

    return $runningnum;
  }
}