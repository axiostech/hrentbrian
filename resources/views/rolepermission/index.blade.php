@extends('layouts.app')
@section('header')
    <a href="/rolepermission" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-id-badge"></i>
        Role & Permission
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexRoleController">
                <index-role></index-role>
            </div>
            <div id="indexPermissionController" class="pt-3">
                <index-permission></index-permission>
            </div>
        </div>
    </div>
</div>

<template id="index-role-template">
  <div>
    {{-- @can('read roles') --}}
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-id-badge"></i>
              Role
          </span>
          {{-- @can('create roles') --}}
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#role_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          {{-- @endcan --}}
          </div>
      </div>
      <div class="card-body">
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Role Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Role Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="status" class="control-label">Status</label>
                <select2 v-model="search.status">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="99">Inactive</option>
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
                            <a href="#" @click="sortBy('name')">Name</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('name')">Attached Permissions</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
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
                        <td class="text-left">
                            @{{ data.display_name }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            {{-- @can('update roles') --}}
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#role_modal" @click="editSingleEntry(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            {{-- @endcan --}}
                            {{-- @can('deactivate roles') --}}
{{--
                            <button type="button" class="btn btn-danger btn-sm" @click="toggleSingleEntry(data)">
                                <i class="fas fa-ban"></i>
                            </button> --}}
                            {{-- @endcan --}}
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
{{--
    @else
        @include('error.unauthorised')
    @endcan --}}
    <form-role @updatetable="searchData" :data="formdata"></form-role>
  </div>
</template>

<template id="index-permission-template">
  <div>
    {{-- @can('read permissions') --}}
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-id-badge"></i>
              Permission
          </span>
          {{-- @can('create permissions') --}}
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#permission_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          {{-- @endcan --}}
          </div>
      </div>
      <div class="card-body">
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Permission Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Permission Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="status" class="control-label">Status</label>
                <select2 v-model="search.status">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="99">Inactive</option>
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
                            <a href="#" @click="sortBy('name')">Name</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('table_name')">Module Name</a>
                            <span v-if="sortkey == 'table_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'table_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-left">
                            <h5>
                                <span class="badge" :class="'badge-'+data.label">
                                    @{{ data.name }}
                                </span>
                            </h5>
                        </td>
                        <td class="text-left">
                            @{{ data.table_name }}
                        </td>
{{--
                        <td class="text-center">
                            <div class="btn-group">
                            @can('deactivate permissions')
                            <button type="button" :class="data.status==1 ? 'btn-danger' : 'btn-success'" class="btn btn-sm" @click="toggleSingleEntry(data)">
                                <i class="fas" :class="data.status==1 ? 'fa-ban' : 'fa-check-circle'"></i>
                            </button>
                            @endcan
                            </div>
                        </td> --}}
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
{{--
    @else
        @include('error.unauthorised')
    @endcan --}}
    <form-permission @updatetable="searchData" :data="formdata"></form-permission>
  </div>
</template>

@include('rolepermission.form_role')
@include('rolepermission.form_permission')
@endsection
