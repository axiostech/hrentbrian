@extends('layouts.app')
@section('header')
    <a href="/collection" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-users"></i>
        ARC Collection
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexCollectionController">
                <index-collection></index-collection>
            </div>
        </div>
    </div>
</div>

<template id="index-collection-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-table"></i>
              ARC Collections
          </span>
{{--
          <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#tenant_modal" @click="createSingleTenant">
              <i class="fas fa-plus"></i>
          </button> --}}
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Tenant Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Tenant Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">Property</label>
                <select2 v-model="search.property_id" @input="fetchUnitSearchOptions">
                    <option value="">All</option>
                    <option v-for="property in property_options" :value="property.id">
                      @{{property.name}}
                    </option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="unit_id" class="control-label">Unit</label>
                <select2 v-model="search.unit_id">
                    <option value="">All</option>
                    <option v-for="unit in unit_options" :value="unit.value">
                      @{{unit.label}}
                    </option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="monthyear" class="control-label">Month Year</label>
                <select2 v-model="search.monthyear" @input="onFilterChanged">
                    <option v-for="monthname in monthyear_options" :value="monthname.id">
                      @{{monthname.name}}
                    </option>
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
                    <select v-model="selected_page" name="pageNum" @change="searchData">
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
                        <th>
                          <input type="checkbox" id="checkAll"/>
                        </th>
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('name')">Name</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('code')">Tenancy ID</a>
                            <span v-if="sortkey == 'code' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'code' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                          Room Name
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('property_id')">Property</a>
                            <span v-if="sortkey == 'property_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'property_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('unit_name')">Unit</a>
                            <span v-if="sortkey == 'unit_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'unit_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('rental')">Rental</a>
                            <span v-if="sortkey == 'rental' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'rental' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('status')">Status</a>
                            <span v-if="sortkey == 'status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                          <input class="form-check-input" type="checkbox" v-model="data.recurrence_id" :disabled="data.status != '5'">
                        </td>
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-left">
                            @{{ data.name }}
                        </td>
                        <td class="text-left">
                            @{{ data.code }}
                        </td>
                        <td class="text-left">
                            @{{ data.room_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.property_name }}
                        </td>
                        <td class="text-left">
                            @{{data.block_number}}<span v-if="data.block_number">-</span>@{{data.unit_number}}<span v-if="data.address">-</span>@{{data.address}}
                        </td>
                        <td class="text-left">
                            @{{ data.rental }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success" v-if="data.status == '1'">
                                Active
                            </span>
                            <div class="badge badge-secondary" v-else>
                                Inactive
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#collection_modal" @click="editSingleEntry(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" @click="deactivateSingleEntry(data)">
                                <i class="fas fa-user-lock"></i>
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
    {{-- <form-collection @updatetable="searchData" :clearform="clearform" :data="formdata"></form-collection> --}}
  </div>
</template>

{{-- @include('collection.form') --}}
@endsection
