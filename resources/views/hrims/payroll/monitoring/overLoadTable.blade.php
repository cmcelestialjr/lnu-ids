<table id="overLoadTable" class="table table-bordered table-fixed"
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
            @php
                $current_date = clone $date_from;
            @endphp
            @while ($current_date <= $date_to)
                <th data-sortable="true" data-align="center" rowspan="2">{{ $current_date->format('M') }}</th>
                @php
                    $current_date->modify('+1 month');
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
                $rate = $units = '-';
                $total_hours = $accumulated = 0;
                $option_id = $option_id1 = NULL;

                if ($part_time) {
                    $option_id1 = $part_time->pt_option_id;
                    $rate = $part_time->rate;
                    $units = $part_time->units;
                    $total_hours = $part_time->total_hours;
                }

                $name = $name_services->lastname($r->lastname, $r->firstname, $r->middlename, $r->extname);

                if ($r->work->first()->pt_option) {
                    $option_id = $r->work->first()->pt_option_id;
                    if ($option_id != $option_id1) {
                        $rate = $units = '-';
                        $total_hours = 0;
                    }
                }

                $work_id = $r->work->first()->id;

                foreach ($r->payrolls as $payroll) {
                    if ($payroll->months) {
                        foreach ($payroll->months as $rowMonth) {
                            $accumulated += $rowMonth->amount;
                        }
                    }
                }

                $remaining = $total_hours - $accumulated;
                $total_hours = ($total_hours <= 0) ? '-' : number_format($total_hours, 2);
                $accumulated = ($accumulated <= 0) ? '-' : number_format($accumulated, 2);
                $remaining = ($remaining <= 0) ? '-' : number_format($remaining, 2);
            @endphp
            <tr>
                <td>{{ $x++ }}</td>
                <td>{{ $r->id_no }}</td>
                <td>{{ $name }}</td>
                <td>
                    @if($r->work->first()->pt_option)
                        {{ $r->work->first()->pt_option->name }}
                    @endif
                </td>
                <td>{{ $rate }}</td>
                <td>{{ $units }}</td>
                <td>{{ $total_hours }}</td>
                <td>{{ $accumulated }}</td>
                <td>{{ $remaining }}</td>
                @php
                    $current_date = clone $date_from;
                @endphp
                @while ($current_date <= $date_to)
                    <td>
                        <span class="btn btn-secondary btn-secondary-scan btn-sm viewOptions"
                            data-id="{{ $r->id }}"
                            data-o="{{ $option_id }}"
                            data-w="{{ $work_id }}">
                            <span class="fa fa-calendar-times-o"></span>
                        </span>
                        @php
                            $payroll_found = false;
                        @endphp
                        @foreach ($r->payrolls as $payroll)
                            @if ($payroll->months)
                                @foreach($payroll->months as $rowMonth)
                                    @if ($rowMonth->year == $current_date->format('Y') && $rowMonth->month == $current_date->format('m'))
                                        @if($payroll->pt_option_id == $option_id) <br>
                                            hr:{{ number_format($rowMonth->amount, 2) }} <br>
                                            P{{ number_format($rowMonth->earned, 2) }}
                                        @endif
                                        @php
                                            $payroll_found = true;
                                            break;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @php
                            $current_date->modify('+1 month');
                        @endphp
                    </td>
                @endwhile
                <td>
                    <button class="btn btn-info btn-info-scan btn-sm olUpdate"
                        data-id="{{$r->id}}"
                        data-o="{{$option_id}}"
                        data-w="{{$work_id}}">
                        <span class="fa fa-edit"></span>
                    </button>
                    <button class="btn btn-danger btn-danger-scan btn-sm olRemove"
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
$('#overLoadTable').bootstrapTable();
</script>
