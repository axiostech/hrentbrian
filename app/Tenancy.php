<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Tenancy extends Model
{
/*
    status
    1 = active
    99 = deactivated

    is_arc
    1 = true
    0 = false
    */



    protected $fillable = [
        'tenancy_date', 'datefrom', 'dateto', 'duration_month', 'rental',
        'deposit', 'remarks', 'is_arc', 'status', 'code', 'room_name',
        'agreement_url',

        'unit_id', 'beneficiary_id', 'tenant_id', 'profile_id', 'creator_id',
        'updater_id'
    ];

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

    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

    public function tenant()
    {
        return $this->belongsTo('App\Tenant');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updater_id');
    }
}
