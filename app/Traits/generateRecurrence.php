<?php

namespace App\Traits;
use App\User;
use App\Arc;
use App\Recurrence;

trait generateRecurrence
{
  // use HasRoles;

  public function generateRecurrence($arc_id)
  {
    $arc = Arc::findOrFail($arc_id);

    $existing_recurrence = Recurrence::where('arc_id', $arc->id)->first();

    if(!$existing_recurrence) {
      $recurrence = new Recurrence();

      $recurrence->total_term = $arc->tenancy->duration_month;
      $recurrence->current_term = 1;
    }
  }
}
