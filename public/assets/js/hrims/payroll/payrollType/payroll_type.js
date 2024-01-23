list_table();
$(document).off('click', '#list button[name="new"]').on('click', '#list button[name="new"]', function (e) {
    list_new();
});
$(document).off('click', '#listNewModal button[name="submit"]').on('click', '#listNewModal button[name="submit"]', function (e) {
    list_new_submit();
});
$(document).off('click', '#list .update').on('click', '#list .update', function (e) {
    var thisBtn = $(this);
    list_update(thisBtn);
});
$(document).off('click', '#listUpdateModal button[name="submit"]').on('click', '#listUpdateModal button[name="submit"]', function (e) {
    list_update_submit();
});
$(document).off('change', '#select[name="w_guideline"]').on('change', 'select[name="w_guideline"]', function (e) {
    $('.wGuidelineDiv').addClass('hide');
    if($('select[name="w_guideline"] option:selected').val()=='Yes'){
        $('.wGuidelineDiv').removeClass('hide');
    }
});
function list_table(){
    var form_data = {
        url_table:base_url+'/hrims/payroll/payrollType/table',
        tid:'listTable'
    };
    loadTable(form_data);
}
function list_new(){
    var thisBtn = $('#source button[name="new"]');
    var url = base_url+'/hrims/payroll/payrollType/newModal';
    var modal = 'default';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function list_new_submit(){
    var thisBtn = $('#listNewModal button[name="submit"]');
    var name = $('#listNewModal input[name="name"]').val();
    var gov_service = $('#listNewModal select[name="gov_service"] option:selected').val();
    var w_guideline = $('#listNewModal select[name="w_guideline"] option:selected').val();
    var w_salary = $('#listNewModal select[name="w_salary"] option:selected').val();
    var w_salary_name = $('#listNewModal input[name="w_salary_name"]').val();
    var column_name = $('#listNewModal input[name="column_name"]').val();
    var amount = $('#listNewModal input[name="amount"]').val();
    var column_name2 = $('#listNewModal input[name="column_name2"]').val();
    var amount2 = $('#listNewModal input[name="amount2"]').val();
    var month_no = $('#listNewModal select[name="month_no"] option:selected').val();
    var month_as_of = $('#listNewModal select[name="month_as_of"] option:selected').val();
    var day_as_of = $('#listNewModal select[name="day_as_of"] option:selected').val();
    var month_from = $('#listNewModal select[name="month_from"] option:selected').val();
    var day_from = $('#listNewModal select[name="day_from"] option:selected').val();
    var x = 0;
    $('#listNewModal input[name="name"]').removeClass('border-require');
    if(name==''){
        $('#listNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Payroll Type');
        x++;
    }
    if ($('#listNewModal input[name="aggregate"]').is(":checked")) {
        var aggregate = 1;
    } else {
        var aggregate = 0;
    }
    if ($('#listNewModal input[name="preceding_year"]').is(":checked")) {
        var preceding_year = 1;
    } else {
        var preceding_year = 0;
    }
    if ($('#listNewModal input[name="grant_separated"]').is(":checked")) {
        var grant_separated = 1;
    } else {
        var grant_separated = 0;
    }
    if(x==0){
        var form_data = {
            name:name,
            gov_service:gov_service,
            w_guideline:w_guideline,
            w_salary:w_salary,
            w_salary_name:w_salary_name,
            column_name:column_name,
            amount:amount,
            column_name2:column_name2,
            amount2:amount2,
            month_no:month_no,
            month_as_of:month_as_of,
            day_as_of:day_as_of,
            month_from:month_from,
            day_from:day_from,
            aggregate:aggregate,
            preceding_year:preceding_year,
            grant_separated:grant_separated
        };
        $.ajax({
            url: base_url+'/hrims/payroll/payrollType/newSubmit',
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
                    list_table();
                    $('#modal-default').modal('hide');
                }else{
                    toastr.error('Error.');
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
function list_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/payroll/payrollType/updateModal';
    var modal = 'default';
    var modal_size = 'modal-lg';
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
function list_update_submit(){
    var thisBtn = $('#listUpdateModal button[name="submit"]');
    var id = $('#listUpdateModal input[name="id"]').val();
    var name = $('#listUpdateModal input[name="name"]').val();
    var gov_service = $('#listUpdateModal select[name="gov_service"] option:selected').val();
    var w_guideline = $('#listUpdateModal select[name="w_guideline"] option:selected').val();
    var w_salary = $('#listUpdateModal select[name="w_salary"] option:selected').val();
    var w_salary_name = $('#listUpdateModal input[name="w_salary_name"]').val();
    var column_name = $('#listUpdateModal input[name="column_name"]').val();
    var amount = $('#listUpdateModal input[name="amount"]').val();
    var column_name2 = $('#listUpdateModal input[name="column_name2"]').val();
    var amount2 = $('#listUpdateModal input[name="amount2"]').val();
    var month_no = $('#listUpdateModal select[name="month_no"] option:selected').val();
    var month_as_of = $('#listUpdateModal select[name="month_as_of"] option:selected').val();
    var day_as_of = $('#listUpdateModal select[name="day_as_of"] option:selected').val();
    var month_from = $('#listUpdateModal select[name="month_from"] option:selected').val();
    var day_from = $('#listUpdateModal select[name="day_from"] option:selected').val();

    var x = 0;    
    $('#listUpdateModal input[name="name"]').removeClass('border-require');
    if(name==''){
        $('#listUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Payroll Type Name');
        x++;
    }
    if ($('#listUpdateModal input[name="aggregate"]').is(":checked")) {
        var aggregate = 1;
    } else {
        var aggregate = 0;
    }
    if ($('#listUpdateModal input[name="preceding_year"]').is(":checked")) {
        var preceding_year = 1;
    } else {
        var preceding_year = 0;
    }
    if ($('#listUpdateModal input[name="grant_separated"]').is(":checked")) {
        var grant_separated = 1;
    } else {
        var grant_separated = 0;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            gov_service:gov_service,
            w_guideline:w_guideline,
            w_salary:w_salary,
            w_salary_name:w_salary_name,
            column_name:column_name,
            amount:amount,
            column_name2:column_name2,
            amount2:amount2,
            month_no:month_no,
            month_as_of:month_as_of,
            day_as_of:day_as_of,
            month_from:month_from,
            day_from:day_from,
            aggregate:aggregate,
            preceding_year:preceding_year,
            grant_separated:grant_separated
        };
        $.ajax({
            url: base_url+'/hrims/payroll/payrollType/updateSubmit',
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
                    list_table();
                }else{
                    toastr.error('Error.');
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