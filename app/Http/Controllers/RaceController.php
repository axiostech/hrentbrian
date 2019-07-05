<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RaceController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get all countries api
    public function getAllRacesApi()
    {
        $results = DB::table('races')
            ->orderBy('id')
            ->get();

        return $results;
    }
}
