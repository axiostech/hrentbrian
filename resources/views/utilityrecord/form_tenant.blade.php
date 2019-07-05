@extends('layouts.app_guest')
@section('header')
    <a href="/utilityrecord" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-tachometer-alt"></i>
        Utilities Records
    </a>
@stop
@section('content_guest')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <input type="hidden" id="tenancy_id" class="hidden" value="{{$tenancy_id}}">
            <table class="table table-bordered table-sm">
                <tr>
                    <th colspan="2" class="text-center">
                        Tenancy Details
                    </th>
                </tr>
                <tr>
                    <th>ID</th>
                    <td>{{$tenancy->code}}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{$tenancy->tenant->name}}</td>
                </tr>
                <tr>
                    <th>Tenancy From</th>
                    <td>{{\Carbon\Carbon::parse($tenancy->datefrom)->format('Y-m-d')}}</td>
                </tr>
                <tr>
                    <th>Tenancy To</th>
                    <td>{{\Carbon\Carbon::parse($tenancy->dateto)->format('Y-m-d')}}</td>
                </tr>
                <tr>
                    <th>Property Name</th>
                    <td>{{$tenancy->unit->property->name}}</td>
                </tr>
                <tr>
                    <th>Unit</th>
                    <td>
                        {{$tenancy->unit->block_number}}
                        @if($tenancy->unit->block_number)
                            <span>,</span>
                        @endif
                        {{$tenancy->unit->unit_number}}
                        @if($tenancy->unit->address)
                            <span>,</span>
                        @endif
                        {{$tenancy->unit->address}}
                    </td>
                </tr>
{{--
                <tr>
                    <th>Monthly Rental (RM)</th>
                    <td>{{$tenancy->rental}}</td>
                </tr> --}}
            </table>
            <div id="tenantUtilityrecordController">
                <index-tenant-utilityrecord></index-tenant-utilityrecord>
            </div>
        </div>
    </div>
</div>

<template id="index-tenant-utilityrecord-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-tachometer-alt"></i>
              Utilities Records
          </span>
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#tenant_utilityrecord_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="monthyear" class="control-label">Month Year</label>
                <select2 v-model="search.monthyear" @input="onFilterChanged">
                    <option value="">All</option>
                    <option v-for="monthname in monthyear_options" :value="monthname.id">
                      @{{monthname.name}}
                    </option>
                </select2>
            </div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="type" class="control-label">Utility Type</label>
                <select2 v-model="search.type">
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
                          Records
                        </th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-left">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                @{{ index + pagination.from }}.
                                <span class="badge badge-danger" v-else-if="data.is_request_cancel == 1 && data.status != 2">
                                    Requested Delete
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
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <a :href="data.image_url">
                                    <img :src="data.image_url" alt="No photo found" height="260" width="260" style="border:2px solid black">
                                </a>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th scope="row">
                                        <label class="control-label">
                                            Month
                                        </label>
                                    </th>
                                    <td>
                                        <span class="pl-2">
                                            @{{data.month | monthname}} @{{data.year}}
                                            <br>
                                            <small class="pl-1">
                                                (Created at: @{{data.created_date}})
                                            </small>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label class="control-label">
                                            Type
                                        </label>
                                    </th>
                                    <td>
                                        <select2 v-model="data.type" disabled>
                                            @foreach(config('constant_utilityrecord.utilityrecord_type') as $type)
                                            <option value="{{$type['id']}}">
                                                {{$type['name']}}
                                            </option>
                                            @endforeach
                                        </select2>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label class="control-label">
                                            Reading
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" name="reading" class="form-control" v-model="data.reading" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label class="control-label">
                                            Remarks
                                        </label>
                                    </th>
                                    <td>
                                        <textarea name="remarks" rows="2" class="form-control" v-model="data.remarks" readonly></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn btn-danger btn-sm" @click="requestRemoveSingleEntry(data.id)" :disabled="data.status == 2 || data.is_request_cancel == 1">
                                            Request Delete
                                        </button>
                                    </td>
                                </tr>
                            </table>

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
    <form-tenant-utilityrecord @updatetable="searchData" :clearform="clearform" :data="formdata" :tenancyid="tenancy_id"></form-tenant-utilityrecord>
  </div>
</template>
@include('utilityrecord.form_tenant_form')
@endsection
