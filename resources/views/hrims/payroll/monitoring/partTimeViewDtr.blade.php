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
                        $dtr[$i]['hours'] = '';
                        $dtr[$i]['minutes'] = '';
                        $dtr[$i]['tardy_hr'] = '';
                        $dtr[$i]['tardy_min'] = '';
                        $dtr[$i]['tardy_no'] = '';
                        $dtr[$i]['ud_hr'] = '';
                        $dtr[$i]['ud_min'] = '';
                        $dtr[$i]['ud_no'] = '';
                        $dtr[$i]['hd_hr'] = '';
                        $dtr[$i]['hd_min'] = '';
                        $dtr[$i]['hd_no'] = '';
                        $dtr[$i]['abs_hr'] = '';
                        $dtr[$i]['abs_min'] = '';
                        $dtr[$i]['abs_no'] = '';
                    @endphp

                @endfor
                @foreach($getHolidays as $row)
                    @php
                        $day = date('j',strtotime($row->date));
                        $dtr[$day]['check'] = '';
                        $dtr[$day]['holiday'] = $row->name;
                    @endphp
                @endforeach
                @foreach($getDtr as $row)
                    @php
                        $day = date('j',strtotime($row->date));
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
                            @endif
                        @else
                            @if($dtr[$j]['time_type']==1 ||
                                    $dtr[$j]['time_type']==4)
                                <td colspan="4"><span class="text-primary">{{$dtr[$j]['time_type_name']}}</span></td>
                            @else
                                @if($dtr[$j]['time_in_am_type'] && $dtr[$j]['time_out_pm_type'])
                                    <td colspan="4"><span class="text-primary">{{$dtr[$j]['time_type_name']}}</span></td>
                                @else
                                    <td>{{$dtr[$j]['in_am']}}</td>
                                    <td>{{$dtr[$j]['out_am']}}</td>
                                    <td>{{$dtr[$j]['in_pm']}}</td>
                                    <td>{{$dtr[$j]['out_pm']}}</td>
                                @endif
                            @endif
                        @endif

                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                        <td>{{$dtr[$j]['hours']}}</td>
                    </tr>
                @endfor
            </body>
        </table>
    </div>
</div>
