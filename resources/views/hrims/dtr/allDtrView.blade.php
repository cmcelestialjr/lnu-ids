<div class="modal-content" id="dtrDiv">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$name}} - {{$id_no}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-3">
              <input type="hidden" name="id_no" value="{{$id_no}}">
              <input type="hidden" name="dtr_type" value="0">
              <label>Year</label>
              <select class="form-control select2-default" name="year" id="select-individual-year">
                @for ($i = date('Y'); $i >= 2023; $i--)
                  @if($year==$i)
                    <option value="{{$i}}">{{$i}}</option>
                  @else
                    <option value="{{$i}}">{{$i}}</option>
                  @endif
                @endfor
              </select>
            </div>
            <div class="col-lg-3">
              <label>Month</label>
              <select class="form-control select2-default" name="month" id="select-individual-month">
                  @for($i=1;$i<=12;$i++)
                    @if($month==$i)
                      <option value="{{$i}}" selected>{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                    @else
                      <option value="{{$i}}">{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                    @endif
                  @endfor
              </select>
            </div>
            <div class="col-lg-3">
              <label>Range</label>
              <div class="input-group mb-3">
                <select class="form-control select2-default" name="range">
                  @if($range==1)
                    <option value="1">Whole Month</option>
                    <option value="2">Half Month (1-15)</option>
                  @else
                    <option value="1">Whole Month</option>
                    <option value="2" selected>Half Month (1-15)</option>
                  @endif
                </select>
                <div class="input-group-prepend">
                  <button type="button" class="btn btn-info btn-info-scan" name="submit">
                    <span class="fa fa-check"></span></button>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="card card-info card-outline">
                <div class="card-body row">
                    <div class="col-lg-12" id="previewDiv">

                    </div>
                    <div class="col-lg-12 center" id="body">
                      <section class="hide" id="not-found">
                        <div id="title"><br><br><br>
                        </div>
                        <div class="circles">
                          <p><br>
                           <small>No Data Found!</small>
                           <br><br><br><br>
                          </p>
                          <span class="circle big"></span>
                          <span class="circle med"></span>
                          <span class="circle small"></span>
                        </div>
                      </section>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<script src="{{ asset('assets/js/hrims/dtr/individual.js') }}"></script>
<!-- /.modal-content -->
