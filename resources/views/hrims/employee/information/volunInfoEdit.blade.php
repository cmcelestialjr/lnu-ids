<div class="modal-content" id="volun-edit-modal">
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
                <label for="date_to">Date To:</label> check if present?
                @if($query->date_to)
                <input type="checkbox" id="present_check">
                <input type="text" class="form-control datepicker" name="date_to" id="date_to" value="{{$query->date_to}}">
                @else
                <input type="checkbox" id="present_check" checked>
                <input type="text" class="form-control datepicker" name="date_to" id="date_to" value="{{$query->date_to}}" readonly>
                @endif
            </div>
            <div class="col-lg-6">
                <label for="hours">No. of Hours:</label>
                <input type="text" class="form-control" name="hours" id="hours" value="{{$query->hours}}">
            </div>
            <div class="col-lg-6">
                <label for="position">Nature of Work:</label>
                <input type="text" class="form-control" name="position" id="position" value="{{$query->position}}">
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
