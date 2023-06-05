<div class="row" id="timeDiv">
    <div class="col-lg-12">
        @php
            $time_from = date('h:ia',strtotime('07:30:00'));
            $time_to = date('h:ia',strtotime('09:00:00'));
        @endphp
        <label>Schedule</label>
        <select class="select2-schedule" name="schedule" style="width:100%">
            <option value="new" {{$selected1}}>New</option>
            @php
                $x = 0;
            @endphp
            @foreach($query as $row)
                @php
                    $time_from = date('h:ia',strtotime($row->time_from));
                    $time_to = date('h:ia',strtotime($row->time_to));
                @endphp
                @if($schedule_id==$row->id)
                    <option value="{{$row->id}}" selected>{{$time_from}}-{{$time_to}}</option>
                @else
                    @if($x==0)
                    <option value="{{$row->id}}" {{$selected2}}>{{$time_from}}-{{$time_to}}</option>
                    @else
                    <option value="{{$row->id}}">{{$time_from}}-{{$time_to}}</option>
                    @endif
                @endif
                @php
                    $x++;
                @endphp
            @endforeach
        </select>
    </div>    
</div>
