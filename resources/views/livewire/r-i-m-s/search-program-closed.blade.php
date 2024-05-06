<div>
    <div class="input-group">
        <div class="input-group-append">
            <div class="input-group-text"><span class="fa fa-search"></span></div>
        </div>
        <input type="text" class="form-control" wire:model.debounce.200ms="name">
    </div>
    <center><div class="livewire-loader"></div></center>
    <div class="table-responsive livewire-table" style="height:500px">        
        <table class="table table-bordered table-fixed" id="tableClosed">
            <thead>
                <tr>
                    <th>Closed Programs</th>
                </tr>
            </thead>
            <tbody>
                @foreach($query as $row)
                    <tr>
                        <td><button class="btn btn-danger btn-danger-scan programs" style="width: 100%"
                                data-id="{{$row->id}}"
                                data-val="{{$row->status_id}}"
                                data-tx="{{$row->name}} ({{$row->shorten}})">
                                <span class="fa fa-arrow-left"></span> &nbsp;
                                {{$row->name}} ({{$row->shorten}})</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
