
<div class="modal-content" id="elig-new-modal">
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
                            <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" class="form-control elig_new hide" name="elig_name" id="elig_name" placeholder="Please type new eligibility">
                <input type="text" class="form-control elig_new hide" name="elig_shorten" id="elig_shorten" placeholder="Please type new eligibility shorten">
            </div>
            <div class="col-lg-6">
                <label for="rating">Rating:</label>
                <input type="text" class="form-control" name="rating" id="rating">
            </div>
            <div class="col-lg-6">
                <label for="date">Date:</label>
                <input type="text" class="form-control datepicker" name="date" id="date">
            </div>
            <div class="col-lg-6">
                <label for="place">Place:</label>
                <input type="text" class="form-control" name="place" id="place">
            </div>
            <div class="col-lg-6">
                <label for="license_no">License No.:</label>
                <input type="text" class="form-control" name="license_no" id="license_no">
            </div>
            <div class="col-lg-6">
                <label for="date_validity">Date Validity:</label>
                <input type="text" class="form-control datepicker" name="date_validity" id="date_validity">
            </div>
            <div class="col-lg-6">
                <label for="file">File:</label>
                <div class="file-drop-area">
                   <button class="btn btn-primary btn-primary-scan">Choose file</button>
                   &nbsp; <span class="file-message">or drag and drop file here</span>
                   <input class="file-input" type="file" name="file[]" id="file" accept="application/pdf,image/*" multiple>
               </div>
               <div id="file-selected-count"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="submit"><span class="fa fa-save"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
