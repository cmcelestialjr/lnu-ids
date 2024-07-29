<style>
    .dtrInput{
        font-size: 10px;
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
                                <td colspan="4"></td>
                            @endif
                        @else
                            @php
                                $colspans = ['in_am' => 1, 'out_am' => 1, 'in_pm' => 1, 'out_pm', 1];
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
                            {{-- @if (in_array(1, $include))
                                @if($dtrEntry['time_in_am_type']>0)
                                    <td colspan="{{$colspans['in_am']}}">
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
                                        @if($dtrEntry['time_in_am_type']==0 && $dtrEntry['time_in_am_type']!='' && $current_url!='mydtr')
                                            <button type="button" class="btn btn-default dtrInput text-require border-require"
                                                data-d="{{$j}}"
                                                data-time_type="{{$dtrEntry['time_type']}}">
                                                <span class="fa fa-edit">
                                                {{$dtrEntry['in_am']}}</span>
                                            </button>
                                        @else
                                            {{$dtrEntry['in_am']}}
                                        @endif
                                    </td>
                                @endif
                            @endif
                            @if (in_array(2, $include))
                                @if($dtrEntry['time_out_am_type']>0)
                                    <td colspan="{{$colspans['out_am']}}">
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
                                        @if($dtrEntry['time_out_am_type']==0 && $dtrEntry['time_out_am_type']!='' && $current_url!='mydtr')
                                            <button type="button" class="btn btn-default dtrInput text-require border-require"
                                                data-d="{{$j}}"
                                                data-time_type="{{$dtrEntry['time_type']}}">
                                                <span class="fa fa-edit">
                                                {{$dtrEntry['out_am']}}</span>
                                            </button>
                                        @else
                                            {{$dtrEntry['out_am']}}
                                        @endif
                                    </td>
                                @endif
                            @endif
                            @if (in_array(3, $include))
                                @if($dtrEntry['time_in_pm_type']>0)
                                    <td colspan="{{$colspans['in_pm']}}">
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
                                        @if($dtrEntry['time_in_pm_type']==0 && $dtrEntry['time_in_pm_type']!='' && $current_url!='mydtr')
                                            <button type="button" class="btn btn-default dtrInput text-require border-require"
                                                data-d="{{$j}}"
                                                data-time_type="{{$dtrEntry['time_type']}}">
                                                <span class="fa fa-edit">
                                                {{$dtrEntry['in_pm']}}</span>
                                            </button>
                                        @else
                                            {{$dtrEntry['in_pm']}}
                                        @endif
                                    </td>
                                @endif
                            @endif
                            @if (in_array(4, $include))
                                @if($dtrEntry['time_out_pm_type']>0)
                                    <td colspan="{{$colspans['out_pm']}}">
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
                                        @if($dtrEntry['time_out_pm_type']==0 && $dtrEntry['time_out_pm_type']!='' && $current_url!='mydtr')
                                            <button type="button" class="btn btn-default dtrInput text-require border-require"
                                                data-d="{{$j}}"
                                                data-time_type="{{$dtrEntry['time_type']}}">
                                                <span class="fa fa-edit">
                                                {{$dtrEntry['out_pm']}}</span>
                                            </button>
                                        @else
                                            {{$dtrEntry['out_pm']}}
                                        @endif
                                    </td>
                                @endif
                            @endif --}}
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
                <tr>
                    <td colspan="5">TOTAL</td>
                    @foreach($dtrTotal as $row)
                        <td>{{$row}}</td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</div>
