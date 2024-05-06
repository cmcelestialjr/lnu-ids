
<div class="modal-content" id="programsDiv">
    <div class="modal-header">
        <h4 class="modal-title">{{$query->year_from}} - {{$query->year_to}} ({{$query->grade_period->name}}) 
            <br> Programs</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary btn-primary-scan" name="submit" style="width: 100%">
                    <span class="fa fa-check"></span> Submit</button>                
            </div>
            <div class="col-lg-12">
                <br>
                <select class="form-control select2-default" name="departments">
                    <option value="UN">UNDERGRAD SCHOOL</option>
                    <option value="GS">GRADUATE SCHOOL</option>
                </select>
                <br>
            </div>
            <div class="col-lg-6">
                <div class="card card-success card-outline">
                    <div class="card-body">                        
                        <div wire:ignore>
                            <livewire:r-i-m-s.search-program-open />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-danger card-outline">
                    <div class="card-body">
                        <div wire:ignore>
                            <livewire:r-i-m-s.search-program-closed />
                        </div>                        
                    </div>
                </div>           
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        
    </div>
</div>
<!-- /.modal-content -->
