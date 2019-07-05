<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Insurance;
use App\Unit;
use DB;

class InsuranceController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return insurance index page
    public function index()
    {
       
        return view('insurance.index');
    }

    // get all insurances api
    public function getAllInsurancesApi()
    {
        $insurances = DB::table('insurances')
            ->select(
                'insurances.id', 'insurances.name', 'insurances.coverages', 'insurances.remarks'
            )
            ->orderBy('insurances.name')
            ->get();

        return $insurances;
    }

    // retrieve insurances by given filter
    public function getInsurancesApi()
    {
        $perpage = request('perpage');

        $insurances = DB::table('units')
        ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
        ->leftJoin('happyrent_insurance', 'happyrent_insurance.insurance_unit_id', '=', 'units.id')
        ->leftJoin('happyrent_insurance_plan','happyrent_insurance_plan.insurance_plan_id', '=', 'units.id')
        
            ->select(
                'properties.id AS property_id', 'properties.name AS property_name', 'properties.city', 'properties.state',
                'properties.postcode',

                'happyrent_insurance.insurance_id', 'happyrent_insurance.insurance_unit_id', 'happyrent_insurance.insurance_plan_id', 'happyrent_insurance.insurance_transaction_id', 'happyrent_insurance.insurance_date', 'happyrent_insurance.insurance_status',
                'happyrent_insurance.insurance_doc_path', 'happyrent_insurance.created_at', 'happyrent_insurance.updated_at',
                
                
                'units.block_number', 'units.unit_number', 'units.address', 'units.remarks AS unit_remarks', 'units.squarefeet', 'units.purchase_price', 'units.bedbath_room', 'units.purchased_at', 'units.id'
            );
     
            
        $insurances = $this->filterInsurancesApi($insurances);

        if($perpage != 'All') {
            $insurances = $insurances->paginate($perpage);
        }else {
            $insurances = $insurances->get();
        }

        
            $insurances_plans = DB::table('happyrent_insurance_plan')
            ->select(
                'happyrent_insurance_plan.insurance_plan_id', 'happyrent_insurance_plan.insurance_plan_name', 'happyrent_insurance_plan.insurance_plan_price', 'happyrent_insurance_plan.insurance_plan_duration','happyrent_insurance_plan.insurance_plan_status'
            )
            ->orderBy('happyrent_insurance_plan.insurance_plan_name')
            ->get();

            $insurances_re['insurances'] = $insurances;
            $insurances_re['plans'] = $insurances_plans;
        return $insurances_re;
    }

    // store or update new individual insurance
    public function storeUpdateInsuranceApi(Request $request)
    {  
        if($request->session()->has('current_checkout_data'))
        $request->session()->forget('current_checkout_data');

        $data = $request->all();
        $total =0;
        foreach($data as $value){
            
            $myarray[$value['unit_number']] = $value['plan_amount'];
            $plans[$value['unit_number']] = $value['plan_id'];
            
           

        }
        
        foreach($myarray as $val){
            $total += $val;
        }
        $setting_details = DB::table('happyrent_site_setting')
        ->select(
            'happyrent_site_setting.billplz_secret_key',
            'happyrent_site_setting.billplz_signature',
            'happyrent_site_setting.billplz_collection_id', 
            'happyrent_site_setting.tax_amount'
        )->first();
       
        $response = array(
            'response_data' => $myarray,
            'total' => $total,
            'plans' => $plans,
            'tax_amount' => $setting_details->tax_amount

        );
        
        $request->session()->put('row_data',$response);
        $request->session()->put('current_checkout_data',$response);
        // $request->session()->forget('current_checkout_data');//delete session

        // if($request->session()->has('current_checkout_data'))
        //  echo $request->session()->get('current_checkout_data');//get session


        return $response;
        
    }

    // delete single insurance api(int insurance_id)
    public function destroySingleInsuranceApi($insurance_id)
    {
        $insurance = Insurance::findOrFail($insurance_id);
        $insurance->delete();
    }

    // insurances api filter(Query query)
    private function filterInsurancesApi($query)
    {
        $block_number = request('block_number');//name
        $unit_number = request('unit_number');//coverages
        $address = request('address');//remarks
        $status = request('status');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($block_number) {
            $query = $query->where('units.block_number', 'LIKE', '%'.$block_number.'%');
        }
        if($unit_number) {
            $query = $query->where('units.unit_number', 'LIKE', '%'.$unit_number.'%');
        }
        if($address) {
            $query = $query->where('units.address', 'LIKE', '%'.$address.'%');
        }
        // if($status) {
        //     $query = $query->where('units.status', $status);
        // }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('units.block_number');
        }

        return $query;
    }
}
