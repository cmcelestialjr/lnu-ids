<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item informationEditDiv" data-id="personalInfoEdit">
          <a class="nav-link" data-toggle="pill" href="#personalInfo" role="tab" aria-selected="true">Personal Info</a>
        </li>
        <li class="nav-item informationEditDiv" data-id="educationalBgEdit">
          <a class="nav-link active" data-toggle="pill" href="#educBg" role="tab" aria-selected="false">Educational Background</a>
        </li>
        <li class="nav-item informationEditDiv" data-id="familyBgEdit">
            <a class="nav-link" data-toggle="pill" href="#famBg" role="tab" aria-selected="false">Family Background</a>
          </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="educBg" role="tabpanel">
          <div class="row">
            <div class="col-lg-12">              
              <button class="btn btn-primary btn-primary-scan" id="new" style="float:right"><span class="fa fa-plus"></span> New Education</button>
              <br><br>
              <table class="table">
                @foreach($education_level as $row)
                <tr>
                    <td>
                        <label>{{$row->name}}</label>
                        @if($row->education_bg)
                            <div class="card card-info card-outline">
                                <div class="card-body">                                  
                                  @foreach($row->education_bg as $subRow)
                                  <div class="card card-default card-outline">
                                    <table class="table">
                                      <tr>
                                        <td style="width: 80%">{{$subRow->name}}</td>
                                        <td style="width: 20%" rowspan="2">
                                          <button class="btn btn-danger btn-danger-scan delete" data-id="{{$subRow->id}}" style="float:right"><span class="fa fa-trash"></span> Delete</button>
                                          <button class="btn btn-info btn-info-scan edit" data-id="{{$subRow->id}}" style="float:right"><span class="fa fa-edit"></span> Edit</button>                                        
                                        </td>
                                      </tr>
                                      @if($subRow->program)
                                        <tr>
                                          <td colspan="2">{{$subRow->program->name}}</td>
                                        </tr>
                                      @endif
                                      <tr>
                                        <td>{{date('M d, Y',strtotime($subRow->period_from))}} -
                                            @if($subRow->period_to=='present')
                                              {{$subRow->period_to}}
                                            @else
                                              {{date('M d, Y',strtotime($subRow->period_to))}}
                                            @endif
                                            
                                        </td>
                                      </tr>
                                      @if($subRow->year_grad)
                                      <tr>
                                        <td colspan="2">Year Graduated: {{$subRow->year_grad}}</td>
                                      </tr>                                    
                                      @endif
                                      @if($subRow->honors)
                                      <tr>
                                        <td colspan="2">Honors: {{$subRow->honors}}</td>
                                      </tr>                                    
                                      @endif
                                      </tr>
                                    </table>
                                  </div>
                                  @endforeach
                                  
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<script src="{{ asset('assets/js/sims/information/educ_bg.js') }}"></script>