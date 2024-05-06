<div class="row">
    <div class="col-md-4">
        <label>Status</label>
        @php                                                
        $statusSelect = statusSelect($offered_curriculum->status_id,$offered_curriculum->id,$statuses,'curriculum')
        @endphp
        {!!$statusSelect!!}
    </div>    
    @foreach($year_level as $level)    
    <div class="col-lg-12"> 
        <div class="card card-primary card-outline">
            <div class="card-body">
        <label>{{$level->name}}</label>
        <div class="table-responsive">
            <div class="row">
                @foreach($period as $per)
                    <div class="col-lg-12">
                    @php
                    $lab_total = 0;
                    $unit_total = 0;
                    foreach($offered_courses as $course){
                        if($level->id==$course->course->grade_level_id){
                            $lab_total += $course->course->lab;
                        }
                    }
                    @endphp
                    <div class="card card-info card-outline">
                        <div class="card-body table-responsive">
                            {{$per->name}}
                            <table class="table table-bordered" style="font-size:11px;">
                                <thead>
                                    @if($lab_total>0)
                                    <th style="width: 11%">Course Code</th>
                                    <th style="width: 50%">Descriptive Title</th>
                                    <th style="width: 7%">Units</th>
                                    <th style="width: 7%">Lab</th>
                                    <th style="width: 15%">Pre-req</th>
                                    <th style="width: 10%">Status</th>
                                    @else
                                    <th style="width: 15%">Course Code</th>
                                    <th style="width: 50%">Descriptive Title</th>
                                    <th style="width: 10%">Units</th>
                                    <th style="width: 15%">Pre-req</th>
                                    <th style="width: 10%">Status</th>
                                    @endif                                    
                                </thead>
                                <tbody>
                                    @foreach($offered_courses as $course)
                                        @if($level->id==$course->course->grade_level_id)
                                        <tr>
                                            <td class="center">
                                                {{$course->course->code}}
                                            </td>
                                            <td>{{$course->course->name}}</td>
                                            <td class="center">{{$course->course->units}}</td>
                                            @if($lab_total>0)
                                            <td class="center">{{$course->course->lab}}</td>
                                            @endif
                                            <td class="center">{{$course->course->pre_name}}</td>
                                            <td class="center">
                                                @php
                                                    if($user_access_level==1 || $user_access_level==2){
                                                        $courseStatus = 'courseStatusModal';
                                                    }else{
                                                        $courseStatus = '';
                                                    }
                                                @endphp
                                                @php                                                
                                                $statusSelect = statusSelect($course->status_id,$course->id,$statuses,'course')
                                                @endphp
                                                {!!$statusSelect!!}
                                            </td>
                                        </tr>
                                        @php
                                            $unit_total += $course->course->units;
                                        @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <td colspan="2" class="center">Total</td>
                                    <td class="center">{{$unit_total}}</td>
                                    @if($lab_total>0)
                                        <td class="center">{{$lab_total}}</td>
                                    @endif
                                    <td colspan="2"></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@php
    function statusSelect($status_selected,$id,$statuses,$from){

        $selectHTML = '<select class="form-control select2-table1 selectStatus" style="width:100%">';        
        foreach ($statuses as $status) {
            $color = getStatusColor($status->id);
            if($status_selected==$status->id){
                $selectHTML .= '<option value="'.$status->id.'" data-id="'.$id.'" data-from="'.$from.'" data-color="'.$color.'" selected>'.$status->name.'</option>';
            }else{
                $selectHTML .= '<option value="'.$status->id.'" data-id="'.$id.'" data-from="'.$from.'" data-color="'.$color.'">'.$status->name.'</option>';
            }
        }
        $selectHTML .= '</select>';
        
        return $selectHTML;
    }
    function getStatusColor($status){
        if ($status == 1) {
            return 'green';
        } else{
            return 'red';
        }
    }
@endphp
<script>
    $(document).ready(function() {
        $(".select2-table1").select2({
            dropdownParent: $("#curriculumViewList"),
            templateSelection: function(option) {
                if (!option.id) {
                    return option.text;
                }                    
                var color = $(option.element).data("color");                    
                return $("<span>").text(option.text).css("color", color);
            }
        });
    });
</script>