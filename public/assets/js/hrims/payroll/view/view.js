list_table();
$(document).off('click', '#payrollView button[name="submit"]').on('click', '#payrollView button[name="submit"]', function (e) {
    list_table();
});
$(document).off('change', '#payrollView select[name="by"]').on('change', '#payrollView select[name="by"]', function (e) {
    var val = $(this).val();
    $('#payrollView #monthDiv').removeClass('hide');
    if(val=='year'){
        $('#payrollView #monthDiv').addClass('hide');
    }
});

function list_table(){
    var thisBtn = $('#payrollView button[name="submit"]');
    var payroll_type = $('#payrollView select[name="payroll_type"] option:selected').val();
    var by = $('#payrollView select[name="by"] option:selected').val();
    var year = $('#payrollView select[name="year"] option:selected').val();
    var month = $('#payrollView select[name="month"] option:selected').val();
    var type = $('#payrollView select[name="type"] option:selected').val();

    var form_data = {
        url_table:base_url+'/hrims/payroll/view/table',
        tid:'listTable',
        payroll_type:payroll_type,
        by:by,
        year:year,
        month:month,
        type:type
    };
    loadTablewLoader(form_data,thisBtn);
}