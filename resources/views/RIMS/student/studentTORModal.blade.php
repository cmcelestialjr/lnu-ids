
<div class="modal-content" id="studentTORModal">
    <div class="modal-header">
        <h4 class="modal-title"> Transcript of Record (TOR)</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-12">
                <label>{{$student->lastname}}, {{$student->firstname}} {{$student->extname}} {{$student->middlename}}</label>
            </div>
            <div class="col-lg-4">
                <select class="form-control select2-primary" name="level">
                    @foreach($query as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                  </select>
            </div>
            <div class="col-lg-8">
                <button class="btn btn-primary btn-primary-scan btn-sm" name="print" style="float:right;">
                    <span class="fa fa-print"></span> Print
                </button>
                <button class="btn btn-info btn-info-scan btn-sm" name="add" style="float:right;">
                    <span class="fa fa-plus"></span> Add Course
                </button>
                
            </div>
            <div class="col-lg-12">
                <br>
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div id="studentTORDiv">

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
<!-- /.modal-content -->
