<button class="btn btn-info btn-info-scan" name="lab_fee" style="float: right">
    <span class="fa fa-tasks"></span> Lab Fee
</button>
<button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
    <span class="fa fa-plus"></span> Add Fee
</button><br>
<br>
<table class="table table-bordered">
    <thead>
        <th style="width: 30%" rowspan="2">School Fee</th>
        <th colspan="{{$period->count()}}">Period</th>
        <tr>
            @foreach($period as $row)
            <th>{{$row->name}}</th>
            @endforeach
            @if($period->count()>1)
                <th>All Period</th>
            @endif
        </tr>
        
    </thead>
    <tbody>
        @foreach($list as $row)
            <tr>
                <td>{{$row->fees->name}}</td>
                @foreach($period as $per)
                    <td>
                    @php
                        $amount = 0;
                        foreach($row->period as $per_row){
                            if($per_row->grade_period_id==$per->id){
                                $amount = $per_row->amount;
                            }
                        }
                    @endphp                    
                        <input type="number" class="form-control period period{{$row->id}} period{{$row->id}}{{$per->id}}" data-id="{{$row->id}}" data-period="{{$per->id}}" value="{{$amount}}">
                    </td>
                @endforeach
                @if($period->count()>1)
                    <td><input type="number" class="form-control allPeriod" data-id="{{$row->id}}"></td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>