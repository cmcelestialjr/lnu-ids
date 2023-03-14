
<div class="modal-content" id="curriculumModal">
    <div class="modal-header">
        <h4 class="modal-title">
            {{$program->name}} - {{$program->shorten}}
        </h4>
        @if($user_access_level==1 || $user_access_level==2)
        <button class="btn btn-primary btn-primary-scan" name="curriculumNew" style="float:right;">
            <span class="fa fa-plus-square"></span> New Curriculum</button>
        @endif
    </div>
    <div class="modal-body">
        <input type="hidden" name="id" value="{{$id}}">
        <div class="row" id="curriculumDiv">
            <div class="col-md-2">
                <label>Status Curriculum</label><br>
                @php
                    if($user_access_level==1 || $user_access_level==2){
                        $curriculumStatus = 'curriculumStatus';
                    }else{
                        $curriculumStatus = '';
                    }
                @endphp
                @if($curriculum->status_id==1)
                    <button type="button" class="btn btn-success btn-success-scan {{$curriculumStatus}}"
                        data-id="{{$curriculum->id}}"> Open</button>
                @else
                    <button type="button" class="btn btn-danger btn-danger-scan {{$curriculumStatus}}"
                        data-id="{{$curriculum->id}}"> Close</button>
                @endif
            </div>
            <div class="col-md-3">
                <label>Curriculums</label>
                <div id="curriculums">
                <select class="form-control select2-default" name="curriculum">
                    @foreach($curriculums as $row)
                        <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->status->name}})</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="col-md-2">
                <label>Year Level</label>
                <select class="form-control select2-default" multiple name="year_level[]">
                    @foreach($year_level as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Status Courses</label>
                <select class="form-control select2-default" multiple name="status_course[]">
                    @foreach($status as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                &nbsp;<br>
                <button class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span></button>
            </div>
            <div class="col-lg-12">
                <br>
                @if($user_access_level==1 || $user_access_level==2)
                <button class="btn btn-primary btn-primary-scan" name="newCourse">
                    <span class="fa fa-plus-square"></span> New Subject/Course</button>
                @endif
                <br><br>
            </div>
            <div class="col-lg-12" id="curriculumTable">

            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
