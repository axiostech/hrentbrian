@extends('layouts.app')
@section('header')
    <a href="/insurance" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-house-damage"></i>
        Success
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
              Success
          </span>
          
          </div>
      </div>
      <div class="card-body">
        <div class="form-row" style="padding-top: 20px;">
            <div class="table-responsive text-center"  >
              <h2>Your payment has been successfully!</h2>
              <!-- <p>Order Number : </p> -->
               
            </div>
            
            
            </div>
      </div>
      </div>
    </div>
  
  </div>
</template>

@include('insurance.form')
@endsection
