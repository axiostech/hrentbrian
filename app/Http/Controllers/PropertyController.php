<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use DB;

class PropertyController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return property index page
    public function index()
    {
        return view('property.index');
    }

    // get all properties api
    public function getAllPropertiesApi()
    {
        $properties = DB::table('properties')
            ->leftJoin('propertytypes', 'propertytypes.id', '=', 'properties.propertytype_id')
            ->select(
                'properties.id', 'properties.name', 'properties.address', 'properties.city', 'properties.state',
                'properties.postcode', 'propertytypes.name AS propertytype_name'
            )
            ->orderBy('properties.name')
            ->get();

        return $properties;
    }

    // retrieve properties by given filter
    public function getPropertiesApi()
    {
        $perpage = request('perpage');

        $properties = DB::table('units')
            ->rightJoin('properties', 'properties.id', '=', 'units.property_id')
            ->leftJoin('propertytypes', 'propertytypes.id', '=', 'properties.propertytype_id')
            ->select(
                'properties.id', 'properties.name', 'properties.state', 'properties.city', 'properties.postcode',
                'propertytypes.name AS type_name', 'propertytypes.id AS type_id',
                DB::raw('COUNT(units.id) AS unit_count')
            );

        $properties = $this-> filterPropertiesApi($properties);

        $properties = $properties->groupBy('properties.id', 'properties.name', 'properties.state', 'properties.city', 'properties.postcode', 'propertytypes.name', 'propertytypes.id');

        if($perpage != 'All') {
            $properties = $properties->paginate($perpage);
        }else {
            $properties = $properties->get();
        }
        return $properties;
    }

    // store or update new individual property
    public function storeUpdatePropertyApi()
    {
        $this->validate(request(), [
            'name' => 'required',
            'city' => 'required',
            'state' => 'required'
        ]);

        $fieldsArr = [
            'name' => request('name'),
            'ptd_code' => request('ptd_code'),
            'address' => request('address'),
            'postcode' => request('postcode'),
            'city' => request('city'),
            'state' => request('state'),
            'attn_name' => request('attn_name'),
            'attn_phone_number' => request('attn_phone_number'),
            'propertytype_id' => request('type_id')
        ];

        if(request('id')) {
            $property = Property::findOrFail(request('id'));
            $property->update([$fieldsArr]);
        }else {
            Property::create([$fieldsArr]);
        }
    }

    // delete single property api(int property_id)
    public function destroySinglePropertyApi($property_id)
    {
        $property = Property::findOrFail($property_id);
        $property->delete();
    }

    // properties api filter(Query query)
    private function filterPropertiesApi($query)
    {
        $name = request('name');
        $state = request('state');
        $city = request('city');
        $type_id = request('type_id');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('properties.name', 'LIKE', '%'.$name.'%');
        }
        if($state) {
            $query = $query->where('properties.state', 'LIKE', '%'.$state.'%');
        }
        if($city) {
            $query = $query->where('properties.city', 'LIKE', '%'.$city.'%');
        }
        if($type_id) {
            $query = $query->where('propertytypes.id', $type_id);
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('properties.name');
        }

        return $query;
    }
}
