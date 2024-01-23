
<div class="modal-content" id="listUpdateModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Update Allowance</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">
                        <input type="hidden" name="id" value="{{$query->id}}">
                        <label>Amount</label>
                        <input type="text" class="form-control" name="amount" value="{{$query->amount}}">
                        <label>Monthly?</label>
                        <select class="form-control select2-default" name="monthly">
                            @if($query->monthly=='Yes')
                                <option value="Yes" selected>Yes</option>
                                <option value="No">No</option>
                            @else
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                            @endif                            
                        </select>
                        <label>Employment Status<span class="text-require">*</span></label>
                        <div id="emp_stat_select">
                            <select class="form-control select2-default" name="emp_stat[]" multiple>
                                @foreach($emp_stat as $row)
                                    @if(isset($query->emp_stat))
                                        @foreach($query->emp_stat as $stat)
                                            @php
                                                $emp_stat_array[] = $stat->emp_stat_id;
                                            @endphp
                                        @endforeach
                                        @if(in_array($row->id, $emp_stat_array))
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @else
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <label>Payroll Type<span class="text-require">*</span></label>
                        <div id="payroll_type_select">
                            <select class="form-control select2-default" name="payroll_type[]" multiple>
                                @foreach($payroll_type as $row)
                                    @if(isset($query->payroll_type))
                                        @foreach($query->payroll_type as $type)
                                            @php
                                                $payroll_type_array[] = $type->payroll_type_id;
                                            @endphp
                                        @endforeach
                                        @if(in_array($row->id, $payroll_type_array))
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @else
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
