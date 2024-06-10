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
$(document).off('click', '#payrollView .bank').on('click', '#payrollView .bank', function (e) {
    bankPayroll($(this));
});
$(document).off('click', '#bank #submit').on('click', '#bank #submit', function (e) {
    bankSubmit($(this));
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
function bankPayroll(thisBtn){
    var id = thisBtn.data('id');
    var x = thisBtn.data('x');
    var url = base_url+'/hrims/payroll/view/bank';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        x:x
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
function bankSubmit(thisBtn){
    var id = thisBtn.data('id');
    var x = thisBtn.data('x');
    var date_1 = $('#bank #date_1').val();
    var time_1 = $('#bank #time_1').val();
    var date_2 = $('#bank #date_2').val();
    var time_2 = $('#bank #time_2').val();
    var date_3 = $('#bank #date_3').val();
    var time_3 = $('#bank #time_3').val();

    var form_data = {
        id:id,
        x:x,
        date_1:date_1,
        time_1:time_1,
        date_2:date_2,
        time_2:time_2,
        date_3:date_3,
        time_3:time_3
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/bankSubmit',
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
                $('#bank'+x).html(data.btn)
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
