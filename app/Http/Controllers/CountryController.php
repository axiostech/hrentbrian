<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CountryController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get all countries api
    public function getAllCountriesApi()
    {
        $results = DB::table('countries')
            ->orderBy('id')
            ->get();

        return $results;
    }
}
