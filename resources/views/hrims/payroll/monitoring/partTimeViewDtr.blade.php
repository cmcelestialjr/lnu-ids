<style>
    .dtrInput{
        font-size: 12px;
        background-color: #fee7e7;
        border-color: black;
        padding: 0px;
        width: 100%;
    }
</style>
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
                                <td><button class="btn btn-default dtrInput">gsagas</button></td>
                                <td><button class="btn btn-default dtrInput"></button></td>
                                <td><button class="btn btn-default dtrInput"></button></td>
                                <td><button class="btn btn-default dtrInput"></button></td>
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
                                    <td>
                                        @if($dtr[$j]['in_am']==NULL || ($dtr[$j]['time_in_am_type']==0 && $dtr[$j]['time_in_am_type']!=''))
                                            <button class="btn btn-default dtrInput">
                                                <span class="fa fa-edit"></span>
                                                {{$dtr[$j]['in_am']}}
                                            </button>
                                        @else
                                            {{$dtr[$j]['in_am']}}
                                        @endif

                                    </td>
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
