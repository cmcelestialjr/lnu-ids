
<div class="modal-content" id="coursesOpenModal">
    <div class="modal-header">
        <label>Open a Course</label>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-6">
                <label>Programs</label>
                <select class="form-control select2-primary" name="program">
                    <option value="">Please Select</option>
                    @foreach($programs as $row)
                        <option value="{{$row->id}}">{{$row->name}} - {{$row->program->name}} ({{$row->program->shorten}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <div id="curriculumOpenDiv"></div>
            </div>
            <div class="col-lg-12">
                <div id="curriculumListDiv"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
