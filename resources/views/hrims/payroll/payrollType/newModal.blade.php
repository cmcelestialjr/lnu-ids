
<div class="modal-content" id="listNewModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Payroll Type</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="col-lg-6">
                        <label>Gov't employee?</label>
                        <select class="form-control select2-default" name="gov_service">
                            <option value="all">All</option>
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>With Guidelines?</label>
                        <select class="form-control select2-default" name="w_guideline">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>                        
                    </div>
                    <div class="col-lg-6 wGuidelineDiv hide">
                        <label>With Salary?</label>
                        <select class="form-control select2-default" name="w_salary">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div class="col-lg-6 wGuidelineDiv hide">
                        <label>Column Name of with Salary</label>
                        <input type="text" class="form-control" name="w_salary_name">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv hide">
                        <label>Column Name1</label>
                        <input type="text" class="form-control" name="column_name">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv hide">
                        <label>Column Amount1</label>
                        <input type="number" class="form-control" name="amount">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv hide">
                        <label>Column Name2</label>
                        <input type="text" class="form-control" name="column_name2">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv hide">
                        <label>Column Amount2</label>
                        <input type="number" class="form-control" name="amount2">
                    </div>
                    <div class="col-lg-12 center wGuidelineDiv hide">
                        <label>Rendered Service</label>
                    </div>
                    <div class="col-lg-8 wGuidelineDiv hide">
                        <label>Months to get full payment:</label>
                        <select class="form-control select2-default" name="month_no">
                            <option value="">Please Select..</option>
                            @for($i=0; $i <= 12; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 center wGuidelineDiv hide">
                        <label>Aggregate months of service?
                            <input type="checkbox" class="form-control" name="aggregate"></label>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv hide">
                        <label>Month From</label>
                        <select class="form-control select2-default" name="month_from">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 12; $i++)
                                @php
                                $month = date('m', strtotime(date('Y').'-'.$i.'-01')); 
                                $month_name =  date('F', strtotime(date('Y').'-'.$i.'-01'));
                                @endphp
                                <option value="{{$month}}">{{$month_name}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv hide">
                        <label>Day From</label>
                        <select class="form-control select2-default" name="day_from">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 center wGuidelineDiv hide">
                        <label>Preceding year for From? 
                            <input type="checkbox" class="form-control" name="preceding_year"></label>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv hide">
                        <label> As Of Month</label>                        
                        <select class="form-control select2-default" name="month_as_of">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 12; $i++) 
                                @php
                                $month = date('m', strtotime(date('Y').'-'.$i.'-01')); 
                                $month_name =  date('F', strtotime(date('Y').'-'.$i.'-01'));
                                @endphp
                                <option value="{{$month}}">{{$month_name}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv hide">
                        <label>As Of Day</label>
                        <select class="form-control select2-default" name="day_as_of">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 31; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 center wGuidelineDiv hide">
                        <label>Grant separated before As Of? <input type="checkbox" class="form-control" name="grant_separated"><br></label>
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
