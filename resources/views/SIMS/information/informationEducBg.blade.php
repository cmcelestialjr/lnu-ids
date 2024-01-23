@foreach($educ_bg as $row)
<tr>
    <td>
        <label>{{$row->name}}</label>
        @if($row->education_bg)
            <div class="card card-info card-outline">
                <div class="card-body">
            @foreach($row->education_bg as $subRow)
                {{$subRow->name}}<br>
                @if($subRow->program)
                    {{$subRow->program->name}}<br>
                @endif
                {{date('M d, Y',strtotime($subRow->period_from))}} -
                @if($subRow->period_to=='present')
                  {{$subRow->period_to}}
                @else
                  {{date('M d, Y',strtotime($subRow->period_to))}}
                @endif
                <br>
                @if($subRow->year_grad)
                Year Graduated: {{$subRow->year_grad}}<br>
                @endif
                @if($subRow->honors)
                Honors: {{$subRow->honors}}
                @endif
                <div class="card card-default card-outline"></div>
            @endforeach
                </div>
            </div>
        @endif
    </td>
</tr>
@endforeach