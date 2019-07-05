@extends('layouts.app')
@section('header')
    <a href="/profile" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-building"></i>
        Profiles
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexProfileController">
                <index-profile></index-profile>
            </div>
        </div>
    </div>
</div>

<template id="index-profile-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="far fa-building"></i>
              Profiles
          </span>
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#profile_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Profile Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="attn_name" class="control-label">Attn Name</label>
                <input type="text" name="attn_name" class="form-control" v-model="search.attn_name" placeholder="Attn Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="attn_phone_number" class="control-label">Attn Phone Number</label>
                <input type="text" name="attn_phone_number" class="form-control" v-model="search.attn_phone_number" placeholder="Attn Phone Number" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="domain_name" class="control-label">Domain Name</label>
                <input type="text" name="domain_name" class="form-control" v-model="search.domain_name" placeholder="Domain Name" autocomplete="off" @keyup="onFilterChanged">
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
{{--
                        <th class="text-center">
                            Logo
                        </th> --}}
                        <th class="text-center">
                            <a href="#" @click="sortBy('name')">Profile Name</a>
                            <span v-if="sortkey == 'name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('prefix')">Prefix</a>
                            <span v-if="sortkey == 'prefix' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'prefix' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('attn_name')">Attn Name</a>
                            <span v-if="sortkey == 'attn_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'attn_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('attn_phone_number')">Attn Phone Number</a>
                            <span v-if="sortkey == 'attn_phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'attn_phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('email')">Email</a>
                            <span v-if="sortkey == 'email' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'email' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('domain_name')">Domain Name</a>
                            <span v-if="sortkey == 'domain_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'domain_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('user_id')">Can Login?</a>
                            <span v-if="sortkey == 'user_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'user_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
{{--
                        <td class="text-center">
                            <img v-bind:src="data.logo_url" width="50" height="50" v-if="data.logo_url">
                        </td> --}}
                        <td class="text-left">
                            @{{ data.name }}
                        </td>
                        <td class="text-center">
                            @{{ data.prefix }}
                        </td>
                        <td class="text-center">
                            @{{ data.attn_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.attn_phone_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.email }}
                        </td>
                        <td class="text-center">
                            @{{ data.domain_name }}
                        </td>
                        <td class="text-center">
                            <i class="fas fa-check-circle" v-if="data.user_id" style="color: green;"></i>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#profile_modal" @click="editSingleEntry(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" @click="removeSingleEntry(data)">
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
    <form-profile @updatetable="searchData" :clearform="clearform" :data="formdata"></form-profile>
  </div>
</template>

@include('profile.form')
@endsection
