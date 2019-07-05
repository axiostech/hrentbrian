<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterPhoneNumber;

class Tenant extends Model
{

    use FilterPhoneNumber;
/*
    status
    1 = active
    99 = deactivate */

    protected $fillable = [
        'name', 'email', 'status', 'phone_number', 'alt_phone_number', 'id_value',

        'idtype_id', 'profile_id', 'creator_id', 'updater_id', 'user_id'
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

    public function created_by()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updater_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
