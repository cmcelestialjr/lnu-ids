<div class="modal-content" id="educBgNewModal">
    <div class="modal-header">
        <h4 class="modal-title"> <span class="fa fa-plus"></span> New</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Level</label>
                        <input type="hidden" name="educ_id" value="{{$id}}">
                        <div class="autocomplete">
                            <select class="form-control select2-info" name="level">
                                @foreach($program_level as $row)
                                    @if($educ->level_id==$row->id)
                                        <option value="{{$row->id}}" data-p="{{$row->program}}" selected>{{$row->name}}</option>
                                    @else
                                        <option value="{{$row->id}}" data-p="{{$row->program}}">{{$row->name}}</option>
                                    @endif                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label>School</label> - <label>not in list? <input type="checkbox" name="check_school" id="check_school"></label>
                        <div id="schoolSearch">
                            <select class="form-control-info schoolSearch" name="school" style="width: 100%">
                                <option value="{{$educ->school_id}}">{{$educ->name}}</option>
                                <option value="">Search School...</option>
                            </select>
                        </div>
                        <div class="hide" id="schoolNewDiv">
                            <input type="text" class="form-control" name="new_school" placeholder="New School Name">
                        </div>
                    </div>
                    <div class="col-lg-12 {{$program_hide}}" id="programsDiv">
                        <label>Program</label> - <label>not in list? <input type="checkbox" name="check_program" id="check_program"></label>                        
                        <div id="programSearch2">
                            <select class="form-control-info programSearch2" name="program" style="width: 100%">
                                @if($educ->program_id)
                                    <option value="{{$educ->program_id}}">{{$educ->program->name}}</option>
                                @endif                                
                                <option value="">Search Program...</option>
                            </select>
                        </div>
                        <div class="hide" id="programNewDiv">
                            <input type="text" class="form-control" name="new_program" placeholder="New Program Name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label>Period From</label>
                        <input type="text" class="form-control datePicker" name="period_from" value="{{date('m/d/Y',strtotime($educ->period_from))}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Period To</label>: 
                        @if($educ->period_to=='present')
                            <label>present? <input type="checkbox" name="period_to_present" checked></label>
                            <input type="text" class="form-control datePicker" name="period_to" readonly>
                        @else
                            <label>present? <input type="checkbox" name="period_to_present"></label>
                            <input type="text" class="form-control datePicker" name="period_to" value="{{date('m/d/Y',strtotime($educ->period_to))}}">
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <label>Unit Earned</label>
                        <input type="text" class="form-control" name="units_earned" >
                    </div>
                    <div class="col-lg-6">
                        <label>Year Graduated</label>
                        <input type="yearpicker" class="form-control yearpicker" name="year_grad" value="{{$educ->year_grad}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Honors Recieved</label>
                        <input type="yearpicker" class="form-control" name="honors" value="{{$educ->honors}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="educBgEditSubmit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
<script src="{{ asset('assets/js/search/school1Search.js') }}"></script>
<script src="{{ asset('assets/js/search/programSearch.js') }}"></script>
<script src="{{ asset('assets/js/sims/information/educ_bg_program.js') }}"></script>
