
<div class="modal-content" id="guidelineNewModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Guideline</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name">
                        <label>With Salary Percent %</label>
                        <input type="number" class="form-control" name="w_salary_percent">
                        <label>Amount1</label>
                        <input type="number" class="form-control" name="amount">
                        <label>Percent1 %</label>
                        <input type="number" class="form-control" name="percent">
                        <label>Amount2</label>
                        <input type="number" class="form-control" name="amount2">
                        <label>Percent2 %</label>
                        <input type="number" class="form-control" name="percent2">
                    </div>
                    <div class="col-lg-12 center">
                        @php
                            $grant_separated = '';
                            $date_as_of = '';
                            if($query->grant_separated==1){
                                $grant_separated = 'checked';
                            }
                            if($query->month_as_of!=NULL && $query->day_as_of!=NULL){
                                $date_as_of = date('F d, Y',strtotime(date('Y').'-'.$query->month_as_of.'-'.$query->day_as_of));
                            }
                        @endphp
                        <label>Grant separated before As Of {{$date_as_of}}?                             
                            <input type="checkbox" class="form-control" name="grant_separated1" {{$grant_separated}}></label>
                    </div>
                    <div class="col-lg-12 center">
                        <label>No. of Months</label>
                    </div>
                    <div class="col-lg-6 center">
                        <label>From >=</label>
                        <select class="form-control select2-primary" name="from">
                            @for($i=0; $i <= 12; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-6 center">
                        <label>< To</label>
                        <select class="form-control select2-primary" name="to">
                            @for($i=0; $i <= 12; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="btnSaveNewGuideline"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
