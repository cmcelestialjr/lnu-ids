
<div class="modal-content" id="enrollModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-plus-square"></span> Enroll for School Year 
           {{$school_year->year_from}}-{{$school_year->year_to}} ({{$school_year->grade_period->name}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-4" id="students">
                <label>Student</label>
                <select class="form-control select2-default search_student" name="student">
                  <option value=""></option>
                </select>
           </div>
           <div class="col-lg-8">
                <button class="btn btn-info btn-info-scan hide" id="courseAddModal" style="float:right;">
                    <span class="fa fa-plus-square"></span> Add Course
                </button>
           </div>
           <div class="col-lg-12" id="studentInformationDiv">
           </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
<script src="{{ asset('assets/js/rims/student/search.js') }}"></script>
