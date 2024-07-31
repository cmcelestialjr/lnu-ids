<br>
<div id="time-div">
<table class="table table-bordered">
    <tr>
        <th colspan="2">AM</th>
        <th colspan="2">PM</th>
    </tr>
    <tr>
        <th style="width: 25%">Arrival</th>
        <th style="width: 25%">Departure</th>
        <th style="width: 25%">Arrival</th>
        <th style="width: 25%">Departure</th>
    </tr>
    @if($time_type=='' || $time_type==NULL)
        @if($query!=NULL)
            <tr>
                @if($query->time_in_am!=NULL && $query->time_in_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_am" value="{{date('h:ia', strtotime($query->time_in_am))}}" readonly></td>
                @elseif($query->time_in_am!=NULL && $query->time_in_am_type==1)
                    <td><input type="time" class="form-control" name="time_in_am" value="{{date('H:i', strtotime($query->time_in_am))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_in_am"></td>
                @endif
                @if($query->time_out_am!=NULL && $query->time_out_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_am" value="{{date('h:ia', strtotime($query->time_out_am))}}" readonly></td>
                @elseif($query->time_out_am!=NULL && $query->time_out_am_type==1)
                    <td><input type="time" class="form-control" name="time_out_am" value="{{date('H:i', strtotime($query->time_out_am))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_out_am"></td>
                @endif
                @if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_pm" value="{{date('h:ia', strtotime($query->time_in_pm))}}" readonly></td>
                @elseif($query->time_in_pm!=NULL && $query->time_in_pm_type==1)
                    <td><input type="time" class="form-control" name="time_in_pm" value="{{date('H:i', strtotime($query->time_in_pm))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_in_pm"></td>
                @endif
                @if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_pm" value="{{date('h:ia', strtotime($query->time_out_pm))}}" readonly></td>
                @elseif($query->time_out_pm!=NULL && $query->time_out_pm_type==1)
                    <td><input type="time" class="form-control" name="time_out_pm" value="{{date('H:i', strtotime($query->time_out_pm))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_out_pm"></td>
                @endif
            </tr>
        @else
            <tr>
                <td><input type="time" class="form-control" name="time_in_am"></td>
                <td><input type="time" class="form-control" name="time_out_am"></td>
                <td><input type="time" class="form-control" name="time_in_pm"></td>
                <td><input type="time" class="form-control" name="time_out_pm"></td>
            </tr>
        @endif
    @elseif($time_type==1)
        <tr>
            <td><input type="text" class="form-control" name="time_in_am" value="Absent" readonly></td>
            <td><input type="text" class="form-control" name="time_out_am" value="Absent" readonly></td>
            <td><input type="text" class="form-control" name="time_in_pm" value="Absent" readonly></td>
            <td><input type="text" class="form-control" name="time_out_pm" value="Absent" readonly></td>
        </tr>
    @elseif($time_type==2)
        @if($query!=NULL)
            <tr>
                <td><input type="text" class="form-control" name="time_in_am" value="Half Day" readonly></td>
                <td><input type="text" class="form-control" name="time_out_am" value="Half Day" readonly></td>
                @if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_pm" value="{{date('h:ia', strtotime($query->time_in_pm))}}" readonly></td>
                @elseif($query->time_in_pm!=NULL && $query->time_in_pm_type==1)
                    <td><input type="time" class="form-control" name="time_in_pm" value="{{date('H:i', strtotime($query->time_in_pm))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_in_pm"></td>
                @endif
                @if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_pm" value="{{date('h:ia', strtotime($query->time_out_pm))}}" readonly></td>
                @elseif($query->time_out_pm!=NULL && $query->time_out_pm_type==1)
                    <td><input type="time" class="form-control" name="time_out_pm" value="{{date('H:i', strtotime($query->time_out_pm))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_out_pm"></td>
                @endif
            </tr>
        @else
            <tr>
                <td><input type="text" class="form-control" name="time_in_am" value="Half Day" readonly></td>
                <td><input type="text" class="form-control" name="time_out_am" value="Half Day" readonly></td>
                <td><input type="time" class="form-control" name="time_in_pm"></td>
                <td><input type="time" class="form-control" name="time_out_pm"></td>
            </tr>
        @endif
    @elseif($time_type==3)
        @if($query!=NULL)
            <tr>
                @if($query->time_in_am!=NULL && $query->time_in_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_am" value="{{date('h:ia', strtotime($query->time_in_am))}}" readonly></td>
                @elseif($query->time_in_am!=NULL && $query->time_in_am_type==1)
                    <td><input type="time" class="form-control" name="time_in_am" value="{{date('H:i', strtotime($query->time_in_am))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_in_am"></td>
                @endif
                @if($query->time_out_am!=NULL && $query->time_out_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_am" value="{{date('h:ia', strtotime($query->time_out_am))}}" readonly></td>
                @elseif($query->time_out_am!=NULL && $query->time_out_am_type==1)
                    <td><input type="time" class="form-control" name="time_out_am" value="{{date('H:i', strtotime($query->time_out_am))}}"></td>
                @else
                    <td><input type="time" class="form-control" name="time_out_am"></td>
                @endif
                <td><input type="text" class="form-control" name="time_in_pm" value="Half Day" readonly></td>
                <td><input type="text" class="form-control" name="time_out_pm" value="Half Day" readonly></td>
            </tr>
        @else
            <tr>
                <td><input type="time" class="form-control" name="time_in_am"></td>
                <td><input type="time" class="form-control" name="time_out_am"></td>
                <td><input type="text" class="form-control" name="time_in_pm" value="Half Day" readonly></td>
                <td><input type="text" class="form-control" name="time_out_pm" value="Half Day" readonly></td>
            </tr>
        @endif
    @elseif($time_type==4)
        <tr>
            <td><input type="text" class="form-control" name="time_in_am" value="Leave" readonly></td>
            <td><input type="text" class="form-control" name="time_out_am" value="Leave" readonly></td>
            <td><input type="text" class="form-control" name="time_in_pm" value="Leave" readonly></td>
            <td><input type="text" class="form-control" name="time_out_pm" value="Leave" readonly></td>
        </tr>
    @elseif($time_type==5)
        @if($query!=NULL)
            <tr>
                @if($query->time_in_am!=NULL && $query->time_in_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_am" value="{{date('h:ia', strtotime($query->time_in_am))}}" readonly></td>
                @elseif($query->time_in_am!=NULL && $query->time_in_am_type==1)
                    <td id="cdo_in_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_am" value="{{date('H:i', strtotime($query->time_in_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                    data-val="time"
                                    data-id="cdo_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="cdo_in_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_am" value="CDO" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                    data-val="cdo"
                                    data-id="cdo_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_am!=NULL && $query->time_out_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_am" value="{{date('h:ia', strtotime($query->time_out_am))}}" readonly></td>
                @elseif($query->time_out_am!=NULL && $query->time_out_am_type==1)
                    <td id="cdo_out_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_am" value="{{date('H:i', strtotime($query->time_out_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                    data-val="time"
                                    data-id="cdo_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="cdo_out_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_am" value="CDO" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                    data-val="cdo"
                                    data-id="cdo_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_pm" value="{{date('h:ia', strtotime($query->time_in_pm))}}" readonly></td>
                @elseif($query->time_in_pm!=NULL && $query->time_in_pm_type==1)
                    <td id="cdo_in_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_pm" value="{{date('H:i', strtotime($query->time_in_pm))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                    data-val="time"
                                    data-id="cdo_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="cdo_in_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_pm" value="CDO" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                    data-val="cdo"
                                    data-id="cdo_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_pm" value="{{date('h:ia', strtotime($query->time_out_pm))}}" readonly></td>
                @elseif($query->time_out_pm!=NULL && $query->time_out_pm_type==1)
                    <td id="cdo_out_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_pm" value="{{date('H:i', strtotime($query->time_out_pm))}}">
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                data-val="time"
                                data-id="cdo_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="cdo_out_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_pm" value="CDO" readonly>
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                data-val="cdo"
                                data-id="cdo_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @else
            <tr>
                <td id="cdo_in_am">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_in_am" value="CDO" readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                data-val="cdo"
                                data-id="cdo_in_am"
                                data-n="time_in_am">
                                <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
                <td id="cdo_out_am">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_out_am" value="CDO" readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                data-val="cdo"
                                data-id="cdo_out_am"
                                data-n="time_out_am">
                                <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
                <td id="cdo_in_pm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_in_pm" value="CDO" readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_cdo"
                                data-val="cdo"
                                data-id="cdo_in_pm"
                                data-n="time_in_pm">
                                <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
                <td id="cdo_out_pm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_out_pm" value="CDO" readonly>
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-info btn-info-scan change_cdo"
                            data-val="cdo"
                            data-id="cdo_out_pm"
                            data-n="time_out_pm">
                            <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    @elseif($time_type==6)
        @if($query!=NULL)
            <tr>
                @if($query->time_in_am!=NULL && $query->time_in_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_am" value="{{date('h:ia', strtotime($query->time_in_am))}}" readonly></td>
                @elseif($query->time_in_am!=NULL && $query->time_in_am_type==1)
                    <td id="travel_in_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_am" value="{{date('H:i', strtotime($query->time_in_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_travel"
                                    data-val="time"
                                    data-id="travel_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="travel_in_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_am" value="Travel" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_travel"
                                    data-val="travel"
                                    data-id="travel_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_am!=NULL && $query->time_out_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_am" value="{{date('h:ia', strtotime($query->time_out_am))}}" readonly></td>
                @elseif($query->time_out_am!=NULL && $query->time_out_am_type==1)
                    <td id="travel_out_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_am" value="{{date('H:i', strtotime($query->time_out_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_travel"
                                    data-val="time"
                                    data-id="travel_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="travel_out_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_am" value="Travel" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_travel"
                                    data-val="travel"
                                    data-id="travel_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_pm" value="{{date('h:ia', strtotime($query->time_in_pm))}}" readonly></td>
                @elseif($query->time_in_pm!=NULL && $query->time_in_pm_type==1)
                    <td id="travel_in_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_pm" value="{{date('H:i', strtotime($query->time_in_pm))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_travel"
                                    data-val="time"
                                    data-id="travel_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="travel_in_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_pm" value="Travel" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_travel"
                                    data-val="travel"
                                    data-id="travel_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_pm" value="{{date('h:ia', strtotime($query->time_out_pm))}}" readonly></td>
                @elseif($query->time_out_pm!=NULL && $query->time_out_pm_type==1)
                    <td id="travel_out_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_pm" value="{{date('H:i', strtotime($query->time_out_pm))}}">
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_travel"
                                data-val="time"
                                data-id="travel_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="travel_out_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_pm" value="Travel" readonly>
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_travel"
                                data-val="travel"
                                data-id="travel_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @else
            <tr>
                <td id="travel_in_am">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_in_am" value="Travel" readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_travel"
                                data-val="travel"
                                data-id="travel_in_am"
                                data-n="time_in_am">
                                <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
                <td id="travel_out_am">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_out_am" value="Travel" readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_travel"
                                data-val="travel"
                                data-id="travel_out_am"
                                data-n="time_out_am">
                                <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
                <td id="travel_in_pm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_in_pm" value="Travel" readonly>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_travel"
                                data-val="travel"
                                data-id="travel_in_pm"
                                data-n="time_in_pm">
                                <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
                <td id="travel_out_pm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="time_out_pm" value="Travel" readonly>
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-info btn-info-scan change_travel"
                            data-val="travel"
                            data-id="travel_out_pm"
                            data-n="time_out_pm">
                            <span class="fa fa-refresh"></span></button>
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    @elseif($time_type==7)
        @if($query!=NULL)
            <tr>
                @if($query->time_in_am!=NULL && $query->time_in_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_am" value="{{date('h:ia', strtotime($query->time_in_am))}}" readonly></td>
                @elseif($query->time_in_am!=NULL && $query->time_in_am_type==1)
                    <td id="vacant_in_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_am" value="{{date('H:i', strtotime($query->time_in_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                    data-val="time"
                                    data-id="vacant_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="vacant_in_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_am" value="Vacant" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                    data-val="vacant"
                                    data-id="vacant_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_am!=NULL && $query->time_out_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_am" value="{{date('h:ia', strtotime($query->time_out_am))}}" readonly></td>
                @elseif($query->time_out_am!=NULL && $query->time_out_am_type==1)
                    <td id="vacant_out_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_am" value="{{date('H:i', strtotime($query->time_out_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                    data-val="time"
                                    data-id="vacant_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="vacant_out_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_am" value="Vacant" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                    data-val="vacant"
                                    data-id="vacant_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_pm" value="{{date('h:ia', strtotime($query->time_in_pm))}}" readonly></td>
                @elseif($query->time_in_pm!=NULL && $query->time_in_pm_type==1)
                    <td id="vacant_in_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_pm" value="{{date('H:i', strtotime($query->time_in_pm))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                    data-val="time"
                                    data-id="vacant_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="vacant_in_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_pm" value="Vacant" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                    data-val="vacant"
                                    data-id="vacant_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_pm" value="{{date('h:ia', strtotime($query->time_out_pm))}}" readonly></td>
                @elseif($query->time_out_pm!=NULL && $query->time_out_pm_type==1)
                    <td id="vacant_out_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_pm" value="{{date('H:i', strtotime($query->time_out_pm))}}">
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                data-val="time"
                                data-id="vacant_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="vacant_out_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_pm" value="Vacant" readonly>
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_vacant"
                                data-val="vacant"
                                data-id="vacant_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @else
        <tr>
            <td id="vacant_in_am">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_in_am" value="Vacant" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-info-scan change_vacant"
                            data-val="vacant"
                            data-id="vacant_in_am"
                            data-n="time_in_am">
                            <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
            <td id="vacant_out_am">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_out_am" value="Vacant" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-info-scan change_vacant"
                            data-val="vacant"
                            data-id="vacant_out_am"
                            data-n="time_out_am">
                            <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
            <td id="vacant_in_pm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_in_pm" value="Vacant" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-info-scan change_vacant"
                            data-val="vacant"
                            data-id="vacant_in_pm"
                            data-n="time_in_pm">
                            <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
            <td id="vacant_out_pm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_out_pm" value="Vacant" readonly>
                    <div class="input-group-prepend">
                      <button type="button" class="btn btn-info btn-info-scan change_vacant"
                        data-val="vacant"
                        data-id="vacant_out_pm"
                        data-n="time_out_pm">
                        <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
        </tr>
        @endif
    @elseif($time_type==8)
        @if($query!=NULL)
            <tr>
                @if($query->time_in_am!=NULL && $query->time_in_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_am" value="{{date('h:ia', strtotime($query->time_in_am))}}" readonly></td>
                @elseif($query->time_in_am!=NULL && $query->time_in_am_type==1)
                    <td id="suspension_in_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_am" value="{{date('H:i', strtotime($query->time_in_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                    data-val="time"
                                    data-id="suspension_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="suspension_in_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_am" value="Suspension" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                    data-val="suspension"
                                    data-id="suspension_in_am"
                                    data-n="time_in_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_am!=NULL && $query->time_out_am_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_am" value="{{date('h:ia', strtotime($query->time_out_am))}}" readonly></td>
                @elseif($query->time_out_am!=NULL && $query->time_out_am_type==1)
                    <td id="suspension_out_am">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_am" value="{{date('H:i', strtotime($query->time_out_am))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                    data-val="time"
                                    data-id="suspension_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="suspension_out_am">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_am" value="Suspension" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                    data-val="suspension"
                                    data-id="suspension_out_am"
                                    data-n="time_out_am">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_in_pm" value="{{date('h:ia', strtotime($query->time_in_pm))}}" readonly></td>
                @elseif($query->time_in_pm!=NULL && $query->time_in_pm_type==1)
                    <td id="suspension_in_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_in_pm" value="{{date('H:i', strtotime($query->time_in_pm))}}">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                    data-val="time"
                                    data-id="suspension_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="suspension_in_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_in_pm" value="Suspension" readonly>
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                    data-val="suspension"
                                    data-id="suspension_in_pm"
                                    data-n="time_in_pm">
                                    <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
                @if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)
                    <td><input type="text" class="form-control" name="time_out_pm" value="{{date('h:ia', strtotime($query->time_out_pm))}}" readonly></td>
                @elseif($query->time_out_pm!=NULL && $query->time_out_pm_type==1)
                    <td id="suspension_out_pm">
                        <div class="input-group mb-3">
                            <input type="time" class="form-control" name="time_out_pm" value="{{date('H:i', strtotime($query->time_out_pm))}}">
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                data-val="time"
                                data-id="suspension_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @else
                    <td id="suspension_out_pm">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="time_out_pm" value="Suspension" readonly>
                            <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan change_suspension"
                                data-val="suspension"
                                data-id="suspension_out_pm"
                                data-n="time_out_pm">
                                <span class="fa fa-refresh"></span></button>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @else
        <tr>
            <td id="suspension_in_am">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_in_am" value="Suspension" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-info-scan change_suspension"
                            data-val="suspension"
                            data-id="suspension_in_am"
                            data-n="time_in_am">
                            <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
            <td id="suspension_out_am">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_out_am" value="Suspension" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-info-scan change_suspension"
                            data-val="suspension"
                            data-id="suspension_out_am"
                            data-n="time_out_am">
                            <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
            <td id="suspension_in_pm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_in_pm" value="Suspension" readonly>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn-info-scan change_suspension"
                            data-val="suspension"
                            data-id="suspension_in_pm"
                            data-n="time_in_pm">
                            <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
            <td id="suspension_out_pm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="time_out_pm" value="Suspension" readonly>
                    <div class="input-group-prepend">
                      <button type="button" class="btn btn-info btn-info-scan change_suspension"
                        data-val="suspension"
                        data-id="suspension_out_pm"
                        data-n="time_out_pm">
                        <span class="fa fa-refresh"></span></button>
                    </div>
                </div>
            </td>
        </tr>
        @endif
    @endif
</table>
</div>
