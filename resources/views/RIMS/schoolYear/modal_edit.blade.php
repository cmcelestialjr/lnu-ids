
<div class="modal-content bg-info" id="editDiv">
    <div class="modal-header">
        <h4 class="modal-title">{{$query->year_from}} - {{$query->year_to}} ({{$query->grade_period->name}}) 
            <br> School Year</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-7">
                <label>School Duration (Date)</label>
                <div class="input-group date">
                  <div class="input-group-append">
                    <div class="input-group-text"><span class="fa fa-calendar"></span></div>
                  </div>
                  <input type="text" class="form-control date-range" name="date_duration" value="{{$query->date_from}} - {{$query->date_to}}">
                </div>
              </div>
              <div class="col-lg-4">
                <label>Extension</label>
                <input type="text" class="form-control datepicker" name="date_extension" value="{{$query->date_extension}}">
              </div>
              <div class="col-lg-12">
              </div>
              <div class="col-lg-7">
                <label>Enrollment Duration</label>
                <div class="input-group date">
                  <div class="input-group-append">
                    <div class="input-group-text"><span class="fa fa-calendar"></span></div>
                  </div>
                  <input type="text" class="form-control date-range" name="enrollment_duration" value="{{$query->enrollment_from}} - {{$query->enrollment_to}}">
                </div>
              </div>
              <div class="col-lg-4">
                <label>Extension</label>
                <input type="text" class="form-control datepicker" name="enrollment_extension" value="{{$query->enrollment_extension}}">
              </div>
              <div class="col-lg-12">
              </div>
              <div class="col-lg-7">
                <label>Add/Dropping Duration</label>
                <div class="input-group date">
                  <div class="input-group-append">
                    <div class="input-group-text"><span class="fa fa-calendar"></span></div>
                  </div>
                  <input type="text" class="form-control date-range" name="add_dropping_duration" value="{{$query->add_dropping_from}} - {{$query->add_dropping_to}}">
                </div>
              </div>
              <div class="col-lg-4">
                <label>Extension</label>
                <input type="text" class="form-control datepicker" name="add_dropping_extension" value="{{$query->add_dropping_extension}}">
              </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit">
            <span class="fa fa-check"></span> Submit</button>    
    </div>
</div>
<!-- /.modal-content -->
