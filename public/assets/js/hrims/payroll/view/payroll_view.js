var url = window.location.href;
var url_parts = url.split("/");
var payroll_id = url_parts[url_parts.length-2];
var code = url.substring(url.lastIndexOf('/') + 1).replace('?', '');
list_table();
employeePayrollSearch(payroll_id,code);
$(document).off('click', 'button[name="generate"]').on('click', 'button[name="generate"]', function (e) {
    var thisBtn = $(this);
    generatePayroll(thisBtn);
});
$(document).off('click', '.deductionModal').on('click', '.deductionModal', function (e) {
    var thisBtn = $(this);
    deductionModal(thisBtn);
});
$(document).off('click', 'button[name="addEmployeeDisplay"]').on('click', 'button[name="addEmployeeDisplay"]', function (e) {
    var thisBtn = $(this);
    $(this).addClass('hide');
    $('#addEmployeeDiv').removeClass('hide');
});
$(document).off('click', 'button[name="addEmployeeDivHide"]').on('click', 'button[name="addEmployeeDivHide"]', function (e) {
    var thisBtn = $(this);
    $('button[name="addEmployeeDisplay"]').removeClass('hide');
    $('#addEmployeeDiv').addClass('hide');
});
$(document).off('click', 'button[name="addEmployeeSubmit"]').on('click', 'button[name="addEmployeeSubmit"]', function (e) {
    var thisBtn = $(this);
    addEmployeeSubmit(thisBtn);
});
$(document).off('click', '.removeEmployeeModal').on('click', '.removeEmployeeModal', function (e) {
    var thisBtn = $(this);
    removeEmployeeModal(thisBtn);
});
$(document).off('click', '#removeEmployeeModal button[name="submit"]').on('click', '#removeEmployeeModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    removeEmployeeModalSubmit(thisBtn);
});
$(document).off('click', '#deductionModalDiv #allowanceLi').on('click', '#deductionModalDiv #allowanceLi', function (e) {
    var thisBtn = $(this);
    deductionModal(thisBtn);
});
$(document).off('blur', '#deductionModalDiv .input').on('blur', '#deductionModalDiv .input', function (e) {
    var thisBtn = $(this);
    deductionModalInput(thisBtn);
});
$(document).off('click', '#deductionModalDiv #allowanceLi').on('click', '#deductionModalDiv #allowanceLi', function (e) {
    var thisBtn = $(this);
    allowanceModalTable(thisBtn);
});
$(document).off('click', '#deductionModalDiv .allowance').on('click', '#deductionModalDiv .allowance', function (e) {
    var thisBtn = $(this);
    allowanceModalCheck(thisBtn);
});
$(document).off('blur', '#deductionModalDiv .lwopInput').on('blur', '#deductionModalDiv .lwopInput', function (e) {
    var thisBtn = $(this);
    lwopModalInput(thisBtn);
});
$(document).off('input', '#deductionModalDiv .lwopInput').on('input', '#deductionModalDiv .lwopInput', function (e) {
    var thisBtn = $(this);
    lwopModalInputDisplay(thisBtn);
});
$(document).off('blur', '#deductionModalDiv .lwopInput').on('blur', '#deductionModalDiv .lwopInput', function (e) {
    var thisBtn = $(this);
    lwopModalInput(thisBtn);
});
$(document).off('change', '#deductionModalDiv select[name="salary"]').on('change', '#deductionModalDiv select[name="salary"]', function (e) {
    var thisBtn = $(this);
    salaryChange(thisBtn);
});
$(document).off('blur', '.month_input').on('blur', '.month_input', function (e) {
    var thisBtn = $(this);
    monthInput(thisBtn);
});
$(document).off('click', '#payrollPrintModal').on('click', '#payrollPrintModal', function (e) {
    var thisBtn = $(this);
    payrollPrintModal(thisBtn);
});
function list_table(){

    var form_data = {
        url_table:base_url+'/hrims/payroll/view/payroll_table',
        tid:'listTable',
        payroll_id:payroll_id,
        code:code
    };
    loadTable(form_data);
}
function allowanceModalTable(){
    var id = $('#deductionModalDiv input[name="id"]').val();
    var form_data = {
        url_table:base_url+'/hrims/payroll/view/allowanceModalTable',
        tid:'allowanceModalTable',
        id:id
    };
    loadTable(form_data);
}
function removeEmployeeModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/payroll/view/removeEmployeeModal';
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
function deductionModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/payroll/view/deductionModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/payroll/view/deductionModalTable',
        tid:'deductionModalTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function payrollPrintModal(thisBtn){
    var id = $('#payroll_id').val();
    var url = base_url+'/hrims/payroll/view/payrollPrintModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
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
function deductionModalInput(thisBtn){
    var id = $('#deductionModalDiv input[name="id"]').val();
    var did = thisBtn.data('did');
    var amount = thisBtn.val();
    var form_data = {
        id:id,
        did:did,
        amount:amount
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/deductionModalInput',
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
                updateValues(id,data);
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
function allowanceModalCheck(thisBtn){
    var id = $('#deductionModalDiv input[name="id"]').val();
    var aid = thisBtn.data('id');
    if(thisBtn.is(':checked')){
        var check = 'yes';
    }else{
        var check = 'no';
    }
    var form_data = {
        id:id,
        aid:aid,
        check:check
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/allowanceModalCheck',
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
                updateValues(id,data);
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
function lwopModalInput(thisBtn){
    var id = $('#deductionModalDiv input[name="id"]').val();
    var n = thisBtn.data('n');
    var val = thisBtn.val();
    var form_data = {
        id:id,
        n:n,
        val:val
    };
    if(n=='day_accu' && val>22){
        thisBtn.val(22);
    }
    $.ajax({
        url: base_url+'/hrims/payroll/view/lwopModalInput',
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
                if(data.lwop!=null){
                    if(data.lwop.gov=='N'){
                        $('#deductionModalDiv input[name="lwop_total"]').val(data.lwop.lwop);
                    }
                }
                updateValues(id,data);
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
function monthInput(thisBtn){
    var id = thisBtn.data('id');
    var t = thisBtn.data('t');
    var val = thisBtn.val();

    var form_data = {
        id:id,
        t:t,
        val:val
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/monthInput',
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
                $('#hours_'+data.values.list_id).html(data.values.hours);
                updateValues1(data.values.list_id,data);
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
function salaryChange(thisBtn){
    var id = $('#deductionModalDiv input[name="id"]').val();
    var val = thisBtn.val();

    var form_data = {
        id:id,
        val:val
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/salaryChange',
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
                updateValues(data.values.list_id,data);
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
function addEmployeeSubmit(thisBtn){
    var val = $('.employeePayrollSearch option:selected').val();
    if(val==''){
        toastr.error('Please select an employee!');
    }else{
        var form_data = {
            id:payroll_id,
            code:code,
            val:val
        };
        $.ajax({
            url: base_url+'/hrims/payroll/view/addEmployeeSubmit',
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
                    $('.employeePayrollSearch').empty();
                    list_table();
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
}
function removeEmployeeModalSubmit(thisBtn){
    var id = $('#removeEmployeeModal input[name="employee_id"]').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/removeEmployeeModalSubmit',
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
                if(data.check==0){
                    window.location.href = base_url+'/ids/hrims/payroll_view/s';
                }else{
                    $('#modal-default').modal('hide');
                    list_table();
                }
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
function generatePayroll(thisBtn){
    var form_data = {
        id:payroll_id,
        code:code
    };
    $.ajax({
        url: base_url+'/hrims/payroll/view/generatePayroll',
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
                location.reload();
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
function updateValues(id,data){
    if(data.values!=null){
        $('#deductionModalDiv input[name="amount_base"]').val(data.values.amount_base);
        $('#deductionModalDiv input[name="column_amount"]').val(data.values.column_amount);
        $('#deductionModalDiv input[name="column_amount2"]').val(data.values.column_amount2);
        $('#deductionModalDiv input[name="earned"]').val(data.values.earned);
        $('#deductionModalDiv input[name="allowance"]').val(data.values.allowance);
        $('#deductionModalDiv input[name="deductions"]').val(data.values.deduction);
        $('#deductionModalDiv input[name="netpay"]').val(data.values.netpay);
        updateValues1(id,data);
    }
}
function updateValues1(id,data){
    if(data.values!=null){
        $('#salary_'+id).html(data.values.salary);
        $('#amount_base_'+id).html(data.values.amount_base);
        $('#column_amount_'+id).html(data.values.column_amount);
        $('#column_amount2_'+id).html(data.values.column_amount2);
        $('#earned_'+id).html(data.values.earned);
        $('#deduction_'+id).html(data.values.deduction);
        $('#netpay_'+id).html(data.values.netpay);
        $('#lwop_'+id).html(data.values.lwop);
    }
}
function lwopModalInputDisplay(thisBtn){
    var salary = parseFloat($('#deductionModalDiv select[name="salary"] option:selected').val());
    var n = thisBtn.data('n');
    if(n=='lwop_day' || n=='lwop_hour' || n=='lwop_minute'){
        var lwop_day = parseInt($('#deductionModalDiv input[name="lwop_day"]').val());
        var lwop_hour = parseInt($('#deductionModalDiv input[name="lwop_hour"]').val());
        var lwop_minute = parseInt($('#deductionModalDiv input[name="lwop_minute"]').val());
        var day = round((salary/22),2);
        var hour = round((day/8),2);
        var minute = round((hour/60),2);
        $('#deductionModalDiv #lwopDayTotal').html('');
        $('#deductionModalDiv #lwopHourTotal').html('');
        $('#deductionModalDiv #lwopMinuteTotal').html('');

        if(lwop_day>0){
            var lwop_day_total = round((day*lwop_day),2);
            $('#deductionModalDiv #lwopDayTotal').html(lwop_day_total);
        }
        if(lwop_hour>0){
            var lwop_hour_total = round((hour*lwop_hour),2);
            $('#deductionModalDiv #lwopHourTotal').html(lwop_hour_total);
        }
        if(lwop_minute>0){
            var lwop_minute_total = round((minute*lwop_minute),2);
            $('#deductionModalDiv #lwopMinuteTotal').html(lwop_minute_total);
        }
    }
}

function round(value, precision) {
    var aPrecision = Math.pow(10, precision);
    return Math.round(value*aPrecision)/aPrecision;
}
