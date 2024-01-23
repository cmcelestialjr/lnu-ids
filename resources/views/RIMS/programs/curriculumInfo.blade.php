<div class="col-md-2">
    <label>Status Curriculum</label><br>
    @php
        if($user_access_level==1 || $user_access_level==2){
            $curriculumStatus = 'curriculumStatus';
        }else{
            $curriculumStatus = '';
        }
    @endphp
    @if($curriculum!=NULL)
        @if($curriculum->status_id==1)
            <button type="button" class="btn btn-success btn-success-scan {{$curriculumStatus}}"
                data-id="{{$curriculum->id}}"> Open</button>
        @else
            <button type="button" class="btn btn-danger btn-danger-scan {{$curriculumStatus}}"
                data-id="{{$curriculum->id}}"> Close</button>
        @endif
    @endif
</div>
<div class="col-md-2">
    <label>Name</label>
    <input type="text" class="form-control curriculum_input" data-n="name" value="{{$curriculum->name}}">
</div>
<div class="col-md-2">
    <label>Curriculums</label>
    <div id="curriculums">
    <select class="form-control select2-default-info curriculumSelects" name="curriculum">
        @foreach($curriculums as $row)
            @if($curriculum->id==$row->id)
                <option value="{{$row->id}}" selected>{{$row->year_from}} - {{$row->year_to}} ({{$row->code}}) ({{$row->status->name}})</option>
            @else
                <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->code}}) ({{$row->status->name}})</option>
            @endif            
        @endforeach
    </select>
    </div>
</div>
<div class="col-md-2">
    <label>Year Level</label>
    <select class="form-control select2-default-info curriculumSelects" multiple name="year_level[]">
        @foreach($year_level as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2">
    <label>Status Courses</label>
    <select class="form-control select2-default-info curriculumSelects" multiple name="status_course[]">
        @foreach($status as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2">
    <label>Remarks</label>
    <textarea class="form-control curriculum_input" data-n="remarks">{{$curriculum->remarks}}</textarea>
</div>