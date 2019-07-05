<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phonenumber extends Model
{
    protected $fillable = [
        'number', 'is_primary',
        'user_id', 'country_id'
    ];

    // relationship
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
