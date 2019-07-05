@inject('idtypes', 'App\Idtype')

@extends('layouts.app_guest')
@section('header')
    <a href="/tenant" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-users"></i>
        Auto Rental Collection (ARC)
    </a>
@stop
@section('content_guest')
<div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />

  @php
      $readonly = 'readonly';
      $disabled = 'disabled';
/*
      if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('superadmin')) {
        $readonly = '';
      } */
  @endphp
  <div class="card card-default">
    <form method="POST" action="{{route('createMandate')}}" autocomplete="off">
      {{csrf_field()}}
      <div class="card-header">
        Auto Rental Collection (ARC)
      </div>
      <div class="card-body">
        <input type="hidden" name="user_id" value="{{$data['user_id']}}">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
              Application Status
            </label>
            @if($data['arc']->mandate_status == '00')
              <h4>
                <span class="badge badge-success">
                  Tenant Approved
                </span>
              </h4>
            @elseif($data['arc']->mandate_status == null)
              <h4>
                <span class="badge badge-info">
                  New
                </span>
              </h4>
            @else
              <h4>
                <span class="badge badge-danger">
                  Unsuccessful
                </span>
              </h4>
              <small>(Code: {{$data['arc']->mandate_status}})</small>
            @endif
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
              Reference Number
            </label>
            <input type="text" name="ref_number" value="{{$data['arc']->ref_number}}" class="form-control" readonly>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="form-row">
            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                <label class="control-label">
                  Tenant Name
                </label>
                <label for="required" class="control-label" style="color:red;">*</label>
                <input type="text" name="name" class="form-control" value="{{$data['arc']->name}}" {{$readonly}}>
            </div>
            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                <label class="control-label">
                  Tenant Email
                </label>
                <label for="required" class="control-label" style="color:red;">*</label>
                <input type="text" name="email" class="form-control" value="{{$data['arc']->email}}" {{$readonly}}>
            </div>
          </div>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
              Rental Amount (RM)
            </label>
            <label for="required" class="control-label" style="color:red;">*</label>
            <input type="text" name="amount" class="form-control text-right" value="{{$data['arc']->amount}}" {{$readonly}}>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="form-row">
            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                <label class="control-label">
                    ID Type
                </label>
                <label for="required" class="control-label" style="color:red;">*</label>
                <select name="idtype_id" class="form-control select" disabled="disabled">
                    @foreach($idtypes::all() as $idtype)
                    <option value="{{$idtype->id}}" {{$data['arc']->idtype_id == $idtype->id ? 'selected' : ''}}>
                      {{$idtype->name}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                <label class="control-label">
                    ID Number
                </label>
                <label for="required" class="control-label" style="color:red;">*</label>
                <input type="text" name="id_value" class="form-control" value="{{$data['arc']->id_value}}" {{$readonly}}>
            </div>
          </div>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="city" class="control-label">Bank:</label>
            <label for="required" class="control-label" style="color:red;">*</label>
            <select class="select form-control" name="bank_code" {{$data['arc']->bank_code && $data['arc']->mandate_status == '00' ? 'disabled' : ''}}>
              <option value=""></option>
                @foreach($data['mandate_banks']  as $bank)
                    <option value="{{$bank['id'][0]}}" {{$data['arc']->bank_code == $bank['id'][0] ? 'selected' : ''}}>
                      {{$bank['name'][0]}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <div class="btn-group">
            @if($data['arc']->mandate_status == null)
              <button type="submit" class="btn btn-success">
                <i class="far fa-check-circle"></i>
                Submit
              </button>
            @elseif($data['arc']->mandate_status != null and $data['arc']->mandate_status != '00')
              <button type="submit" class="btn btn-success">
                <i class="far fa-check-circle"></i>
                Retry
              </button>
            @endif
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <small class="text-muted">
            By completing the Auto Rental Collection (ARC) form and accepting these <a href="https://go.curlec.com/terms-and-condition" target="_blank"> Terms and Conditions </a> the Account Holder authorises the Bank to arrange for funds to be debited from the Account Holder's Account including any relevant transaction fees/charges in using the Direct Debit Service which is not payable by the Corporation. These Terms and Conditions may be superseded by variations, revisions or changes at any time, subject to prior notice and the Bank is obliged to inform you that your continued use of the Direct Debit Service constitutes your acceptance to such variations, revisions or changes without any reservations.
          </small>
        </div>
      </div>
    </form>
  </div>
</div>
  <script
  src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
  integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
  <script>
    $('.select').select2({
      placeholder: 'Select..'
    });
    jQuery(function ($) {
      $('form').bind('submit', function () {
        $(this).find(':input').prop('disabled', false);
      });
      $(document).on('touchend', function(){
        $(".select2-search, .select2-focusser").remove();
      });
    });
  </script>
@endsection
