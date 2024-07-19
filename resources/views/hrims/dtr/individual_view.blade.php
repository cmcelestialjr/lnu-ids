@php
$user = $users::with('employee_default.emp_stat')
            ->where('id_no',$id_no)->first();
        $user_id = $user->id;


        $emp_stat_gov = $user->employee_default->emp_stat->gov;
        if($user->employee_default->role_id==3){
            if($user->employee_default->credit_type_id==2){
                $emp_type = 'Personnel';
            }else{
                $emp_type = 'Faculty';
            }
        }else{
            $emp_type = 'Personnel';
        }

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
            $dtr[$m]['tardy_hr'] = 0;
            $dtr[$m]['tardy_min'] = 0;
            $dtr[$m]['tardy_no'] = 0;
            $dtr[$m]['ud_hr'] = 0;
            $dtr[$m]['ud_min'] = 0;
            $dtr[$m]['ud_no'] = 0;
            $dtr[$m]['hd_hr'] = 0;
            $dtr[$m]['hd_min'] = 0;
            $dtr[$m]['hd_no'] = 0;
            $dtr[$m]['abs_hr'] = 0;
            $dtr[$m]['abs_min'] = 0;
            $dtr[$m]['abs_no'] = 0;

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


            $schedTimeFrom = $usersSchedTime::where('user_id',$user_id)
                ->where('option_id',1)
                ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                ->where('date_from','<=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                ->whereHas('days', function ($query) use ($weekDay) {
                    $query->where('day',$weekDay);
                })->orderBy('time_from','ASC')
                ->first();

            $schedTimeTo = $usersSchedTime::where('user_id',$user_id)
                ->where('option_id',1)
                ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                ->where('date_from','<=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                ->whereHas('days', function ($query) use ($weekDay) {
                    $query->where('day',$weekDay);
                })->orderBy('time_to','DESC')
                ->first();

            if($schedTimeFrom!=NULL){
                $time_from = date('H:i',strtotime($schedTimeFrom->time_from));
            }
            if($schedTimeTo!=NULL){
                $time_to = date('H:i',strtotime($schedTimeTo->time_to));
            }

            $dtr[$l]['time_from'] = $time_from;
            $dtr[$l]['time_to'] = $time_to;
            $user_dtr = $usersDtr::where('id_no',$id_no)
                ->where('date',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                ->first();

            $next_user_dtr = $usersDtr::where('id_no',$id_no)
                ->where('date',date('Y-m-d', strtotime("+1 day", strtotime($year.'-'.$month.'-'.$l))))
                ->first();
            $next_time_in_am_for = '';
            $next_time_out_am_for = '';
            $next_time_in_pm_for = '';
            $next_time_out_pm_for = '';
            if($next_user_dtr){
                if($next_user_dtr->time_in_am){
                    $next_time_in_am_for = date('H:i',strtotime($next_user_dtr->time_in_am));
                }
                if($next_user_dtr->time_out_am){
                    $next_time_out_am_for = date('H:i',strtotime($next_user_dtr->time_out_am));
                }
                if($next_user_dtr->time_in_pm){
                    $next_time_in_pm_for = date('H:i',strtotime($next_user_dtr->time_in_pm));
                }
                if($next_user_dtr->time_out_pm){
                    $next_time_out_pm_for = date('H:i',strtotime($next_user_dtr->time_out_pm));
                }
            }

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
                $tardy_minutes = 0;
                $tardy_no = 0;
                $ud_minutes = 0;
                $ud_no = 0;
                $hd_minutes = 0;
                $hd_no = 0;
                $abs_minutes = 0;
                $abs_no = 0;
                $is_rotation_duty = 'No';

                $schedTimeGet = $usersSchedTime::where('user_id',$user_id)
                    ->where('option_id',1)
                    ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->where('date_from','<=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->whereHas('days', function ($query) use ($weekDay) {
                        $query->where('day',$weekDay);
                    })->get();

                if($schedTimeGet->count()>0){
                    foreach($schedTimeGet as $key => $rowSchedTime){
                        $time_from = date('H:i',strtotime($rowSchedTime->time_from));
                        $time_to = date('H:i',strtotime($rowSchedTime->time_to));
                        $is_rotation_duty = $rowSchedTime->is_rotation_duty;

                        if($is_rotation_duty=='Yes'){
                            if(($time_from<'12:00' && $time_in_am_for=='') ||
                                ($time_from>='12:00' && $time_in_pm_for=='')){
                                $count_days += 1;
                            }
                            if($time_to<'12:00' && $time_from>='12:00' && $next_time_out_am_for==''){
                                $count_days += 1;
                            }elseif($time_to>='12:00' && $time_from<'12:00' && $time_out_pm_for==''){
                                $count_days += 1;
                            }
                        }else{
                            if($time_from<'12:00' && $time_to>'12:00'){
                                if(($time_in_am_for=='' || $time_out_am_for=='' || $time_out_pm_for=='' || $time_out_pm_for=='')
                                    && $row->time_type==NULL){
                                    $count_days += 1;
                                }
                            }elseif(($time_from<'12:00' && $time_to<'13:00') && $row->time_type==3){
                                if($time_in_am_for=='' || $time_out_am_for==''){
                                    $count_days += 1;
                                }
                            }else{
                                if(($time_in_pm_for=='' || $time_out_pm_for=='') && $row->time_type==2){
                                    $count_days += 1;
                                }
                            }
                        }

                            if($time_from<'12:00'){
                                if($time_in_am_for!='' && $time_in_am_for>$time_from){
                                    $time_from_ = Carbon::parse($time_from)->seconds(0);
                                    $time_to_ = Carbon::parse($time_in_am_for)->seconds(0);
                                    $total_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_no++;
                                }
                            }else{
                                if($time_in_pm_for!='' && $time_in_pm_for>$time_from){
                                    $time_from_ = Carbon::parse($time_from)->seconds(0);
                                    $time_to_ = Carbon::parse($time_in_pm_for)->seconds(0);
                                    $total_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_no++;
                                }
                            }
                            if($is_rotation_duty=='Yes'){
                                if($time_to<'12:00' && $time_from>='12:00'){
                                    if($next_time_out_am_for!='' && $next_time_out_am_for<$time_to){
                                        $time_from_ = Carbon::parse($next_time_out_am_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }else{
                                    if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                        $time_from_ = Carbon::parse($time_out_pm_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }
                            }else{
                                if($time_to<'13:00'){
                                    if($time_out_am_for!='' && $time_out_am_for<$time_to){
                                        $time_from_ = Carbon::parse($time_out_am_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }else{
                                    if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                        $time_from_ = Carbon::parse($time_out_pm_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }
                            }

                            if($row->time_type==1 || $row->time_type==2 || $row->time_type==3){
                                $time_from_ = Carbon::parse($time_from)->seconds(0);
                                $time_to_ = Carbon::parse($time_to)->seconds(0);
                                $get_time_diff = $time_to_->diffInMinutes($time_from_);
                                if($emp_stat_gov=='N'){
                                    $total_minutes += $get_time_diff;
                                }
                                if($row->time_type==1){
                                    $abs_minutes += $get_time_diff;
                                    $abs_no = 1;
                                }elseif($row->time_type==2){
                                    $hd_minutes = $get_time_diff;
                                    $hd_no = 1;
                                }elseif($row->time_type==3){
                                    $hd_minutes = $get_time_diff;
                                    $hd_no = 1;
                                }

                            }
                    }
                }

                $hours = 0;
                $minutes = $total_minutes;
                if($total_minutes>=60){
                    $hours = floor($total_minutes / 60);
                    $minutes = $total_minutes % 60;
                }
                $tardy_hr = 0;
                $tardy_min = $tardy_minutes;
                if($tardy_minutes>=60){
                    $tardy_hr = floor($tardy_minutes / 60);
                    $tardy_min = $tardy_minutes % 60;
                }
                $ud_hr = 0;
                $ud_min = $ud_minutes;
                if($ud_minutes>=60){
                    $ud_hr = floor($ud_minutes / 60);
                    $ud_min = $ud_minutes % 60;
                }
                $hd_hr = 0;
                $hd_min = $hd_minutes;
                if($hd_minutes>=60){
                    $hd_hr = floor($hd_minutes / 60);
                    $hd_min = $hd_minutes % 60;
                }
                $abs_hr = 0;
                $abs_min = $abs_minutes;
                if($abs_minutes>=60){
                    $abs_hr = floor($abs_minutes / 60);
                    $abs_min = $abs_minutes % 60;
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
                $dtr[$date_day]['tardy_hr'] = $tardy_hr;
                $dtr[$date_day]['tardy_min'] = $tardy_min;
                $dtr[$date_day]['tardy_no'] = $tardy_no;
                $dtr[$date_day]['ud_hr'] = $ud_hr;
                $dtr[$date_day]['ud_min'] = $ud_min;
                $dtr[$date_day]['ud_no'] = $ud_no;
                $dtr[$date_day]['hd_hr'] = $hd_hr;
                $dtr[$date_day]['hd_min'] = $hd_min;
                $dtr[$date_day]['hd_no'] = $hd_no;
                $dtr[$date_day]['abs_hr'] = $abs_hr;
                $dtr[$date_day]['abs_min'] = $abs_min;
                $dtr[$date_day]['abs_no'] = $abs_no;
                $dtr[$date_day]['is_rotation_duty'] = $is_rotation_duty;

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

@endphp
<style>
.dtrInput{
    font-size: 12px;
    background-color: #fee7e7;
    border-color: black;
}
table{
    border-collapse: collapse;
    border: 1px solid;
    width:100%;
    font-size:10px;
}
table th{
    border: 1px solid;
    border-color: black;
    padding-top:2px;
    padding-bottom:2px;
    padding-left:2px;
    padding-right:2px;
    background-color: #fcfcfc;
}
table td{
    border: 1px solid;
    border-color: black;
    padding-top:2px;
    padding-bottom:2px;
    padding-left:2px;
    padding-right:2px;
    background-color: #fcfcfc;
}
.dtrInput{
    font-size: 10px;
    padding: 0px;
}
</style>
<div class="row">
    <div class="col-lg-12">
        @if($count_days<=0)
            {{-- <form action="{{url('/hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range.'/o')}}" method="GET" target="_blank"> --}}
                <button class="btn btn-info btn-info-scan dtrPrint"
                    data-id="o"
                    data-y="{{$year}}"
                    data-m="{{$month}}"
                    data-r="{{$range}}"
                    style="float:right">
                <span class="fa fa-file-pdf"></span>
                Overload Print
                </button>
            {{-- </form> --}}
            {{-- <form action="{{url('/hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range.'/p')}}" method="GET" target="_blank"> --}}
                <button class="btn btn-primary btn-primary-scan dtrPrint"
                    data-id="p"
                    data-y="{{$year}}"
                    data-m="{{$month}}"
                    data-r="{{$range}}"
                    style="float:right">
                <span class="fa fa-file-pdf"></span>
                Print
                </button>
            {{-- </form> --}}
        @else
            <button class="btn btn-info btn-info-scan" style="float:right" disabled>
                <span class="fa fa-file-pdf"></span>
                Overload Print
            </button>
            <button class="btn btn-primary btn-primary-scan" style="float:right" disabled>
                <span class="fa fa-file-pdf"></span>
                Print
            </button>
        @endif

        <input type="hidden" name="id_no" value="{{$id_no}}">
        <input type="hidden" name="user_information" value="{{$user_id}}">

            <button class="btn btn-info btn-info-scan" name="fill_duration"><span class="fa fa-edit"></span> Fill in Duration</button>
            @if($check_user_role)
            <button class="btn btn-primary btn-primary-scan" name="department"><span class="fa fa-edit"></span> Department</button>
            @endif

        <button class="btn btn-primary btn-primary-scan" name="schedule" style="float:right"><span class="fa fa-edit"></span> Schedule</button>
    </div>
</div>
<div class="center table-responsive">
    <h5>
        @if($range==2)
            {{date('F 1-15, Y',strtotime($year.'-'.$month.'-01'))}}
        @else
            {{date('F 1-t, Y',strtotime($year.'-'.$month.'-01'))}}
        @endif
    </h5>
<table class="center">
    <thead>
        <tr>
            <th rowspan="2" style="width:3%">Day</th>
            <th colspan="2" style="width:26%">AM</th>
            <th colspan="2" style="width:26%">PM</th>
            <th colspan="2" style="width:9%">Total</th>
            <th colspan="3" style="width:9%">Tardy</th>
            <th colspan="3" style="width:9%">UD</th>
            <th colspan="3" style="width:9%">HD</th>
            <th colspan="3" style="width:9%">Abs</th>
        </tr>
        <tr>
            <th style="width: 13%">Arrival</th>
            <th style="width: 13%">Departure</th>
            <th style="width: 13%">Arrival</th>
            <th style="width: 13%">Departure</th>
            <th style="width: 4.5%">Hrs</th>
            <th style="width: 4.5%">Mins</th>
            <th style="width: 3%">Hr</th>
            <th style="width: 3%">Min</th>
            <th style="width: 3%">No</th>
            <th style="width: 3%">Hr</th>
            <th style="width: 3%">Min</th>
            <th style="width: 3%">No</th>
            <th style="width: 3%">Hr</th>
            <th style="width: 3%">Min</th>
            <th style="width: 3%">No</th>
            <th style="width: 3%">Hr</th>
            <th style="width: 3%">Min</th>
            <th style="width: 3%">No</th>
        </tr>
    <thead>
    <body>
@php
    $total_minutes = 0;
    $tardy_min_total = 0;
    $tardy_no_total = 0;
    $ud_min_total = 0;
    $ud_no_total = 0;
    $hd_min_total = 0;
    $hd_no_total = 0;
    $abs_min_total = 0;
    $abs_no_total = 0;

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
                            if($current_url=='mydtr'){
                                echo '<td colspan="4">'.$td_name.'</td>';
                            }else{
                                echo '<td colspan="4">
                                    <button class="btn btn-default dtrInput"
                                        data-d="'.$j.'"
                                        data-time_type="'.$dtr[$j]['time_type'].'"
                                        style="width:100%;">
                                        <span class="fa fa-edit"></span> '.$td_name.'
                                    </button></td>';
                            }

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
                                            if($current_url=='mydtr'){
                                                echo '<td></td>';
                                            }else{
                                                echo '<td><button class="btn btn-default dtrInput"
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;">
                                                            <span class="fa fa-edit"></span>
                                                        </button>
                                                    </td>';
                                            }
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL &&
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['in_am'].'</td>';
                                            }else{
                                                if($current_url=='mydtr'){
                                                    echo '<td>'.$dtr[$j]['in_am'].'</td>';
                                                }else{
                                                    echo '<td>
                                                            <button class="btn btn-default dtrInput"
                                                                data-d="'.$j.'"
                                                                data-time_type="'.$dtr[$j]['time_type'].'"
                                                                style="width:100%;color:'.$color.';'.$border_color.'">
                                                                <span class="fa fa-edit"></span> '.$dtr[$j]['in_am'].'
                                                            </button></td>';
                                                }
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
                                            if($current_url=='mydtr'){
                                                echo '<td></td>';
                                            }else{
                                                echo '<td>
                                                        <button class="btn btn-default dtrInput"
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;">
                                                            <span class="fa fa-edit"></span>
                                                        </button>
                                                    </td>';
                                            }
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL &&
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['out_am'].'</td>';
                                            }else{
                                                if($current_url=='mydtr'){
                                                    echo '<td>'.$dtr[$j]['out_am'].'</td>';
                                                }else{
                                                    echo '<td>
                                                            <button class="btn btn-default dtrInput"
                                                                data-d="'.$j.'"
                                                                data-time_type="'.$dtr[$j]['time_type'].'"
                                                                style="width:100%;color:'.$color.';'.$border_color.'">
                                                                <span class="fa fa-edit"></span> '.$dtr[$j]['out_am'].'
                                                            </button></td>';
                                                }
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
                                            if($current_url=='mydtr'){
                                                echo '<td></td>';
                                            }else{
                                                echo '<td><button class="btn btn-default dtrInput"
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;">
                                                            <span class="fa fa-edit"></span>
                                                        </button>
                                                    </td>';
                                            }
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL &&
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['in_pm'].'</td>';
                                            }else{
                                                if($current_url=='mydtr'){
                                                    echo '<td>'.$dtr[$j]['in_pm'].'</td>';
                                                }else{
                                                    echo '<td>
                                                            <button class="btn btn-default dtrInput"
                                                                data-d="'.$j.'"
                                                                data-time_type="'.$dtr[$j]['time_type'].'"
                                                                style="width:100%;color:'.$color.';'.$border_color.'">
                                                                <span class="fa fa-edit"></span> '.$dtr[$j]['in_pm'].'
                                                            </button></td>';
                                                }
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
                                            if($current_url=='mydtr'){
                                                echo '<td>'.$dtr[$j]['in_pm'].'</td>';
                                            }else{
                                                echo '<td><button class="btn btn-default dtrInput"
                                                            data-d="'.$j.'"
                                                            data-time_type="'.$dtr[$j]['time_type'].'"
                                                            style="width:100%;">
                                                            <span class="fa fa-edit"></span>
                                                        </button>
                                                    </td>';
                                            }
                                        }else{
                                            if($dtr[$j]['in_am']!='' && $dtr[$j]['time_in_am_type']==NULL &&
                                            $dtr[$j]['out_am']!='' && $dtr[$j]['time_out_am_type']==NULL &&
                                            $dtr[$j]['in_pm']!='' && $dtr[$j]['time_in_pm_type']==NULL &&
                                            $dtr[$j]['out_pm']!='' && $dtr[$j]['time_out_pm_type']==NULL
                                                ){
                                                echo '<td style="color:'.$color.';
                                                    '.$border_color.'">'.$dtr[$j]['out_pm'].'</td>';
                                            }else{
                                                if($current_url=='mydtr'){
                                                    echo '<td>'.$dtr[$j]['out_pm'].'</td>';
                                                }else{
                                                    echo '<td>
                                                            <button class="btn btn-default dtrInput"
                                                                data-d="'.$j.'"
                                                                data-time_type="'.$dtr[$j]['time_type'].'"
                                                                style="width:100%;color:'.$color.';'.$border_color.'">
                                                                <span class="fa fa-edit"></span> '.$dtr[$j]['out_pm'].'
                                                            </button></td>';
                                                }
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

            if($dtr[$j]['tardy_hr']>0){
                $tardy_hr = $dtr[$j]['tardy_hr'];
            }else{
                $tardy_hr = '';
            }
            echo '<td>'.$tardy_hr.'</td>';

            if($dtr[$j]['tardy_min']>0){
                $tardy_min = $dtr[$j]['tardy_min'];
            }else{
                $tardy_min = '';
            }
            echo '<td>'.$tardy_min.'</td>';

            if($dtr[$j]['tardy_no']>0){
                $tardy_no = $dtr[$j]['tardy_no'];
            }else{
                $tardy_no = '';
            }
            echo '<td>'.$tardy_no.'</td>';

            if($dtr[$j]['ud_hr']>0){
                $ud_hr = $dtr[$j]['ud_hr'];
            }else{
                $ud_hr = '';
            }
            echo '<td>'.$ud_hr.'</td>';

            if($dtr[$j]['ud_min']>0){
                $ud_min = $dtr[$j]['ud_min'];
            }else{
                $ud_min = '';
            }
            echo '<td>'.$ud_min.'</td>';

            if($dtr[$j]['ud_no']>0){
                $ud_no = $dtr[$j]['ud_no'];
            }else{
                $ud_no = '';
            }
            echo '<td>'.$ud_no.'</td>';

            if($dtr[$j]['hd_hr']>0){
                $hd_hr = $dtr[$j]['hd_hr'];
            }else{
                $hd_hr = '';
            }
            echo '<td>'.$hd_hr.'</td>';

            if($dtr[$j]['hd_min']>0){
                $hd_min = $dtr[$j]['hd_min'];
            }else{
                $hd_min = '';
            }
            echo '<td>'.$hd_min.'</td>';

            if($dtr[$j]['hd_no']>0){
                $hd_no = $dtr[$j]['hd_no'];
            }else{
                $hd_no = '';
            }
            echo '<td>'.$hd_no.'</td>';

            if($dtr[$j]['abs_hr']>0){
                $abs_hr = $dtr[$j]['abs_hr'];
            }else{
                $abs_hr = '';
            }
            echo '<td>'.$abs_hr.'</td>';

            if($dtr[$j]['abs_min']>0){
                $abs_min = $dtr[$j]['abs_min'];
            }else{
                $abs_min = '';
            }
            echo '<td>'.$abs_min.'</td>';

            if($dtr[$j]['abs_no']>0){
                $abs_no = $dtr[$j]['abs_no'];
            }else{
                $abs_no = '';
            }
            echo '<td>'.$abs_no.'</td>';

            echo '
            </tr>';
            $total_minutes += $dtr[$j]['minutes']+$dtr[$j]['hours']*60;
            $tardy_min_total += $dtr[$j]['tardy_min']+$dtr[$j]['tardy_hr']*60;
            $tardy_no_total += $dtr[$j]['tardy_no'];
            $ud_min_total += $dtr[$j]['ud_min']+$dtr[$j]['ud_hr']*60;
            $ud_no_total += $dtr[$j]['ud_no'];
            $hd_min_total += $dtr[$j]['hd_min']+$dtr[$j]['hd_hr']*60;
            $hd_no_total += $dtr[$j]['hd_no'];
            $abs_min_total += $dtr[$j]['abs_min']+$dtr[$j]['abs_hr']*60;
            $abs_no_total += $dtr[$j]['abs_no'];
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

        $tardy_hr = 0;
        $tardy_min = $tardy_min_total;
        if($tardy_min_total>=60){
            $tardy_hr = floor($tardy_min_total / 60);
            $tardy_min = $tardy_min_total % 60;
        }
        if($tardy_hr>0){
            $tardy_hr = $tardy_hr;
        }else{
            $tardy_hr = '';
        }
        if($tardy_min>0){
            $tardy_min = $tardy_min;
        }else{
            $tardy_min = '';
        }
        if($tardy_no_total>0){
            $tardy_no_total = $tardy_no_total;
        }else{
            $tardy_no_total = '';
        }
        echo '<th>'.$tardy_hr.'</th>';
        echo '<th>'.$tardy_min.'</th>';
        echo '<th>'.$tardy_no_total.'</th>';

        $ud_hr = 0;
        $ud_min = $ud_min_total;
        if($ud_min_total>=60){
            $ud_hr = floor($ud_min_total / 60);
            $ud_min = $ud_min_total % 60;
        }
        if($ud_hr>0){
            $ud_hr = $ud_hr;
        }else{
            $ud_hr = '';
        }
        if($ud_min>0){
            $ud_min = $ud_min;
        }else{
            $ud_min = '';
        }
        if($ud_no_total>0){
            $ud_no_total = $ud_no_total;
        }else{
            $ud_no_total = '';
        }
        echo '<th>'.$ud_hr.'</th>';
        echo '<th>'.$ud_min.'</th>';
        echo '<th>'.$ud_no_total.'</th>';

        $hd_hr = 0;
        $hd_min = $hd_min_total;
        if($hd_min_total>=60){
            $hd_hr = floor($hd_min_total / 60);
            $hd_min = $hd_min_total % 60;
        }
        if($hd_hr>0){
            $hd_hr = $hd_hr;
        }else{
            $hd_hr = '';
        }
        if($hd_min>0){
            $hd_min = $hd_min;
        }else{
            $hd_min = '';
        }
        if($hd_no_total>0){
            $hd_no_total = $hd_no_total;
        }else{
            $hd_no_total = '';
        }
        echo '<th>'.$hd_hr.'</th>';
        echo '<th>'.$hd_min.'</th>';
        echo '<th>'.$hd_no_total.'</th>';

        $abs_hr = 0;
        $abs_min = $abs_min_total;
        if($abs_min_total>=60){
            $abs_hr = floor($abs_min_total / 60);
            $abs_min = $abs_min_total % 60;
        }
        if($abs_hr>0){
            $abs_hr = $abs_hr;
        }else{
            $abs_hr = '';
        }
        if($abs_min>0){
            $abs_min = $abs_min;
        }else{
            $abs_min = '';
        }
        if($abs_no_total>0){
            $abs_no_total = $abs_no_total;
        }else{
            $abs_no_total = '';
        }
        echo '<th>'.$abs_hr.'</th>';
        echo '<th>'.$abs_min.'</th>';
        echo '<th>'.$abs_no_total.'</th>';

        echo '
        </tr>';
@endphp
    </body>
</table>
</div>
