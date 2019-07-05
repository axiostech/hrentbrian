<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Traits\FilterPhoneNumber;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, FilterPhoneNumber;

/*
    status
    1 = active
    0 = deactivate */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'last_login_at',
        'last_login_ip', 'status', 'is_temporary_password',

        'profile_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // mutators
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = $this->filterMalaysiaPhoneNumber($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
