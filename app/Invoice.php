<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /*

    type
    <option value="1">ARC Rental</option>
    <option value="2">ARC Others</option>
    <option value="3">Utilities - Electric</option>
    <option value="4">Utilities - Water</option>
    <option value="5">Utilities - Others</option>
    <option value="6">Top Up</option>
    <option value="7">Insurance</option>
    <option value="8">MC & SF</option>
    <option value="9">Others</option>

    status
    1 = open
    2 = draft
    3 = confirmed
    99 = archieved

    payment_status
    1 = owe
    2 = paid
    */

    protected $fillable = [
        'invoice_number', 'send_date', 'due_date', 'paid_date', 'subtotal', 'grandtotal',
        'status', 'payment_status', 'paid_amount', 'is_sent', 'is_arc',

        'tenancy_id', 'unit_id', 'profile_id', 'creator_id', 'updater_id', 'user_id', 'recurrence_id'
    ];
}
