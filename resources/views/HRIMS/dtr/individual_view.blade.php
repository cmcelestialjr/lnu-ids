@php
$user = $users::where('id_no',$id_no)->first();
        $user_id = $user->id;

        $work = $_work::where('user_id',$user_id)->orderBy('date_from','DESC')->orderBy('emp_stat_id','ASC')->first();
        if($work->role_id==3){
            if($work->credit_type_id==2 || $work->credit_type_id==NULL){
                $emp_type = 'Employee';
            }else{
                $emp_type = 'Faculty';
            }
        }else{
            $emp_type = 'Employee';
        }
        //$emp_type = 'Faculty';
        $user_dtr = $usersDtr::where('id_no',$id_no)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)
            ->orderBy('date','ASC')
            ->orderBy('time_type','DESC')
            ->get();
        $holidays = $_holidays::
            where(function ($query) use ($month) {
                $query->whereMonth('date', $month)
                    ->where('option','Yes');
            })
            ->orWhere(function ($query) use ($year,$month) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('option','No');
            })
            ->orderBy('date','ASC')
            ->get();
        $count_days = 0;
        $count_days_with = 0;
        for($m=1;$m<=date('t',strtotime($year.'-'.$month.'-01'));$m++){
            $dtr[$m]['check'] = '';
            $dtr[$m]['val'] = '';
            $dtr[$m]['in_am'] = '';
            $dtr[$m]['out_am'] = '';
            $dtr[$m]['in_pm'] = '';
            $dtr[$m]['out_pm'] = '';  
            $dtr[$m]['time_from'] = '';
            $dtr[$m]['time_to'] = '';          
            $dtr[$m]['time_type'] = '';
            $dtr[$m]['time_type_name'] = '';
            $dtr[$m]['time_in_am_type'] = '';
            $dtr[$m]['time_out_am_type'] = '';
            $dtr[$m]['time_in_pm_type'] = '';
            $dtr[$m]['time_out_pm_type'] = '';
            $dtr[$m]['hours'] = 0;
            $dtr[$m]['minutes'] = 0;
            $time_from = '';
            $time_to = '';
            if($range==2 && $m>=15){
                $l = 15;
            }else{
                $l = $m;
                if(date('Y-m')==date('Y-m',strtotime($year.'-'.$month.'-01')) && $m>date('d')){
                   $l = date('d');
                }
            }
            $weekDay = date('w', strtotime($year.'-'.$month.'-'.$l));
            if($weekDay==0){
                $weekDay = 7;
            }
            $time_minutes_total = 0;
            if($emp_type=='Employee'){
                $day = $usersSchedDays::where('user_id',$user_id)->where('day',$weekDay)->first();
                if($day!=NULL){
                    $time_from = date('H:i',strtotime($day->time->time_from));
                    $time_to = date('H:i',strtotime($day->time->time_to));
                }
            }else{
                $day = $educOfferedScheduleDay::where('no',$weekDay)
                    ->whereHas('schedule', function ($query) use ($user_id,$year,$month) {                        
                        $query->whereHas('course', function ($query) use ($user_id,$year,$month) {
                            $query->where('instructor_id',$user_id);
                            $query->whereHas('curriculum', function ($query) use ($year,$month) {
                                $query->whereHas('offered_program', function ($query) use ($year,$month) {                                    
                                    $query->whereHas('school_year', function ($query) use ($year,$month) {
                                        $query->where('year_from','>=',$year);
                                        $query->whereHas('grade_period', function ($query) use ($month) {
                                            $query->whereHas('month', function ($query) use ($month) {
                                                $query->where('month',$month);
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    })
                    ->pluck('offered_schedule_id')->toArray();
                $time_from_query = $educOfferedSchedule::whereIn('id',$day)->orderBy('time_from','ASC')
                    ->whereHas('course', function ($query) {
                        $query->where('load_type',1);
                    })
                    ->first();
                $time_to_query = $educOfferedSchedule::whereIn('id',$day)->orderBy('time_to','DESC')
                    ->whereHas('course', function ($query) {
                        $query->where('load_type',1);
                    })
                    ->first();
                $time_minutes = $educOfferedSchedule::whereIn('id',$day)
                    ->whereHas('course', function ($query) {
                        $query->where('load_type',1);
                    })->get();                
                if($time_minutes->count()>0){                    
                    foreach($time_minutes as $row){
                        $time_from_ = Carbon::parse($row->time_from);
                        $time_to_ = Carbon::parse($row->time_to);
                        $time_minutes_total += $time_to_->diffInMinutes($time_from_);
                    }
                }
                if($time_from_query!=NULL){
                    $time_from = date('H:i',strtotime($time_from_query->time_from));
                }
                if($time_to_query!=NULL){
                    $time_to =date('H:i',strtotime( $time_to_query->time_to));
                }
            }
            $dtr[$l]['time_from'] = $time_from;
            $dtr[$l]['time_to'] = $time_to;
            $user_dtr = $usersDtr::where('id_no',$id_no)
                ->where('date',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                ->first();
            if($user_dtr!=NULL){
                $row = $user_dtr;
                $date_day = date('j',strtotime($row->date));
                $weekDay = date('w', strtotime($row->date));
                if($weekDay==0){
                    $weekDay = 7;
                }
                $time_from = '';
                $time_to = '';
                $time_minutes_total = 0;
                if($emp_type=='Employee'){
                    $day = $usersSchedDays::where('user_id',$user_id)->where('day',$weekDay)->first();
                    if($day!=NULL){
                        $time_from = date('H:i',strtotime($day->time->time_from));
                        $time_to = date('H:i',strtotime($day->time->time_to));
                    }
                }else{
                    $day = $educOfferedScheduleDay::where('no',$weekDay)
                        ->whereHas('schedule', function ($query) use ($user_id,$year,$month) {                        
                            $query->whereHas('course', function ($query) use ($user_id,$year,$month) {
                                $query->where('instructor_id',$user_id);
                                $query->whereHas('curriculum', function ($query) use ($year,$month) {
                                    $query->whereHas('offered_program', function ($query) use ($year,$month) {                                    
                                        $query->whereHas('school_year', function ($query) use ($year,$month) {
                                            $query->where('year_from','>=',$year);
                                            $query->whereHas('grade_period', function ($query) use ($month) {
                                                $query->whereHas('month', function ($query) use ($month) {
                                                    $query->where('month',$month);
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        })
                        ->pluck('offered_schedule_id')->toArray();
                    $time_from_query = $educOfferedSchedule::whereIn('id',$day)->orderBy('time_from','ASC')
                        ->whereHas('course', function ($query) {
                            $query->where('load_type',1);
                        })
                        ->first();
                    $time_to_query = $educOfferedSchedule::whereIn('id',$day)->orderBy('time_to','DESC')
                        ->whereHas('course', function ($query) {
                            $query->where('load_type',1);
                        })
                        ->first();
                    $time_minutes = $educOfferedSchedule::whereIn('id',$day)
                        ->whereHas('course', function ($query) {
                            $query->where('load_type',1);
                        })->get();                
                    if($time_minutes->count()>0){                    
                        foreach($time_minutes as $r){
                            $time_from_ = Carbon::parse($r->time_from);
                            $time_to_ = Carbon::parse($r->time_to);
                            $time_minutes_total += $time_to_->diffInMinutes($time_from_);
                        }
                    }
                    if($time_from_query!=NULL){
                        $time_from = date('H:i',strtotime($time_from_query->time_from));
                    }
                    if($time_to_query!=NULL){
                        $time_to =date('H:i',strtotime( $time_to_query->time_to));
                    }
                }
                $dtr[$date_day]['check'] = 'dtr';
                
                if($row->time_in_am==NULL){
                    $time_in_am = '';
                    $time_in_am_for = '';
                }else{
                    $time_in_am = date('h:ia',strtotime($row->time_in_am));
                    $time_in_am_for = date('H:i',strtotime($row->time_in_am));
                }
                if($row->time_out_am==NULL){
                    $time_out_am = '';
                    $time_out_am_for = '';
                }else{
                    $time_out_am = date('h:ia',strtotime($row->time_out_am));
                    $time_out_am_for = date('H:i',strtotime($row->time_out_am));
                }
                if($row->time_in_pm==NULL){
                    $time_in_pm = '';
                    $time_in_pm_for = '';
                }else{
                    $time_in_pm = date('h:ia',strtotime($row->time_in_pm));
                    $time_in_pm_for = date('H:i',strtotime($row->time_in_pm));
                }
                if($row->time_out_pm==NULL){
                    $time_out_pm = '';
                    $time_out_pm_for = '';
                }else{
                    $time_out_pm = date('h:ia',strtotime($row->time_out_pm));
                    $time_out_pm_for = date('H:i',strtotime($row->time_out_pm));
                }
                $total_minutes = 0;
                if($time_from<'12:00' && $time_to>'12:00'){
                        if(($time_in_am_for=='' || $time_out_am_for=='' || $time_out_pm_for=='' || $time_out_pm_for=='')
                            && $row->time_type==NULL){
                            $count_days += 1;
                        }
                    }elseif(($time_from<'12:00' && $time_to<'13:00') || $row->time_type==3){
                        if($time_in_am_for=='' || $time_out_am_for==''){
                            $count_days += 1;
                        }
                    }else{
                        if(($time_in_pm_for=='' || $time_out_pm_for=='') || $row->time_type==2){
                            $count_days += 1;
                        }
                    }
                if($time_from!=''){
                    // if($time_from<'12:00' && $time_to>'12:00'){
                    //     if(($time_in_am_for=='' || $time_out_am_for=='' || $time_out_pm_for=='' || $time_out_pm_for=='')
                    //         && $row->time_type==NULL){
                    //         $count_days += 1;
                    //     }
                    // }elseif(($time_from<'12:00' && $time_to<'13:00') || $row->time_type==3){
                    //     if($time_in_am_for=='' || $time_out_am_for==''){
                    //         $count_days += 1;
                    //     }
                    // }else{
                    //     if(($time_in_pm_for=='' || $time_out_pm_for=='') || $row->time_type==2){
                    //         $count_days += 1;
                    //     }
                    // }
                    if($time_from<'12:00'){
                        if($time_in_am_for!='' && $time_in_am_for>$time_from){
                            $time_from_ = Carbon::parse($time_from);
                            $time_to_ = Carbon::parse($time_in_am_for);
                            $total_minutes = $time_to_->diffInMinutes($time_from_);
                        }
                        if($time_to>'13:00'){
                            if($time_out_am_for!='' && $time_out_am_for<'12:00'){
                                $time_from_ = Carbon::parse($time_out_am_for);
                                $time_to_ = Carbon::parse('12:00');
                                $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                            }
                            if($time_in_pm_for!='' && $time_in_pm_for>'13:00'){
                                $time_from_ = Carbon::parse('13:00');
                                $time_to_ = Carbon::parse($time_in_pm_for);
                                $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                            }
                            if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                $time_from_ = Carbon::parse($time_out_pm_for);
                                $time_to_ = Carbon::parse($time_to);
                                $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                            }
                            if($row->time_type==2){
                                $time_from_ = Carbon::parse($time_from);
                                $time_to_ = Carbon::parse('12:00');
                                $total_minutes = $time_to_->diffInMinutes($time_from_);
                            }elseif($row->time_type==3){
                                $time_from_ = Carbon::parse('13:00');
                                $time_to_ = Carbon::parse($time_to);
                                $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                            }elseif($row->time_type==1){
                                $time_from_ = Carbon::parse($time_from);
                                $time_to_ = Carbon::parse($time_to);
                                $total_minutes = $time_to_->diffInMinutes($time_from_);
                                if($total_minutes>=540){
                                    $total_minutes = 480;
                                }
                            }
                        }else{
                            if($time_out_am_for!='' && $time_out_am_for<$time_to){
                                $time_from_ = Carbon::parse($time_out_am_for);
                                $time_to_ = Carbon::parse($time_to);
                                $total_minutes = $time_to_->diffInMinutes($time_from_);
                            }
                            if($row->time_type==1){
                                $total_minutes = $time_minutes_total;
                            }
                        }                    
                    }else{
                        if($time_in_pm_for!='' && $time_in_pm_for>$time_from){
                            $time_from_ = Carbon::parse($time_from);
                            $time_to_ = Carbon::parse($time_in_pm_for);
                            $total_minutes = $time_to_->diffInMinutes($time_from_);
                        }
                        if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                            $time_from_ = Carbon::parse($time_out_pm_for);
                            $time_to_ = Carbon::parse($time_to);
                            $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                        }
                        if($row->time_type==1){
                            $total_minutes = $time_minutes_total;
                        }
                    }
                }
                $hours = 0;
                $minutes = $total_minutes;
                if($total_minutes>=60){
                    $hours = floor($total_minutes / 60);
                    $minutes = $total_minutes % 60;
                }
                if($row->time_type_==NULL){
                    $time_type_name = '';
                }else{
                    $time_type_name = $row->time_type_->name;
                }
                $dtr[$date_day]['in_am'] = $time_in_am;
                $dtr[$date_day]['out_am'] = $time_out_am;
                $dtr[$date_day]['in_pm'] = $time_in_pm;
                $dtr[$date_day]['out_pm'] = $time_out_pm;
                // $dtr[$date_day]['time_from'] = $time_from;
                // $dtr[$date_day]['time_to'] = $time_to;
                $dtr[$date_day]['time_type'] = $row->time_type;
                $dtr[$date_day]['time_type_name'] = $time_type_name;
                $dtr[$date_day]['time_in_am_type'] = $row->time_in_am_type;
                $dtr[$date_day]['time_out_am_type'] = $row->time_out_am_type;
                $dtr[$date_day]['time_in_pm_type'] = $row->time_in_pm_type;
                $dtr[$date_day]['time_out_pm_type'] = $row->time_out_pm_type;
                $dtr[$date_day]['hours'] = $hours;
                $dtr[$date_day]['minutes'] = $minutes;
                $count_days_with += 1;
            }else{
                if($dtr[$m]['time_from']!=''){
                    $count_days += 1;
                }else{
                    if($weekDay!=7 && $weekDay!=6){
                        $count_days += 1;
                    }
                }
            }
        }
        foreach($holidays as $row){
            $date_day = date('j',strtotime($row->date));
            if($dtr[$date_day]['check']==''){
                $dtr[$date_day]['check'] = 'holiday';
                $dtr[$date_day]['val'] = $row->name;
                $count_days = $count_days-1;
            }
        }
        // foreach($user_dtr as $row){
            
        // }
@endphp
<div class="row">
    <div class="col-lg-6">
        <input type="hidden" name="id_no" value="{{$id_no}}">
        <button class="btn btn-info btn-info-scan" name="fill_duration"><span class="fa fa-edit"></span> Fill in Duration</button>
    </div>
    <div class="col-lg-6">        
        {{-- <form action="{{url('/hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range)}}" method="GET" target="_blank">
            <button class="btn btn-primary btn-primary-scan" style="float:right">
              <span class="fa fa-file-pdf"></span>
              View
            </button>
        </form> --}}
        @if($count_days<=0)
        <form action="{{url('/hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range)}}" method="GET" target="_blank">
            <button class="btn btn-primary btn-primary-scan" style="float:right">
              <span class="fa fa-file-pdf"></span>
              View
            </button>
        </form>
        @endif
    </div>
</div>
<div class="center">
<br><h4>
    @if($range==2)
    {{date('F 1-15, Y',strtotime($year.'-'.$month.'-01'))}}
    @else
    {{date('F 1-t, Y',strtotime($year.'-'.$month.'-01'))}}
    @endif
    
    </h4>
<table class="table table-bordered center" style="width: 100%;line-height:3px">
    <thead>
        <tr>
            <th rowspan="2" style="width:5%">Day</th>
            <th colspan="2" style="width:35%">AM</th>
            <th colspan="2" style="width:35%">PM</th>
            <th colspan="2" style="width:25%">Undertime</th>
        </tr>
        <tr>
            <th style="width: 17.5%">Arrival</th>
            <th style="width: 17.5%">Departure</th>
            <th style="width: 17.5%">Arrival</th>
            <th style="width: 17.5%">Departure</th>
            <th style="width: 12.5%">Hours</th>
            <th style="width: 12.5%">Minutes</th>
        </tr>
    <thead>
    <body>
@php
    $total_minutes = 0;
        for($j=1;$j<=date('t',strtotime($year.'-'.$month.'-01'));$j++){
            echo '
            <tr>';
            $weekDay = date('w', strtotime($year.'-'.$month.'-'.$j));
            echo '<td>'.$j.'</td>';

            if(($weekDay == 0 || $weekDay == 6) && $dtr[$j]['check']==''){
                if($weekDay==0){
                    $color = 'rgba(220,20,60)';
                }else{
                    $color = 'rgba(0,0,0)';
                }
                $weekDayName = date('l', strtotime($year.'-'.$month.'-'.$j));
                echo '<td colspan="4" style="color:'.$color.'">'.$weekDayName.'</td>';

            }elseif($dtr[$j]['check']=='holiday'){
                $color = 'rgba(65,105,225)';
                echo '<td colspan="4" style="color:'.$color.'">'.$dtr[$j]['val'].'</td>';
            }else{
                if($range==2 && $j>15){
                    echo '<td colspan="4">-------------------</td>';
                }else{
                    // if(date('Y-m')==date('Y-m',strtotime($year.'-'.$month.'-01')) && $j>date('d')){
                    //     echo '<td colspan="4">-------------------</td>';
                    // }else{
                        if($dtr[$j]['time_type']=='1' || $dtr[$j]['time_type']=='4' || $dtr[$j]['time_type']=='5'
                            || ($dtr[$j]['time_in_am_type']!=NULL && $dtr[$j]['time_in_am_type']!='1' &&
                                $dtr[$j]['time_out_am_type']!=NULL && $dtr[$j]['time_out_am_type']!='1' &&
                                $dtr[$j]['time_in_pm_type']!=NULL && $dtr[$j]['time_in_pm_type']!='1' &&
                                $dtr[$j]['time_out_pm_type']!=NULL && $dtr[$j]['time_out_pm_type']!='1')){
                            if($dtr[$j]['time_type']==7){
                                $td_name = '-------------------';
                            }else{
                                $td_name = $dtr[$j]['time_type_name'];
                            }
                            echo '<td colspan="4">
                                    <button class="btn btn-default dtrInput" 
                                        data-d="'.$j.'"
                                        data-time_type="'.$dtr[$j]['time_type'].'"
                                        style="width:100%;height:40px;">
                                        <span class="fa fa-edit"></span> '.$td_name.'
                                    </button></td>';
                        }else{
                            if($dtr[$j]['time_type']=='2'){
                                echo '<td colspan="2">Half Day</td>';
                            }elseif($dtr[$j]['time_in_am_type']!=NULL && $dtr[$j]['time_in_am_type']!='1' &&
                                    $dtr[$j]['time_out_am_type']!=NULL && $dtr[$j]['time_out_am_type']!='1'){
                                if($dtr[$j]['time_type']==7){
                                    echo '<td colspan="2">-------------------</td>';
                                }else{
                                    echo '<td colspan="2">'.$dtr[$j]['time_type_name'].'</td>';
                                }
                            }else{
                                if($dtr[$j]['time_in_am_type']!=NULL && $dtr[$j]['time_in_am_type']!='1'){
                                    if($dtr[$j]['time_type']==7){
                                        echo '<td>------</td>';
                                    }else{
                                        echo '<td>'.$dtr[$j]['time_type_name'].'</td>';
                                    }
                                }else{
                                    if($dtr[$j]['time_in_am_type']=='1'){
                                        $color = 'rgba(220,20,60)';
                                        $border_color = 'border-color:'.$color;
                                    }else{
                                        $color = 'rgba(0,0,0)';
                                        $border_color = '';
                                    }
                                    if($dtr[$j]['time_from']!='' && $dtr[$j]['time_from']>'12:00' && $dtr[$j]['in_am']==''){
                                        echo '<td>------</td>';
                                    }else{
                                        if($dtr[$j]['in_am']==''){
                                            echo '<td><button class="btn btn-default dtrInput" 
                                                        data-d="'.$j.'"
                                                        data-time_type="'.$dtr[$j]['time_type'].'"
                                                        style="width:100%;height:40px;">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                </td>';
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL && 
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['in_am'].'</td>';
                                            }else{
                                                echo '<td>
                                                        <button class="btn btn-default dtrInput" 
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;height:40px;color:'.$color.';'.$border_color.'">
                                                            <span class="fa fa-edit"></span> '.$dtr[$j]['in_am'].'
                                                        </button></td>';
                                            }
                                        }
                                    }
                                }
                                if($dtr[$j]['time_out_am_type']!=NULL && $dtr[$j]['time_out_am_type']!='1'){
                                    if($dtr[$j]['time_type']==7){
                                        echo '<td>------</td>';
                                    }else{
                                        echo '<td>'.$dtr[$j]['time_type_name'].'</td>';
                                    }
                                }else{
                                    if($dtr[$j]['time_out_am_type']=='1'){
                                        $color = 'rgba(220,20,60)';
                                        $border_color = 'border-color:'.$color;
                                    }else{
                                        $color = 'rgba(0,0,0)';
                                        $border_color = '';
                                    }
                                    if($dtr[$j]['time_from']!='' && $dtr[$j]['time_from']>'12:00' && $dtr[$j]['out_am']==''){
                                        echo '<td>------</td>';
                                    }else{
                                        if($dtr[$j]['out_am']==''){
                                            echo '<td><button class="btn btn-default dtrInput" 
                                                        data-d="'.$j.'"
                                                        data-time_type="'.$dtr[$j]['time_type'].'"
                                                        style="width:100%;height:40px;">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                </td>';
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL && 
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['out_am'].'</td>';
                                            }else{
                                                echo '<td>
                                                        <button class="btn btn-default dtrInput" 
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;height:40px;color:'.$color.';'.$border_color.'">
                                                            <span class="fa fa-edit"></span> '.$dtr[$j]['out_am'].'
                                                        </button></td>';
                                            }
                                        }
                                    }
                                }
                            }
                            if($dtr[$j]['time_type']=='3'){
                                echo '<td colspan="2">Half Day</td>';
                            }elseif($dtr[$j]['time_in_pm_type']!=NULL && $dtr[$j]['time_in_pm_type']!='1' &&
                                    $dtr[$j]['time_out_pm_type']!=NULL && $dtr[$j]['time_out_pm_type']!='1'){
                                if($dtr[$j]['time_type']==7){
                                    echo '<td colspan="2">-------------------</td>';
                                }else{
                                    echo '<td colspan="2">'.$dtr[$j]['time_type_name'].'</td>';
                                }
                            }else{
                                if($dtr[$j]['time_in_pm_type']!=NULL && $dtr[$j]['time_in_pm_type']!='1'){
                                    if($dtr[$j]['time_type']==7){
                                        echo '<td>------</td>';
                                    }else{
                                        echo '<td>'.$dtr[$j]['time_type_name'].'</td>';
                                    }
                                }else{
                                    if($dtr[$j]['time_in_pm_type']=='1'){
                                        $color = 'rgba(220,20,60)';
                                        $border_color = 'border-color:'.$color;
                                    }else{
                                        $color = 'rgba(0,0,0)';
                                        $border_color = '';
                                    }
                                    if($dtr[$j]['time_to']!='' && $dtr[$j]['time_to']<'13:00' && $dtr[$j]['in_pm']==''){
                                        echo '<td>------</td>';
                                    }else{
                                        if($dtr[$j]['in_pm']==''){
                                            echo '<td><button class="btn btn-default dtrInput" 
                                                        data-d="'.$j.'"
                                                        data-time_type="'.$dtr[$j]['time_type'].'"
                                                        style="width:100%;height:40px;">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                </td>';
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL && 
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['in_pm'].'</td>';
                                            }else{
                                                echo '<td>
                                                        <button class="btn btn-default dtrInput" 
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;height:40px;color:'.$color.';'.$border_color.'">
                                                            <span class="fa fa-edit"></span> '.$dtr[$j]['in_pm'].'
                                                        </button></td>';
                                            }
                                        }                                
                                    }
                                }
                                if($dtr[$j]['time_out_pm_type']!=NULL && $dtr[$j]['time_out_pm_type']!='1'){
                                    if($dtr[$j]['time_type']==7){
                                        echo '<td>------</td>';
                                    }else{
                                        echo '<td>'.$dtr[$j]['time_type_name'].'</td>';
                                    }
                                }else{
                                    if($dtr[$j]['time_out_pm_type']=='1'){
                                        $color = 'rgba(220,20,60)';
                                        $border_color = 'border-color:'.$color;
                                    }else{
                                        $color = 'rgba(0,0,0)';
                                        $border_color = '';
                                    }
                                    if($dtr[$j]['time_to']!='' && $dtr[$j]['time_to']<'13:00' && $dtr[$j]['out_pm']==''){
                                        echo '<td>------</td>';
                                    }else{
                                        if($dtr[$j]['out_pm']==''){
                                            echo '<td><button class="btn btn-default dtrInput" 
                                                        data-d="'.$j.'"
                                                        data-time_type="'.$dtr[$j]['time_type'].'"
                                                        style="width:100%;height:40px;">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                </td>';
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL &&
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['out_pm'].'</td>';
                                            }else{
                                                echo '<td>
                                                        <button class="btn btn-default dtrInput" 
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;height:40px;color:'.$color.';'.$border_color.'">
                                                            <span class="fa fa-edit"></span> '.$dtr[$j]['out_pm'].'
                                                        </button></td>';
                                            }
                                        }
                                        
                                    }
                                }
                            }                        
                        // }
                    }
                }
            }   
            if($dtr[$j]['hours']>0){
                $hours = $dtr[$j]['hours'];
            }else{
                $hours = '';
            }
            echo '<td>'.$hours.'</td>';

            if($dtr[$j]['minutes']>0){
                $minutes = $dtr[$j]['minutes'];
            }else{
                $minutes = '';
            }
            echo '<td>'.$minutes.'</td>';            
            echo '
            </tr>';
            $total_minutes += $dtr[$j]['minutes']+$dtr[$j]['hours']*60;
        }
        echo '
        <tr>';  
        echo '<th colspan="5">TOTAL</th>'; 
        $hours = 0;
        $minutes = $total_minutes;
        if($total_minutes>=60){
            $hours = floor($total_minutes / 60);
            $minutes = $total_minutes % 60;
        }
        if($hours>0){
            $hours = $hours;
        }else{
            $hours = '';
        }
        if($minutes>0){
            $minutes = $minutes;
        }else{
            $minutes = '';
        }
        echo '<th>'.$hours.'</th>';
        echo '<th>'.$minutes.'</th>';
        echo '
        </tr>';
@endphp
    </body>
</table>
</div>