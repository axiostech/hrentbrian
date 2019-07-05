@extends('layouts.app')
@section('header')
    <a href="/user" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-id-badge"></i>
        Users
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexUserController">
                <index-user></index-user>
            </div>
        </div>
    </div>
</div>

<template id="index-user-template">
  <div>
    {{-- @can('read users') --}}
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-id-badge"></i>
              Users
          </span>
          {{-- @can('create users') --}}
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#user_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          {{-- @endcan --}}
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="email" class="control-label">Email</label>
                <input type="text" name="email" class="form-control" v-model="search.email" placeholder="Email" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="phone_number" class="control-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" v-model="search.phone_number" placeholder="Phone Number" autocomplete="off" @keyup="onFilterChanged">
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
                            <a href="#" @click="sortBy('email')">Email</a>
                            <span v-if="sortkey == 'email' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'email' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('phone_number')">Phone Number</a>
                            <span v-if="sortkey == 'phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('last_login_at')">Last Login</a>
                            <span v-if="sortkey == 'last_login_at' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'last_login_at' && reverse" class="fa fa-caret-up"></span>
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
                            @{{ data.email }}
                        </td>
                        <td class="text-center">
                            @{{ data.phone_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.last_login_at }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success" v-if="data.status == 1">
                                Active
                            </span>
                            <div class="badge badge-secondary" v-else>
                                Inactive
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            {{-- @can('update users') --}}
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#user_modal" @click="editSingleEntry(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            {{-- @endcan --}}
                            {{-- @can('delete users') --}}
                            <button type="button" class="btn btn-danger btn-sm" @click="deactivateSingleEntry(data)">
                                <i class="fas fa-trash"></i>
                            </button>
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
    <form-user @updatetable="searchData" :clearform="clearform" :data="formdata"></form-user>
  </div>
</template>

@include('user.form')
@endsection
