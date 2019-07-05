<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Tenancy;
use App\Utilityrecord;
use App\Traits\FilterPhoneNumber;
use App\Traits\GenerateUtilityRecordUrlContent;
use Carbon\Carbon;
use DB;

class UtilityrecordController extends Controller
{
    use FilterPhoneNumber, GenerateUtilityRecordUrlContent;
    // auth control
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getTenantUtilityRecordIndex', 'getUtilityRecordIndexApi', 'storeUpdateUtilityrecordApi']]);
    }

    // send utility record form via whatsapp
    public function sendUtilityRecordFormWhatsapp($tenancy_id)
    {
        $tenancy = Tenancy::findOrFail($tenancy_id);

        $url = $this->generateUtilityRecordUrl($tenancy->id);
        $message = $this->generateUtilityRecordMessage($tenancy->id, $url);
        $phone_number_noplus = $this->removePhoneNumberPlus($tenancy->tenant->phone_number);
        return [
            'message' => $message,
            'phone_number_noplus' => $phone_number_noplus,
        ];
    }

    // return view for utilityrecords(String token)
    public function getTenantUtilityRecordIndex($token)
    {
        $token_data = json_decode(decrypt($token), true);
        // $utilityrecords = $this->getUtilityRecordIndexApi($tenancy->id);
        $tenancy_id = $token_data['tenancy_id'];
        $tenancy = Tenancy::findOrFail($token_data['tenancy_id']);
/*
        $data = [
            'tenancy' => $tenancy,
            'utilityrecords' => $utilityrecords
        ]; */

        return view('utilityrecord.form_tenant', compact('tenancy_id', 'tenancy'));
    }

    // return view for system user()
    public function getUtilityRecordIndex()
    {
        return view('utilityrecord.index');
    }

    // return view for system user()
    public function getUtilityrecordIndexApi($tenancy_id = null)
    {
        $perpage = request('perpage');

        $utilityrecords = Utilityrecord::leftJoin('tenancies', 'tenancies.id', '=', 'utilityrecords.tenancy_id')
            ->leftJoin('units', 'units.id', '=', 'tenancies.unit_id')
            ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
            ->leftJoin('tenants', 'tenants.id', '=', 'tenancies.tenant_id')
            ->select(
                'utilityrecords.id', 'utilityrecords.tenancy_id', 'utilityrecords.image_url', 'utilityrecords.reading', 'utilityrecords.remarks',
                'utilityrecords.status', 'utilityrecords.type', 'utilityrecords.type', 'utilityrecords.year', 'utilityrecords.month',
                DB::raw('DATE(utilityrecords.created_at) AS created_date'),
                'utilityrecords.is_request_cancel',
                'tenancies.code AS tenancy_code', 'tenancies.room_name AS tenancy_room_name',
                'properties.name AS property_name',
                'units.block_number AS unit_block_number', 'units.unit_number AS unit_unit_number', 'units.address AS unit_address',
                'tenants.name AS tenant_name', 'tenants.phone_number AS tenant_phone_number'
            );

        $utilityrecords = $utilityrecords->filterProfile();

        $utilityrecords = $this->filterUtilityrecordApi($utilityrecords);

        if($tenancy_id and $tenancy_id != 'null') {
            $utilityrecords = $utilityrecords->where('utilityrecords.tenancy_id', $tenancy_id);
        }
        // dd($utilityrecords->get()->toArray());
        if($perpage != 'All') {
            $utilityrecords = $utilityrecords->paginate($perpage);
        }else {
            $utilityrecords = $utilityrecords->get();
        }
        return $utilityrecords;
    }

    // store or update new individual profile
    public function storeUpdateUtilityrecordApi()
    {
        $this->validate(request(), [
            'image' => 'required',
            'monthyear' => 'required',
            'type' => 'required'
        ]);

        $tenancy = Tenancy::findOrFail(request('tenancy_id'));
        $monthyear = request('monthyear');

        $fieldsArr = [
            'tenancy_id' => $tenancy->id,
            'image' => request('image'),
            'reading' => request('reading'),
            'month' => explode("-", $monthyear)[0],
            'year' => explode("-", $monthyear)[1],
            'type' => request('type'),
            'remarks' => request('remarks'),
            'profile_id' => $tenancy->profile->id,
            'creator_id' => auth()->check() ? auth()->user()->id : null
        ];

        $utilityrecord = Utilityrecord::create($fieldsArr);

        $image = request('image');
        if($image) {
            File::delete(public_path() . $utilityrecord->image_url);
            $name = (Carbon::now()->format('dmYHi')) . $image->getClientOriginalName();
            $image->move('tenancy/utilityrecord/' . request('tenancy_id') . '/', $name);
            $utilityrecord->image_url = '/tenancy/utilityrecord/' . request('tenancy_id') . '/' . $name;
            $utilityrecord->save();
        }
    }

    // request to remove utilityrecord api(int utilityrecord_id)
    public function requestRemoveSingleUtilityrecordApi($utilityrecord_id)
    {
        $utilityrecord = Utilityrecord::findOrFail($utilityrecord_id);
        $utilityrecord->is_request_cancel = 1;
        $utilityrecord->save();
    }

    // approve or reject single entry utilityrecord api (int utilityrecord_id)
    public function approveRejectUtilityrecord($utilityrecord_id, $decision)
    {
        $utilityrecord = Utilityrecord::findOrFail($utilityrecord_id);
        if($decision == 'verified') {
            $utilityrecord->status = 2;
        }else if($decision == 'rejected') {
            $utilityrecord->status = 3;
        }
        $utilityrecord->save();
    }

    // utilityrecords api filter(Query query)
    private function filterUtilityrecordApi($query)
    {
        $monthyear = request('monthyear');
        $type = request('type');
        $tenancy_id = request('tenancy_id');
        $property_id = request('property_id');
        $tenant_name = request('tenant_name');
        $tenant_phone_number = request('tenant_phone_number');
        $tenancy_code = request('tenancy_code');
        $tenancy_room_name = request('tenancy_room_name');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($monthyear) {
            $month = explode("-", $monthyear)[0];
            $year = explode("-", $monthyear)[1];

            $query = $query->where('utilityrecords.month', $month)->where('utilityrecords.year', $year);
        }
        if($type) {
            $query = $query->where('utilityrecords.type', $type);
        }
        if($property_id) {
            $query = $query->where('properties.id', $property_id);
        }
        if($tenant_name) {
            $query = $query->where('tenants.name', 'LIKE', '%'.$tenant_name.'%');
        }
        if($tenant_phone_number) {
            $query = $query->where('tenants.phone_number', 'LIKE', '%'.$tenant_phone_number.'%');
        }
        if($tenancy_code) {
            $query = $query->where('tenancies.code', 'LIKE', '%'.$tenancy_code.'%');
        }
        if($tenancy_room_name) {
            $query = $query->where('tenancies.room_name', 'LIKE', '%'.$tenancy_room_name.'%');
        }
        if($tenancy_id and $tenancy_id != 'null') {
            $query = $query->where('utilityrecords.tenancy_id', $tenancy_id);
        }
        if ($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        } else {
            $query = $query->latest('utilityrecords.created_at');
        }

        return $query;
    }

}
