
<div class="modal-content" id="listUpdateModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Update Payroll Type</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">
                        <input type="hidden" name="id" value="{{$query->id}}">
                    </div>
                    <div class="col-lg-6">
                        <label>For Gov't employee?</label>
                        <select class="form-control select2-default" name="gov_service">
                            @if($query->gov_service=='Y')
                                <option value="all">All</option>
                                <option value="Y" selected>Yes</option>
                                <option value="N">No</option>
                            @elseif($query->gov_service=='N')
                                <option value="all">All</option>
                                <option value="Y">Yes</option>
                                <option value="N" selected>No</option>
                            @else
                                <option value="all" selected>All</option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            @endif                            
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>With Guidelines?</label>
                        <select class="form-control select2-default" name="w_guideline">
                            @if($query->w_guideline=='No')
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                            @else
                            <option value="No">No</option>
                            <option value="Yes" selected>Yes</option>
                            @endif
                        </select>
                    </div>
                    @php
                    $hide_div = '';
                    if($query->w_guideline=='No'){
                        $hide_div = 'hide';
                    }
                    @endphp
                    <div class="col-lg-6 wGuidelineDiv {{$hide_div}}">
                        <label>With Salary?</label>
                        <select class="form-control select2-default" name="w_salary">
                            @if($query->w_salary=='No')
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                            @else
                            <option value="No">No</option>
                            <option value="Yes" selected>Yes</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-6 wGuidelineDiv {{$hide_div}}">
                        <label>Column Name of with Salary</label>
                        <input type="text" class="form-control" name="w_salary_name" value="{{$query->w_salary_name}}">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv {{$hide_div}}">
                        <label>Column Name1</label>
                        <input type="text" class="form-control" name="column_name" value="{{$query->column_name}}">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv {{$hide_div}}">
                        <label>Column Amount1</label>
                        <input type="number" class="form-control" name="amount" value="{{$query->amount}}">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv {{$hide_div}}">
                        <label>Column Name2</label>
                        <input type="text" class="form-control" name="column_name2" value="{{$query->column_name2}}">
                    </div>
                    <div class="col-lg-6 wGuidelineDiv {{$hide_div}}">
                        <label>Column Amount2</label>
                        <input type="number" class="form-control" name="amount2" value="{{$query->amount2}}">
                    </div>
                    <div class="col-lg-12 center wGuidelineDiv {{$hide_div}}">
                        <label>Rendered Service</label>
                    </div>                    
                    <div class="col-lg-8 wGuidelineDiv {{$hide_div}}">
                        <label>Months to get full payment:</label>
                        <select class="form-control select2-default" name="month_no">
                            <option value="">Please Select..</option>
                            @for($i=0; $i <= 12; $i++)
                                @if($i==$query->month_no)
                                    <option value="{{$i}}" selected>{{$i}}</option>
                                @else
                                    <option value="{{$i}}">{{$i}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 center wGuidelineDiv {{$hide_div}}">
                        <label>Aggregate months of service?
                            @php
                                $aggregate = '';
                                if($query->aggregate==1){
                                    $aggregate = 'checked';
                                }
                            @endphp
                            <input type="checkbox" class="form-control" name="aggregate" {{$aggregate}}></label>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv {{$hide_div}}">
                        <label>Month From</label>
                        <select class="form-control select2-default" name="month_from">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 12; $i++)
                                @php
                                $month = date('m', strtotime(date('Y').'-'.$i.'-01')); 
                                $month_name =  date('F', strtotime(date('Y').'-'.$i.'-01'));
                                @endphp
                                @if($query->month_from==$month)
                                    <option value="{{$month}}" selected>{{$month_name}}</option>
                                @else
                                    <option value="{{$month}}">{{$month_name}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv {{$hide_div}}">
                        <label>Day From</label>
                        <select class="form-control select2-default" name="day_from">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 31; $i++)
                                @if($i==$query->day_from)
                                    <option value="{{$i}}" selected>{{$i}}</option>
                                @else
                                    <option value="{{$i}}">{{$i}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 center wGuidelineDiv {{$hide_div}}">
                        <label>Preceding year for From? 
                            @php
                                $preceding_year = '';
                                if($query->preceding_year==1){
                                    $preceding_year = 'checked';
                                }
                            @endphp
                            <input type="checkbox" class="form-control" name="preceding_year" {{$preceding_year}}></label>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv {{$hide_div}}">
                        <label> As Of Month</label>                        
                        <select class="form-control select2-default" name="month_as_of">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 12; $i++) 
                                @php
                                $month = date('m', strtotime(date('Y').'-'.$i.'-01')); 
                                $month_name =  date('F', strtotime(date('Y').'-'.$i.'-01'));
                                @endphp
                                @if($query->month_as_of==$month)
                                    <option value="{{$month}}" selected>{{$month_name}}</option>
                                @else
                                    <option value="{{$month}}">{{$month_name}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 wGuidelineDiv {{$hide_div}}">
                        <label>As Of Day</label>
                        <select class="form-control select2-default" name="day_as_of">
                            <option value="">Please Select..</option>
                            @for($i=1; $i <= 31; $i++)
                                @if($i==$query->day_as_of)
                                    <option value="{{$i}}" selected>{{$i}}</option>
                                @else
                                    <option value="{{$i}}">{{$i}}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-4 center wGuidelineDiv {{$hide_div}}">
                        <label>Grant separated before As Of? 
                            @php
                                $grant_separated = '';
                                if($query->grant_separated==1){
                                    $grant_separated = 'checked';
                                }
                            @endphp
                            <input type="checkbox" class="form-control" name="grant_separated" {{$grant_separated}}><br></label>
                    </div>
                    <div class="col-lg-12"><br>
                        <button type="button" class="btn btn-success btn-success-scan" name="submit" style="width: 100%;"><span class="fa fa-save"></span> Submit</button>
                    </div>
                    <div class="col-lg-12 wGuidelineDiv {{$hide_div}}"><br>
                        <label>Rendered Service Guidelines for Pro Rated</label>
                        <button class="btn btn-primary btn-info-scan newGuideline" style="float:right">New Guideline</button>
                        <div class="hide" id="tableGuideline1"></div>
                        <div id="tableGuideline">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Title</th>
                                        <th rowspan="2">W/ Salary %</th>
                                        <th rowspan="2">Amount1</th>
                                        <th rowspan="2">% Percent1</th>
                                        <th rowspan="2">Amount2</th>
                                        <th rowspan="2">% Percent2</th>
                                        <th colspan="2">No. of Months</th>
                                        <th rowspan="2">Option</th>
                                    </tr>
                                    <tr>
                                        <th>From >=</th>
                                        <th>< To</th>
                                    </tr>
                                </thead>
                                <tbody class="center">
                                    @if($query->guideline)
                                        @php
                                        $x = 1;
                                        @endphp
                                        @foreach($query->guideline as $row)
                                            <tr>
                                                <td>{{$x}}</td>
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->w_salary_percent}}</td>
                                                <td>{{$row->amount}}</td>
                                                <td>{{$row->percent}}</td>
                                                <td>{{$row->amount2}}</td>
                                                <td>{{$row->percent2}}</td>
                                                <td>{{$row->from}}</td>
                                                <td>{{$row->to}}</td>
                                                <td>
                                                    <button class="btn btn-info btn-info-scan btn-xs editGuideline"
                                                        data-id="{{$row->id}}">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                    <button class="btn btn-danger btn-danger-scan btn-xs deleteGuideline"
                                                        data-id="{{$row->id}}">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                            @php
                                            $x++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
    </div>
</div>
<script src="{{ asset('assets/js/hrims/payroll/payrollType/guideline.js') }}"></script>
<!-- /.modal-content -->
