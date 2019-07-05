<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterPhoneNumber;

class Profile extends Model
{
    use FilterPhoneNumber;
/*
    status
    0 = inactive
    1 = active */

    protected $fillable = [
        'name', 'roc', 'attn_name', 'attn_phone_number', 'address', 'postcode',
        'city', 'state', 'domain_name', 'logo_url', 'theme_color', 'is_superprofile',
        'tenancy_running_num', 'prefix', 'email', 'status',

        'user_id'
    ];

    public function setAttnPhoneNumberAttribute($value)
    {
        $this->attributes['attn_phone_number'] = $this->filterMalaysiaPhoneNumber($value);
    }

    // relationships
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tenants()
    {
        return $this->hasMany('App\Tenant');
    }

    public function beneficiaries()
    {
        return $this->hasMany('App\Beneficiary');
    }

    public function units()
    {
        return $this->hasMany('App\Unit');
    }

    public function tenancies()
    {
        return $this->hasMany('App\Tenancy');
    }
}
