
<div class="modal-content" id="signatoryUpdate">
    <div class="modal-header">
        <h4><span class="fa fa-edit"></span> Signatory</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12"><br>
                <label>Name</label>
                <div id="employeeSearch">
                    <select class="form-control select2 employeeSearch" name="signatory" style="width: 100%">
                      <option value=""></option>
                    </select>
                </div>
                <br>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn btn-success btn-success-scan" name="submit" data-id="{{$id}}">
            <span class="fa fa-save"></span> Submit
        </button>
    </div>
</div>
<!-- /.modal-content -->
<script src="{{ asset('assets/js/search/employee.js') }}"></script>
