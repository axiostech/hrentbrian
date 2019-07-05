@extends('layouts.app_guest')
@section('header')
    <a href="/tenancy" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-file-signature"></i>
        ARC form
    </a>
@stop
@section('content_guest')
<div>
    <div class="card card-arc">
        <div class="card-header">
            <div class="form-row">
                <span class="mr-auto">
                    <i class="fas fa-file-signature"></i>
                      Auto Debit Rental Form
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($form_return ==  true)
                <div class="alert alert-success">Request submitted successfully</div>
            @endif


        <form action="{{route('createMandate')}}" method="POST" autocomplete="off" @if($form_return ==  true) disabled="true" @endif>
            {{csrf_field()}}
            <input type="hidden" name="reference_number" value="{{$tenant_arc->reference_number}}">
            <input type="hidden" name="resident_id" value="{{$resident_id}}">
            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                    <label for="tenant_name" class="control-label">Tenant Name:</label>
                    <input type="text" name="tenant_name" class="form-control" placeholder="Tenant Name" autocomplete="off" required readonly value="{{$tenant->name}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                    <label for="tenant_mobile_number" class="control-label">Tenant Email:</label>
                    <input type="email" name="tenant_email" class="form-control" placeholder="Tenant Tel No." autocomplete="off" required readonly value="{{$tenant->email}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                    <label for="tenant_mobile_number" class="control-label">Max Amount:</label>
                    <input type="text" name="amount" class="form-control" placeholder="Tenant Tel No." autocomplete="off" required readonly value="{{$tenant_arc->tenancy->rental}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                    <label for="city" class="control-label">Bank:</label>
                    <select class="form-control" name="bank_code" required>
                       <option value="TEST0023">SBI Bank A</option>
                        @foreach($mandate_banks  as $bank)
                            <option value="{{$bank['id'][0]}}">{{$bank['name'][0]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                    <label for="city" class="control-label">ID proof:</label>
{{--
                    <select class="form-control" name="id_type"  id="id_type" required>
                      <option value="NRIC" selected>NRIC</option>
                      <option value="PASSPORT_NUMBER">Passport number</option>
                      <option value="OLD_IC">Old IC</option>
                      <option value="BUSINESS_REGISTRATION_NUMBER">Business registration number</option>
                      <option value="OTHERS">Others</option>
                    </select> --}}
                    <select2 v-model="form.idtype_id">
                        <option value="">All</option>
                        <option v-for="idtype in idtype_options" :value="idtype.id">
                            @{{idtype.name}}
                        </option>
                    </select2>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                    <label for="tenant_mobile_number" class="control-label"> ID Value:</label>
                    <input type="text" name="id_value" class="form-control" placeholder="ID Value" autocomplete="off" value="{{$tenant->id_value}}">
                </div>
            </div>
            @if($form_return !=  true)
                <div class="form-row">
                    <div class="form-group col-md-6 col-sm-12 col-xs-12">
                        <button class="btn btn-success" type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            @endif
        </form>
      </div>
    </div>
</div>
@endsection
