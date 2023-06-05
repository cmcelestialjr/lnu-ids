<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link {{$active_view}}" data-toggle="pill" href="#view" role="tab" aria-selected="true">View</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{$active_table}}" data-toggle="pill" href="#table" role="tab" aria-selected="false">Table</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade {{$active_view}}" id="view" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 table-responsive"><br><br>
                    @php
                    $days = [];
                    @endphp
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (SU) Sunday</th>                          
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (M) Monday</th>
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (T) Tuesday</th>
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (W) Wednesday</th>
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (TH) Thursday</th>
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (F) Friday</th>
                            <th class="center" style="width: 14.25%;
                                padding-top:25px;
                                padding-bottom:25px">
                                (S) Saturday</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($time as $row)
                                @foreach($row->days as $day)
                                    @php
                                    $days[] = $day->day;
                                    $days_list[$day->day][] = date('h:ia',strtotime($row->time_from)).'-'.date('h:ia',strtotime($row->time_to));
                                    @endphp
                                @endforeach
                            @endforeach
                            <tr>
                            @php
                            $counts = array_count_values($days);
                            if(count($counts)>0){
                                $highest_count = max($counts);
                            }else{
                                $highest_count = 0;
                            }
                            @endphp
                            @for ($i = 1; $i <= $highest_count; $i++)
                                @php
                                for($j=1;$j<=7;$j++){
                                    $td[$j][$i] = '';
                                    if(isset($days_list[$j][$i-1])){
                                        $td[$j][$i] = $days_list[$j][$i-1];
                                    }
                                }
                                @endphp
                                <tr>
                                    <td class="center">{!!$td['7'][$i]!!}</td>
                                    <td class="center">{!!$td['1'][$i]!!}</td>
                                    <td class="center">{!!$td['2'][$i]!!}</td>
                                    <td class="center">{!!$td['3'][$i]!!}</td>
                                    <td class="center">{!!$td['4'][$i]!!}</td>
                                    <td class="center">{!!$td['5'][$i]!!}</td>
                                    <td class="center">{!!$td['6'][$i]!!}</td>
                                </tr>
                            @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade {{$active_table}}" id="table" role="tabpanel">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <button class="btn btn-primary btn-primary-scan btn-sm schedNewModal" style="float:right">
                        <span class="fa fa-plus"></span> New Schedule
                    </button><br><br>
                    <table class="table table-bordered center">
                        <thead>
                            <th>#</th>
                            <th>Time</th>
                            <th>Days</th>
                            <th>Remarks</th>
                            <th>Doc</th>
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                            <th>Option</th>
                            @endif
                        </thead>
                        <tbody id="schedTbody">
                            @php
                            $x = 1;
                            @endphp
                            @foreach($time as $row)
                                <tr>
                                    <td>{{$x}}</td>
                                    <td>{{date('h:ia',strtotime($row->time_from)).'-'.date('h:ia',strtotime($row->time_to))}}</td>
                                    <td>
                                        @php
                                        $day_disp_array = array();
                                        @endphp
                                        @foreach($row->days as $day)
                                            @php                                            
                                            if($day->day==7){
                                                $day_disp = '0';
                                            }else{
                                                $day_disp = $day->day;
                                            }
                                            $day_disp_array[] = date('D', strtotime("Sunday +{$day_disp} days"));
                                            @endphp
                                        @endforeach
                                        @php
                                        $day_disp_array1 = implode(',',$day_disp_array);
                                        @endphp
                                        {{$day_disp_array1}}
                                    </td>
                                    <td>{{$row->remarks}}</td>
                                    <td></td>
                                    @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                    <td><button class="btn btn-info btn-info-scan btn-xs schedEditModal"
                                            data-id="{{$row->id}}">
                                            <span class="fa fa-edit"></span></button>
                                        <button class="btn btn-danger btn-danger-scan btn-xs schedDeleteModal"
                                            data-id="{{$row->id}}">
                                            <span class="fa fa-trash"></span></button>
                                    </td>
                                    @endif
                                </tr>
                                @php
                                $x++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/js/hrims/employee/information/schedule.js') }}"></script>
    </div>
</div>
