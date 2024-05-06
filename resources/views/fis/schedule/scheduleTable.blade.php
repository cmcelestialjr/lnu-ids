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
        @foreach($query as $row)
            {{-- @php
            $d = ['7' => '', '1' => '', '2' => '', '3' => '', '4' => '', '5' => '', '6' => ''];
            @endphp --}}
            @foreach($row->days as $day)
                @php
                $days[] = $day->no;
                $days_list[$day->no][] = date('h:ia',strtotime($row->time_from)).'-'.date('h:ia',strtotime($row->time_to)).
                    '<br>('.$row->course->code.')';
                // $d[$day->no] = date('h:ia',strtotime($row->time_from)).'-'.date('h:ia',strtotime($row->time_to)).
                //     '<br>('.$row->course->code.')';
                @endphp
            @endforeach
            {{-- <tr>
                <td class="center">{!!$d['7']!!}</td>
                <td class="center">{!!$d['1']!!}</td>
                <td class="center">{!!$d['2']!!}</td>
                <td class="center">{!!$d['3']!!}</td>
                <td class="center">{!!$d['4']!!}</td>
                <td class="center">{!!$d['5']!!}</td>
                <td class="center">{!!$d['6']!!}</td>
            </tr> --}}
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

