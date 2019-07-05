<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PropertytypeController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get all properties api
    public function getAllPropertytypesApi()
    {
        $propertytypes = DB::table('propertytypes')
            ->orderBy('name')
            ->select('id AS value', 'name AS label')
            ->get();

        return $propertytypes;
    }
}
