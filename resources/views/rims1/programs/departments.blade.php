@php
    $department_count = count($departments);
    $division_no = 12;
    if($department_count>1){
        $division_no = 12/$department_count;
    }
    $department_count1 = count($departments1);
    $division_no1 = 12;
    if($department_count1>1){
        $division_no1 = 12/$department_count1;
    }
@endphp
@if($department_count>0)
    @foreach($departments as $row)
        @php
            $program_count = 0;
            if(isset($row->programs)){
                $program_count = count($row->programs);
            }
        @endphp
        <div class="col-lg-{{$division_no}}">
            <div class="center" style="width: 100%">
                <a class="btn btn-app bg-{{$row->bg}}">
                    <span class="badge bg-{{$row->badge}}">{{$program_count}}</span>
                    <span class="{{$row->icon}}"></span> {{$row->shorten}}<br>{{$row->name}}
                </a>
            </div>
            @if(isset($row->programs))
                @foreach($row->programs as $program)
                    <div class="callout callout-{{$row->bg}}">
                        {{$program->shorten}}
                        @foreach($program->codes as $code)
                            <button class="btn btn-default btn-default-scan programStatus"
                                            data-id="{{$code->id}}"
                                            ><span class="fa fa-edit"></span></button>
                        @endforeach
                    </div>
                @endforeach
            @endif

        </div>
    @endforeach
@else
    @foreach($departments1 as $row)
        <div class="col-lg-{{$division_no1}}">
            <div class="center" style="width: 100%">
                <a class="btn btn-app bg-{{$row->bg}}">
                    <span class="badge bg-{{$row->badge}}">0</span>
                    <span class="{{$row->icon}}"></span> {{$row->shorten}}<br>{{$row->name}}
                </a>
            </div>
        </div>
    @endforeach
@endif
