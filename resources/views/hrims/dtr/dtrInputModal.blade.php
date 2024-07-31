<div class="modal-content" id="dtrInputModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$users->id_no}} - {{$users->lastname}}, {{$users->firstname}} {{$users->extname}} - {{$date_name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" name="day" value="{{$day}}">
                <div class="card card-info card-outline">
                    <div class="card-body table-responsive">
                        @php
                        if($query!=NULL){
                            $time_type = $query->time_type;
                        }else{
                            $time_type = '';
                        }
                        @endphp
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Time Type</label>
                                <select class="form-control select2-primary" name="time_type">
                                    <option value="">Default</option>
                                    @foreach($time_type_ as $row)
                                        @if($time_type==$row->id)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12" id="dtrInputTable">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
        {{-- @if($query!=NULL)
            @if($query->status==NULL)
            <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
            @endif
        @else
            <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
        @endif --}}
    </div>
</div>
<!-- /.modal-content -->
