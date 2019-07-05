<?php

namespace App\Http\Controllers;

use Propaganistas\LaravelPhone\PhoneNumber;
use App\Traits\SyncUser;
use App\Tenancy;
use App\Tenant;
use DB;

class TenantController extends Controller
{
    use SyncUser;

    // auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return tenancy index page
    public function index()
    {
        return view('tenant.index');
    }

    // retrieve tenants by given filter
    public function getTenantsApi()
    {
        $perpage = request('perpage');

        $tenants = Tenancy::rightJoin('tenants', 'tenants.id', '=', 'tenancies.tenant_id')
            ->leftJoin('units', 'units.id', '=', 'tenancies.unit_id')
            ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->leftJoin('idtypes', 'idtypes.id', '=', 'tenants.idtype_id')
            ->select(
                'tenants.id', 'tenants.name', 'tenants.email', 'tenants.status', 'tenants.phone_number', 'tenants.alt_phone_number',
                'idtypes.id AS idtype_id', 'idtypes.name AS idtype_name', 'tenants.id_value'
            );

        $tenants = $tenants->filterProfile('tenants');

        $tenants = $this->filterTenantsApi($tenants);

        $tenants = $tenants->groupBy('tenants.id', 'tenants.name', 'tenants.email', 'tenants.status', 'tenants.phone_number', 'tenants.alt_phone_number', 'idtypes.id', 'idtypes.name', 'tenants.id_value');

        if ($perpage != 'All') {
            $tenants = $tenants->paginate($perpage);
        } else {
            $tenants = $tenants->get();
        }
        return $tenants;
    }

    // get all tenants api
    public function getAllTenantsApi()
    {
        $tenants = Tenant::leftJoin('idtypes', 'idtypes.id', '=', 'tenants.idtype_id')
            ->select(
                'tenants.name', 'tenants.email', 'tenants.phone_number', 'tenants.alt_phone_number', 'tenants.email', 'tenants.id_value',
                'idtypes.name AS idtype_name', 'tenants.id'
            )
            ->filterProfile()
            ->orderBy('name')
            ->get();

        return $tenants;
    }

    // deactivate single tenant api(int tenant_id)
    public function deactivateSingleTenantApi($tenant_id)
    {
        $tenant = Tenant::findOrFail($tenant_id);
        $tenant->status = 99;
        $tenant->save();
    }

    // store or update new individual tenant
    public function storeUpdateTenantApi()
    {
        $this->validate(request(), [
            'name' => 'required',
            'idtype_id' => 'required',
            'id_value' => 'required',
            'phone_number' => 'required'
        ]);
        if (request('id')) {
            $tenant = Tenant::findOrFail(request('id'));
            $tenant->update([
                'name' => request('name'),
                'email' => request('email'),
                'status' => request('status') ? request('status') : $tenant->status,
                'phone_number' => request('phone_number'),
                'alt_phone_number' => request('alt_phone_number'),
                'idtype_id' => request('idtype_id'),
                'id_value' => request('id_value'),
                'updater_id' => auth()->user()->id
            ]);

            $this->syncUpdateUser($tenant);
        } else {
            Tenant::create([
                'name' => request('name'),
                'email' => request('email'),
                'phone_number' => request('phone_number'),
                'alt_phone_number' => request('alt_phone_number'),
                'idtype_id' => request('idtype_id'),
                'id_value' => request('id_value'),
                'profile_id' => auth()->user()->profile->id,
                'creator_id' => auth()->user()->id
            ]);
        }
    }

    // tenants api filter(Query query)
    private function filterTenantsApi($query)
    {
        $name = request('name');
        $phone_number = request('phone_number');
        $id_value = request('id_value');
        $email = request('email');
        $status = request('status');
        $property_id = request('property_id');
        $unit_id = request('unit_id');
        $tenancy_id = request('tenancy_id');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('tenants.name', 'LIKE', '%'.$name.'%');
        }
        if($phone_number) {
            $query = $query->where('tenants.phone_number', 'LIKE', '%'.$phone_number.'%');
        }
        if($id_value) {
            $query = $query->where('tenants.id_value', 'LIKE', '%'.$id_value.'%');
        }
        if($email) {
            $query = $query->where('tenants.email', 'LIKE', '%'.$email.'%');
        }
        if($status) {
            $query = $query->where('tenants.status', $status);
        }
        if($property_id) {
            $query = $query->where('properties.id', $property_id);
        }
        if($unit_id) {
            $query = $query->where('units.id', $unit_id);
        }
        if($tenancy_id) {
            $query = $query->where('tenancies.id', $tenancy_id);
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('tenants.created_at', 'desc');
        }

        return $query;
    }
}
