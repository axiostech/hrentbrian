<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterPhoneNumber;

class Beneficiary extends Model
{
    use FilterPhoneNumber;
/*
    status
    1 = active
    99 = deactivate */

    protected $fillable = [
        'name', 'id_value', 'address', 'city', 'state', 'postcode',
        'phone_number', 'alt_phone_number', 'email', 'occupation', 'invest_property_num',
        'remarks', 'status',

        'idtype_id', 'gender_id', 'nationality_id', 'race_id', 'profile_id', 'creator_id', 'updater_id'
    ];

    // mutators
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = $this->filterMalaysiaPhoneNumber($value);
    }

    public function setAltPhoneNumberAttribute($value)
    {
        $this->attributes['alt_phone_number'] = $this->filterMalaysiaPhoneNumber($value);
    }

    // scopes
    public function scopeFilterProfile($query, $table_name = null)
    {
        if(!auth()->user()->hasRole('superadmin')) {
            if ($table_name == null) {
                $table_name = $this->getTable();
            }

            $profile_table = $table_name.'.profile_id';

            $query = $query->where($profile_table, auth()->user()->profile_id);
        }

        return $query;
    }

    // relationships
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
