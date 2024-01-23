@extends('layouts.header')
@section('content')
<div class="row" id="signatory">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <div class="row">
                  <div class="col-lg-3">
                      <label>Type</label>
                      <select class="form-control select2" name="type">
                        @foreach($signatory_type as $row)
                            <option value="{{$row->type}}">{{$row->type}}</option>
                        @endforeach
                      </select>
                  </div>
                  <a href="{{url('/download')}}">download</a>
                  <div class="col-lg-12">
                      <table id="signatoryTable" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="500"
                            data-buttons-class="primary"
                            data-show-export="true"
                            data-show-columns-toggle-all="true"
                            data-mobile-responsive="true"
                            data-pagination="true"
                            data-page-size="10"
                            data-page-list="[10, 50, 100, 500, 1000, All]"
                            data-loading-template="loadingTemplate"
                            data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                          <thead>
                              {{-- <tr>
                                  <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                  <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                  <th data-field="f3" data-sortable="true" data-align="center">Signatory</th>
                                  <th data-field="f4" data-sortable="true" data-align="center">Updated By</th>
                                  <th data-field="f5" data-sortable="true" data-align="center">DateTime</th>
                              </tr> --}}
                              <tr>
                                <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                <th data-field="stud_id" data-sortable="true">stud_id</th>
                                <th data-field="surname" data-sortable="true">surname</th>
                                <th data-field="first_name" data-sortable="true">first_name</th>
                                <th data-field="middle_name" data-sortable="true">middle_name</th>
                                <th data-field="qualifier" data-sortable="true">qualifier</th>
                                <th data-field="gender" data-sortable="true">gender</th>
                                <th data-field="date_of_birth" data-sortable="true">date_of_birth</th>
                                <th data-field="course" data-sortable="true">course</th>
                                <th data-field="year_level" data-sortable="true">year_level</th>
                                <th data-field="father_lastname" data-sortable="true">father_lastname</th>
                                <th data-field="father_firstname" data-sortable="true">father_firstname</th>
                                <th data-field="father_middlename" data-sortable="true">father_middlename</th>
                                <th data-field="mother_lastname" data-sortable="true">mother_lastname</th>
                                <th data-field="mother_firstname" data-sortable="true">mother_firstname</th>
                                <th data-field="mother_middlename" data-sortable="true">mother_middlename</th>
                                <th data-field="address" data-sortable="true">address</th>
                                <th data-field="zip" data-sortable="true">zip</th>
                                <th data-field="phone_no" data-sortable="true">phone_no</th>
                            </tr>
                          </thead>
                      </table>
                  </div>
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
<script src="{{ asset('assets/js/signatory.js') }}"></script>
@endsection