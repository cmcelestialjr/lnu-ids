
<div class="modal-content" id="courseUpdateModal">
    <div class="modal-header">
        <h4 class="modal-title">
            
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$query->id}}">
            <div class="col-md-4">
                <label>Course Code</label>
                <input type="text" class="form-control req" name="code" value="{{$query->code}}">
            </div>
            <div class="col-md-8">
                <label>Descriptive Title</label>
                <input type="text" class="form-control req" name="name" value="{{$query->name}}">
            </div>            
            <div class="col-md-3">
                <label>Lab Group</label>
                <select class="form-control select2-div" name="lab_group">
                    <option value="None">None</option>
                    @foreach($lab_group as $row)
                        @if($row->id==$lab_group_course)
                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif                        
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Units</label>
                <input type="number" class="form-control req" name="units" value="{{$query->units}}">
            </div>
            <div class="col-md-3">
                <label>Lab</label>
                <input type="number" class="form-control req" name="lab" value="{{$query->lab}}">
            </div>
            <div class="col-md-3">
                <label>Pay</label>
                <input type="number" class="form-control req" name="pay_units" value="{{$query->pay_units}}">
            </div>
            <div class="col-md-12"></div>
            <div class="col-md-3">
                <label>Type</label>
                <select class="form-control select2-primary" name="course_type" id="specialization_name_select">
                    @foreach($course_type as $row)
                        @if($row->id==$query->course_type_id)
                            <option value="{{$row->id}}" selected>{{$row->name}} - {{$row->shorten}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}} - {{$row->shorten}}</option>
                        @endif 
                    @endforeach
                </select>
            </div>
            @if($query->course_type_id==3)
            <div class="col-md-3" id="specialization_name_div">
            @else
            <div class="col-md-3 hide" id="specialization_name_div">
            @endif
                <label>Specialization Name:</label>
                <input type="text" class="form-control" name="specialization_name" value="{{$query->specialization_name}}">
            </div>
            <div class="col-md-12">
                <br>
                <label>Pre-requisite</label>
                <div class="row">
                    <div class="col-lg-6">
                        <label>Name appear in Pre-requisite</label>
                        <input type="text" class="form-control" name="pre_name" value="{{$query->pre_name}}">
                    </div>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-2">
                        <br><br>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxPrimary1" class="all">
                                <label for="checkboxPrimary1">
                                    Check All
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="courseTablePre">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
