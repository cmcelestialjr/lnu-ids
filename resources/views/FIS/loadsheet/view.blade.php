@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('assets/css/loader-skeleton.css') }}">
@section('content')
<div class="row" id="loadSheet">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-body">
            <div class="row">                        
                <div class="col-lg-3">
                  <label>School Year</label>
                  <select class="form-control select2" name="school_year">                    
                    @foreach($school_year as $row)
                      <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} ({{$row->grade_period->name}})</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div id="loader"></div>
                    <iframe class="hide" id="documentPreview" src="{{asset('assets\pdf\pdf_error.pdf')}}" style="width:100%;height:800px"></iframe>
                </div>
                <div class="col-md-1"></div>
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
<script src="{{ asset('assets/js/loader/skeleton.js') }}"></script>
<script src="{{ asset('assets/js/fis/loadsheet.js') }}"></script>
@endsection