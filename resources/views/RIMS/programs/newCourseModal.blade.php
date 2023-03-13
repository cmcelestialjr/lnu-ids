
<div class="modal-content" id="newCourseModal">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$curriculum->year_from}} - {{$curriculum->year_to}} ({{$curriculum->status->name}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <label>Period</label>
                <select class="form-control select2-primary" name="grade_period">
                    @foreach($grade_period as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Year Level</label>
                <select class="form-control select2-primary" name="year_level">
                    @foreach($year_level as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Course Code</label>
                <input type="text" class="form-control req" name="code">
            </div>
            <div class="col-md-4">
                <label>Descriptive Title</label>
                <input type="text" class="form-control req" name="name">
            </div>
            <div class="col-md-4">
                <label>Units</label>
                <input type="number" class="form-control req" name="units">
            </div>
            <div class="col-md-12">
                <br>
                <label>Pre-requisite</label>
                <div id="curriculumTablePre">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
