
<div class="modal-content" id="newModal">
    <div class="modal-header">
        <h4 class="modal-title">
            <span class="fa fa-plus"> New Curriculum</span>
        </h4>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Level</label>
                        <select class="form-control select2-default" name="level">
                            @foreach($levels as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Program</label>
                        <select class="form-control select2-default" name="program">
                            @foreach($programs as $row)
                                <option value="{{$row->id}}">{{$row->shorten}}-{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 center">
                        <label>Curriculum Details</label>
                    </div>
                    <div class="col-lg-12">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="col-lg-12">
                        <label>Year From</label>
                        <input type="text" class="form-control yearpicker" name="year_from">
                    </div>
                    <div class="col-lg-12">
                        <label>Year To</label>
                        <input type="text" class="form-control yearpicker" name="year_to">
                    </div>
                    <div class="col-lg-12">
                        <label>Status</label>
                        <select class="form-control select2-default" name="status">
                            @foreach($statuses as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            <span class="fa fa-times"></span> Close
        </button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit">
            <span class="fa fa-check"></span> Submit
        </button>
    </div>
</div>
