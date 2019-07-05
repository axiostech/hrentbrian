<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Arc;
use App\Beneficiary;
use App\Property;
use App\Tenancy;
use App\Tenant;
use App\Unit;
use App\Traits\RunningNum;
use App\Traits\checkIsDateValid;
use App\Traits\SyncUser;
use Carbon\Carbon;
use DB;

class TenancyController extends ArcController
{
    use RunningNum, checkIsDateValid, SyncUser;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // return tenancy index page
    public function index()
    {
        return view('tenancy.index');
    }

    // get all tenancies api
    public function getAllTenanciesApi()
    {
        $tenancies = Tenancy::leftJoin('tenants', 'tenants.id', '=', 'tenancies.tenant_id')
            ->leftJoin('units', 'units.id', '=', 'tenancies.unit_id')
            ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->orderBy('properties.name')
            ->orderBy('units.block_number')
            ->orderBy('units.unit_number')
            ->filterProfile('tenancies')
            ->get();

        return $tenancies;
    }

    // retrieve tenancies api
    public function getTenanciesApi()
    {
        $perpage = request('perpage');

        $tenancies = Tenancy::leftJoin('tenants', 'tenants.id', '=', 'tenancies.tenant_id')
            ->leftJoin('units', 'units.id', '=', 'tenancies.unit_id')
            ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->leftJoin('beneficiaries', 'beneficiaries.id', '=', 'tenancies.beneficiary_id')
            ->leftJoin('arcs', 'arcs.tenancy_id', '=', 'tenancies.id')
            ->select(
                'properties.name AS property_name',
                'units.block_number', 'units.unit_number', 'units.address',
                'tenants.name AS tenant_name', 'tenants.phone_number AS tenant_phone_number',
                DB::raw('DATE(tenancies.datefrom) AS datefrom'), DB::raw('DATE(tenancies.dateto) AS dateto'), 'tenancies.rental',
                'tenancies.status', 'tenancies.code', 'tenancies.room_name','tenancies.id as tenancy_id',
                'beneficiaries.name AS beneficiary_name', 'arcs.status AS arc_status'
            );

        $tenancies = $tenancies->filterProfile('tenancies');

        $tenancies = $this->filterTenanciesApi($tenancies);

        $tenancies = $tenancies->groupBy(
            'properties.name', 'units.block_number', 'units.unit_number', 'units.address',
            'tenants.name', 'tenants.phone_number', 'tenancies.datefrom', 'tenancies.dateto', 'tenancies.rental', 'tenancies.status', 'tenancies.code', 'tenancies.room_name', 'tenancies.id',
            'beneficiaries.name',
            'arcs.status'
        );

        if($perpage != 'All') {
            $tenancies = $tenancies->paginate($perpage);
        }else {
            $tenancies = $tenancies->get();
        }
        return $tenancies;
    }

    // retrieve single tenancy(int $tenancy_id)
    public function getSingleTenancyApi($tenancy_id)
    {
        $tenancy = Tenancy::leftJoin('profiles', 'profiles.id', '=', 'tenancies.profile_id')
                    ->leftJoin('beneficiaries', 'beneficiaries.id', '=', 'tenancies.beneficiary_id')
                    ->leftJoin('tenants', 'tenants.id', '=', 'tenancies.tenant_id')
                    ->leftJoin('units', 'units.id', '=', 'tenancies.unit_id')
                    ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
                    ->leftJoin('idtypes AS beneficiaryidtypes', 'beneficiaryidtypes.id', '=', 'beneficiaries.idtype_id')
                    ->leftJoin('idtypes AS tenantidtypes', 'tenantidtypes.id', '=', 'tenants.idtype_id')
                    ->leftJoin('arcs', 'arcs.tenancy_id', '=', 'tenancies.id')
                    ->select(
                        'tenancies.id AS tenancy_id', 'tenancies.code AS tenancy_code',
                        DB::raw('DATE(tenancies.tenancy_date) AS tenancy_date'),
                        DB::raw('DATE(tenancies.datefrom) AS tenancy_datefrom'),
                        DB::raw('DATE(tenancies.dateto) AS tenancy_dateto'),
                        'tenancies.duration_month AS tenancy_durationmonth', 'tenancies.rental AS tenancy_rental', 'tenancies.deposit AS tenancy_deposit', 'tenancies.room_name', 'tenancies.agreement_url', 'arcs.status AS arc_status',
                        'beneficiaries.id AS beneficiary_id', 'beneficiaries.name AS beneficiary_name', 'beneficiaryidtypes.name AS beneficiary_idtype_name', 'beneficiaries.id_value AS beneficiary_idvalue',
                        'tenants.id AS tenant_id', 'tenants.name AS tenant_name', 'tenantidtypes.name AS tenant_idtype_name', 'tenants.id_value AS tenant_idvalue',
                        'properties.name AS property_name',
                        'units.block_number AS unit_blocknumber', 'units.unit_number AS unit_unitnumber', 'units.address AS unit_address',
                        'profiles.id AS profile_id', 'profiles.name AS profile_name', 'profiles.roc AS profile_roc', 'profiles.address AS profile_address'
                    )
                    ->filterProfile()
                    ->where('tenancies.id', $tenancy_id)
                    ->first();

        return $tenancy;
    }

    // store or update new individual tenancy
    public function storeUpdateTenancyApi()
    {
        $tenantForm = request('tenantForm');
        $propertyForm = request('propertyForm');
        $tenancyForm = request('tenancyForm');
        $beneficiaryForm = request('beneficiaryForm');
        $tenant = '';
        $property = '';
        $unit = '';
        $beneficiary = '';

        // $tenant_register_user = request()->has($tenancyForm['tenant_register_user']);

        if($tenantForm['tenant_id']) {
            $tenant = Tenant::findOrFail($tenantForm['tenant_id']['id']);
        }else {
            $tenant = Tenant::create([
                'name' => $tenantForm['name'],
                'idtype_id' => $tenantForm['idtype_id'],
                'id_value' => $tenantForm['id_value'],
                'phone_number' => $tenantForm['phone_number'],
                'alt_phone_number' => $tenantForm['alt_phone_number'],
                'email' => $tenantForm['email'],
                'creator_id' => auth()->user()->id,
                'profile_id' => auth()->user()->profile->id
            ]);
        }

        if($propertyForm['property_id']) {
            $property = Property::findOrFail($propertyForm['property_id']['id']);
        }else {
            $property = Property::create([
                'name' => $propertyForm['name'],
                'ptd_code' => $propertyForm['ptd_code'],
                'postcode' => $propertyForm['postcode'],
                'address' => $propertyForm['address'],
                'city' => $propertyForm['city'],
                'state' => $propertyForm['state'],
                'attn_name' => $propertyForm['attn_name'],
                'attn_phone_number' => $propertyForm['attn_phone_number'],
                'propertytype_id' => $propertyForm['type_id']
            ]);
        }

        if($property) {
            if($propertyForm['unit']['unit_id']) {
                $unit = Unit::findOrFail($propertyForm['unit']['unit_id']['id']);
            }else {
                $unit = Unit::create([
                    'block_number' => $propertyForm['unit']['block_number'],
                    'unit_number' => $propertyForm['unit']['unit_number'],
                    'address' => $propertyForm['unit']['address'],
                    'label' => $propertyForm['unit']['label'],
                    'remarks' => $propertyForm['unit']['remarks'],
                    'squarefeet' => $propertyForm['unit']['squarefeet'],
                    'purchase_price' => $propertyForm['unit']['purchase_price'],
                    'bedbath_room' => $propertyForm['unit']['bedbath_room'],
                    'purchased_at' => date('Y-m-d',strtotime( $propertyForm['unit']['purchased_at'])),
                    'property_id' => $property->id,
                    'creator_id' => auth()->user()->id,
                    'profile_id' => auth()->user()->profile->id
                ]);
            }
        }

        if($beneficiaryForm['beneficiary_id'] != null and $beneficiaryForm['name'] != null) {
            if($beneficiaryForm['beneficiary_id']) {
                $beneficiary = Beneficiary::findOrFail($beneficiaryForm['beneficiary_id']['id']);
            }else {
                $beneficiary = Beneficiary::create([
                    'name' => $beneficiaryForm['name'],
                    'email' => $beneficiaryForm['email'],
                    'phone_number' => $beneficiaryForm['phone_number'],
                    'alt_phone_number' => $beneficiaryForm['alt_phone_number'],
                    'id_value' => $beneficiaryForm['id_value'],
                    'idtype_id' => $beneficiaryForm['idtype_id'],
                    'address' => $beneficiaryForm['address'],
                    'city' => $beneficiaryForm['city'],
                    'state' => $beneficiaryForm['state'],
                    'postcode' => $beneficiaryForm['postcode'],
                    'occupation' => $beneficiaryForm['occupation'],
                    'invest_property_num' => $beneficiaryForm['invest_property_num'],
                    'remarks' => $beneficiaryForm['remarks'],
                    'gender_id' => $beneficiaryForm['gender_id'],
                    'nationality_id' => $beneficiaryForm['nationality_id'],
                    'race_id' => $beneficiaryForm['race_id'],
                    'creator_id' => auth()->user()->id,
                    'profile_id' => auth()->user()->profile->id
                ]);
            }
        }

        $arc = request('tenancyForm')['arc'];

        if($tenancyForm) {
            $tenancy = Tenancy::create([
                'code' => $this->generateRefNumber(auth()->user()->profile_id),
                'tenancy_date' => $tenancyForm['tenancy_date'],
                'datefrom' => $tenancyForm['datefrom'],
                'dateto' => $tenancyForm['dateto'],
                'duration_month' => $tenancyForm['duration_month']['id'],
                'rental' => $tenancyForm['rental'],
                'deposit' => $tenancyForm['deposit'],
                'room_name' => $tenancyForm['room_name'],
                'is_arc' => $arc ? 1 : 0,
                'status' => $this->checkIsDateValid($tenancyForm['datefrom'], $tenancyForm['dateto']) ? 1 : 0,
                'unit_id' => $unit ? $unit->id : null,
                'beneficiary_id' => $beneficiary ? $beneficiary->id : null,
                'tenant_id' => $tenant ? $tenant->id : null,
                'creator_id' => auth()->user()->id,
                'profile_id' => auth()->user()->profile->id
            ]);

            // store tenancy running number to profile
            if($tenancy) {
                $tenancy->profile->update([
                    'tenancy_running_num' => $tenancy->code
                ]);
            }
        }

        // if arc successfully created, send the form
        if($arc) {
            $this->createAndSendArcForm($tenancy->id);
        }

        // if register tenant register as system user clicked, register user using tenant credential
        $tenant_register_user = isset(request('tenancyForm')['tenant_register_user']);

        if($tenant_register_user) {
            $tenant->user_id = $this->syncNewUser($tenant, 'tenant');
            $tenant->save();
        }
    }

    // delete single tenancy api(int tenancy_id)
    public function destroySingleTenancyApi($tenancy_id)
    {
        $tenancy = Tenancy::findOrFail($tenancy_id);
        $tenancy->delete();
    }

    // upload tenancy agreement api (int tenancy_id)
    public function uploadTenancyAgreement($tenancy_id)
    {
        // dd(request()->all());
        $tenancy = Tenancy::findOrFail($tenancy_id);
        $agreement = request('agreement');
        if($agreement) {
            File::delete(public_path() . $tenancy->agreement_url);
            $name = (Carbon::now()->format('dmYHi')) . $agreement->getClientOriginalName();
            $agreement->move('tenancy/' . $tenancy->id . '/', $name);
            $tenancy->agreement_url = '/tenancy/' . $tenancy->id . '/' . $name;
            $tenancy->save();
        }
    }

    // remove tenancy agreement api (int tenancy_id)
    public function removeTenancyAgreement($tenancy_id)
    {
        $tenancy = Tenancy::findOrFail($tenancy_id);
        $tenancy->agreement_url = null;
        $tenancy->save();
    }

    // tenancies api filter(Query query)
    private function filterTenanciesApi($query)
    {
        $property_id = request('property_id');
        $unit_id = request('unit_id');
        $tenant_id = request('tenant_id');
        $tenant_name = request('tenant_name');
        $tenant_phone_number = request('tenant_phone_number');
        $datefrom = request('datefrom');
        $dateto = request('dateto');
        $code = request('code');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($property_id) {
            $query = $query->where('properties.id', $property_id);
        }
        if($unit_id) {
            $query = $query->where('units.id', $unit_id);
        }
        if($tenant_id) {
            $query = $query->where('tenants.id', $tenant_id);
        }
        if($tenant_name) {
            $query = $query->where('tenants.name', 'LIKE', '%'. $tenant_name.'%');
        }
        if($tenant_phone_number) {
            $query = $query->where('tenants.phone_number', 'LIKE', '%'.$tenant_phone_number.'%');
        }
        if($code) {
            $query = $query->where('tenancies.code', 'LIKE', '%'.$code.'%');
        }
        if($datefrom) {
            $query = $query->where(function($subquery) use ($datefrom){
                $subquery->whereDate('tenancies.datefrom', '>=', $datefrom)
                    ->orWhereDate('tenancies.dateto', '>=', $datefrom);
            });
        }
        if($dateto) {
            $query = $query->where(function($subquery) use ($dateto) {
                $subquery->whereDate('tenancies.datefrom', '<=', $dateto)
                    ->orWhereDate('tenancies.dateto', '<=', $dateto);
            });
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->latest('tenancies.created_at');
        }

        return $query;
    }

}