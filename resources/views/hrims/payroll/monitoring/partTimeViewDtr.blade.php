<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered center" style="font-size: 10px;">
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
            </thead>
            <body>
                @php
                    $dtr = [];
                    $included_days = [];
                @endphp
                @for ($i = 1; $i <= $lastDay; $i++)
                    @php
                        $weekDay = date('w', strtotime($year.'-'.$month.'-'.$i));
                        if($weekDay==0){
                            $weekDay = 7;
                        }
                        $dtr[$i]['day'] = $i;
                        $dtr[$i]['check'] = '';
                        $dtr[$i]['holiday'] = '';
                        $dtr[$i]['in_am'] = '';
                        $dtr[$i]['out_am'] = '';
                        $dtr[$i]['in_pm'] = '';
                        $dtr[$i]['out_pm'] = '';
                        $dtr[$i]['time_type'] = '';
                        $dtr[$i]['time_type_name'] = '';
                        $dtr[$i]['time_in_am_type'] = '';
                        $dtr[$i]['time_out_am_type'] = '';
                        $dtr[$i]['time_in_pm_type'] = '';
                        $dtr[$i]['time_out_pm_type'] = '';
                        $dtr[$i]['hours'] = 0;
                        $dtr[$i]['minutes'] = 0;
                        $dtr[$i]['tardy_hr'] = 0;
                        $dtr[$i]['tardy_min'] = 0;
                        $dtr[$i]['tardy_no'] = 0;
                        $dtr[$i]['ud_hr'] = 0;
                        $dtr[$i]['ud_min'] = 0;
                        $dtr[$i]['ud_no'] = 0;
                        $dtr[$i]['hd_hr'] = 0;
                        $dtr[$i]['hd_min'] = 0;
                        $dtr[$i]['hd_no'] = 0;
                        $dtr[$i]['abs_hr'] = 0;
                        $dtr[$i]['abs_min'] = 0;
                        $dtr[$i]['abs_no'] = 0;
                        $dtr[$i]['sched_time'] = [];
                    @endphp
                    @foreach ($getDtrSched as $row)
                        @php
                            if($weekDay==$row->day){
                                $dtr[$i]['check'] = 'included';
                                $dtr[$i]['sched_time'][] = [
                                    'in' => $row->time->time_from,
                                    'out' => $row->time->time_to,
                                    'is_rotation_duty' => $row->time->is_rotation_duty
                                ];
                            }
                        @endphp
                    @endforeach
                    @if($dtr[$i]['check'] == 'included')
                        @php
                            $included_days[] = $i;
                        @endphp
                    @endif
                @endfor
                @foreach($getHolidays as $row)
                    @php
                        $day = date('j',strtotime($row->date));
                        $dtr[$day]['check'] = '';
                        $dtr[$day]['holiday'] = $row->name;

                        $index = array_search($day, $included_days);
                        if ($index !== false) {
                            unset($included_days[$index]);
                        }
                    @endphp
                @endforeach
                @for ($k = 0; $k < $getDtr->count(); $k++)
                    @php
                        $row = $getDtr[$k];
                        $day = date('j', strtotime($row->date));

                        $index = array_search($day, $included_days);
                        if ($index !== false) {
                            unset($included_days[$index]);
                        }

                        $time_in_am = (strtotime($row->time_in_am)) ? date('H:i',strtotime($row->time_in_am)) : NULL;
                        $time_out_am = (strtotime($row->time_out_am)) ? date('H:i',strtotime($row->time_out_am)) : NULL;
                        $time_in_pm = (strtotime($row->time_in_pm)) ? date('H:i',strtotime($row->time_in_pm)) : NULL;
                        $time_out_pm = (strtotime($row->time_out_pm)) ? date('H:i',strtotime($row->time_out_pm)) : NULL;

                        $in_am = (strtotime($row->time_in_am)) ? date('h:ia',strtotime($row->time_in_am)) : NULL;
                        $out_am = (strtotime($row->time_out_am)) ? date('h:ia',strtotime($row->time_out_am)) : NULL;
                        $in_pm = (strtotime($row->time_in_pm)) ? date('h:ia',strtotime($row->time_in_pm)) : NULL;
                        $out_pm = (strtotime($row->time_out_pm)) ? date('h:ia',strtotime($row->time_out_pm)) : NULL;

                        $time_in_am_type = $row->time_in_am_type;
                        $time_out_am_type = $row->time_out_am_type;
                        $time_in_pm_type = $row->time_in_pm_type;
                        $time_out_pm_type = $row->time_out_pm_type;

                        $dtr[$day]['check'] = 'time';
                        $dtr[$day]['in_am'] = $in_am;
                        $dtr[$day]['out_am'] = $out_am;
                        $dtr[$day]['in_pm'] = $in_pm;
                        $dtr[$day]['out_pm'] = $out_pm;

                        if($row->time_type_){
                            $dtr[$day]['time_type'] = $row->time_type;
                            $dtr[$day]['time_type_name'] = $row->time_type_->name;
                            $dtr[$day]['time_in_am_type'] = $time_in_am_type;
                            $dtr[$day]['time_out_am_type'] = $time_out_am_type;
                            $dtr[$day]['time_in_pm_type'] = $time_in_pm_type;
                            $dtr[$day]['time_out_pm_type'] = $time_out_pm_type;
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

                        $sched_count = count($dtr[$day]['sched_time']);
                        $total_time_diff = 0;

                        foreach($dtr[$day]['sched_time'] as $sched){
                            if(strtotime($sched['in']) && strtotime($sched['out'])){
                                $in_from = date('H:i',strtotime($sched['in']));
                                $out_to = date('H:i',strtotime($sched['out']));

                                $in_from_ = Carbon::parse($in_from)->seconds(0);
                                $out_to_ = Carbon::parse($out_to)->seconds(0);

                                $total_time_diff += $out_to_->diffInMinutes($in_from_);

                                if($out_to>$in_from){
                                    if($in_from>='00:00' && $out_to<='13:59'){
                                        if($time_in_am && $time_in_am>$in_from){
                                            $time_from_ = Carbon::parse($in_from)->seconds(0);
                                            $time_to_ = Carbon::parse($time_in_am)->seconds(0);
                                            $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                            $total_minutes += $tardy_minutes;
                                            $tardy_no++;
                                        }

                                        if($time_out_am && $time_out_am<$out_to){
                                            $time_from_ = Carbon::parse($time_out_am)->seconds(0);
                                            $time_to_ = Carbon::parse($out_to)->seconds(0);
                                            $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                            $total_minutes += $ud_minutes;
                                            $ud_no++;
                                        }

                                        if($time_in_am==NULL && $time_out_am==NULL &&
                                            $time_in_am_type==NULL && $time_out_am_type==NULL
                                        ){
                                            if($sched_count>1){
                                                $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                                $total_minutes += $hd_minutes;
                                                $hd_no = 1;
                                            }else{
                                                $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                                $total_minutes += $abs_minutes;
                                                $abs_no = 1;
                                            }
                                        }
                                    }elseif($in_from>='10:00' && $out_to<='24:00'){
                                        if($time_in_pm && $time_in_pm>$in_from){
                                            $time_from_ = Carbon::parse($in_from)->seconds(0);
                                            $time_to_ = Carbon::parse($time_in_pm)->seconds(0);
                                            $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                            $total_minutes += $tardy_minutes;
                                            $tardy_no++;
                                        }

                                        if($time_out_pm && $time_out_pm<$out_to){
                                            $time_from_ = Carbon::parse($time_out_pm)->seconds(0);
                                            $time_to_ = Carbon::parse($out_to)->seconds(0);
                                            $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                            $total_minutes += $ud_minutes;
                                            $ud_no++;
                                        }

                                        if($time_in_pm==NULL && $time_out_pm==NULL &&
                                            $time_in_pm_type==NULL && $time_out_pm_type==NULL
                                        ){
                                            if($sched_count>1){
                                                $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                                $total_minutes += $hd_minutes;
                                                $hd_no = 1;
                                            }else{
                                                $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                                $total_minutes += $abs_minutes;
                                                $abs_no = 1;
                                            }
                                        }
                                    }else{
                                        if($time_in_am && $time_in_am>$in_from){
                                            $time_from_ = Carbon::parse($in_from)->seconds(0);
                                            $time_to_ = Carbon::parse($time_in_am)->seconds(0);
                                            $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                            $total_minutes += $tardy_minutes;
                                            $tardy_no++;
                                        }
                                        if($time_out_pm && $time_out_pm<$out_to){
                                            $time_from_ = Carbon::parse($time_out_pm)->seconds(0);
                                            $time_to_ = Carbon::parse($out_to)->seconds(0);
                                            $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                            $total_minutes += $ud_minutes;
                                            $ud_no++;
                                        }
                                        if($time_in_am==NULL && $time_out_pm==NULL &&
                                            $time_in_am_type==NULL && $time_out_pm_type==NULL
                                        ){
                                            if($sched_count>1){
                                                $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                                $total_minutes += $hd_minutes;
                                                $hd_no = 1;
                                            }else{
                                                $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                                $total_minutes += $abs_minutes;
                                                $abs_no = 1;
                                            }
                                        }
                                    }
                                }else{

                                }
                            }
                        }

                        if($row->time_type==1){
                            $abs_minutes = $total_time_diff;
                            $total_minutes += $abs_minutes;
                            $abs_no = 1;
                        }elseif($row->time_type==2 || $row->time_type==3){
                            $hd_minutes = $total_time_diff/2;
                            $total_minutes += $hd_minutes;
                            $hd_no = 1;
                        }

                        $hours = 0;
                        $minutes = $total_minutes;
                        if($total_minutes>=60){
                            $hours = floor($total_minutes / 60);
                            $minutes = $total_minutes % 60;
                        }
                        $tardy_hr = 0;
                        $tardy_min = $tardy_minutes;
                        if($tardy_min>=60){
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


                        $dtr[$day]['hours'] = $hours;
                        $dtr[$day]['minutes'] = $minutes;
                        $dtr[$day]['tardy_hr'] = $tardy_hr;
                        $dtr[$day]['tardy_min'] = $tardy_min;
                        $dtr[$day]['tardy_no'] = $tardy_no;
                        $dtr[$day]['ud_hr'] = $ud_hr;
                        $dtr[$day]['ud_min'] = $ud_min;
                        $dtr[$day]['ud_no'] = $ud_no;
                        $dtr[$day]['hd_hr'] = $hd_hr;
                        $dtr[$day]['hd_min'] = $hd_min;
                        $dtr[$day]['hd_no'] = $hd_no;
                        $dtr[$day]['abs_hr'] = $abs_hr;
                        $dtr[$day]['abs_min'] = $abs_min;
                        $dtr[$day]['abs_no'] = $abs_no;
                    @endphp
                @endfor
                @foreach($included_days as $row)
                    @php
                        $total_minutes = 0;
                        $total_time_diff = 0;
                        foreach($dtr[$row]['sched_time'] as $sched){
                            if(strtotime($sched['in']) && strtotime($sched['out'])){
                                $in_from = date('H:i',strtotime($sched['in']));
                                $out_to = date('H:i',strtotime($sched['out']));

                                $in_from_ = Carbon::parse($in_from)->seconds(0);
                                $out_to_ = Carbon::parse($out_to)->seconds(0);

                                $total_time_diff += $out_to_->diffInMinutes($in_from_);
                            }
                        }
                        $abs_minutes = $total_time_diff;
                        $total_minutes += $abs_minutes;
                        $abs_no = 1;
                        $hours = 0;
                        $minutes = $total_minutes;
                        if($total_minutes>=60){
                            $hours = floor($total_minutes / 60);
                            $minutes = $total_minutes % 60;
                        }
                        $abs_hr = 0;
                        $abs_min = $abs_minutes;
                        if($abs_minutes>=60){
                            $abs_hr = floor($abs_minutes / 60);
                            $abs_min = $abs_minutes % 60;
                        }
                        $dtr[$row]['hours'] = $hours;
                        $dtr[$row]['minutes'] = $minutes;
                        $dtr[$row]['abs_hr'] = $abs_hr;
                        $dtr[$row]['abs_min'] = $abs_min;
                        $dtr[$row]['abs_no'] = $abs_no;
                    @endphp
                @endforeach
                @foreach ($getDtrInfo as $row)
                    @php
                        $day = date('j',strtotime($row->date));
                        $dtr[$day]['hours'] = $row->hours;
                        $dtr[$day]['minutes'] = $row->minutes;
                        $dtr[$day]['tardy_hr'] = $row->tardy_hr;
                        $dtr[$day]['tardy_min'] = $row->tardy_min;
                        $dtr[$day]['tardy_no'] = $row->tardy_no;
                        $dtr[$day]['ud_hr'] = $row->ud_hr;
                        $dtr[$day]['ud_min'] = $row->ud_min;
                        $dtr[$day]['ud_no'] = $row->ud_no;
                        $dtr[$day]['hd_hr'] = $row->hd_hr;
                        $dtr[$day]['hd_min'] = $row->hd_min;
                        $dtr[$day]['hd_no'] = $row->hd_no;
                        $dtr[$day]['abs_hr'] = $row->abs_hr;
                        $dtr[$day]['abs_min'] = $row->abs_min;
                        $dtr[$day]['abs_no'] = $row->abs_no;
                    @endphp
                @endforeach
                @for ($j = 1; $j <= $lastDay; $j++)
                    @php
                        $weekdayName = date('l', strtotime("$year-$month-$j"));
                        $dayOfWeek = date('N', strtotime("$year-$month-$j"));
                    @endphp
                    <tr>
                        <td>{{$dtr[$j]['day']}}</td>

                        @if ($dtr[$j]['check']=='')
                            @if ($dayOfWeek == 6 || $dayOfWeek == 7)
                                @if($dayOfWeek == 7)
                                    <td colspan="4"><span class="text-require">{{$weekdayName}}</span></td>
                                @else
                                    <td colspan="4">{{$weekdayName}}</td>
                                @endif
                            @elseif($dtr[$j]['holiday']!='')
                                <td colspan="4"><span class="text-primary">{{$dtr[$j]['holiday']}}</span></td>
                            @else
                                <td>{{$dtr[$j]['in_am']}}</td>
                                <td>{{$dtr[$j]['out_am']}}</td>
                                <td>{{$dtr[$j]['in_pm']}}</td>
                                <td>{{$dtr[$j]['out_pm']}}</td>
                            @endif
                        @else
                            @php
                                $colspan1 = 1;
                                $colspan2 = 1;
                                $colspan3 = 1;
                                $include = [1,2,3,4];
                                if($dtr[$j]['time_in_am_type']==$dtr[$j]['time_out_am_type'] &&
                                    $dtr[$j]['time_out_am_type']==$dtr[$j]['time_in_pm_type'] &&
                                    $dtr[$j]['time_in_pm_type']==$dtr[$j]['time_out_pm_type'] &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan1 = 4;
                                    $include = [1];
                                }elseif($dtr[$j]['time_in_am_type']==$dtr[$j]['time_out_am_type'] &&
                                    $dtr[$j]['time_out_am_type']==$dtr[$j]['time_in_pm_type'] &&
                                    ($dtr[$j]['time_out_pm_type']==NULL || $dtr[$j]['time_out_pm_type']==0) &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan1 = 3;
                                    $include = [1,4];
                                }elseif($dtr[$j]['time_in_am_type']==$dtr[$j]['time_out_am_type'] &&
                                    ($dtr[$j]['time_in_pm_type']==NULL || $dtr[$j]['time_in_pm_type']==0) &&
                                    ($dtr[$j]['time_out_pm_type']==NULL || $dtr[$j]['time_out_pm_type']==0) &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan1 = 2;
                                    $include = [1,3,4];
                                }elseif($dtr[$j]['time_out_am_type']==$dtr[$j]['time_in_pm_type'] &&
                                    $dtr[$j]['time_out_am_type']==$dtr[$j]['time_out_pm_type'] &&
                                    ($dtr[$j]['time_in_am_type']==NULL || $dtr[$j]['time_in_am_type']==0) &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan2 = 3;
                                    $include = [1,2];
                                }elseif($dtr[$j]['time_out_am_type']==$dtr[$j]['time_in_pm_type'] &&
                                    ($dtr[$j]['time_in_am_type']==NULL || $dtr[$j]['time_in_am_type']==0)&&
                                    ($dtr[$j]['time_out_pm_type']==NULL || $dtr[$j]['time_out_pm_type']==0) &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan2 = 3;
                                    $include = [1,2,4];
                                }elseif($dtr[$j]['time_in_pm_type']==$dtr[$j]['time_out_pm_type'] &&
                                    ($dtr[$j]['time_in_am_type']==NULL || $dtr[$j]['time_in_am_type']==0)&&
                                    ($dtr[$j]['time_out_am_type']==NULL || $dtr[$j]['time_out_am_type']==0) &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan3 = 2;
                                    $include = [1,2,3];
                                }
                            @endphp
                            @if (in_array(1, $include))
                                @if($dtr[$j]['time_in_am_type']>0)
                                    <td colspan="{{$colspan1}}"><span class="text-primary">{{$dtr[$j]['time_type_name']}}</span></td>
                                @else
                                    <td>{{$dtr[$j]['in_am']}}</td>
                                @endif
                            @endif
                            @if (in_array(2, $include))
                                @if($dtr[$j]['time_out_am_type']>0)
                                    <td colspan="{{$colspan2}}"><span class="text-primary">{{$dtr[$j]['time_type_name']}}</span></td>
                                @else
                                    <td>{{$dtr[$j]['out_am']}}</td>
                                @endif
                            @endif
                            @if (in_array(3, $include))
                                @if($dtr[$j]['time_in_pm_type']>0)
                                    <td colspan="{{$colspan3}}"><span class="text-primary">{{$dtr[$j]['time_type_name']}}</span></td>
                                @else
                                    <td>{{$dtr[$j]['in_pm']}}</td>
                                @endif
                            @endif
                            @if (in_array(4, $include))
                                @if($dtr[$j]['time_out_pm_type']>0)
                                    <td><span class="text-primary">{{$dtr[$j]['time_type_name']}}</span></td>
                                @else
                                    <td>{{$dtr[$j]['out_pm']}}</td>
                                @endif
                            @endif
                        @endif

                        <td>{{ $dtr[$j]['hours'] <= 0 ? '' : $dtr[$j]['hours'] }}</td>
                        <td>{{ $dtr[$j]['minutes'] <= 0 ? '' : $dtr[$j]['minutes'] }}</td>
                        <td>{{ $dtr[$j]['tardy_hr'] <= 0 ? '' : $dtr[$j]['tardy_hr'] }}</td>
                        <td>{{ $dtr[$j]['tardy_min'] <= 0 ? '' : $dtr[$j]['tardy_min'] }}</td>
                        <td>{{ $dtr[$j]['tardy_no'] <= 0 ? '' : $dtr[$j]['tardy_no'] }}</td>
                        <td>{{ $dtr[$j]['ud_hr'] <= 0 ? '' : $dtr[$j]['ud_hr'] }}</td>
                        <td>{{ $dtr[$j]['ud_min'] <= 0 ? '' : $dtr[$j]['ud_min'] }}</td>
                        <td>{{ $dtr[$j]['ud_no'] <= 0 ? '' : $dtr[$j]['ud_no'] }}</td>
                        <td>{{ $dtr[$j]['hd_hr'] <= 0 ? '' : $dtr[$j]['hd_hr'] }}</td>
                        <td>{{ $dtr[$j]['hd_min'] <= 0 ? '' : $dtr[$j]['hd_min'] }}</td>
                        <td>{{ $dtr[$j]['hd_no'] <= 0 ? '' : $dtr[$j]['hd_no'] }}</td>
                        <td>{{ $dtr[$j]['abs_hr'] <= 0 ? '' : $dtr[$j]['abs_hr'] }}</td>
                        <td>{{ $dtr[$j]['abs_min'] <= 0 ? '' : $dtr[$j]['abs_min'] }}</td>
                        <td>{{ $dtr[$j]['abs_no'] <= 0 ? '' : $dtr[$j]['abs_no'] }}</td>
                    </tr>
                @endfor
            </body>
        </table>
    </div>
</div>
