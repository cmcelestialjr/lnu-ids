
<div class="modal-content" id="educ-edit-modal">
    <div class="modal-header">
        <span class="fa fa-edit"> Edit</span>
    </div>
    <div class="modal-body card card-info card-outline">
        <div class="row">
            <div class="col-lg-12">
                <label for="level">Level:</label>
                <select class="form-control select2-info" id="level">
                    @foreach($levels as $row)
                        @if($query->level_id==$row->id)
                            <option value="{{$row->id}}" data-w="{{$row->program}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}" data-w="{{$row->program}}">{{$row->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6">
                <label for="school">School</label> check if not in the list?
                    <input type="checkbox" id="school_check">
                    <div class="school_div" id="schoolSearch">
                        <select class="form-control select2-info schoolSearch" name="school" id="school">
                            <option value="{{$query->school_id}}" selected>{{$query->name}}</option>
                        </select>
                    </div>
                    <input type="text" class="form-control school_new hide" name="school_name" id="school_name" placeholder="Please type new school">
                    <input type="text" class="form-control school_new hide" name="school_shorten" id="school_shorten" placeholder="Please type new school shorten">
            </div>
            @if($query->level->program=='w')
            <div class="col-lg-6" id="program_div">
            @else
            <div class="col-lg-6 hide" id="program_div">
            @endif
                <label for="program">Program</label> check if not in the list?
                <input type="checkbox" id="program_check">
                <div class="program_div" id="programSearch2">
                    <select class="form-control select2-info programSearch2" name="program" id="program">
                        @if($query->program)
                        <option value="{{$query->program_id}}" selected>{{$query->program->name}}</option>
                        @else
                        <option value="0" selected></option>
                        @endif
                    </select>
                </div>
                <input type="text" class="form-control hide" name="program_name" id="program_name" placeholder="Please type new program">
            </div>
            <div class="col-lg-6">
                <label for="period_from">Period From:</label>
                <input type="text" class="form-control datepicker" name="period_from" id="period_from" value="{{$query->period_from}}">
            </div>
            <div class="col-lg-6">
                <label for="period_to">Period to:</label> check if present?
                @if($query->period_to==NULL)
                <input type="checkbox" id="present_check" checked>
                <input type="text" class="form-control datepicker" name="period_to" id="period_to" readonly>
                @else
                <input type="checkbox" id="present_check">
                <input type="text" class="form-control datepicker" name="period_to" id="period_to" value="{{$query->period_to}}">
                @endif
            </div>
            <div class="col-lg-6">
                <label for="units_earned">Units Earned:</label>
                <input type="text" class="form-control" name="units_earned" id="units_earned" value="{{$query->units_earned}}">
            </div>
            <div class="col-lg-6">
                <label for="year_grad">Year Graduated:</label>
                <input type="text" class="form-control" name="year_grad" id="year_grad" value="{{$query->year_grad}}">
            </div>
            <div class="col-lg-6">
                <label for="honors">Honors:</label>
                <input type="text" class="form-control" name="honors" id="honors" value="{{$query->honors}}">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="submit" data-id="{{$query->id}}"><span class="fa fa-save"></span> Save</button>
    </div>
</div>
<script src="{{ asset('assets/js/search/programSearch.js') }}"></script>
<script src="{{ asset('assets/js/search/school1Search.js') }}"></script>
<!-- /.modal-content -->
