
<div class="modal-content" id="coursesViewModal">
    <div class="modal-header">
        <h4 class="modal-title">{{$program->name}} - {{$program->program->name}} ({{$program->program->shorten}})</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-4">
                <label>Curriculum</label>
                <select class="form-control select2-primary" name="curriculum">
                    @foreach($curriculums as $row)
                        <option value="{{$row->id}}">{{$row->curriculum->year_from}} - {{$row->curriculum->year_to}}</option>
                    @endforeach
                </select>
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
