@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#teachers" role="tab" aria-selected="true">Teachers</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" role="tabpanel" id="teachers">
                    <div class="card card-info card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Program Level</label>
                                    <select class="form-control select2" name="program_level">
                                        <option value="0">All</option>
                                        @foreach($program_level as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>School Year</label>
                                    <select class="form-control select2" name="school_year">
                                        <option value="0">All</option>
                                        @foreach($school_year as $row)
                                            <option value="{{$row['id']}}">{{$row['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <table id="teachersTable" class="table table-bordered table-fixed"
                                                data-toggle="table"
                                                data-search="true"
                                                data-height="460"
                                                data-buttons-class="primary"
                                                data-show-export="true"
                                                data-show-columns-toggle-all="true"
                                                data-mobile-responsive="true"
                                                data-pagination="true"
                                                data-page-size="10"
                                                data-page-list="[10, 50, 100, All]"
                                                data-loading-template="loadingTemplate"
                                                data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                                        <thead>
                                            <tr>
                                                <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                                <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                                <th data-field="f3" data-sortable="true" data-align="center">Level</th>
                                                <th data-field="f4" data-sortable="true" data-align="center">No of Courses</th>
                                                <th data-field="f5" data-sortable="true" data-align="center">View</th>
                                            </tr>
                                        </thead>
                                    </table>
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
<script src="{{ asset('assets/js/sims/teachers.js') }}"></script>
@endsection