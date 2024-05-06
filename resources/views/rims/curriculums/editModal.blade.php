
<div class="modal-content" id="editModal">
    <div class="modal-header">
        <h4 class="modal-title">
            <span class="fa fa-edit"></span> {{$curriculum->curriculum->programs->shorten}}-{{$curriculum->curriculum->programs->name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 center">
                        <label>Curriculum Details</label>
                        <input type="hidden" name="id" value="{{$curriculum->curriculum_id}}" data-x="{{$x}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{$curriculum->curriculum->name}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Year From</label>
                        <input type="text" class="form-control yearpicker" name="year_from" value="{{$curriculum->curriculum->year_from}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Year To</label>
                        <input type="text" class="form-control yearpicker" name="year_to" value="{{$curriculum->curriculum->year_to}}">
                    </div>
                    <div class="col-lg-12">
                        <label>Status</label>
                        <select class="form-control select2-default" name="status">
                            @foreach($statuses as $row)
                                @if($curriculum->status_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks">{{$curriculum->curriculum->remarks}}</textarea>
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
