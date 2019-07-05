<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recurrence extends Model
{
    protected $fillable = [
        'total_term', 'current_term,', 'paid_amount', 'total_amount', 'paid_date', 'month_send_date',
        'month_due_day', 'status', 'month', 'year', 'is_auto',

        'arc_id'
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
    public function arc()
    {
        return $this->belongsTo('App\Arc');
    }
}
