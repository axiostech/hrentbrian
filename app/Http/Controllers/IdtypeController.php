<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class IdtypeController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get all properties api
    public function getAllIdtypesApi()
    {
        $idtypes = DB::table('idtypes')
            ->orderBy('id')
            ->get();

        return $idtypes;
    }
}
