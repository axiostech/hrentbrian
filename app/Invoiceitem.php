<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoiceitem extends Model
{
    protected $fillable = [
        'description', 'qty', 'amount',
        'item_id', 'invoice_id'
    ];
}
