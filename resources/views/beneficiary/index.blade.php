@extends('layouts.app')
@section('header')
    <a href="/beneficiary" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-address-card"></i>
        Beneficiaries
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexBeneficiaryController">
                <index-beneficiary></index-beneficiary>
            </div>
        </div>
    </div>
</div>

<template id="index-beneficiary-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="far fa-address-card"></i>
              Beneficiaries
          </span>
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#beneficiary_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="phone_number" class="control-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" v-model="search.phone_number" placeholder="Phone Number" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="nric" class="control-label">ID Value</label>
                <input type="text" name="id_value" class="form-control" v-model="search.id_value" placeholder="ID Value" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="email" class="control-label">Email</label>
                <input type="text" name="email" class="form-control" v-model="search.email" placeholder="Email" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">Status</label>
                <select2 v-model="search.status" @input="fetchUnitSearchOptions">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
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
                                <small>You ‘ve changed the filter, Search?</small>
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
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('name')">Name</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('idtype_name')">ID Type</a>
                            <span v-if="sortkey == 'idtype_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'idtype_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('id_value')">ID Value</a>
                            <span v-if="sortkey == 'id_value' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'id_value' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('phone_number')">Phone Number</a>
                            <span v-if="sortkey == 'phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('alt_phone_number')">Alt Phone Number</a>
                            <span v-if="sortkey == 'alt_phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'alt_phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('email')">Email</a>
                            <span v-if="sortkey == 'email' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'email' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('invest_property_num')">Property Count</a>
                            <span v-if="sortkey == 'invest_property_num' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'invest_property_num' && reverse" class="fa fa-caret-up"></span>
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
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-left">
                            @{{ data.name }}
                        </td>
                        <td class="text-center">
                            @{{ data.idtype_name }}
                        </td>
                        <td class="text-left">
                            @{{ data.id_value }}
                        </td>
                        <td class="text-left">
                            @{{ data.phone_number }}
                        </td>
                        <td class="text-left">
                            @{{ data.alt_phone_number }}
                        </td>
                        <td class="text-left">
                            @{{ data.email }}
                        </td>
                        <td class="text-right">
                            @{{ data.invest_property_num }}
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
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#beneficiary_modal" @click="editSingleEntry(data)">
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
    <form-beneficiary @updatetable="searchData" :data="formdata"></form-beneficiary>
  </div>
</template>

@include('beneficiary.form')
@endsection
