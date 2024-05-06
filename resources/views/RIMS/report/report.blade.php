@extends('layouts.header')
@section('content')
<div class="row" id="loadSheet">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-body">
            <div class="row">                        
                <div class="col-lg-2">
                  <label>School Year</label>
                  <select class="form-control select2" name="school_year">                    
                        @foreach($school_years as $row)
                          <option value="{{$row}}">{{$row}}</option>
                        @endforeach
                  </select>
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    
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
@endsection