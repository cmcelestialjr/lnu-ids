
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New</h4>
    </div>
    <form method="POST" id="olySyNew">
        <div class="modal-body table-responsive">
            <div class="card card-info card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>School Year</label>
                            <select class="form-control select2-default" name="school_year">
                                @for($i=date('Y');$i>=2023;$i--)
                                    <option value="{{$i}}-{{$i+1}}">{{$i}}-{{$i+1}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Period</label>
                            <select class="form-control select2-default" name="period">
                                @foreach($grade_periods as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Month From</label>
                            <select class="form-control select2-default" name="month_from">
                                @for($i=1;$i<=12;$i++)
                                    @php
                                        $month = date('m',strtotime(date('Y').'-'.$i.'-01'));
                                        $month_name = date('F',strtotime(date('Y').'-'.$i.'-01'));
                                    @endphp
                                    <option value="{{$month}}">{{$month_name}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Month To</label>
                            <select class="form-control select2-default" name="month_to">
                                @for($i=1;$i<=12;$i++)
                                    @php
                                        $month = date('m',strtotime(date('Y').'-'.$i.'-01'));
                                        $month_name = date('F',strtotime(date('Y').'-'.$i.'-01'));
                                    @endphp
                                    <option value="{{$month}}">{{$month_name}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
            <button type="button" class="btn btn-success btn-success-scan" name="submit">
                <span class="fa fa-check"></span> Submit</button>
        </div>
    </form>
</div>
<!-- /.modal-content -->
