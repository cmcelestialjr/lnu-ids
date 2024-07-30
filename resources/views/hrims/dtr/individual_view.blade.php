
<style>
.dtrInput{
    font-size: 10px;
    background-color: #fee7e7;
    border-color: rgb(89, 87, 87);
    padding: 0px;
    width: 100%;
}
table{
    border-collapse: collapse;
    border: 1px solid;
    width:100%;
    font-size:10px;

}
table th{
    border: 1px solid;
    border-color: rgb(89, 87, 87);
    padding-top:2px;
    padding-bottom:2px;
    padding-left:2px;
    padding-right:2px;
    background-color: #fcfcfc;
}
table td{
    border: 1px solid;
    border-color: rgb(89, 87, 87);
    padding-top:2px;
    padding-bottom:2px;
    padding-left:2px;
    padding-right:2px;
    background-color: #fcfcfc;
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
    <table class="center" style="font-size: 10px;">
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
                    $dtrEntry = $dtr[$j];
                @endphp
                <tr>
                    <td>{{$dtrEntry['day']}}</td>

                    @if ($dtrEntry['check']=='')
                        @if ($dayOfWeek == 6 || $dayOfWeek == 7)
                            @if($dayOfWeek == 7)
                                <td colspan="4"><span class="text-require">{{$weekdayName}}</span></td>
                            @else
                                <td colspan="4">{{$weekdayName}}</td>
                            @endif
                        @elseif($dtrEntry['holiday']!='')
                            <td colspan="4"><span class="text-primary">{{$dtrEntry['holiday']}}</span></td>
                        @else
                            <td colspan="4">-------------------</td>
                        @endif
                    @elseif($dtrEntry['check']=='---')
                        <td colspan="4">-------------------</td>
                    @else
                        @php
                            $colspans = ['in_am' => 1, 'out_am' => 1, 'in_pm' => 1, 'out_pm' => 1];
                            $include = [1,2,3,4];
                            if($dtrEntry['time_type']>0){
                                if($dtrEntry['time_in_am_type']==$dtrEntry['time_out_am_type'] &&
                                    $dtrEntry['time_out_am_type']==$dtrEntry['time_in_pm_type'] &&
                                    $dtrEntry['time_in_pm_type']==$dtrEntry['time_out_pm_type']){
                                    $colspans['in_am'] = 4;
                                    $include = [1];
                                }elseif($dtrEntry['time_in_am_type']==$dtrEntry['time_out_am_type'] &&
                                    $dtrEntry['time_out_am_type']==$dtrEntry['time_in_pm_type'] &&
                                    (!$dtrEntry['time_out_pm_type'] || $dtrEntry['time_out_pm_type']==0)){
                                    $colspans['in_am'] = 3;
                                    $include = [1,4];
                                }elseif($dtrEntry['time_in_am_type']==$dtrEntry['time_out_am_type'] &&
                                    (!$dtrEntry['time_in_pm_type'] || $dtrEntry['time_in_pm_type']==0) &&
                                    (!$dtrEntry['time_out_pm_type'] || $dtrEntry['time_out_pm_type']==0)){
                                    $colspans['in_am'] = 2;
                                    $include = [1,3,4];
                                }elseif($dtrEntry['time_out_am_type']==$dtrEntry['time_in_pm_type'] &&
                                    $dtrEntry['time_out_am_type']==$dtrEntry['time_out_pm_type'] &&
                                    (!$dtrEntry['time_in_am_type'] || $dtrEntry['time_in_am_type']==0)){
                                    $colspans['out_am'] = 3;
                                    $include = [1,2];
                                }elseif($dtrEntry['time_out_am_type']==$dtrEntry['time_in_pm_type'] &&
                                    (!$dtrEntry['time_in_am_type'] || $dtrEntry['time_in_am_type']==0)&&
                                    (!$dtrEntry['time_out_pm_type'] || $dtrEntry['time_out_pm_type']==0)){
                                    $colspans['out_am'] = 2;
                                    $include = [1,2,4];
                                }elseif($dtrEntry['time_in_pm_type']==$dtrEntry['time_out_pm_type'] &&
                                    (!$dtrEntry['time_in_am_type'] || $dtrEntry['time_in_am_type']==0)&&
                                    (!$dtrEntry['time_out_am_type'] || $dtrEntry['time_out_am_type']==0)){
                                    $colspans['in_pm'] = 2;
                                    $include = [1,2,3];
                                }
                            }
                        @endphp
                        @foreach([1 => 'in_am', 2 => 'out_am', 3 => 'in_pm', 4 => 'out_pm'] as $key => $field)
                            @if (in_array($key, $include))
                                @if($dtrEntry["time_{$field}_type"]>0)
                                    <td colspan="{{$colspans[$field]}}">
                                        @if($current_url=='mydtr')
                                            {{$dtrEntry['time_type_name']}}
                                        @else
                                            <button type="button" class="btn btn-default dtrInput text-primary border-primary"
                                                    data-d="{{$j}}"
                                                    data-time_type="{{$dtrEntry['time_type']}}">
                                                <span class="fa fa-edit"></span>
                                                {{$dtrEntry['time_type_name']}}
                                            </button>
                                        @endif
                                    </td>
                                @else
                                    <td>
                                        @if($dtrEntry["time_{$field}_type"]==0 && $dtrEntry["time_{$field}_type"]!='' && $current_url!='mydtr')
                                            <button type="button" class="btn btn-default dtrInput text-require border-require"
                                                data-d="{{$j}}"
                                                data-time_type="{{$dtrEntry['time_type']}}">
                                                <span class="fa fa-edit">
                                                {{$dtrEntry[$field]}}</span>
                                            </button>
                                        @else
                                            {{$dtrEntry[$field]}}
                                        @endif
                                    </td>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    @foreach (['hours',
                                'minutes',
                                'tardy_hr',
                                'tardy_min',
                                'tardy_no',
                                'ud_hr',
                                'ud_min',
                                'ud_no',
                                'hd_hr',
                                'hd_min',
                                'hd_no',
                                'abs_hr',
                                'abs_min',
                                'abs_no']
                                as $field)
                        <td>{{ $dtrEntry[$field] <= 0 ? '' : $dtrEntry[$field] }}</td>
                    @endforeach
                </tr>
            @endfor
        </body>
        <tfoot>
            @if($dtrTotal)
                <tr>
                    <th colspan="5">TOTAL</th>
                    @foreach (['hours',
                                'minutes',
                                'tardy_hr',
                                'tardy_min',
                                'tardy_no',
                                'ud_hr',
                                'ud_min',
                                'ud_no',
                                'hd_hr',
                                'hd_min',
                                'hd_no',
                                'abs_hr',
                                'abs_min',
                                'abs_no']
                                as $field)
                        <th>{{ $dtrTotal->$field <= 0 ? '' : $dtrTotal->$field }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th colspan="5">
                        No. of Days Present: {{$dtrTotal->days}}
                    </th>
                    <th colspan="14">
                        Earned Hours.Minutes: {{$dtrTotal->earned_hours}}.{{$dtrTotal->earned_minutes}}
                    </th>
                </tr>
            @endif
        </tfoot>
    </table>
</div>
