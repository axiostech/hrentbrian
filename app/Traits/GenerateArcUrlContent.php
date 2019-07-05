<?php

namespace App\Traits;

use App\Traits\ShortenUrl;
use App\Arc;

trait GenerateArcUrlContent
{
  use ShortenUrl;

  public function generateArcUrl($arc_id)
  {
    $arc = Arc::findOrFail($arc_id);

    $token = encrypt(json_encode([
      'arc_id' => $arc->id,
      'user_id' => $arc->created_by->id
    ]));

    $url = $this->get_tiny_url(url('/arc/form/' . $token));

    return $url;
  }

  public function generateArcMessage($arc_id, $url)
  {
    $arc = Arc::findOrFail($arc_id);

$message = 'Hi ' . $arc->tenancy->tenant->name . ', ' . $arc->profile->name . ' has created Auto Rent Collection (ARC) for you, please click the link to approve. ' . $url . '
Rent details:
' . $arc->tenancy->unit->block_number . ' ' . $arc->tenancy->unit->unit_number . ',' . $arc->tenancy->unit->address . '
Rental: RM ' . $arc->amount . '
Thanks
Happy Rent
Supported by RHB Bank
Insured by Allianz Insurance';

    return $message;
  }
}
