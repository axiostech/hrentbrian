<?php

namespace App\Http\Controllers;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Request;
use App\Beneficiary;
use DB;

class BeneficiaryController extends Controller
{
    // auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return beneficiary index page
    public function index()
    {
        return view('beneficiary.index');
    }

    // get all beneficiaries api
    public function getAllBeneficiariesApi()
    {
        $tenants = Beneficiary::leftJoin('idtypes', 'idtypes.id', '=', 'beneficiaries.idtype_id')
            ->leftJoin('genders', 'genders.id', '=', 'beneficiaries.gender_id')
            ->leftJoin('countries', 'countries.id', '=', 'beneficiaries.nationality_id')
            ->leftJoin('races', 'races.id', '=', 'beneficiaries.race_id')
            ->select(
                'beneficiaries.name', 'beneficiaries.email', 'beneficiaries.phone_number', 'beneficiaries.alt_phone_number', 'beneficiaries.email', 'beneficiaries.id_value', 'idtypes.name AS idtype_name','beneficiaries.id', 'beneficiaries.address', 'beneficiaries.city', 'beneficiaries.state', 'beneficiaries.postcode', 'beneficiaries.occupation', 'beneficiaries.invest_property_num', 'beneficiaries.remarks', 'beneficiaries.status', 'idtypes.id AS idtype_id', 'genders.id AS gender_id', 'countries.id AS country_id', 'races.id AS race_id'
            )
            ->filterProfile()
            ->orderBy('beneficiaries.name')
            ->get();

        return $tenants;
    }

    // retrieve beneficiaries by given filter
    public function getBeneficiariesApi()
    {
        $perpage = request('perpage');

        $beneficiaries = Beneficiary::leftJoin('idtypes', 'idtypes.id', '=', 'beneficiaries.idtype_id')
            ->leftJoin('genders', 'genders.id', '=', 'beneficiaries.gender_id')
            ->leftJoin('countries', 'countries.id', '=', 'beneficiaries.nationality_id')
            ->leftJoin('races', 'races.id', '=', 'beneficiaries.race_id')
            ->select(
                'beneficiaries.id', 'beneficiaries.name', 'beneficiaries.id_value', 'beneficiaries.address', 'beneficiaries.city', 'beneficiaries.state', 'beneficiaries.postcode', 'beneficiaries.phone_number', 'beneficiaries.alt_phone_number', 'beneficiaries.email', 'beneficiaries.occupation', 'beneficiaries.invest_property_num', 'beneficiaries.remarks', 'beneficiaries.status', 'beneficiaries.alt_phone_number', 'beneficiaries.occupation', 'beneficiaries.invest_property_num', 'beneficiaries.remarks',
                'idtypes.id AS idtype_id', 'idtypes.name AS idtype_name', 'genders.id AS gender_id', 'genders.name AS gender_name', 'countries.id AS nationality_id', 'countries.name AS nationality_name', 'races.id AS race_id', 'races.name AS race_name'
            )
            ->filterProfile();

        $beneficiaries = $this->filterBeneficiariesApi($beneficiaries);

        if ($perpage != 'All') {
            $beneficiaries = $beneficiaries->paginate($perpage);
        } else {
            $beneficiaries = $beneficiaries->get();
        }
        return $beneficiaries;
    }

    // deactivate single beneficiary api(int beneficiary_id)
    public function deactivateSingleBeneficiaryApi($beneficiary_id)
    {
        $beneficiary = Beneficiary::findOrFail($beneficiary_id);
        $beneficiary->status = 99;
        $beneficiary->save();
    }

    // store or update new individual beneficiary
    public function storeUpdateBeneficiaryApi()
    {
        $this->validate(request(), [
            'name' => 'required',
            'idtype_id' => 'required',
            'id_value' => 'required',
            'phone_number' => 'required'
        ]);
        if (request('id')) {
            $beneficiary = Beneficiary::findOrFail(request('id'));
            $beneficiary->update([
                'name' => request('name'),
                'nric' => request('idtype_id') == 1 ? request('id_value') : request('nric'),
                'email' => request('email'),
                'address' => request('address'),
                'city' => request('city'),
                'state' => request('state'),
                'postcode' => request('postcode'),
                'occupation' => request('occupation'),
                'invest_property_num' => request('invest_property_num'),
                'remarks' => request('remarks'),
                'status' => request('status') ? request('status') : $beneficiary->status,
                'phone_number' => request('phone_number'),
                'alt_phone_number' => request('alt_phone_number'),
                'idtype_id' => request('idtype_id'),
                'id_value' => request('id_value'),
                'gender_id' => request('gender_id'),
                'nationality_id' => request('nationality_id'),
                'race_id' => request('race_id'),
                'updater_id' => auth()->user()->id
            ]);
        } else {
            Beneficiary::create([
                'name' => request('name'),
                'nric' => request('nric'),
                'email' => request('email'),
                'address' => request('address'),
                'city' => request('city'),
                'state' => request('state'),
                'postcode' => request('postcode'),
                'occupation' => request('occupation'),
                'invest_property_num' => request('invest_property_num'),
                'remarks' => request('remarks'),
                'phone_number' => request('phone_number'),
                'alt_phone_number' => request('alt_phone_number'),
                'idtype_id' => request('idtype_id'),
                'id_value' => request('id_value'),
                'gender_id' => request('gender_id'),
                'nationality_id' => request('nationality_id'),
                'race_id' => request('race_id'),
                'creator_id' => auth()->user()->id,
                'profile_id' => auth()->user()->profile->id,
            ]);
        }
    }

    // beneficiaries api filter(Query query)
    private function filterBeneficiariesApi($query)
    {
        $name = request('name');
        $phone_number = request('phone_number');
        $id_value = request('id_value');
        $email = request('email');
        $status = request('status');
/*
        $property_id = request('property_id');
        $unit_id = request('unit_id');
        $tenancy_id = request('tenancy_id'); */
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('beneficiaries.name', 'LIKE', '%'.$name.'%');
        }
        if($phone_number) {
            $query = $query->where('beneficiaries.phone_number', 'LIKE', '%'.$phone_number.'%');
        }
        if($id_value) {
            $query = $query->where('beneficiaries.id_value', 'LIKE', '%'.$id_value.'%');
        }
        if($email) {
            $query = $query->where('beneficiaries.email', 'LIKE', '%'.$email.'%');
        }
        if($status) {
            $query = $query->where('beneficiaries.status', $status);
        }
/*
        if($property_id) {
            $query = $query->where('properties.id', $property_id);
        }
        if($unit_id) {
            $query = $query->where('units.id', $unit_id);
        }
        if($tenancy_id) {
            $query = $query->where('tenancies.id', $tenancy_id);
        } */
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('beneficiaries.created_at', 'desc');
        }

        return $query;
    }
}
