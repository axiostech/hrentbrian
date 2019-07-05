<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utilityrecord extends Model
{
    /*
    status
    1 = new
    2 = verified
    3 = rejected

    type
    1 = electric
    2 = water
    3 = broadband
    4 = others
    */
    protected $fillable = [
        'month', 'year', 'reading', 'amount', 'image_url',
        'remarks', 'status', 'type', 'is_request_cancel',

        'tenancy_id', 'profile_id', 'creator_id', 'updater_id'
    ];

    // scopes
    public function scopeFilterProfile($query, $table_name = null)
    {
        if (!auth()->user()->hasRole('superadmin')) {
            if ($table_name == null) {
                $table_name = $this->getTable();
            }

            $profile_table = $table_name . '.profile_id';

            $query = $query->where($profile_table, auth()->user()->profile_id);
        }

        return $query;
    }

    // relationships
    public function tenancy()
    {
        return $this->belongsTo('App\Tenancy');
    }
}
