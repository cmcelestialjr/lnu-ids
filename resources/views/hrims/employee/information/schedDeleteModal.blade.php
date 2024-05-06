
<div class="modal-content" id="schedDeleteModal">
    <div class="modal-header">
        <h4><span class="fa fa-trash"></span> Remove</h4>
    </div>
    <div class="card card-danger card-outline">
        <div class="modal-body">
            <input type="hidden" name="time_id" value="{{$query->id}}">
            <div class="row">
                <div class="col-lg-12 center">
                    <label>Are you really sure you want to remove this schedule<br>
                        {{date('h:ia',strtotime($query->time_from)).'-'.date('h:ia',strtotime($query->time_to))}}<br>
                        @php
                        $day_disp_array = array();
                        @endphp
                        @foreach($query->days as $day)
                            @php                                            
                            if($day->day==7){
                                $day_disp = '0';
                            }else{
                                $day_disp = $day->day;
                            }
                            $day_disp_array[] = date('D', strtotime("Sunday +{$day_disp} days"));
                            @endphp
                        @endforeach
                        @php
                        $day_disp_array1 = implode(',',$day_disp_array);
                        @endphp
                        ({{$day_disp_array1}})
                        ?</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Yes</button>
    </div>
</div>
<!-- /.modal-content -->
