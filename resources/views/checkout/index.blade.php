@extends('layouts.app')
@section('header')
    <a href="/insurance" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-house-damage"></i>
        Insurance
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexCheckoutController">
                <index-insurance></index-insurance>
            </div>
        </div>
    </div>
</div>

<template id="index-checkout-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-house-damage"></i>
              Checkout
          </span>
          <!-- <button type="button" class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#insurance_modal" @click="createSingleInsurance">
              <i class="fas fa-plus"></i>
          </button> -->
          </div>
      </div>
      <div class="card-body">
        <div class="form-row" style="padding-top: 20px;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tr class="table-secondary">
                        
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
                            <a href="#" @click="sortBy('property_id')">Property</a>
                            <span v-if="sortkey == 'property_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'property_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('insurance_status')">Plan</a>
                            <span v-if="sortkey == 'insurance_status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'insurance_status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            Amount
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        
                        <td class="text-center">
                            @{{ data.block_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.unit_number }}
                        </td>
                      
                        <td class="text-center">
                            @{{ data.address }}
                        </td>
                        <td class="text-center"  >
                            <p v-for="(plan,yindex) in plans" v-if="yindex==index"> @{{ plan.insurance_plan_name }} @{{ plan.insurance_plan_price }}</p>
                            
                        </td>
                       
                        <td class="text-center">
                        <p v-for="(plan,yindex) in plans" v-if="yindex==index"> @{{ plan.insurance_plan_price }}</p>
                            
                        </td>
                        <td>
                        <div class="text-center">
                            
                            
                            <button type="button" class="btn btn-danger btn-sm" @click="removeSingleUnit(data)">
                                <i class="fas fa-trash"></i>
                            </button>
                            </div>
                        </td>
                    </tr>
                   <!-- <tr v-if="! pagination.total">
                        <td colspan="18" class="text-center"> No Results Found </td>
                    </tr> -->
                    
                </table>
               
            </div>
            <div class="pull-left">
                <pagination :pagination="pagination" :callback="fetchTable" :offset="4"></pagination>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col col-40">Tax Amount:  @{{tax_amount}}  RM</div>
                    <div class="col col-40">Total Amount:  @{{total_amount}}  RM</div>
                    <div class="col col-20"> 
                        <button type="button" id="createinsurance"   @click="createBillForInsurance" class="btn btn-primary btn-md ml-auto" style="width: 70%;">
                        Continue
                        </button>
                        <p v-if="processingmsg" style="color:green;">Please wait..</p>
                    </div>
                </div>
            </div>
            <!-- <div class="col">
                <div class="row">
                <p v-if="processingmsg" style="color:green;">Please wait.. you will redirect to payment page.</p>
                </div>
            </div> -->
           
            </div>
      </div>
      </div>
    </div>
    <!-- <form-insurance @updatetable="searchData" :data="formdata"></form-insurance> -->
  </div>
</template>

@include('insurance.form')
@endsection
