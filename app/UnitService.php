<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitService extends Model
{
    protected $fillable = [
        'unit_id', 'operator_id', 'operator_type'
    ];
}
