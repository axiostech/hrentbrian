@extends('layouts.app')
@section('header')
    <a href="/unit" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-city"></i>
        Units
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexUnitController">
                <index-unit></index-unit>
            </div>
        </div>
    </div>
</div>

<template id="index-unit-template">
  <div>
    <div class="card">
    <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-city"></i>
              Unit
          </span>
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#unit_modal" @click="createSingleUnit">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="property_id" class="control-label">Property</label>
                <select2 v-model="search.property_id">
                    <option value="">All</option>
                    <option v-for="property in property_options" :value="property.id">
                      @{{property.name}}
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="unit_id" class="control-label">Unit Address</label>
                <select2 v-model="search.unit_id">
                    <option value="">All</option>
                    <option v-for="unit in unit_options" :value="unit.id">
                      (@{{unit.property_name}}) @{{unit.block_number}} @{{unit.unit_number}} <span v-if="unit.address">,</span> @{{unit.address}}
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
                            <a href="#" @click="sortBy('block_number')">Block Number</a>
                            <span v-if="sortkey == 'block_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'block_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('unit_number')">Unit Number</a>
                            <span v-if="sortkey == 'unit_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'unit_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('address')">Address</a>
                            <span v-if="sortkey == 'address' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'address' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('property_id')">Property</a>
                            <span v-if="sortkey == 'property_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'property_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.block_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.unit_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.address }}
                        </td>
                        <td class="text-center">
                            @{{ data.property_name }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#service_modal" @click="editServices(data)">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#unit_modal" @click="editSingleUnit(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" @click="removeSingleUnit(data)">
                                <i class="fas fa-trash"></i>
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
    <form-unit @updatetable="searchData" :data="formdata"></form-unit>
    <form-service @updatetable="searchData" :data="formdata"></form-service>
  </div>
</template>

@include('unit.form')
@include('unit.services')
@endsection
