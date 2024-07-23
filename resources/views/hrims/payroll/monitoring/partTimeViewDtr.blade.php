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
                                    'out' => $row->time->time_to
                                ];
                            }
                        @endphp
                    @endforeach
                @endfor
                @foreach($getHolidays as $row)
                    @php
                        $day = date('j',strtotime($row->date));
                        $dtr[$day]['check'] = '';
                        $dtr[$day]['holiday'] = $row->name;
                    @endphp
                @endforeach
                @for ($k = 0; $k < $getDtr->count(); $k++)
                    @php
                        $row = $getDtr[$k];
                        $day = date('j', strtotime($row->date));
                        $dtr[$day]['check'] = 'time';
                        $dtr[$day]['in_am'] = date('h:ia',strtotime($row->time_in_am));
                        $dtr[$day]['out_am'] = date('h:ia',strtotime($row->time_out_am));
                        $dtr[$day]['in_pm'] = date('h:ia',strtotime($row->time_in_pm));
                        $dtr[$day]['out_pm'] = date('h:ia',strtotime($row->time_out_pm));
                        if($row->time_type_){
                            $dtr[$day]['time_type'] = $row->time_type;
                            $dtr[$day]['time_type_name'] = $row->time_type_->name;
                            $dtr[$day]['time_in_am_type'] = $row->time_in_am_type;
                            $dtr[$day]['time_out_am_type'] = $row->time_out_am_type;
                            $dtr[$day]['time_in_pm_type'] = $row->time_in_pm_type;
                            $dtr[$day]['time_out_pm_type'] = $row->time_out_pm_type;
                        }
                        foreach($dtr[$day]['sched_time'] as $sched){
                            echo '-- '.$day.'---'.$sched['in'].' '.$sched['out'].'<br>';
                        }
                    @endphp
                @endfor
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
                                    $dtr[$j]['time_in_am_type']==$dtr[$j]['time_in_pm_type'] &&
                                    $dtr[$j]['time_in_am_type']==$dtr[$j]['time_out_pm_type'] &&
                                    $dtr[$j]['time_type']>0){
                                    $colspan1 = 4;
                                    $include = [1];
                                }elseif($dtr[$j]['time_in_am_type']==$dtr[$j]['time_out_am_type'] &&
                                    $dtr[$j]['time_in_am_type']==$dtr[$j]['time_in_pm_type'] &&
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
