@extends('layouts.header')
@section('content')
<div class="card card-primary card-outline" id="sectionDiv">
  <div class="card-body">
      <div class="row">
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
</div>

@include('layouts.script')
<script src="{{ asset('assets/js/rims/sections/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/sections/modal.js') }}"></script>

@endsection