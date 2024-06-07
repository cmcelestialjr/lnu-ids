<table id="listTable" class="table table-bordered table-fixed"
    data-toggle="table"
    data-search="true"
    data-height="450"
    data-buttons-class="primary"
    data-show-export="true"
    data-show-columns-toggle-all="true"
    data-mobile-responsive="true"
    data-pagination="true"
    data-page-size="All"
    data-page-list="[All]"
    data-loading-template="loadingTemplate"
    data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
    <thead>
        <tr>
            <th data-field="f1" data-sortable="true" data-align="center">#</th>
            <th data-field="f2" data-align="center">
                <input type="checkbox" class="form-control" id="all" checked>
            </th>
            <th data-field="f3" data-sortable="true" data-align="center">Name</th>
            <th data-field="f4" data-sortable="true" data-align="center">Position</th>
            <th data-field="f5" data-sortable="true" data-align="center">Salary</th>
            @if($payroll->w_salary=='Yes' && $payroll->w_salary_name!=NULL)
            <th data-field="f6" data-sortable="true" data-align="center">{{$payroll->w_salary_name}}</th>
            @endif
            @if($payroll->column_name!=NULL)
            <th data-field="f7" data-sortable="true" data-align="center">{{$payroll->column_name}}</th>
            @endif
            @if($payroll->column_name2!=NULL)
            <th data-field="f8" data-sortable="true" data-align="center">{{$payroll->column_name2}}</th>
            @endif
            <th data-field="f9" data-sortable="true" data-align="center">Gross</th>
            <th data-field="f10" data-sortable="true" data-align="center">Deduction</th>
            <th data-field="f11" data-sortable="true" data-align="center">NetPay</th>
        </tr>
    </thead>
</table>
