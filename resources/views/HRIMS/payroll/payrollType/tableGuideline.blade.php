<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Title</th>
            <th rowspan="2">W/ Salary %</th>
            <th rowspan="2">Amount1</th>
            <th rowspan="2">% Percent1</th>
            <th rowspan="2">Amount2</th>
            <th rowspan="2">% Percent2</th>
            <th colspan="2">No. of Months</th>
            <th rowspan="2">Option</th>
        </tr>
        <tr>
            <th>From >=</th>
            <th>< To</th>
        </tr>
    </thead>
    <tbody class="center">
        @php
        $x = 1;
        @endphp
        @foreach($query as $row)
        <tr>
            <td>{{$x}}</td>
            <td>{{$row->name}}</td>
            <td>{{$row->w_salary_percent}}</td>
            <td>{{$row->amount}}</td>
            <td>{{$row->percent}}</td>
            <td>{{$row->amount2}}</td>
            <td>{{$row->percent2}}</td>
            <td>{{$row->from}}</td>
            <td>{{$row->to}}</td>
            <td>
                <button class="btn btn-info btn-info-scan btn-xs editGuideline"
                    data-id="{{$row->id}}">
                    <span class="fa fa-edit"></span>
                </button>
                <button class="btn btn-danger btn-danger-scan btn-xs deleteGuideline"
                    data-id="{{$row->id}}">
                    <span class="fa fa-trash"></span>
                </button>
            </td>
        </tr>
        @php
        $x++;
        @endphp
    @endforeach
    </tbody>
</table>