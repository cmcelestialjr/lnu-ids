@extends('layouts.header')
@section('content')
@if($enrollment_date<=date('Y-m-d'))
<link rel="stylesheet" href="{{ asset('assets/css/error/error.css') }}">
@endif
<div class="row" id="preEnrollDiv">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          {{-- <div class="card-header">
          </div> --}}
          <div class="card-body">
            <div class="row">
                @if($enrollment_date<=date('Y-m-d'))
                  <div class="col-lg-12 center" id="body">
                    <section id="not-found">
                      <div id="title"><br><br><br>                               
                      </div>
                      <div class="circles">
                        <p><br>
                         <small>Enrollment is not yet available</small>
                         <br><br><br><br>
                        </p>
                        <span class="circle big"></span>
                        <span class="circle med"></span>
                        <span class="circle small"></span>
                      </div>
                    </section>
                  </div>
                @else
                  <div class="col-lg-4">
                      <label>School Year</label>
                      <input type="hidden" name="school_year_id" value="{{$school_year->id}}">
                      <input type="hidden" name="id" value="{{$student_id}}">
                      <input type="text" class="form-control" name="school_year" value="{{$school_year->year_from}}-{{$school_year->year_to}} ({{$school_year->grade_period->name}})" readonly>
                    
                  </div>
                  @if($student_info->student_status_id==4)
                    <div class="col-lg-3">
                      <label>Program</label>
                      <input type="text" class="form-control" name="program" value="{{$student_info->program->shorten}}" readonly>
                    </div>
                    <div class="col-lg-2">
                      <label>Branch</label>
                      <input type="text" class="form-control" name="code" value="{{$student_info->program_code->name}}" readonly>
                    </div>
                    <div class="col-lg-3">
                      <label>Curriculum</label>
                      <input type="text" class="form-control" name="curriculum" value="{{$curriculum->year_from}}-{{$curriculum->year_to}}" readonly>
                    </div>
                    <div class="col-lg-12"><br>
                      <div class="alert alert-info center">
                        <label>Reminder: Please input below your Transcript of Record (TOR) from your previous school. 
                          Any discrepancy found will be subject for cancellation of your Pre-enrollment. Thank you!</label>
                      </div>
                      
                    </div>
                  @else                            
                    <div class="col-lg-12">
                        <br>
                      <div class="card card-primary card-outline">
                            <div class="card-body table-responsive">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>Program</label>
                                        <input type="text" class="form-control" name="program" value="{{$student_info->program->shorten}}" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Level</label>
                                        <input type="text" class="form-control" name="level" value="{{$student_info->grade_level->name}}" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Branch</label>
                                        <input type="text" class="form-control" name="code" value="{{$student_info->program_code->name}}" readonly>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Curriculum</label>
                                        <input type="text" class="form-control" name="curriculum" value="{{$curriculum->year_from}}-{{$curriculum->year_to}}" readonly>
                                    </div>
                                </div>
                            </div>
                      </div>
                        @if($student_info->program->program_level->id==7 || 
                          $student_info->program->program_level->id==8)
                          <button class="btn btn-info btn-info-scan btn-sm" id="courseAddModal">
                            <span class="fa fa-plus-square"></span> Add Course
                          </button>
                        @endif
                        <label class="text-primary" style="float:right">Total Unit: 
                          <span id="courseTotalUnits"></span></label>
                      <br>                       
                      
                    </div>
                    <div class="col-lg-12" id="preEnrollCourses"></div>
                  @endif
                @endif
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
@if($enrollment_date>date('Y-m-d'))
  @if($student_info->student_status_id==4)
                              
  @else
    <script src="{{ asset('assets/js/sims/pre_enroll/_function.js') }}"></script>
    <script src="{{ asset('assets/js/sims/pre_enroll/modal.js') }}"></script>
    <script src="{{ asset('assets/js/sims/pre_enroll/update.js') }}"></script>
    <script src="{{ asset('assets/js/sims/pre_enroll/view.js') }}"></script>
  @endif
@endif
@endsection