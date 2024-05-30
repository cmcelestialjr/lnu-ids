<div class="modal-content" id="learn-edit-modal">
    <div class="modal-header">
        <span class="fa fa-edit"> Edit</span>
    </div>
    <div class="modal-body card card-info card-outline">
        <div class="row">
            <div class="col-lg-12">
                <label for="name">Name & Address:</label>
                <input type="text" class="form-control" name="name" id="name" value="{{$query->name}}">
            </div>
            <div class="col-lg-6">
                <label for="date_from">Date From:</label>
                <input type="text" class="form-control datepicker" name="date_from" id="date_from" value="{{$query->date_from}}">
            </div>
            <div class="col-lg-6">
                <label for="date_to">Date To:</label>
                <input type="text" class="form-control datepicker" name="date_to" id="date_to" value="{{$query->date_to}}">
            </div>
            <div class="col-lg-6">
                <label for="hours">No. of Hours:</label>
                <input type="text" class="form-control" name="hours" id="hours" value="{{$query->hours}}">
            </div>
            <div class="col-lg-6">
                <label for="type">Type of LD:</label>check if present?
                <input type="checkbox" id="type_check">
                <div id="type_div">
                    <select class="form-control select2-info" name="type" id="type">
                        @foreach ($types as $row)
                            @if($query->type_id==$row->id)
                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                            @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <input type="text" class="form-control hide" name="type_name" id="type_name">
            </div>
            <div class="col-lg-6">
                <label for="conducted_by">Conducted By:</label>
                <input type="text" class="form-control" name="conducted_by" id="conducted_by" value="{{$query->conducted_by}}">
            </div>
            <div class="col-lg-12">
                <label for="file">File:</label>
                <div class="file-drop-area">
                   <button class="btn btn-primary btn-primary-scan">Choose file</button>
                   &nbsp; <span class="file-message">or drag and drop file here</span>
                   <input class="file-input" type="file" name="file[]" id="files" accept="application/pdf,image/*" multiple>
               </div>
               <div id="file-selected-count"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="submit" data-id="{{$query->id}}"><span class="fa fa-save"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
