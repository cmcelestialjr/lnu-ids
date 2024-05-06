
<div class="modal-content" id="coursesViewModal">
    <div class="modal-header">
        <h4 class="modal-title">{{$program->program->name}} ({{$program->program->shorten}}) - {{$program->branch->name}}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-4">
                <label>Curriculum</label>
                <select class="form-control select2-primary" name="curriculum">
                    @foreach($curriculums as $row)
                        <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->code}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label>Grade Level</label>
                <select class="form-control select2-primary" name="grade_level[]" multiple>
                    @foreach($grade_level as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><br>
                <button class="btn btn-success btn-success-scan btn-sm" name="refresh" style="float:right"><span class="fa fa-refresh"></span> Refrest</button>
            </div>
            <div class="col-lg-12">
                <div id="curriculumViewList"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>   
    </div>
</div>
<!-- /.modal-content -->

