<div class="col-lg-12">
    <div class="card card-primary card-outline">
        <div class="card-body">
          <div class="row">
              <div class="col-lg-3">
                  <label>Office Type</label>
                  <select class="form-control select2-div" name="office_type">
                      <option value="0">All</option>
                      @foreach($office_type as $row)
                          <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-lg-12">
                @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                    <button class="btn btn-primary btn-primary-scan new" style="float:right;">
                        <span class="fa fa-plus"></span> New
                    </button>
                    <br>
                @endif
                <br>
                <table id="officeTable" class="table table-bordered table-fixed"
                      data-toggle="table"
                      data-search="true"
                      data-height="600"
                      data-buttons-class="primary"
                      data-show-export="true"
                      data-show-columns-toggle-all="true"
                      data-mobile-responsive="true"
                      data-pagination="true"
                      data-page-size="10"
                      data-page-list="[10, 50, 100, All]"
                      data-loading-template="loadingTemplate"
                      data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center">#</th>
                            <th data-field="f2" data-sortable="true" data-align="center">Office</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Type</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Parent Office</th>
                            {{-- <th data-field="f6" data-sortable="true" data-align="center">No. of Employee</th> --}}
                            @if($user_access_level==1 || $user_access_level==2 || $user_access_level==3)
                                <th data-field="f7" data-sortable="true" data-align="center">Option</th>
                            @endif
                        </tr>
                    </thead>
                </table>
              </div>
          </div>
        </div>
        <div class="card-footer">
          
        </div>
    </div>
</div>