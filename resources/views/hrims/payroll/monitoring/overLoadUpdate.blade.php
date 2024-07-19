
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-edit"></span> Update</h4>
    </div>
    <form method="POST" id="olUpdate">
        <div class="modal-body table-responsive">
            <div class="card card-info card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>{{$name}}</label>
                        </div>
                        <div class="col-md-12">
                            <label>Type</label>
                            <select class="form-control select2-default" name="type">
                                @foreach($pt_options as $row)
                                    @if($option_id)
                                        @if($option_id==$row->id)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @endif
                                    @else
                                        <option value="">Please select...</option>
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 @if($option_id!=4 || $nstp_id==NULL) hide @endif" id="nstpDiv">
                            <label>NSTP</label>
                            <select class="form-control select2-default" name="nstp">
                                <option value="">Please select...</option>
                                @foreach($nstp_options as $row)
                                    @if($nstp_id==$row->id)
                                        <option value="{{$row->id}}" selected>{{$row->shorten}}</option>
                                    @else
                                        <option value="{{$row->id}}">{{$row->shorten}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Fund Source</label>
                            <div id="fundsourceDiv">
                                <select class="form-control select2-default" name="fund_source">
                                    <option value="">Please select...</option>
                                    @foreach($fund_sources as $row)
                                        @if($fund_source_id==$row->id)
                                            <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Fund Service</label>
                            <div id="fundserviceDiv">
                                <select class="form-control select2-default" name="fund_service">
                                    <option value="">None.</option>
                                    @foreach($fund_services as $row)
                                        @if($fund_services_id==$row->id)
                                            <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Department</label>
                            <div id="departmentDiv">
                                <select class="form-control select2-default" name="department">
                                    <option value="">Please select...</option>
                                    @foreach($departments as $row)
                                        @if($office_id==$row->office_id)
                                            <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Rate</label>
                            <input type="number" class="form-control" name="rate" value="{{$rate}}">
                        </div>
                        <div class="col-md-12">
                            <label>Units</label>
                            <input type="number" class="form-control" name="units" value="{{$units}}">
                        </div>
                        <div class="col-md-12">
                            <label>Total Hours</label>
                            <input type="number" class="form-control" name="total_hours" value="{{$total_hours}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
            <button type="button" class="btn btn-success btn-success-scan" name="submit"
                data-id="{{$id}}"
                data-w="{{$work_id}}">
                <span class="fa fa-check"></span> Submit</button>
        </div>
    </form>
</div>
<!-- /.modal-content -->
