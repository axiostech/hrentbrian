@extends('layouts.app')
@section('header')
    <a href="/utilityrecord" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-tachometer-alt"></i>
        Utilities Records
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexUtilityrecordController">
                <index-utilityrecord></index-utilityrecord>
            </div>
        </div>
    </div>
</div>

<template id="index-utilityrecord-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-tachometer-alt"></i>
              Utilities Records
          </span>
{{--
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#utilityrecord_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button> --}}
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Property Name</label>
                <select2 v-model="search.property_id" @input="onFilterChanged">
                    <option value="">All</option>
                    <option v-for="property in property_options" :value="property.id">
                      @{{property.name}}
                    </option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Tenant Name</label>
                <input type="text" name="tenant_name" class="form-control" v-model="search.tenant_name" placeholder="Tenant Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Tenant Phone Number</label>
                <input type="text" name="tenant_phone_number" class="form-control" v-model="search.tenant_phone_number" placeholder="Tenant Phone Number" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Tenancy ID</label>
                <input type="text" name="tenancy_code" class="form-control" v-model="search.tenancy_code" placeholder="Tenancy ID" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label class="control-label">Room Name</label>
                <input type="text" name="tenancy_room_name" class="form-control" v-model="search.tenancy_room_name" placeholder="Room Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="domain_name" class="control-label">Month Year</label>
                <select2 v-model="search.monthyear" @input="onFilterChanged">
                    <option value="">All</option>
                    <option v-for="monthname in monthyear_options" :value="monthname.id">
                      @{{monthname.name}}
                    </option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="domain_name" class="control-label">Type</label>
                <select2 v-model="search.type" @input="onFilterChanged">
                  <option value="">All</option>
                    @foreach(config('constant_utilityrecord.utilityrecord_type') as $type)
                        <option value="{{$type['id']}}">
                            {{$type['name']}}
                        </option>
                    @endforeach
                </select2>
            </div>
        </div>
        <div class="form-row">
            <div class="mr-auto">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-light btn-outline-dark">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                </div>
                <div class="form-row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="mr-auto">
                            <span class="font-weight-light" v-if="filterchanged">
                                <small>You have changed the filter, Search?</small>
                            </span>
                        </div>
                    </div>
                </div>
                <pulse-loader :loading="searching" :height="50" :width="100" style="padding-top:5px;"></pulse-loader>
            </div>
            <div class="ml-auto">
                <div>
                    <label for="display_num">Display</label>
                    <select v-model="selected_page" name="pageNum" @change="fetchTable">
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                        <option value="All">All</option>
                    </select>
                    <label for="display_num2" style="padding-right: 20px">per Page</label>
                </div>
                <div>
                    <label class="" style="padding-right:18px;" for="totalnum">Showing @{{list.length}} of @{{pagination.total}} entries</label>
                </div>
            </div>
        </div>
        </form>

        <div class="form-row" style="padding-top: 20px;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tr class="table-secondary">
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('month')">Month</a>
                            <span v-if="sortkey == 'month' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'month' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('year')">Year</a>
                            <span v-if="sortkey == 'year' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'year' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('type')">Type</a>
                            <span v-if="sortkey == 'type' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'type' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tenancy_code')">Tenancy ID</a>
                            <span v-if="sortkey == 'tenancy_code' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tenancy_code' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tenancy_room_name')">Room Name</a>
                            <span v-if="sortkey == 'tenancy_room_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tenancy_room_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('property_name')">Property Name</a>
                            <span v-if="sortkey == 'property_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'property_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('unit_block_number')">Block</a>
                            <span v-if="sortkey == 'unit_block_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'unit_block_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('unit_unit_number')">Unit Number</a>
                            <span v-if="sortkey == 'unit_unit_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'unit_unit_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('unit_address')">Address</a>
                            <span v-if="sortkey == 'unit_address' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'unit_address' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tenant_name')">Tenant Name</a>
                            <span v-if="sortkey == 'tenant_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tenant_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tenant_phone_number')">Tenant Phone Number</a>
                            <span v-if="sortkey == 'tenant_phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tenant_phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                          Image
                        </th>
                        <th class="text-center">
                          Readings
                        </th>
                        <th class="text-center">
                          Status
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.month | monthname }}
                        </td>
                        <td class="text-center">
                            @{{ data.year }}
                        </td>
                        <td class="text-center">
                          <div v-if="data.type == 1">
                            Electricity
                          </div>
                          <div v-if="data.type == 2">
                            Water
                          </div>
                          <div v-if="data.type == 3">
                            Broadband
                          </div>
                          <div v-if="data.type == 4">
                            Others
                          </div>
                        </td>
                        <td class="text-center">
                            @{{ data.tenancy_code }}
                        </td>
                        <td class="text-center">
                            @{{ data.tenancy_room_name }}
                        </td>
                        <td class="text-left">
                            @{{ data.property_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.unit_block_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.unit_unit_number }}
                        </td>
                        <td class="text-left">
                            @{{ data.unit_address }}
                        </td>
                        <td class="text-center">
                            @{{ data.tenant_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.tenant_phone_number }}
                        </td>
                        <td class="text-center">
                            <img @click="zoomImage(data.image_url)" data-toggle="modal" data-target="#image_modal" v-bind:src="data.image_url" width="50" height="50" v-if="data.image_url">
                        </td>
                        <td class="text-center">
                            @{{ data.reading }}
                        </td>
                        <td class="text-center">
                          <span class="badge badge-danger" v-if="data.is_request_cancel == 1">
                            Tenant Request Cancel
                          </span>
                          <span class="badge badge-secondary" v-if="data.status == 1">
                            New
                          </span>
                          <span class="badge badge-success" v-if="data.status == 2">
                            Validated
                          </span>
                          <span class="badge badge-danger" v-if="data.status == 3">
                            Rejected
                          </span>
                        </td>
                        <td class="text-center">

                            <div class="btn-group">
{{--
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#utilityrecord_modal" @click="editSingleEntry(data)">
                                <i class="fas fa-edit"></i>
                            </button> --}}
                            <button type="button" class="btn btn-success btn-sm" v-if="data.status != 2" @click="approveSingleEntry(data)">
                                <i class="far fa-check-circle"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" v-if="data.status != 3" @click="rejectSingleEntry(data)">
                                <i class="fas fa-ban"></i>
                            </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="! pagination.total">
                        <td colspan="18" class="text-center"> No Results Found </td>
                    </tr>
                </table>
            </div>
            <div class="pull-left">
                <pagination :pagination="pagination" :callback="fetchTable" :offset="4"></pagination>
            </div>
        </div>
      </div>
    </div>
    {{-- <form-utilityrecord @updatetable="searchData" :clearform="clearform" :data="formdata"></form-utilityrecord> --}}

    <div class="modal" id="image_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <img v-bind:src="zoom_image_url" width="100%" height="100%">
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                      <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</template>

{{-- @include('utilityrecord.form') --}}
@endsection
