
<div class="modal-content" id="elig-edit-modal">
    <div class="modal-header">
        <span class="fa fa-plus-square"> New</span>
    </div>
    <div class="modal-body card card-primary card-outline">
        <div class="row">
            <div class="col-lg-12">
                <label for="level">Eligibility:</label>check if not in the list?
                <input type="checkbox" id="elig_check">
                <div id="elig_div">
                    <select class="form-control select2-info" id="eligibility">
                        @foreach($eligibilities as $row)
                            @if($query->eligibility_id==$row->id)
                            <option value="{{$row->id}}" selected>{{$row->shorten}} - {{$row->name}}</option>
                            @else
                            <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                            @endif
                        @endforeach
                        <option value="0">Please Select...</option>
                    </select>
                </div>
                <input type="text" class="form-control hide" name="elig_name" id="elig_name" placeholder="Please type new eligibility">

                <input type="text" class="form-control hide" name="elig_shorten" id="elig_shorten" placeholder="Please type new eligibility shorten">
            </div>
            <div class="col-lg-6">
                <label for="rating">Rating:</label>
                <input type="text" class="form-control" name="rating" id="rating" value="{{$query->rating}}">
            </div>
            <div class="col-lg-6">
                <label for="date">Date of Examination:</label>
                <input type="text" class="form-control datepicker" name="date" id="date" value="{{$query->date}}">
            </div>
            <div class="col-lg-6">
                <label for="place">Place of Examination:</label>
                <input type="text" class="form-control" name="place" id="place" value="{{$query->place}}">
            </div>
            <div class="col-lg-6">
                <label for="license_no">License No.:</label>
                <input type="text" class="form-control" name="license_no" id="license_no" value="{{$query->license_no}}">
            </div>
            <div class="col-lg-6">
                <label for="date_validity">Date of Validity:</label>
                <input type="text" class="form-control datepicker" name="date_validity" id="date_validity" value="{{$query->date_validity}}">
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
