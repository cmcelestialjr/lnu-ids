
<div class="modal-content" id="sectionNewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$query->name}} - {{$query->program->name}} ({{$query->program->shorten}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <label>Curriculum</label>
                <select class="form-control select2-default" name="curriculum">
                    @foreach($curriculum as $row)
                        <option value="{{$row->id}}">{{$row->curriculum->year_from}} - {{$row->curriculum->year_to}} ({{$row->curriculum->code}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12">
                <div id="gradeLevelDiv">
                    <label>Grade Level</label>
                    <select class="form-control select2-default" name="grade_level">
                        @foreach($grade_level as $row)
                            <option value="{{$row->level}}">{{$row->name}} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <label>No. of Section to add</label>
                <input type="number" class="form-control" name="no">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
