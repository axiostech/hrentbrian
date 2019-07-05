<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'name', 'ptd_code', 'postcode', 'address',
        'city', 'state', 'attn_name', 'attn_phone_number',
        'latitude', 'longitude',

        'propertytype_id'
    ];
}
