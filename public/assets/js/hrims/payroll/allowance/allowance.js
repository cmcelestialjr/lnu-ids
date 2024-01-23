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
function list_table(){
    var form_data = {
        url_table:base_url+'/hrims/payroll/allowance/table',
        tid:'listTable'
    };
    loadTable(form_data);
}
function list_new(){
    var thisBtn = $('#source button[name="new"]');
    var url = base_url+'/hrims/payroll/allowance/newModal';
    var modal = 'default';
    var modal_size = 'modal-md';
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
    var amount = $('#listNewModal input[name="amount"]').val();
    var monthly = $('#listNewModal select[name="monthly"] option:selected').val();
    var emp_stat=[];
    var payroll_type=[];
    var x = 0;
    $('#listNewModal select[name="emp_stat[]"] option:selected').each(function(){
        emp_stat.push($(this).val());
    });
    $('#listNewModal select[name="payroll_type[]"] option:selected').each(function(){
        payroll_type.push($(this).val());
    });
    
    $('#listNewModal input[name="name"]').removeClass('border-require');
    $('#listNewModal #emp_stat_select').removeClass('border-require');
    $('#listNewModal #payroll_type_select').removeClass('border-require');
    if(name==''){
        $('#listNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Allowance Name');
        x++;
    }
    if(emp_stat==''){
        $('#listNewModal #emp_stat_select').addClass('border-require');
        toastr.error('Please select Employment Status');
        x++;
    }
    if(payroll_type==''){
        $('#listNewModal #payroll_type_select').addClass('border-require');
        toastr.error('Please select Payroll Type');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            amount:amount,
            monthly:monthly,
            emp_stat:emp_stat,
            payroll_type:payroll_type
        };
        $.ajax({
            url: base_url+'/hrims/payroll/allowance/newSubmit',
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
    var url = base_url+'/hrims/payroll/allowance/updateModal';
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
function list_update_submit(){
    var thisBtn = $('#listUpdateModal button[name="submit"]');
    var id = $('#listUpdateModal input[name="id"]').val();
    var name = $('#listUpdateModal input[name="name"]').val();
    var amount = $('#listUpdateModal input[name="amount"]').val();
    var monthly = $('#listUpdateModal select[name="monthly"] option:selected').val();
    var emp_stat=[];
    var payroll_type=[];
    var x = 0;
    $('#listUpdateModal select[name="emp_stat[]"] option:selected').each(function(){
        emp_stat.push($(this).val());
    });
    $('#listUpdateModal select[name="payroll_type[]"] option:selected').each(function(){
        payroll_type.push($(this).val());
    });
    
    $('#listUpdateModal input[name="name"]').removeClass('border-require');
    $('#listUpdateModal #emp_stat_select').removeClass('border-require');
    $('#listUpdateModal #payroll_type_select').removeClass('border-require');
    if(name==''){
        $('#listUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Allowance Name');
        x++;
    }
    if(emp_stat==''){
        $('#listUpdateModal #emp_stat_select').addClass('border-require');
        toastr.error('Please select Employment Status');
        x++;
    }
    if(payroll_type==''){
        $('#listUpdateModal #payroll_type_select').addClass('border-require');
        toastr.error('Please select Payroll Type');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            amount:amount,
            monthly:monthly,
            emp_stat:emp_stat,
            payroll_type:payroll_type
        };
        $.ajax({
            url: base_url+'/hrims/payroll/allowance/updateSubmit',
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