<div class="row">
    <div class="col-md-1">
    </div>
    <div class="col-md-10"><br>
        <div class="main-timeline">
            <div class="timeline">
                <a href="#" class="timeline-content">
                    <span class="timeline-year"></span>
                    <div class="timeline-icon">
                        <i class="{{$doc->office->icon}}"></i>
                    </div>
                    <h3 class="timeline-title">
                        {{$doc->office->shorten}} - {{$doc->office->name}}

                    </h3>
                    <p class="description">
                        <table class="description" style="width: 100%">
                            <tr>
                                <td style="width: 50%">DTS No: <b>{{$doc->dts_id}}</b></td>
                                <td style="width: 50%">Document: <b>{{$doc->type->name}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">Particulars: <b>{{$doc->particulars}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">Description: {{$doc->description}}</td>
                            </tr>
                            <tr>
                                <td>Date Created: <b>{{date('M d, Y h:i a', strtotime($doc->created_at))}}</b></td>
                                <td>Created By: <b>{{$doc->created_by_info->lastname}}, {{$doc->created_by_info->firstname}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    Status:
                                    @php
                                        $latest_office_id = '';
                                        if($doc->latest){
                                            $latest_office_id = $doc->latest->office_id;
                                        }
                                    @endphp
                                    @if($doc->office_id==$user_office_id || $latest_office_id==$user_office_id)
                                        <span class="{{$doc->status->btn}}" id="docStatus" data-id="{{$doc->dts_id}}">{{$doc->status->name}}</span>
                                    @else
                                        <span class="{{$doc->status->btn}}">{{$doc->status->name}}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td id="statusChangeAt">@if($doc->status_id>1) {{$doc->status->name}} at: <b>{{date('M d, Y h:i a', strtotime($doc->status_change_at))}}</b> @endif</td>
                                <td id="statusChangeBy">@if($doc->status_id>1) {{$doc->status->name}} by: <b>{{$doc->change_by_info->lastname}}, {{$doc->change_by_info->firstname}}</b> @endif</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    @php
                                        $date_from = Carbon::parse($doc->created_at);
                                        $date_to = Carbon::now();
                                        if($doc->status_id>1){
                                            $date_to = Carbon::parse($doc->status_change_at);
                                        }

                                        $diff = $date_to->diff($date_from);

                                        $days = $diff->days;
                                        $hours = $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    Total Duration: <b id="duration">{{getDuration($days.'-'.$hours.'-'.$minutes)}}</b>
                                </td>
                            </tr>
                        </table>
                    </p>
                </a>
            </div>
            @if($doc->history)
                @php
                    $history_count = $doc->history->count();
                @endphp

                @foreach($doc->history as $key => $row)
                    @if($row->option_id==2)
                        @php
                            $received_date = '';
                            $received_dhm = '';
                        @endphp
                        @if($key + 1 < $history_count)
                            @php
                                $nextRow = $doc->history[$key + 1];
                                $received_date = date('M d, Y h:i a', strtotime($nextRow->created_at));
                                $received_dhm = $nextRow->dhm;
                            @endphp
                        @endif
                        <div class="timeline">
                            <a href="#" class="timeline-content">
                                <span class="timeline-year"></span>
                                <div class="timeline-icon">
                                    <i class="{{$row->office->icon}}"></i>
                                </div>
                                <h3 class="timeline-title">{{$row->office->shorten}} - {{$row->office->name}}</h3>
                                <p class="description">
                                    Duration: <b>{{getDuration($row->dhm)}}</b><br>
                                    Date {{$row->option->name}}: <b>{{date('M d, Y h:i a', strtotime($row->created_at))}}</b><br>
                                    Duration: <b>{{getDuration($received_dhm)}}</b><br>
                                    Date Received: <b>{{ $received_date }}</b><br>
                                    @if($row->option_id==2)
                                    Remarks: {{$row->remarks}}
                                    @endif
                                </p>
                            </a>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
@php
    function getDuration($dhm){
        if($dhm==''){
            return '';
        }
        $explode = explode('-',$dhm);
        $days = $explode[0];
        $hrs = $explode[1];
        $mins = $explode[2];

        if($days==1){
            $days_view = $days.' day ';
        }elseif($days>1){
            $days_view = $days.' days ';
        }else{
            $days_view = '';
        }

        if($hrs==1){
            $hrs_view = $hrs.' hr ';
        }elseif($hrs>1){
            $hrs_view = $hrs.' hrs ';
        }else{
            $hrs_view = '';
        }

        if($mins==1){
            $mins_view = $mins.' min ';
        }elseif($mins>1){
            $mins_view = $mins.' mins ';
        }else{
            $mins_view = '';
        }

        return $days_view.$hrs_view.$mins_view;
    }
@endphp
