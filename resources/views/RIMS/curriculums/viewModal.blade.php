
<div class="modal-content" id="viewModal">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$curriculum->year_from}} - {{$curriculum->programs->shorten}}-{{$curriculum->programs->name}}
        </h4>
    </div>
    <div class="modal-body">
        <div id="coursesDiv">
            <div class="row">
                <input type="hidden" name="curriculum_id" value="{{$id}}">
                <div class="col-md-2">
                    <label>Status Curriculum</label><br>
                    @if($curriculum!=NULL)
                        @if($curriculum->status_id==1)
                            <button type="button" class="btn btn-success btn-success-scan"> Open</button>
                        @else
                            <button type="button" class="btn btn-danger btn-danger-scan"> Close</button>
                        @endif
                    @endif
                </div>
                <div class="col-md-3">
                    <label>Year Level</label>
                    <select class="form-control select2-default" multiple name="year_level[]">
                        @foreach($year_level as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status Courses</label>
                    <select class="form-control select2-default" multiple name="status_course[]">
                        @foreach($status as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12" id="coursesTable"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            <span class="fa fa-times"></span> Close
        </button>
    </div>
</div>
<!-- /.modal-content -->

<script src="{{ asset('assets/js/rims/courses/courses.js') }}"></script>
