@extends('layouts.app')
@section('header')
    <a href="/invoice" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-building"></i>
        Invoices
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexInvoiceController">
                <index-invoice></index-invoice>
            </div>
        </div>
    </div>
</div>

<template id="index-invoice-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="far fa-building"></i>
              Invoices
          </span>
          <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#invoice_modal" @click="createSingleEntry">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="invoice_number" class="control-label">Invoice Number</label>
                <input type="text" name="invoice_number" class="form-control" v-model="search.invoice_number" placeholder="Invoice Number" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="type" class="control-label">Type</label>
                <select2 v-model="search.type" @input="onFilterChanged">
                    <option value="">All</option>
                    <option value="1">ARC Rental</option>
                    <option value="2">ARC Others</option>
                    <option value="3">Utilities - Electric</option>
                    <option value="4">Utilities - Water</option>
                    <option value="5">Utilities - Others</option>
                    <option value="6">Top Up</option>
                    <option value="7">Insurance</option>
                    <option value="8">MC & SF</option>
                    <option value="9">Others</option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="status" class="control-label">Status</label>
                <select2 v-model="search.status" @input="onFilterChanged">
                    <option value="">All</option>
                    <option value="1">Open</option>
                    <option value="2">Draft</option>
                    <option value="3">Confirmed</option>
                    <option value="4">Archieved</option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="payment_status" class="control-label">Payment Status</label>
                <select2 v-model="search.payment_status" @input="onFilterChanged">
                    <option value="">All</option>
                    <option value="1">Owe</option>
                    <option value="2">Paid</option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="sendfrom" class="control-label">Sent From</label>
                <div class="input-group">
                <datetimepicker name="sendfrom" v-model="search.sendfrom" placeholder="Date valid From" autocomplete="off"></datetimepicker>
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="sendto" class="control-label">Sent To</label>
                <div class="input-group">
                <datetimepicker name="sendto" v-model="search.sendto" placeholder="Date valid To" autocomplete="off"></datetimepicker>
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
                <table class="table table-bordered table-hover">
                    <tr class="table-secondary">
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('invoice_number')">Invoice Number</a>
                            <span v-if="sortkey == 'invoice_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'invoice_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('send_date')">Send At</a>
                            <span v-if="sortkey == 'send_date' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'send_date' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('grand_total')">Total</a>
                            <span v-if="sortkey == 'grand_total' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'grand_total' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('payment_status')">Payment Status</a>
                            <span v-if="sortkey == 'payment_status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'payment_status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('paid_amount')">Full Payment?></a>
                            <span v-if="sortkey == 'paid_amount' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'paid_amount' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_arc')">Is ARC?</a>
                            <span v-if="sortkey == 'is_arc' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_arc' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_sent')">Is Sent?</a>
                            <span v-if="sortkey == 'is_sent' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_sent' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.invoice_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.send_date }}
                        </td>
                        <td class="text-right">
                            @{{ data.grand_total }}
                        </td>
                        <td class="text-center">
                          <span v-if="data.payment_status == 1" class="badge badge-warning">
                            Owe
                          </span>
                          <span v-if="data.payment_status == 2" class="badge badge-success">
                            Paid
                          </span>
                        </td>
                        <td class="text-right">
                            <span v-if="data.paid_amount == data.grand_total">
                              <i class="fas fa-check-circle" style="background-color: green;"></i>
                            </span>
                            <span v-else>
                              <i class="fas fa-check-times" style="background-color: red;"></i>
                            </span>
                        </td>
                        <td class="text-right">
                            <span v-if="data.is_arc">
                              <i class="fas fa-check-circle" style="background-color: green;"></i>
                            </span>
                            <span v-else>
                              <i class="fas fa-check-times" style="background-color: red;"></i>
                            </span>
                        </td>
                        <td class="text-right">
                            <span v-if="data.is_sent">
                              <i class="fas fa-check-circle" style="background-color: green;"></i>
                            </span>
                            <span v-else>
                              <i class="fas fa-check-times" style="background-color: red;"></i>
                            </span>
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
    <form-invoice @updatetable="searchData" :data="formdata"></form-invoice>
  </div>
</template>

@include('invoice.form')
@endsection
