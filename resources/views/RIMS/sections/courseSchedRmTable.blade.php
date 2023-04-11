<div class="table-responsive" style="height: 400px;">
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
            if($course_curriculum->count()>0){
                foreach ($course_curriculum as $row) {
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
                                                            $row->course->code.'</button>';
                                }
                                if($sched_time_to==$time_list){
                                    $d[$day_no] = '<button class="bg-info btn-no-design scheduleTimeUpdate"
                                                        id="dayTime'.$count_x.$day->no.'"
                                                        data-t="'.$time_list.'"
                                                        data-d="'.$day->no.'"
                                                        data-x="'.$count_x.'"
                                                        style="height:100%;width:100%;">'.
                                                            $row->course->code.'</button>';
                                }
                                if($sched_time_from<$time_list && $sched_time_to>$time_list){
                                    $d[$day_no] = '<button class="bg-info btn-no-design" style="height:100%;width:100%;"
                                                        id="dayTime'.$count_x.$day->no.'">&nbsp;&nbsp;</button>';
                                }
                            }
                        }
                    }
                }
            }
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
                                                                $row->course->code.'</button>';
                                    }
                                    if($sched_time_to==$time_list){
                                        $d[$day_no] = '<button class="bg-warning btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $row->course->code.'</button>';
                                    }
                                    if($sched_time_from<$time_list && $sched_time_to>$time_list){
                                        $d[$day_no] = '<button class="bg-warning btn-no-design" style="height:100%;width:100%;"
                                                            id="dayTime'.$count_x.$day->no.'">&nbsp;&nbsp;</button>';
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
                                                                $row->course->code.'</button>';
                                    }
                                    if($sched_time_to==$time_list){
                                        $d[$day_no] = '<button class="bg-primary btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $row->course->code.'</button>';
                                    }
                                    if($sched_time_from<$time_list && $sched_time_to>$time_list){
                                        $d[$day_no] = '<button class="bg-primary btn-no-design" style="height:100%;width:100%;"
                                                            id="dayTime'.$count_x.$day->no.'">&nbsp;&nbsp;</button>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($this_offered_schedule->count()>0){
                foreach ($this_offered_schedule as $row) {
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
                        if($sched_time_from<$time_list && $sched_time_to>$time_list){
                            $d[$day_no] = '<button class="bg-success-light btn-no-design" 
                                                style="height:100%;width:100%;"
                                                id="dayTime'.$count_x.$day->no.'">&nbsp;&nbsp;</button>';
                        }
                    }
                }
            }
            if($schedule!=NULL){
                $schedule_time_from = date('h:ia',strtotime($schedule->time_from));
                if($schedule->time_to!=NULL){
                    $schedule_time_to = date('h:ia',strtotime($schedule->time_to));
                }else{
                    $schedule_time_to = '';
                }
                foreach ($schedule->days as $day) {
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
                    if($schedule_time_from<$time_list && $schedule_time_to>$time_list){
                        $d[$day_no] = '<button class="bg-success btn-no-design scheduleTimeUpdate"
                                            id="dayTime'.$count_x.$day->no.'"
                                            data-t="'.$time_list.'"
                                            data-d="'.$day->no.'"
                                            data-x="'.$count_x.'"
                                            style="height:100%;width:100%;">&nbsp;&nbsp;</button>';
                    }
                }
            }
            
            if($room_schedule_conflict!=NULL){
                if($room_schedule_conflict->count()>0){
                    foreach ($room_schedule_conflict as $row) {
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
                                        $d[$day_no] = '<button class="bg-danger btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $row->course->code.'</button>';
                                    }
                                    if($sched_time_to==$time_list){
                                        $d[$day_no] = '<button class="bg-danger btn-no-design scheduleTimeUpdate"
                                                            id="dayTime'.$count_x.$day->no.'"
                                                            data-t="'.$time_list.'"
                                                            data-d="'.$day->no.'"
                                                            data-x="'.$count_x.'"
                                                            style="height:100%;width:100%;">'.
                                                                $row->course->code.'</button>';
                                    }
                                    if($sched_time_from<$time_list && $sched_time_to>$time_list){
                                        $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                            id="dayTime'.$count_x.$day->no.'">&nbsp;&nbsp;</button>';
                                    }
                                }
                            }
                        }
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
                                                    $row->course->code.'</button>';
                            }
                            if($sched_time_to==$time_list){
                                $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                    id="dayTime'.$count_x.$day->no.'">'.$row->course->code.'</button>';
                            }
                            if($sched_time_from<$time_list && $sched_time_to>$time_list){
                                $d[$day_no] = '<button class="bg-danger btn-no-design" style="height:100%;width:100%;"
                                                    id="dayTime'.$count_x.$day->no.'">&nbsp;&nbsp;</button>';
                            }
                        }
                    }
                }
            }
            $count_x++;
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