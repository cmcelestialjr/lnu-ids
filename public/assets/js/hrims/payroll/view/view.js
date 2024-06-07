listTable();
$(document).off('click', '#payrollView button[name="submit"]').on('click', '#payrollView button[name="submit"]', function (e) {
    listTable();
});
$(document).off('change', '#payrollView select[name="by"]').on('change', '#payrollView select[name="by"]', function (e) {
    var val = $(this).val();
    $('#payrollView #monthDiv').removeClass('hide');
    if(val=='year'){
        $('#payrollView #monthDiv').addClass('hide');
    }
});
$(document).off('click', '#payrollView .delete').on('click', '#payrollView .delete', function (e) {
    deletePayroll($(this));
});
$(document).off('click', '#deletePayrollSubmit').on('click', '#deletePayrollSubmit', function (e) {
    deletePayrollSubmit($(this));
});
function listTable(){
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
function deletePayroll(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/payroll/view/delete';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function deletePayrollSubmit(thisBtn){
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/deleteSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                $('#deletePayroll'+id).closest('tr').remove();
                $('#modal-default').modal('hide');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
            }
            setTimeout(function() {
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
