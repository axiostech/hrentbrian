<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arc extends Model
{
/*
    status
    1 = send link
    2 = opened link
    3 = tenant approved
    4 = tenant binding failure
    5 = bank enrp approved
    6 = bank enrp rejected
     */

    protected $fillable = [
        'type', 'status', 'ref_number', 'amount', 'name',
        'email', 'id_value', 'bank_code', 'mandate_status', 'enrp_status',
        'enrp_status_updated_at', 'fpx_transaction_id', 'ex_order_no',
        'tenancy_id', 'idtype_id', 'profile_id', 'creator_id', 'updater_id'
    ];

    // relationships
    public function tenancy()
    {
        return $this->belongsTo('App\Tenancy');
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }
}
