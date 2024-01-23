<div class="table-responsive" style="height: 600px;">
<table class="table-sched">
    <thead>
        <tr class="{{$scheduleRemoveDayTr}}" id="scheduleRemoveDayTr">
            <th>Day</th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="7">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="1">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="2">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="3">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="4">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="5">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
            <th>
                <button class="btn btn-danger btn-danger-scan btn-xs scheduleRemoveDay" style="width: 100%"
                    data-d="6">
                    <span class="fa fa-times"></span> Remove
                </button>
            </th>
        </tr>
        <tr>
            <th style="width: 12.5%">Time</th>
            <th style="width: 12.5%">(SU)Sunday</th>
            <th style="width: 12.5%">(M)Monday</th>
            <th style="width: 12.5%">(T)Tuesday</th>
            <th style="width: 12.5%">(W)Wednesday</th>
            <th style="width: 12.5%">(TH)Thursday</th>
            <th style="width: 12.5%">(F)Friday</th>
            <th style="width: 12.5%">(S)Saturday</th>
        </tr>
        
    </thead>
    <tbody>
        @php
        $count_x = 0;
        @endphp
        @foreach ($time_period as $time)
            @php
                $time_list = $time->format('h:ia');
                $time_check['0'] = 0;
                $time_check['1'] = 0;
                $time_check['2'] = 0;
                $time_check['3'] = 0;
                $time_check['4'] = 0;
                $time_check['5'] = 0;
                $time_check['6'] = 0;
                $d['0'] = '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'7"
                                data-t="'.$time_list.'"
                                data-d="7"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                $d['1'] = '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'1"
                                data-t="'.$time_list.'"
                                data-d="1"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                $d['2'] = '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'2"
                                data-t="'.$time_list.'"
                                data-d="2"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                $d['3'] = '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'3"
                                data-t="'.$time_list.'"
                                data-d="3"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                $d['4'] = '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'4"
                                data-t="'.$time_list.'"
                                data-d="4"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                $d['5'] = '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'5"
                                data-t="'.$time_list.'"
                                data-d="5"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                $d['6']= '<button class="btn-no-design scheduleTimeUpdate blank"
                                id="dayTime'.$count_x.'6"
                                data-t="'.$time_list.'"
                                data-d="6"
                                data-x="'.$count_x.'"
                                style="height:100%;width:100%;">&nbsp;&nbsp;</button>'; 
            if($course_room!=NULL){                
                if($course_room->count()>0){
                    foreach ($course_room as $row) {
                        if($row->schedule!=NULL){
                            foreach ($row->schedule as $sched) {
                                $sched_time_from = date('h:ia',strtotime($sched->time_from));
                                if($sched->time_to!=NULL){
                                    $sched_time_to = date('h:ia',strtotime($sched->time_to));
                                }else{
                                    $sched_time_to = '';
                                }
                                foreach ($sched->days as $day) {
                                    if($day->no=='7'){
                                        $day_no = 0;
                                    }else{
                                        $day_no = $day->no;
                                    }
                                    if($sched_time_from==$time_list){
                                        $d[$day_no] = '<button class="bg-warning btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $time_list.'</button>';
                                    }
                                    if($sched_time_to==$time_list){
                                        $d[$day_no] = '<button class="bg-warning btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $time_list.'</button>';
                                    }
                                    if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                        $d[$day_no] = '<button class="bg-warning btn-no-design" style="height:100%;width:100%;"
                                                            id="dayTime'.$count_x.$day->no.'">'.$row->course->code.'</button>';
                                        $time_check[$day_no] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($course_instructor!=NULL){
                if($course_instructor->count()>0){
                    foreach ($course_instructor as $row) {
                        if($row->schedule!=NULL){
                            foreach ($row->schedule as $sched) {
                                $sched_time_from = date('h:ia',strtotime($sched->time_from));
                                if($sched->time_to!=NULL){
                                    $sched_time_to = date('h:ia',strtotime($sched->time_to));
                                }else{
                                    $sched_time_to = '';
                                }
                                foreach ($sched->days as $day) {
                                    if($day->no=='7'){
                                        $day_no = 0;
                                    }else{
                                        $day_no = $day->no;
                                    }
                                    if($sched_time_from==$time_list){
                                        $d[$day_no] = '<button class="bg-primary btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $time_list.'</button>';
                                    }
                                    if($sched_time_to==$time_list){
                                        $d[$day_no] = '<button class="bg-primary btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $time_list.'</button>';
                                    }
                                    if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                        $d[$day_no] = '<button class="bg-primary btn-no-design" style="height:100%;width:100%;"
                                                            id="dayTime'.$count_x.$day->no.'">'.$row->course->code.'</button>';
                                        $time_check[$day_no] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($course_section->count()>0){
                foreach ($course_section as $row) {
                    if($row->schedule!=NULL){                        
                        foreach ($row->schedule as $sched) {
                            $sched_time_from = date('h:ia',strtotime($sched->time_from));
                            if($sched->time_to!=NULL){
                                $sched_time_to = date('h:ia',strtotime($sched->time_to));
                            }else{
                                $sched_time_to = '';
                            }
                            foreach ($sched->days as $day) {
                                if($day->no=='7'){
                                    $day_no = 0;
                                }else{
                                    $day_no = $day->no;
                                }
                                if($sched_time_from==$time_list){
                                    $d[$day_no] = '<button class="bg-info btn-no-design scheduleTimeUpdate"
                                                        id="dayTime'.$count_x.$day->no.'"
                                                        data-t="'.$time_list.'"
                                                        data-d="'.$day->no.'"
                                                        data-x="'.$count_x.'"
                                                        style="height:100%;width:100%;">'.
                                                            $time_list.'</button>';
                                }
                                if($sched_time_to==$time_list){
                                    $d[$day_no] = '<button class="bg-info btn-no-design scheduleTimeUpdate"
                                                        id="dayTime'.$count_x.$day->no.'"
                                                        data-t="'.$time_list.'"
                                                        data-d="'.$day->no.'"
                                                        data-x="'.$count_x.'"
                                                        style="height:100%;width:100%;">'.
                                                            $time_list.'</button>';
                                }
                                if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                    $d[$day_no] = '<button class="bg-info btn-no-design" style="height:100%;width:100%;"
                                                        id="dayTime'.$count_x.$day->no.'">'.$row->course->code.'</button>';
                                    $time_check[$day_no] = 1;
                                }
                            }
                        }
                    }
                }
            }
            if($course_schedule_others->count()>0){
                foreach ($course_schedule_others as $row) {
                    $sched_time_from = date('h:ia',strtotime($row->time_from));
                    if($row->time_to!=NULL){
                        $sched_time_to = date('h:ia',strtotime($row->time_to));
                    }else{
                        $sched_time_to = '';
                    }
                    foreach ($row->days as $day) {
                        if($day->no=='7'){
                            $day_no = 0;
                        }else{
                            $day_no = $day->no;
                        }
                        if($sched_time_from==$time_list){
                            $d[$day_no] = '<button class="bg-success-light btn-no-design scheduleTimeUpdate"
                                                id="dayTime'.$count_x.$day->no.'"
                                                data-t="'.$time_list.'"
                                                data-d="'.$day->no.'"
                                                data-x="'.$count_x.'"
                                                style="height:100%;width:100%;">'.
                                                    $sched_time_from.'</button>';
                        }
                        if($sched_time_to==$time_list){
                            $d[$day_no] = '<button class="bg-success-light btn-no-design scheduleTimeUpdate"
                                                id="dayTime'.$count_x.$day->no.'"
                                                data-t="'.$time_list.'"
                                                data-d="'.$day->no.'"
                                                data-x="'.$count_x.'"
                                                style="height:100%;width:100%;">'.$sched_time_to.'</button>';
                        }
                        if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                            $d[$day_no] = '<button class="bg-success-light btn-no-design" 
                                                style="height:100%;width:100%;"
                                                id="dayTime'.$count_x.$day->no.'">'.$row->course->course->code.'</button>';
                            $time_check[$day_no] = 1;
                        }
                    }
                }
            }
            if($course_schedule!=NULL){
                $schedule_time_from = date('h:ia',strtotime($course_schedule->time_from));
                if($course_schedule->time_to!=NULL){
                    $schedule_time_to = date('h:ia',strtotime($course_schedule->time_to));
                }else{
                    $schedule_time_to = '';
                }
                foreach ($course_schedule->days as $day) {
                    if($day->no=='7'){
                        $day_no = 0;
                    }else{
                        $day_no = $day->no;
                    }
                    if($schedule_time_from==$time_list){
                        $d[$day_no] = '<button class="bg-success btn-no-design scheduleTimeUpdate"
                                            id="dayTime'.$count_x.$day->no.'"
                                            data-t="'.$time_list.'"
                                            data-d="'.$day->no.'"
                                            data-x="'.$count_x.'"
                                            style="height:100%;width:100%;">'.
                                                $schedule_time_from.'</button>';
                    }
                    if($schedule_time_to==$time_list){
                        $d[$day_no] = '<button class="bg-success btn-no-design scheduleTimeUpdate"
                                            id="dayTime'.$count_x.$day->no.'"
                                            data-t="'.$time_list.'"
                                            data-d="'.$day->no.'"
                                            data-x="'.$count_x.'"
                                            style="height:100%;width:100%;">'.$schedule_time_to.'</button>';
                    }
                    if(strtotime($schedule_time_from)<strtotime($time_list) && strtotime($schedule_time_to)>strtotime($time_list)){
                        $d[$day_no] = '<button class="bg-success btn-no-design scheduleTimeUpdate"
                                            id="dayTime'.$count_x.$day->no.'"
                                            data-t="'.$time_list.'"
                                            data-d="'.$day->no.'"
                                            data-x="'.$count_x.'"
                                            style="height:100%;width:100%;">'.$course_schedule->course->course->code.'</button>';
                    }
                }
            }
            
            if($room_schedule_conflict!=NULL){
                if($room_schedule_conflict->count()>0){
                    foreach ($room_schedule_conflict as $sched) {
                        // if($row->schedule!=NULL){
                        //     foreach ($row->schedule as $sched) {
                                $sched_time_from = date('h:ia',strtotime($sched->time_from));
                                if($sched->time_to!=NULL){
                                    $sched_time_to = date('h:ia',strtotime($sched->time_to));
                                }else{
                                    $sched_time_to = '';
                                }
                                foreach ($sched->days as $day) {
                                    if($day->no=='7'){
                                        $day_no = 0;
                                    }else{
                                        $day_no = $day->no;
                                    }
                                    if($sched_time_from==$time_list){
                                        $d[$day_no] = '<button class="bg-danger btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $time_list.'</button>';
                                    }
                                    if($sched_time_to==$time_list){
                                        $d[$day_no] = '<button class="bg-danger btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $time_list.'</button>';
                                    }
                                    if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                        $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                            id="dayTime'.$count_x.$day->no.'">'.$sched->course->course->code.'</button>';
                                        $time_check[$day_no] = 1;
                                    }
                                }
                        //     }
                        // }
                    }
                }
            }
            if($instructor_schedule_conflict!=NULL){
                if($instructor_schedule_conflict->count()>0){                    
                    foreach ($instructor_schedule_conflict as $sched) {
                        $sched_time_from = date('h:ia',strtotime($sched->time_from));
                        if($sched->time_to!=NULL){
                            $sched_time_to = date('h:ia',strtotime($sched->time_to));
                        }else{
                            $sched_time_to = '';
                        }
                        foreach ($sched->days as $day) {
                            if($day->no=='7'){
                                $day_no = 0;
                            }else{
                                $day_no = $day->no;
                            }
                            if($sched_time_from==$time_list){
                                $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                    id="dayTime'.$count_x.$day->no.'">'.
                                                    $time_list.'</button>';
                                $time_check[$day_no] = 1;
                            }
                            if($sched_time_to==$time_list){
                                $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                    id="dayTime'.$count_x.$day->no.'">'.$time_list.'</button>';
                                $time_check[$day_no] = 1;
                            }
                            if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                    id="dayTime'.$count_x.$day->no.'">'.$sched->course->course->code.'</button>';
                                $time_check[$day_no] = 1;
                            }
                        }
                    }
                }
            }
            $count_x++;
            for ($i=0; $i < 7; $i++) { 
                if($time_check[$i]==0){
                    $time_day[$i][] = $time_list;
                }
            }
            @endphp
            <tr>
                <td class="center">{{$time_list}}</td>
                <td>{!!$d['0']!!}</td>
                <td>{!!$d['1']!!}</td>
                <td>{!!$d['2']!!}</td>
                <td>{!!$d['3']!!}</td>
                <td>{!!$d['4']!!}</td>
                <td>{!!$d['5']!!}</td>
                <td>{!!$d['6']!!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@php
for ($h=0; $h < 7; $h++) { 
    $times = $time_day[$h];
    $count = count($times);
    for ($i = 0; $i < $count - 1; $i++) {
        $time_next = $times[$i+1];
        $time_1 = strtotime($times[$i]);
        $time_2 = strtotime($time_next);            
        $diff_in_seconds_ = abs($time_2 - $time_1);
        $diff_in_minutes_ = $diff_in_seconds_ / 60;
        $diff_in_minutes_total = 15;
        $x = 1;
        for ($j = $i + 1; $j < $count; $j++) {
            $time1 = strtotime($times[$i]);
            $time2 = strtotime($times[$j]);                
            $diff_in_seconds = abs($time2 - $time1);
            $diff_in_minutes = $diff_in_seconds / 60;
            $diff_in_hours = $diff_in_seconds / 3600;

            if (in_array($times[$j], $time_day[$h]) && $diff_in_minutes==$x*15){
                if ($diff_in_minutes_ == 15) {
                    if ($diff_in_hours == $hours+($minutes/60)) {
                        if($h==0){
                            $day = 7;
                        }else{
                            $day = $h;
                        }
                        echo '<input type="hidden" name="schedDayTime[]" class="schedDayTimeInput"
                                data-d="'.$day.'"
                                data-t="'.$times[$i].'-'.$times[$j].'"
                                value="'.$times[$i].'-'.$times[$j].'">';
                    }
                }
            }
            $x++;
            $diff_in_minutes_total+=$diff_in_minutes;
        }
    }
}
@endphp
<script src="{{ asset('assets/js/rims/schedule/_select_day_time.js') }}"></script>
