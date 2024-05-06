@extends('layouts.header')
@section('content')
<div class="card card-primary card-outline card-tabs">
    <div class="card-header p-0 pt-1 border-bottom-0">
      <ul class="nav nav-tabs" id="nav-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active"
            id="list-tab"
            data-toggle="pill"
            href="#list"
            role="tab"
            aria-controls="list"
            aria-selected="true">List</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
              id="nstp-tab"
              data-toggle="pill"
              href="#nstp"
              role="nstp"
              aria-controls="nstp"
              aria-selected="false">NSTP</a>
          </li>
        <li class="nav-item">
          <a class="nav-link"
            id="summary-tab"
            data-toggle="pill"
            href="#summary"
            role="summary"
            aria-controls="summary"
            aria-selected="false">Summary</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="list-tabContent">
            <div class="tab-pane fade show active"
            id="list"
            role="tabpanel"
            aria-labelledby="list-tab">
                <div class="row" id="sectionDiv">
                    <div class="col-lg-4">
                        <label>School Year</label>
                        <select class="form-control select2" name="school_year">
                            @foreach($school_year as $row)
                                <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->grade_period->name}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <div id="programsSelectDiv"></div>
                    </div>
                    <div class="col-lg-12">
                    </div>
                    <div class="col-lg-12">
                        <br>
                        <div class="card card-info card-outline">
                            <div class="card-body table-responsive">
                                @if($user_access->level_id==1 || $user_access->level_id==2)
                                <button class="btn btn-primary btn-primary-scan sectionNewModal">
                                    <span class="fa fa-plus-square"></span> New Section
                                </button>
                                @endif
                                <table id="viewTable" class="table table-bordered table-fixed"
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
                                            <th data-field="f2" data-sortable="true" data-align="center">Curriculum</th>
                                            <th data-field="f3" data-sortable="true" data-align="center">Grade Level</th>
                                            <th data-field="f4" data-sortable="true" data-align="center">No of Section</th>
                                            <th data-field="f5" data-sortable="true" data-align="center">View</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show"
            id="nstp"
            role="tabpanel"
            aria-labelledby="nstp-tab">
                <div class="row">
                    <div class="col-lg-4">
                        <label>School Year</label>
                        <select class="form-control select2" name="school_year">
                            @foreach($school_year as $row)
                                <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->grade_period->name}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Branch</label>
                        <select class="form-control select2" name="branch">

                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.script')
<script src="{{ asset('assets/js/rims/sections/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/modal.js') }}"></script>

@endsection
