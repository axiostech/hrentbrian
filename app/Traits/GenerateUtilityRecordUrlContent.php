<?php

namespace App\Traits;

use App\Traits\ShortenUrl;
use App\Tenancy;

trait GenerateUtilityRecordUrlContent
{
  use ShortenUrl;

  public function generateUtilityRecordUrl($tenancy_id)
  {
    $tenancy = Tenancy::findOrFail($tenancy_id);

    $token = encrypt(json_encode([
      'tenancy_id' => $tenancy->id,
    ]));

    $url = $this->get_tiny_url(url('/utilityrecord/form/' . $token));

    return $url;
  }

  public function generateUtilityRecordMessage($tenancy_id, $url)
  {
    $tenancy = Tenancy::findOrFail($tenancy_id);

    $message = 'Hi ' . $tenancy->tenant->name . ', ' . $tenancy->profile->name . ' has created a link for you to update and review the Utility Records. ' . $url . '
Unit details:
' . $tenancy->unit->block_number . ' ' . $tenancy->unit->unit_number . ',' . $tenancy->unit->address . '
Thanks
Happy Rent
Supported by RHB Bank
Insured by Allianz Insurance';

    return $message;
  }
}
