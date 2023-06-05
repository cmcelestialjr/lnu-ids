<div class="col-lg-4">
    <label>Room</label>
    <select class="select2-rm_instructor rm_instructor1" name="room" style="width:100%">
        <option value="TBA">TBA</option>
        @if($rooms!=NULL)
        <option value="{{$rooms->id}}" selected>{{$rooms->name}}</option>
        @endif
    </select>
</div>
<div class="col-lg-6">
    <label>Instructor</label>
    <select class="select2-rm_instructor rm_instructor1" name="instructor" style="width:100%">
        <option value="TBA">TBA</option>
        @if($instructors!=NULL)
            <option value="{{$instructors->user_id}}" selected>
                {{$name_services->lastname($instructors->user->lastname,$instructors->user->firstname,$instructors->user->middlename,$instructors->user->extname)}}
            </option>
        @endif
    </select>
</div>
<div class="col-lg-12"><br></div>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-1">
            <label>Hrs</label>
            <input type="number" class="form-control input-rm_instructor" name="hours" value="{{$hours}}">
        </div>
        <div class="col-lg-1">
            <label>Mins</label>
            <select class="select2-rm_instructor rm_instructor1" name="minutes" style="width:100%">
                @foreach($minutes_list as $row)
                    @if($row==$minutes)
                        <option value="{{$row}}" selected>{{$row}}</option>
                    @else
                        <option value="{{$row}}">{{$row}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <label>Days</label>
            <select class="select2-rm_instructor rm_instructor_day" name="days[]" multiple style="width:100%">
                @if($days_sched!='')
                    @foreach($days_sched as $day)
                        <option value="{{$day->no}}" selected>{{$day->day}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-lg-3" id="rm_instructor_time">
            <label>Time</label>
            <select class="select2-rm_instructor rm_instructor1" name="time" style="width:100%">
                <option value="TBA">TBA</option>
                @if($time_sched!='')
                <option value="{{$time_sched}}" selected>{{$time_sched}}</option>
                @endif
            </select>
        </div>
        <div class="col-sm-1 center">            
            <input type="radio" class="form-control" name="type" id="lecture" value="Lec" {{$lec}}>
            <label for="lecture">Lec</label>
        </div>
        <div class="col-sm-1 center">            
            <input type="radio" class="form-control" name="type" id="laboratory" value="Lab" {{$lab}}>
            <label for="laboratory">Lab</label>
        </div>
        <div class="col-lg-3">
            @if(($schedule_id!=NULL && $schedule_id!='new') || $time_sched!='')
                &nbsp; <br>
                <button class="btn btn-danger btn-danger-scan" name="delete">
                    <span class="fa fa-trash"></span> Remove this Schedule
                </button>
            @endif
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/rims/schedule/_select.js') }}"></script>
<script src="{{ asset('assets/js/rims/schedule/_select_day_time.js') }}"></script>