<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'block_number', 'unit_number', 'address', 'label', 'remarks',
        'squarefeet', 'purchase_price', 'bedbath_room', 'purchased_at',
        'latitude', 'longitude',

        'property_id', 'profile_id', 'creator_id', 'updater_id'
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

    public function property()
    {
        return $this->belongsTo('App\Property');
    }
}
