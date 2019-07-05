<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitServiceAccount extends Model
{
     protected $fillable = [
        'unit_service_id', 'account_number'
    ];
}
