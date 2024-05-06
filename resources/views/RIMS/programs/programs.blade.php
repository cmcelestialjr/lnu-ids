@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
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
                    <div class="row" id="programsDiv">
                        <div class="col-lg-3">
                            <label>Status</label>
                            <select class="form-control select2" name="status">
                                @foreach($statuses as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Branch</label>
                            <select class="form-control select2" name="branch">
                                @foreach($branches as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Level</label>
                            <select class="form-control select2" name="level">
                                @foreach($program_levels as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12">
                            @if($user_access->level_id==1 || $user_access->level_id==2)
                            <button class="btn btn-primary btn-primary-scan programNewModal" style="float:right">
                            <span class="fa fa-plus-square"></span> New Program
                            </button>
                            <br><br>
                            @endif
                            <table id="viewTable" class="table table-bordered table-fixed"
                                        data-toggle="table"
                                        data-search="true"
                                        data-height="600"
                                        data-buttons-class="primary"
                                        data-show-export="true"
                                        data-show-columns-toggle-all="true"
                                        data-mobile-responsive="true"
                                        data-pagination="true"
                                        data-page-size="15"
                                        data-page-list="[15, 10, 50, 100, All]"
                                        data-loading-template="loadingTemplate"
                                        data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                                <thead>
                                    <tr>
                                        <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                        <th data-field="f2" data-sortable="true" data-align="center">Level</th>
                                        <th data-field="f3" data-sortable="true" data-align="center">Department</th>
                                        <th data-field="f4" data-sortable="true" data-align="center">Programs</th>
                                        <th data-field="f5" data-sortable="true" data-align="center">Shorten</th>
                                        <th data-field="f6" data-sortable="true" data-align="center">Edit</th>
                                        <th data-field="f7" data-sortable="true" data-align="center">Status</th>
                                        <th data-field="f8" data-sortable="true" data-align="center">View</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade"
                    id="summary"
                    role="tabpanel"
                    aria-labelledby="summary-tab">
                    <div class="row">
                        <div class="col-lg-3">
                            <label>Status</label>
                            <select class="form-control select2" name="status">
                                @foreach($statuses as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Branch</label>
                            <select class="form-control select2" name="branch">
                                @foreach($branches as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Level</label>
                            <select class="form-control select2" name="level">
                                @foreach($program_levels as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12" id="departmentsDiv"><br>
                            <div class="row" id="departmentsShow">

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
</div>

@include('layouts.script')
<script src="{{ asset('assets/js/rims/programs/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/programs/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/programs/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/programs/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/programs/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/programs/program.js') }}"></script>
<script src="{{ asset('assets/js/rims/programs/branch.js') }}"></script>
<script src="{{ asset('assets/js/search/unitByDepartment.js') }}"></script>
@endsection
