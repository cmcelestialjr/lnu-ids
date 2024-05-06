@extends('layouts.header')
@section('content')
<div class="row" id="scheduleDiv">
  <div class="col-lg-12">
    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">View</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="list" role="tabpanel">
              <div class="row">
                <div class="col-lg-4">
                  <label>School Year</label>
                  <select class="form-control select2" name="school_year">
                    @foreach($school_year as $row)
                      <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} ({{$row->grade_period->name}})</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-4" id="gradeLevelDiv">
                    
                </div>
                <div class="col-lg-12 table-responsive">
                  <br>
                  <div id="scheduleTable"></div>                          
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
@include('layouts.script')
<script src="{{ asset('assets/js/fis/schedule/_function.js') }}"></script>
<script src="{{ asset('assets/js/fis/schedule/view.js') }}"></script>
@endsection