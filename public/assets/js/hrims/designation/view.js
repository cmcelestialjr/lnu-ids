var designationTable = 
'<button class="btn btn-primary btn-primary-scan" id="designationNew" style="float: right">'+
    '<span class="fa fa-plus"></span> New Designation'+
'</button><br><br>'+
'<table id="designationTable" class="table table-bordered table-fixed"'+
        'data-toggle="table"'+
        'data-search="true"'+
        'data-height="470"'+
        'data-buttons-class="primary"'+
        'data-show-export="true"'+
        'data-show-columns-toggle-all="true"'+
        'data-mobile-responsive="true"'+
        'data-pagination="true"'+
        'data-page-size="10"'+
        'data-page-list="[10, 50, 100, All]"'+
        'data-loading-template="loadingTemplate"'+
        'data-export-types="[\'csv\', \'txt\', \'doc\', \'excel\', \'json\', \'sql\']">'+
    '<thead>'+
        '<tr>'+
            '<th data-field="f1" data-sortable="true" data-align="center">#</th>'+
            '<th data-field="f2" data-sortable="true" data-align="center">Name</th>'+
            // '<th data-field="f3" data-sortable="true" data-align="center">Level</th>'+
            '<th data-field="f4" data-sortable="true" data-align="center">Office</th>'+
            '<th data-field="f5" data-sortable="true" data-align="center">Current</th>'+
            '<th data-field="f6" data-sortable="true" data-align="center">Option</th>'+
        '</tr>'+
    '</thead>'+
'</table>'+
'<div id="designationScript"></div>';

$('#designationDiv').html(designationTable);
$('#designationScript').html('');
$(document).off('click', '#designationLink').on('click', '#designationLink', function (e) {
    designationTableList();
    $('#designationScript').html('');
    $('#designationScript').html('<script src="'+base_url+'/assets/js/hrims/designation/new.js"></script>');
});

function designationTableList(){
    var form_data = {
        url_table:base_url+'/hrims/designation/table',
        tid:'designationTable'
    };
    loadTable(form_data);
}