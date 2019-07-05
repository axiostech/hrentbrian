@extends('layouts.app')
@section('header')
    <a href="/tenancy" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-file-signature"></i>
        Tenancies
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexTenancyController">
                <index-tenancy></index-tenancy>
            </div>
        </div>
    </div>
</div>

<template id="index-tenancy-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
                  <i class="fas fa-file-signature"></i>
                  Tenancies
          </span>
          <button type="button" class="btn bg-primary btn-sm ml-auto text-white" data-toggle="modal" data-target="#tenancy_modal" @click="createSingleTenancy">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
{{--
        <div class="alert alert-success" v-if="collection_message">
            Collection successfully created
        </div> --}}
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="code" class="control-label">Tenancy ID</label>
                <input type="text" name="code" class="form-control" v-model="search.code" placeholder="Tenancy ID" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="tenant_name" class="control-label">Tenant Name</label>
                <input type="text" name="tenant_name" class="form-control" v-model="search.tenant_name" placeholder="Tenant Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="tenant_mobile_number" class="control-label">Tenant Tel No.</label>
                <input type="text" name="tenant_mobile_number" class="form-control" v-model="search.tenant_mobile_number" placeholder="Tenant Tel No." autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">Property</label>
                <select2 v-model="search.property_id" @input="fetchUnitSearchOptions">
                    <option value="">All</option>
                    <option v-for="property in property_options" :value="property.value">
                      @{{property.label}}
                </select2>
{{--                 <v-select name="property_id" v-model="search.property_id" :options="property_options" placeholder="Select Property" @change="fetchUnitSearchOptions">
                </v-select> --}}
            </div>
{{--             <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="property_id" class="control-label">Property</label>
                <v-select name="property_id" v-model="search.property_id" :options="property_options" placeholder="Select Property" @change="fetchUnitSearchOptions">
                </v-select>
            </div> --}}
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="unit_id" class="control-label">Unit</label>
                <select2 v-model="search.unit_id">
                    <option value="">All</option>
                    <option v-for="unit in unit_options" :value="unit.value">
                      @{{unit.label}}
                </select2>
{{--                 <v-select name="unit_id" v-model="search.unit_id" :options="unit_options" placeholder="Select Unit" :disabled="unit_options == 0">
                </v-select> --}}
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="datefrom" class="control-label">Date valid From</label>
                <div class="input-group">
                <datetimepicker name="datefrom" v-model="search.datefrom" placeholder="Date valid From" autocomplete="off"></datetimepicker>
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="dateto" class="control-label">Date Valid To</label>
                <div class="input-group">
                <datetimepicker name="dateto" v-model="search.dateto" placeholder="Date valid To" autocomplete="off"></datetimepicker>
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                </div>
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
                <table class="table table-bordered table-hover table-sm">
                    <tr class="table-secondary">
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('code')">ID</a>
                            <span v-if="sortkey == 'code' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'code' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('property_name')">Property</a>
                            <span v-if="sortkey == 'property_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'property_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('unit_name')">Unit</a>
                            <span v-if="sortkey == 'unit_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'unit_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            Room Name
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tenant_name')">Tenant Name</a>
                            <span v-if="sortkey == 'tenant_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tenant_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tenant_phone_number')">Tenant Tel.</a>
                            <span v-if="sortkey == 'tenant_phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tenant_phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('datefrom')">Tenancy Period</a>
                            <span v-if="sortkey == 'datefrom' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'datefrom' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            Remaining Day(s)
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('rental')">Rental</a>
                            <span v-if="sortkey == 'rental' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'rental' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('beneficiary_name')">Beneficiary</a>
                            <span v-if="sortkey == 'beneficiary_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'beneficiary_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('arc_status')">ARC Status</a>
                            <span v-if="sortkey == 'arc_status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'arc_status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('status')">Status</a>
                            <span v-if="sortkey == 'status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            Action
                        </th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.code }}
                        </td>
                        <td class="text-left">
                            @{{ data.property_name }}
                        </td>
                        <td class="text-left">
                            @{{data.block_number}}<span v-if="data.block_number">-</span>@{{data.unit_number}}<span v-if="data.address">-</span>@{{data.address}}
                        </td>
                        <td class="text-center">
                            @{{ data.room_name }}
                        </td>
                        <td class="text-left">
                            @{{ data.tenant_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.tenant_phone_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.datefrom }}
                            <br>
                            @{{ data.dateto }}
                        </td>
                        <td class="text-center">
                            @{{data.dateto | datehumanize}}
                        </td>
                        <td class="text-right">
                            @{{ data.rental }}
                        </td>
                        <td class="text-left">
                            @{{ data.beneficiary_name }}
                        </td>
                        <td class="text-left">
                            <div v-if="data.arc_status == 1">
                                <span class="badge badge-success">
                                    ARC Link Sent
                                </span>
                                <span class="badge badge-secondary">
                                    Pending Tenant
                                </span>
                            </div>
                            <div v-else-if="data.arc_status == 2">
                                <span class="badge badge-success">
                                    Tenant Viewed
                                </span>
                                <span class="badge badge-secondary">
                                    Pending Tenant
                                </span>
                            </div>
                            <div v-else-if="data.arc_status == 3">
                                <span class="badge badge-success">
                                    Tenant Approved
                                </span>
                                <span class="badge badge-info">
                                    Pending Bank
                                </span>
                            </div>
                            <div v-else-if="data.arc_status == 4">
                                <span class="badge badge-danger">
                                    Tenant Unsuccessful
                                </span>
                                <span class="badge badge-secondary">
                                    Pending Tenant
                                </span>
                            </div>
                            <div v-else-if="data.arc_status == 5">
                                <span class="badge badge-success">
                                    Bank Approved (Collection Enabled)
                                </span>
                            </div>
                            <div v-else-if="data.arc_status == 6">
                                <span class="badge badge-danger">
                                    Bank Rejected (Create again)
                                </span>
                            </div>
                            <div v-else>
                                <span class="badge badge-secondary">
                                    Non ARC
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success" v-if="data.status">
                                Active
                            </span>
                            <span class="badge badge-secondary" v-else>
                                Inactive
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#edit_tenancy_modal" @click="editSingleTenancy(data.tenancy_id)">
                                <i class="fas fa-edit"></i>
                            </button>
{{--
                            <button class="btn btn-outline-info btn-sm" v-if="data.arc_status <= 2 || !data.arc_status" @click="registerARC(data.tenancy_id, null)">
                                ARC
                            </button>
                            <button class="btn btn-outline-success btn-sm" v-if="data.arc_status <= 2 || !data.arc_status" @click="sendArcWhatsapp(data.tenancy_id)">
                                <i class="fab fa-whatsapp"></i>
                            </button> --}}
                            </div>
{{--
                            <button class="btn btn-outline-secondary btn-sm" @click="makeCollection(data)">
                                Pay
                            </button> --}}
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
    <form-tenancy @updatetable="searchData" :data="formdata"></form-tenancy>
    @include('tenancy.editform')
  </div>
</template>

@include('tenancy.form')
@endsection
