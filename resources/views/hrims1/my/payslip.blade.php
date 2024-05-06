@extends('layouts.header')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/error/error.css') }}">
<div class="row" id="dtrDiv">
  <div class="col-lg-12">              
      <div class="card card-primary card-outline">
          <div class="card-header">
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-3">
                <label>Payroll Type</label>
                <select class="form-control select2" name="payroll_type">
                    @foreach($payroll_type as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
              </div>
              <div class="col-lg-3">
                <label>Year</label>
                <select class="form-control select2" name="year">
                  @for ($i = date('Y'); $i >= 2023; $i--)
                    <option value="{{$i}}">{{$i}}</option>
                  @endfor
                </select>
              </div>
              <div class="col-lg-3">
                <label>Month</label>
                <select class="form-control select2" name="month">
                    @for($i=1;$i<=12;$i++)
                      @if(date('m')==$i)
                        <option value="{{$i}}" selected>{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                      @else
                        <option value="{{$i}}">{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                      @endif
                    @endfor
                </select>
              </div>
              <div class="col-lg-3"><br>
                <button type="button" class="btn btn-info btn-info-scan" name="submit">
                    <span class="fa fa-check"></span>
                </button>
              </div>
              <div class="col-lg-12"><br>
                <div class="card card-info card-outline">
                  <div class="card-body row">
                      <div class="col-lg-12 center" id="documentPreviewDiv">
                            <iframe id="documentPreview" src="{{asset('assets\pdf\pdf_error.pdf')}}" style="height:900px;width:80%;"></iframe>
                      </div>
                  </div>
              </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
              
          </div>
      </div>
  </div>
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
@include('layouts.script')
<script src="{{ asset('assets/js/hrims/my/payslip.js') }}"></script>
@endsection