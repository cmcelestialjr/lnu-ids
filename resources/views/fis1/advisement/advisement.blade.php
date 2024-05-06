@extends('layouts.header')
@section('content')
<div class="row" id="advisementDiv">
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
                <div class="col-lg-4" id="students">
                    <label>Student</label>
                    <select class="form-control select2 search_student" name="student">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-lg-12">
                    <br>
                    <div class="card card-primary card-outline">
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Program</label>
                                    <input type="text" class="form-control" name="program" value="" readonly>
                                </div>
                                <div class="col-lg-2">
                                    <label>Level</label>
                                    <input type="text" class="form-control" name="level" value="" readonly>
                                </div>
                                <div class="col-lg-1">
                                    <label>Code</label>
                                    <select class="form-control select2" name="code">
                                       
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label>Curriculum</label>
                                    <select class="form-control select2" name="curriculum">
    
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label>Section</label>
                                    <select class="form-control select2" name="section">
    
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                  <label>Year</label>
                                  <select class="form-control select2" name="grade_level[]" multiple>
                                    
                                  </select>
                              </div>
                            </div>
                        </div>
                    </div>
                  <button class="btn btn-info btn-info-scan btn-sm" id="courseAddModal">
                    <span class="fa fa-plus-square"></span> Add Course
                  </button>
                    <label class="text-primary" style="float:right">Total Unit: 
                      <span id="courseTotalUnits"></span></label>
                  <br><br>                       
                  <div id="studentAdvisement"></div>
                  <button class="btn btn-success btn-success-scan" name="submit_advisement" style="width:100%">
                    <span class="fa fa-check"></span> Submit Advisement
                  </button>
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
<script src="{{ asset('assets/js/rims/student/search.js') }}"></script>
<script src="{{ asset('assets/js/fis/advisement.js') }}"></script>
@endsection