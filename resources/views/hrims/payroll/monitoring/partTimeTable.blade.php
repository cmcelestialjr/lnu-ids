<table id="partTimeTable" class="table table-bordered table-fixed"
        data-toggle="table"
        data-search="true"
        data-buttons-class="primary"
        data-show-export="true"
        data-show-columns-toggle-all="true"
        data-mobile-responsive="true"
        data-pagination="false"
        data-loading-template="loadingTemplate"
        data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
    <thead>
        <tr>
            <th data-sortable="true" data-align="center" rowspan="2">#</th>
            <th data-sortable="true" data-align="center" rowspan="2">Employee ID</th>
            <th data-sortable="true" data-align="center" rowspan="2">Name</th>
            <th data-sortable="true" data-align="center" rowspan="2">Type</th>
            <th data-sortable="true" data-align="center" rowspan="2">Rate</th>
            <th data-sortable="true" data-align="center" rowspan="2">Units</th>
            <th data-align="center" colspan="3">Hours</th>
            @while ($date_from <= $date_to)
                <th data-sortable="true" data-align="center" rowspan="2">{{$date_from->format('M')}}</th>
                @php
                    $date_from->modify('+1 month');
                @endphp
            @endwhile
            <th data-sortable="true" data-align="center" rowspan="2">Option</th>
        </tr>
        <tr>
            <th data-sortable="true" data-align="center">Total</th>
            <th data-sortable="true" data-align="center">Accumulated</th>
            <th data-sortable="true" data-align="center">Remaining</th>
        </tr>
    </thead>
    <tbody>
        @php
        $x = 1;
        @endphp
        @foreach($query as $r)
            @php
                $part_time = $r->part_time->first();
                $rate = '-';
                $units = '-';
                $total_hours = '-';
                $option_id = NULL;
                if($part_time){
                    $rate = $part_time->rate;
                    $units = $part_time->units;
                    $total_hours = $part_time->total_hours;
                }
                $name = $name_services->lastname($r->lastname,$r->firstname,$r->middlename,$r->extname);
                if($r->work->first()->pt_option){
                    $option_id = $r->work->first()->pt_option_id;
                }
                $work_id = $r->work->first()->id;
            @endphp
            <tr>
                <td>
                    {{$x}}
                </td>
                <td>
                    {{$r->id_no}}
                </td>
                <td>
                    {{$name}}
                </td>
                <td>
                    @if($r->work->first()->pt_option)
                        {{$r->work->first()->pt_option->name}}
                    @endif
                </td>
                <td>
                    {{$rate}}
                </td>
                <td>
                    {{$units}}
                </td>
                <td>
                    {{$total_hours}}
                </td>
                <td>
                    {{$total_hours}}
                </td>
                <td>
                    {{$total_hours}}
                </td>
                @while ($current_date <= $date_to)
                    <td>
                        @php
                            $payroll_found = false;
                        @endphp
                        @foreach ($r->payrolls as $payroll)
                            @if ($payroll->payroll)
                                @if ($payroll->payroll->year == $current_date->format('Y')
                                        && $payroll->payroll->month == $current_date->format('m'))
                                    {{ number_format($payroll->netpay,2) }}
                                    @php
                                        $payroll_found = true;
                                        break;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                        @php
                            $current_date->modify('+1 month');
                        @endphp
                    </td>
                @endwhile
                <td>
                    <button class="btn btn-info btn-info-scan btn-sm ptUpdate"
                        data-id="{{$r->id}}"
                        data-o="{{$option_id}}"
                        data-w="{{$work_id}}">
                        <span class="fa fa-edit"></span>
                    </button>
                    <button class="btn btn-danger btn-danger-scan btn-sm ptRemove"
                        data-id="{{$r->id}}"
                        data-o="{{$option_id}}"
                        data-w="{{$work_id}}">
                        <span class="fa fa-trash"></span>
                    </button>
                </td>
            <tr>
        @endforeach
    </tbody>
</table>
<script>
$('#partTimeTable').bootstrapTable();
</script>
