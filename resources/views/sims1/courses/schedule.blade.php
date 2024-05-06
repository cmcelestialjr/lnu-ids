@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#schedule" role="tab" aria-selected="true">Courses</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" role="tabpanel" id="schedule">
                    <div class="card card-info card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>School Year</label>
                                    <select class="form-control select2" name="school_year">
                                        @foreach($school_year as $row)
                                            <option value="{{$row['id']}}">{{$row['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12" id="scheduleTable">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@include('layouts.script')
<script src="{{ asset('assets/js/sims/courses/schedule.js') }}"></script>
@endsection