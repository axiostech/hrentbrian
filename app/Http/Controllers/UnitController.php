<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;
use App\Operator;
use DB;

class UnitController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return unit index page
    public function index()
    {
        return view('unit.index');
    }

    // get unit list given property id(int property_id)
    public function getAllUnitsByPropertyIdApi($property_id)
    {
        $units = Unit::where('property_id', $property_id)
            ->orderBy('block_number')
            ->orderBy('unit_number')
            ->select(
                'id', 'block_number', 'unit_number', 'address', 'label', 'remarks',
                'id AS value',
                DB::raw('CONCAT(IFNULL(block_number,""),IF(block_number IS NULL,""," - "),unit_number,IF(address IS NULL,"", " - "),IFNULL(address,"")) AS label')
            )
            ->filterProfile()
            ->get();

        return $units;
    }


    // get all units api
    public function getAllUnitsApi()
    {
        $units = Unit::leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->leftJoin('propertytypes', 'propertytypes.id', '=', 'properties.propertytype_id')
            ->select(
                'properties.id AS property_id', 'properties.name AS property_name', 'properties.city', 'properties.state',
                'properties.postcode',
                'propertytypes.name AS propertytype_name',
                'units.block_number', 'units.unit_number', 'units.address', 'units.remarks AS unit_remarks', 'units.squarefeet', 'units.purchase_price', 'units.bedbath_room', 'units.purchased_at', 'units.id'
            )
            ->orderBy('properties.name')
            ->orderBy('units.block_number')
            ->orderBy('units.unit_number')
            ->filterProfile()
            ->get();

        return $units;
    }

    // retrieve units by given filter
    public function getUnitsApi()
    {
        $perpage = request('perpage');

        $units = Unit::leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->leftJoin('propertytypes', 'propertytypes.id', '=', 'properties.propertytype_id')
            ->select(
                'properties.id AS property_id', 'properties.name AS property_name', 'properties.city', 'properties.state',
                'properties.postcode',
                'propertytypes.name AS propertytype_name',
                'units.block_number', 'units.unit_number', 'units.address', 'units.remarks AS unit_remarks', 'units.squarefeet', 'units.purchase_price', 'units.bedbath_room', 'units.purchased_at', 'units.id'
            )
            ->filterProfile();

        $units = $this-> filterUnitsApi($units);

        $units = $units->groupBy('properties.id', 'properties.name', 'properties.state', 'properties.city', 'properties.postcode', 'propertytypes.name', 'propertytypes.id', 'units.block_number', 'units.unit_number', 'units.address', 'units.remarks', 'units.squarefeet', 'units.purchase_price', 'units.bedbath_room', 'units.purchased_at', 'units.id');

        if($perpage != 'All') {
            $units = $units->paginate($perpage);
        }else {
            $units = $units->get();
        }
        return $units;
    }

    // store or update new individual unit
    public function storeUpdateUnitApi()
    {
        $this->validate(request(), [
            'unit_number' => 'required'
        ]);
        if(request('id')) {
            $unit = Unit::findOrFail(request('id'));
            $unit->update([
                'block_number' => request('block_number'),
                'unit_number' => request('unit_number'),
                'address' => request('address'),
                'remarks' => request('remarks'),
                'squarefeet' => request('squarefeet'),
                'purchase_price' => request('purchase_price'),
                'bedbath_room' => request('bedbath_room'),
                'purchased_at' => request('purchased_at'),
                'updater_id' => auth()->user()->id
            ]);
        }else {
            Unit::create([
                'block_number' => request('block_number'),
                'unit_number' => request('unit_number'),
                'address' => request('address'),
                'remarks' => request('remarks'),
                'squarefeet' => request('squarefeet'),
                'purchase_price' => request('purchase_price'),
                'bedbath_room' => request('bedbath_room'),
                'purchased_at' => request('purchased_at'),
                'property_id' => request('property_id'),
                'creator_id' => auth()->user()->id,
                'profile_id' => auth()->user()->profile->id,
            ]);
        }
    }

    // services
    public function services()
    {
        $operators = Operator::all();

        return $operators;
    }


    // delete single unit api(int unit_id)
    public function destroySingleUnitApi($unit_id)
    {
        $unit = Unit::findOrFail($unit_id);
        $unit->delete();
    }

    // units api filter(Query query)
    private function filterUnitsApi($query)
    {
        $property_id = request('property_id');
        $unit_id = request('unit_id');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($property_id) {
            $query = $query->where('units.property_id', $property_id);
        }
        if($unit_id) {
            $query = $query->where('units.id', $unit_id);
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('properties.name')
                            ->orderBy('units.block_number')
                            ->orderBy('units.unit_number');
        }

        return $query;
    }
}
