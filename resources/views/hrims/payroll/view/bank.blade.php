
<div class="modal-content" id="bank">
    <div class="modal-header">
        <h4></h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                        <div class="col-lg-12">
                            <i>Remove time for selected period to delete the period info</i>
                        </div>
                        <div class="col-lg-12 {{$hide_1}}">
                            <label>Period: {{$day_from}}-15</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text"  class="form-control datepicker float-right" name="date_1" id="date_1" value="{{$date_1}}">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                                <input type="time"  class="form-control float-right" name="time_1" id="time_1" value="{{$time_1}}">
                            </div>
                        </div>
                        <div class="col-lg-12 {{$hide_2}}">
                            <label>Period: 16-{{$day_to}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text"  class="form-control datepicker float-right" name="date_2" id="date_2" value="{{$date_2}}">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                                <input type="time"  class="form-control float-right" name="time_2" id="time_2" value="{{$time_2}}">
                            </div>
                        </div>
                        <div class="col-lg-12 {{$hide_3}}">
                            <label>Period: {{$day_from}}-{{$day_to}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text"  class="form-control datepicker float-right" name="date_3" id="date_3" value="{{$date_3}}">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                                <input type="time"  class="form-control float-right" name="time_3" id="time_3" value="{{$time_3}}">
                            </div>
                        </div>
                    @else
                        <div class="col-lg-12 {{$hide_1}}">
                            Period: <label>{{$day_from}}-15</label><br>
                            DateTime: {{$date_1}} {{$time_view_1}}
                        </div>
                        <div class="col-lg-12 {{$hide_2}}">
                            Period: <label>16-{{$day_to}}</label><br>
                            DateTime: {{$date_2}} {{$time_view_2}}
                        </div>
                        <div class="col-lg-12 {{$hide_3}}">
                            Period: <label>{{$day_from}}-{{$day_to}}</label><br>
                            DateTime: {{$date_3}} {{$time_view_3}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-succes-scan" id="submit"
            data-id="{{$id}}"
            data-x="{{$x}}">Submit</button>
    </div>
</div>
<!-- /.modal-content -->
