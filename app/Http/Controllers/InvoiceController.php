<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;

class InvoiceController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return invoice index page
    public function index()
    {
        return view('invoice.index');
    }

    // get all invoices api
    public function getAllInvoicesApi()
    {
        $invoices = Invoice::leftJoin('tenancies', 'tenancies.id', '=', 'invoices.tenancy_id')
            ->leftJoin('units', 'units.id', '=', 'invoices.unit_id')
            ->leftJoin('profiles', 'profiles.id', '=', 'invoices.profile_id')

            ->select(
                'invoices.id', 'invoices.invoice_number', 'invoices.send_date', 'invoices.due_date', 'invoices.paid_date', 'invoices.subtotal',
                'invoices.grandtotal', 'invoices.status', 'invoices.payment_status', 'invoices.paid_amount', 'invoices.is_sent', 'invoices.is_arc', '.'
            )
            ->orderBy('properties.name')
            ->get();

        return $invoices;
    }

}
