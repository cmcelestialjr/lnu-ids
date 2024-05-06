
<div class="modal-content" id="courseAddModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-plus-square"></span> Add Course
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-4">
                <label>Program</label>
                <select class="form-control select2-primary" name="program">
                    <option value="">Please select...</option>
                    @foreach($query as $row)
                        <option value="{{$row->id}}">{{$row->name}} - {{$row->program->name}} ({{$row->program->shorten}})</option>
                    @endforeach
                </select>
           </div>
           <div class="col-lg-4">
                <label>Curriculum</label>
                <select class="form-control select2-primary" name="curriculum">
                </select>
            </div>
            <div class="col-lg-4">
                <label>Section</label>
                <select class="form-control select2-primary" name="section">
                </select>
            </div>
            <div class="col-lg-12" id="programAddCourseDiv">
                <br><br><br><br><br><br><br><br><br><br><br>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan hide" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
