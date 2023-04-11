<div class="col-lg-4">
    <div class="row">
        <div class="col-lg-12">
            <label>Room</label>
            @if($schedule_id!=NULL && $schedule_id!='new')
            <select class="select2-rm_instructor" name="room" style="width:100%">
            @else
            <select class="select2-rm_instructor" name="room" style="width:100%" disabled>
            @endif
                <option value="TBA">TBA</option>
                @foreach($rooms as $row)
                    @if($row->id==$room_id)
                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                    @else
                    <option value="{{$row->id}}">{{$row->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-lg-12"><br>
        </div>
        <div class="col-lg-2">
            <input type="radio" class="form-control" name="type" id="lecture" value="Lec" {{$lec}}>
        </div>
        <div class="col-lg-4">
            <label for="lecture">Lecture</label>
        </div>
        <div class="col-lg-2">
            <input type="radio" class="form-control" name="type" id="laboratory" value="Lab" {{$lab}}>
        </div>
        <div class="col-lg-4">
            <label for="laboratory">Laboratory</label>
        </div>
    </div>
</div>
<div class="col-lg-5">
    <label>Instructor</label>
    <select class="select2-rm_instructor" name="instructor" style="width:100%">
        <option value="TBA">TBA</option>
        @foreach($instructors as $row)
            @if($row->user_id==$instructor_id)
            <option value="{{$row->user_id}}" selected>
                {{$name_services->lastname($row->user->lastname,$row->user->firstname,$row->user->middlename,$row->user->extname)}}
            </option>
            @else
            <option value="{{$row->user_id}}">
                {{$name_services->lastname($row->user->lastname,$row->user->firstname,$row->user->middlename,$row->user->extname)}}
            </option>
            @endif
         @endforeach
    </select>
</div>
<div class="col-lg-3">
    @if($schedule_id!=NULL && $schedule_id!='new')
        &nbsp; <br>
        <button class="btn btn-danger btn-danger-scan" name="delete">
            <span class="fa fa-trash"></span> Remove this Schedule
        </button>
    @endif
</div>