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
                        @if(isset($program->curriculum))
                            @php
                                $x = 1;
                            @endphp
                            @foreach($program->curriculum as $curriculum)
                                @foreach($curriculum->branch as $branch)
                                    <button type="button" class="btn btn-default editModal"
                                        data-id="{{$branch->id}}"
                                        data-x="{{$x}}">
                                            {{$branch->curriculum->year_from}}</button>
                                    @php
                                        $x++;
                                    @endphp
                                @endforeach
                            @endforeach
                        @endif
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
