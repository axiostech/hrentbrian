<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GenderController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get all genders api
    public function getAllGendersApi()
    {
        $results = DB::table('genders')
            ->orderBy('id')
            ->get();

        return $results;
    }
}
