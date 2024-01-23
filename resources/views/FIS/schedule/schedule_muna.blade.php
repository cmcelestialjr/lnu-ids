@extends('layouts.header')
@section('content')
<div class="row" id="employeeInformationModal">
  <input type="hidden" name="id_no" value="{{$user->id_no}}">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>
            <div class="card-body">
              <div class="col-md-12 callout callout-info" id="displayDiv">
                
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
<script src="{{ asset('assets/js/fis/schedule.js') }}"></script>
@endsection