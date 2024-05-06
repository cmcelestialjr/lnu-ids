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
                    <div class="row" id="curriculumDiv">
                        <div class="col-lg-2">
                            <label>Status</label>
                            <select class="form-control select2 thisBtn" name="status">
                                <option value="0">All</option>
                                @foreach($statuses as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Branch</label>
                            <select class="form-control select2 thisBtn" name="branch">
                                @foreach($branches as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Level</label>
                            <div class="input-group">
                                <select class="form-control select2 thisBtn" name="level">
                                    @foreach($programs as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-prepend">
                                    <button class="btn btn-info btn-info-scan thisBtn" name="list">
                                        <span class="fa fa-check"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            @if($user_access->level_id==1 || $user_access->level_id==2)
                                <button class="btn btn-primary btn-primary-scan newModal" style="float:right">
                                    <span class="fa fa-plus-square"></span> New Curriculum
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
                                        <th data-field="f2" data-sortable="true" data-align="center">Year From</th>
                                        <th data-field="f3" data-sortable="true" data-align="center">Program</th>
                                        <th data-field="f4" data-sortable="true" data-align="center">Status</th>
                                        <th data-field="f5" data-sortable="true" data-align="center">Edit</th>
                                        <th data-field="f6" data-sortable="true" data-align="center">View</th>
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
                        <div class="col-lg-2">
                            <label>Status</label>
                            <select class="form-control select2 thisBtn" name="status">
                                @foreach($statuses as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Branch</label>
                            <select class="form-control select2 thisBtn" name="branch">
                                @foreach($branches as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label>Level</label>
                            <div class="input-group">
                                <select class="form-control select2 thisBtn" name="level">
                                    @foreach($programs as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-prepend">
                                    <button class="btn btn-info btn-info-scan thisBtn" name="list">
                                        <span class="fa fa-check"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="departmentsDiv"><br>
                            <div class="row" id="departmentsShow">

                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
    </div>
</div>

@include('layouts.script')
<script src="{{ asset('assets/js/rims/curriculums/curriculums.js') }}"></script>
@endsection
