@extends('layouts.header')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{$system_selected}}</a></li>
              <li class="breadcrumb-item active">{{mb_strtoupper($nav_selected)}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Content -->
<div class="content" id="workNewModal">
  <!-- Container-fluid -->
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
              <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h4>New Employee</h4>
                  </div>
                  <div class="card-body">
                    <span class="text-require">*</span> Required fields
                    <div class="row">
                      <div class="col-lg-3">
                        <label>Lastname<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="lastname">
                      </div>
                      <div class="col-lg-3">
                        <label>Firstname<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="firstname">
                      </div>
                      <div class="col-lg-3">
                        <label>Middlename</label>
                        <input type="text" class="form-control" name="middlename">
                      </div>
                      <div class="col-lg-3">
                        <label>Extname</label>
                        <input type="text" class="form-control" name="extname">
                      </div>
                      <div class="col-lg-3">
                        <label>Birthdate<span class="text-require">*</span></label>
                        <input type="text" class="form-control datePicker" name="dob">
                      </div>
                      <div class="col-lg-3">
                        <label>Sex<span class="text-require">*</span></label>
                        <select class="form-control select2" name="sex">
                            @foreach($hr_sex as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-lg-3">
                        <label>Civil Status<span class="text-require">*</span></label>
                        <select class="form-control select2" name="civil_status">
                            @foreach($hr_civil_status as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-lg-3">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email">
                      </div>
                      <div class="col-lg-3">
                        <label>Contact No.</label>
                        <input type="text" class="form-control" name="contact_no">
                      </div>
                      <div class="col-lg-12"><br></div>
                      <div class="col-lg-3">
                        <label>Date Hired<span class="text-require">*</span></label>
                        <input type="text" class="form-control datePicker" name="date_from">
                      </div>
                      <div class="col-lg-3">
                          <label>Date To<span class="text-require">*</span></label>
                          <label><input type="radio" name="date_to_option" value="present" checked> present</label>
                          <label><input type="radio" name="date_to_option" value="date"> Date</label>
                          <input type="text" class="form-control datePicker" name="date_to" readonly>
                      </div>
                      <div class="col-lg-6">
                        <label>Position<span class="text-require">*</span></label>
                        <div id="positionList">
                            <select class="form-control select2 positionList" name="position_id">
                            </select>
                        </div>
                        <input type="hidden" name="position_title">
                        <input type="hidden" name="position_shorten">
                      </div>
                      <div class="col-lg-3">
                        <label>Salary<span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="salary">
                      </div>
                      <div class="col-lg-3">
                          <label>SG<span class="text-require">*</span></label>
                          <input type="number" class="form-control" name="sg">
                      </div>
                      <div class="col-lg-3">
                          <label>Step<span class="text-require">*</span></label>
                          <input type="number" class="form-control" name="step">
                      </div>
                      <div class="col-lg-3">
                          <label>Employment Status<span class="text-require">*</span></label>
                          <select class="form-control select2" name="emp_stat" disabled>
                              @foreach($hr_emp_stat as $row)
                              <option value="{{$row->id}}">{{$row->name}}</option>
                              @endforeach
                          </select>
                      </div>             
                      <div class="col-lg-3">
                          <label>Fund Source<span class="text-require">*</span></label>
                          <select class="form-control select2" name="fund_source" disabled>
                              <option value="none">None</option>
                              @foreach($hr_fund_source as $row)
                              <option value="{{$row->id}}">{{$row->code}} ({{$row->name}})</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-3">
                          <label>Gov't Service?<span class="text-require">*</span></label>
                          <select class="form-control select2" name="gov_service" disabled>
                              <option value="Y">Yes</option>
                              <option value="N">No</option>
                          </select>
                      </div>
                      <div class="col-lg-3">
                          <label>Designation</label>
                          <div id="designationList">
                              <select class="form-control select2 designationList" name="designation">
                                  <option value="none">None</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-lg-3">
                          <label>Designation Type<span class="text-require">*</span></label>
                          <select class="form-control select2" name="credit_type" disabled>
                              <option value="none">None</option>
                              @foreach($hr_credit_type as $row)
                              <option value="{{$row->id}}">{{$row->name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-3">
                          <label>Option<span class="text-require">*</span></label>
                          <select class="form-control select2" name="role" disabled>
                              @foreach($hr_user_role as $row)
                              <option value="{{$row->id}}">{{$row->name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-12"><br>
                        <button class="btn btn-success btn-success-scan" name="submit_new_employee" style="width: 100%">
                          <span class="fa fa-check"></span> Submit
                        </button>
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
  </div><!-- /.container-fluid -->
</div>
<!-- /.Content -->
@include('layouts.script')
<script src="{{ asset('assets/js/search/position.js') }}"></script>
<script src="{{ asset('assets/js/search/designation.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/work/work.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/employee_new.js') }}"></script>

@endsection