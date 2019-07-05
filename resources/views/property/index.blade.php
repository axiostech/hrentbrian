@extends('layouts.app')
@section('header')
    <a href="/property" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-home"></i>
        Properties
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexPropertyController">
                <index-property></index-property>
            </div>
        </div>
    </div>
</div>

<template id="index-property-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-home"></i>
              Properties
          </span>
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#property_modal" @click="createSingleProperty">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Property Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="state" class="control-label">State</label>
                <input type="text" name="state" class="form-control" v-model="search.state" placeholder="State" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">City</label>
                <input type="text" name="city" class="form-control" v-model="search.city" placeholder="City" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="type_id" class="control-label">Type</label>
                <select2 v-model="search.type_id">
                    <option value="">All</option>
                    <option v-for="type in propertytype_options" :value="type.value">
                      @{{type.label}}
                </select2>
{{--                 <v-select name="type_id" v-model="search.type_id" :options="propertytype_options" placeholder="Select Type">
                </v-select> --}}
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
                            <a href="#" @click="sortBy('name')">Name</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('state')">State</a>
                            <span v-if="sortkey == 'state' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'state' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('city')">City</a>
                            <span v-if="sortkey == 'city' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'city' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('postcode')">Postcode</a>
                            <span v-if="sortkey == 'postcode' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'postcode' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('type_name')">Type</a>
                            <span v-if="sortkey == 'type_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'type_name' && reverse" class="fa fa-caret-up"></span>
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
                            @{{ data.state }}
                        </td>
                        <td class="text-center">
                            @{{ data.city }}
                        </td>
                        <td class="text-center">
                            @{{ data.postcode }}
                        </td>
                        <td class="text-left">
                            @{{ data.type_name }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#property_modal" @click="editSingleProperty(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" @click="removeSingleProperty(data)">
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
    <form-property @updatetable="searchData" :data="formdata"></form-property>
  </div>
</template>

@include('property.form')
@endsection
