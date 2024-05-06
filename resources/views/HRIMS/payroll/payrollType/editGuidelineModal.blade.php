
<div class="modal-content" id="guidelineEditModal">
    <div class="modal-header">
        <h4><span class="fa fa-edit"></span> Edit Guideline</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">
                        <input type="hidden" name="id" value="{{$query->id}}">
                        <label>With Salary Percent %</label>
                        <input type="number" class="form-control" name="w_salary_percent" value="{{$query->w_salary_percent}}">
                        <label>Amount1</label>
                        <input type="number" class="form-control" name="amount" value="{{$query->amount}}">
                        <label>Percent1 %</label>
                        <input type="number" class="form-control" name="percent" value="{{$query->percent}}">
                        <label>Amount2</label>
                        <input type="number" class="form-control" name="amount2" value="{{$query->amount2}}">
                        <label>Percent2 %</label>
                        <input type="number" class="form-control" name="percent2" value="{{$query->percent2}}">                        
                    </div>
                    <div class="col-lg-12 center">
                        @php
                            $grant_separated = '';
                            $date_as_of = '';
                            if($query->grant_separated==1){
                                $grant_separated = 'checked';
                            }
                            if($query->payroll_type->month_as_of!=NULL && $query->payroll_type->day_as_of!=NULL){
                                $date_as_of = date('F d, Y',strtotime(date('Y').'-'.$query->payroll_type->month_as_of.'-'.$query->payroll_type->day_as_of));
                            }
                        @endphp
                        <label>Grant separated before As Of {{$date_as_of}}?                             
                            <input type="checkbox" class="form-control" name="grant_separated" {{$grant_separated}}></label>
                    </div>
                    <div class="col-lg-12 center">
                        <label>No. of Months</label>
                    </div>
                    <div class="col-lg-6 center">
                        <label>From >=</label>
                        <select class="form-control select2-primary" data-live-search="true" name="from">
                            @for($i=0; $i <= 12; $i++)
                                @if($query->from==$i && $query->from!=NULL)
                                    <option value="{{$i}}" selected>{{$i}}</option>
                                @else
                                    <option value="{{$i}}">{{$i}}</option>
                                @endif                                
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-6 center">
                        <label>< To</label>
                        <select class="form-control select2-primary" data-live-search="true" name="to">
                            @for($i=0; $i <= 12; $i++)
                                @if($query->to==$i && $query->to!=NULL)
                                    <option value="{{$i}}" selected>{{$i}}</option>
                                @else
                                    <option value="{{$i}}">{{$i}}</option>
                                @endif                                
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="btnSaveEditGuideline"><span class="fa fa-save"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
