<div class="row" id="timeDiv">
    <div class="col-lg-12">
        @php
            $time_from = date('h:ia',strtotime('07:30:00'));
            $time_to = date('h:ia',strtotime('09:00:00'));
        @endphp
        <label>Schedule</label>
        <select class="select2-schedule" name="schedule" style="width:100%">
            <option value="new">New</option>
            @php
                $x = 0;    
            @endphp
            @foreach($query as $row)
                @if($x==0)
                    @php
                    $time_from = date('h:ia',strtotime($row->time_from));
                    $time_to = date('h:ia',strtotime($row->time_to));
                    @endphp
                <option value="{{$row->id}}" selected>{{date('h:ia',strtotime($row->time_from))}}-{{date('h:ia',strtotime($row->time_to))}}</option>
                @else
                <option value="{{$row->id}}">{{date('h:ia',strtotime($row->time_from))}}-{{date('h:ia',strtotime($row->time_to))}}</option>
                @endif
                @php
                    $x++;
                @endphp
            @endforeach
        </select>
    </div>
    <div class="col-lg-6">
        <label>Time From</label>
        <input type="text" class="form-control timepicker-course" name="time_from" value="{{$time_from}}">
    </div>
    <div class="col-lg-6">
        <label>Time To</label>
        <input type="text" class="form-control timepicker-course" name="time_to" value="{{$time_to}}">
    </div>
</div>
