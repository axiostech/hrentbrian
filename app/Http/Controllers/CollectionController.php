<?php

namespace App\Http\Controllers;

use App\Recurrence;
use DB;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CollectionController extends Controller
{    // auth access
  public function __construct()
  {
    $this->middleware('auth');
  }

  // return collection index page
  public function index()
  {
    return view('collection.index');
  }

  // get all recurrences api
  public function getRecurrencesApi()
  {
    $perpage = request('perpage');

    $recurrences = Recurrence::leftJoin('arcs', 'arcs.id', '=', 'recurrences.arc_id')
        ->leftJoin('tenancies', 'tenancies.id', '=', 'arcs.tenancy_id')
        ->leftJoin('tenants', 'tenants.id', '=', 'tenancies.tenant_id')
        ->leftJoin('units', 'units.id', '=', 'tenancies.unit_id')
        ->leftJoin('properties', 'properties.id', '=', 'units.property_id')
        ->select(
          'recurrences.recurrence_id', 'recurrences.total_term', 'recurrences.current_term', 'recurrences.paid_amount', 'recurrences.total_amount',
          DB::raw('DATE(recurrences.paid_date) AS paid_date'), 'recurrences.month_send_date', 'recurrences.month_due_date', 'recurrences.recurrence_status', 'recurrences.month', 'recurrences.year', 'recurrences.is_auto',
          'arcs.type', 'arcs.status AS arc_status', 'arcs.ref_number', 'arcs.id AS arc_id', 'arcs.name AS arc_name',
          'tenants.name AS tenant_name',
          'tenancies.id AS tenancy_id', 'tenancies.tenancy_date', 'tenancies.datefrom', 'tenancies.dateto', 'tenancies.code', 'tenancies.room_name', 'tenancies.rental',
          'units.block_number', 'units.unit_number', 'units.address',
          'properties.name AS property_name'
        );

    $recurrences = $this->filterRecurrencesApi($recurrences);

    $recurrences = $recurrences->filterProfile('tenancies');

    $monthyear = request('monthyear');
    if($monthyear) {
      $month_num = explode("-", $monthyear)[0];
      $year_num = explode("-", $monthyear)[0];

      $recurrences = $recurrences->where(function($q) use ($month_num, $year_num) {
        $q->where('recurrences.month', '<>', $month_num)->where('recurrences.year', '<>', $year_num);
      });
    }

    if ($perpage != 'All') {
      $recurrences = $recurrences->paginate($perpage);
    } else {
      $recurrences = $recurrences->get();
    }

    return $recurrences;
  }

  // recurrences api filter(Query query)
  private function filterRecurrencesApi($query)
  {
    $property_id = request('property_id');
    $tenant_name = request('tenant_name');
    $unit_id = request('unit_id');
    $sortkey = request('sortkey');
    $reverse = request('reverse');

    if ($property_id) {
      $query = $query->where('properties.id', $property_id);
    }
    if ($tenant_name) {
      $query = $query->where('tenants.name', 'LIKE', '%' . $tenant_name . '%');
    }
    if ($unit_id) {
      $query = $query->where('units.id', $unit_id);
    }
    if ($sortkey) {
      $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
    } else {
      $query = $query->orderBy('tenancies.tenancy_date', 'desc');
    }

    return $query;
  }
}
