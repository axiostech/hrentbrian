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
            <div id="indexInsuranceController">
                <index-insurance></index-insurance>
            </div>
        </div>
    </div>
</div>

<template id="index-insurance-template">
  <div>
    <div class="card">
      <div class="card-header text-white">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-house-damage"></i>
              Insurances
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
                            <a href="#" @click="sortBy('property_id')">Property</a>
                            <span v-if="sortkey == 'property_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'property_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('insurance_status')">Status</a>
                            <span v-if="sortkey == 'insurance_status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'insurance_status' && reverse" class="fa fa-caret-up"></span>
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
                            @{{ data.property_name }}
                        </td>
                        <td class="text-center" v-if="data.insurance_status == 1" >
                            <span class="badge badge-success">
                                Active
                            </span>
                            
                        </td>
                        <td class="text-center" v-else>
                            <span class="badge badge-danger">
                                Inactive
                            </span>
                        </td>
                        <td class="text-center" v-if="data.insurance_status == 1" >
                            <span class="badge badge-success">
                                Paid
                            </span>
                            
                        </td>
                        <td class="text-center" v-else>
                        <select class="form-group"   @change="getplanonchange($event)" >
                                <option value="0 RM" v-bind:value="'0 RM_unit_'+data.unit_number+'_plan_id_0'" >--Select Plan--</option>
                                <option  v-for="(plan,index) in plans"   v-bind:value="plan.insurance_plan_price+'_unit_'+data.unit_number+'_plan_id_'+plan.insurance_plan_id" >@{{ plan.insurance_plan_name }} @{{ plan.insurance_plan_price }}</option>
                               
                            </select>
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
            <div class="pull-right" v-if="showplanselected.plantotal !=0" style="margin-left: 63%;">
                <b>Total Amount: @{{showplanselected.plantotal}} RM </b> <br><br>
                <a href="/checkout" class="btn btn-primary btn-md ml-auto" style="width: 150px;">
                    Continue
                </a>
                
                
            </div>
            <!-- <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
           
            <input type="hidden" name="totalplan" value="[]"class="form-control" >
                <button type="submit" class="btn btn-success">Create</button>
           </form> -->
        </div>
      </div>
      </div>
    </div>
    <!-- <form-insurance @updatetable="searchData" :data="formdata"></form-insurance> -->
  </div>
</template>

@include('insurance.form')
@endsection
