@if($schedule_conflict->count()==0)
    <button type="button" class="btn btn-success btn-success-scan" id="instructorModalSubmit" style="width: 100%;"><span class="fa fa-check"></span> Select this Instructor</button>
@endif
<div class="row">
    <div class="col-lg-12" style="padding-top:3px;padding-bottom:3px;">
        <div class="row" style="font-size:10px;">
            <div class="col-lg-2">Schedule Legend:</div>
            <div class="col-lg-2">
                <div class="input-group">
                    <div  class="bg-success legend-box">
                    </div>
                    <div class="input-group-append">
                        &nbsp;Course
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="input-group">
                    <div  class="bg-info legend-box">
                    </div>
                    <div class="input-group-append">
                        &nbsp;Instructor
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="input-group">
                    <div  class="bg-danger legend-box">
                    </div>
                    <div class="input-group-append">
                        &nbsp;Conflict
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div class="col-lg-12"> 
        <div class="table-responsive" style="height: 600px;">
            <table class="table-sched">
                <thead>
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
                            $d['0'] = '';
                            $d['1'] = '';
                            $d['2'] = '';
                            $d['3'] = '';
                            $d['4'] = '';
                            $d['5'] = '';
                            $d['6'] = '';                    
        
                            if($course){
                                if(count($course->schedule)>0){
                                    foreach ($course->schedule as $sched) {
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
                                                $d[$day_no] = '<button class="bg-success btn-no-design"
                                                                    style="height:100%;width:100%;">'.
                                                                        $sched_time_from.'</button>';
                                            }
                                            if($sched_time_to==$time_list){
                                                $d[$day_no] = '<button class="bg-success btn-no-design"
                                                                    style="height:100%;width:100%;">'.
                                                                        $sched_time_to.'</button>';
                                            }
                                            if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                                $d[$day_no] = '<button class="bg-success btn-no-design" 
                                                                    style="height:100%;width:100%;">
                                                                    '.$sched->course->code.'</button>';
                                                $time_check[$day_no] = 1;
                                            }
                                        }
                                    }
                                }
                            }

                            if($instructor_schedule->count()>0){
                                foreach ($instructor_schedule as $sched) {
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
                                            $d[$day_no] = '<button class="bg-info btn-no-design"
                                                                style="height:100%;width:100%;">'.
                                                                    $sched_time_from.'</button>';
                                        }
                                        if($sched_time_to==$time_list){
                                            $d[$day_no] = '<button class="bg-info btn-no-design"
                                                                style="height:100%;width:100%;">'.
                                                                    $sched_time_to.'</button>';
                                        }
                                        if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                            $d[$day_no] = '<button class="bg-info btn-no-design" 
                                                                style="height:100%;width:100%;">
                                                                '.$sched->course->code.'</button>';
                                            $time_check[$day_no] = 1;
                                        }
                                    }
                                }
                            }
        
                        if($schedule_conflict->count()>0){
                                foreach ($schedule_conflict as $sched) {
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
                                            $d[$day_no] = '<button class="bg-danger btn-no-design"
                                                                style="height:100%;width:100%;">'.
                                                                    $sched_time_from.'</button>';
                                        }
                                        if($sched_time_to==$time_list){
                                            $d[$day_no] = '<button class="bg-danger btn-no-design"
                                                                style="height:100%;width:100%;">'.
                                                                    $sched_time_to.'</button>';
                                        }
                                        if(strtotime($sched_time_from)<strtotime($time_list) && strtotime($sched_time_to)>strtotime($time_list)){
                                            $d[$day_no] = '<button class="bg-danger btn-no-design" 
                                                                style="height:100%;width:100%;">
                                                                '.$sched->course->code.'</button>';
                                            $time_check[$day_no] = 1;
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
    </div>
</div>