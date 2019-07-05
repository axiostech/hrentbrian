<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'email', 'is_primary',
        'user_id'
    ];

    // relationship
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
