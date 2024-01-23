$(document).off('click', '.newGuideline').on('click', '.newGuideline', function (e) {
    var thisBtn = $(this);
    var id = $('#listUpdateModal input[name="id"]').val();
    var url = base_url+'/hrims/payroll/payrollType/newGuideline';
    var modal = 'primary';
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
});
$(document).off('click','#btnSaveNewGuideline').on('click','#btnSaveNewGuideline',function(){
    var thisBtn = $('#btnSaveNewGuideline');
    var id = $('#listUpdateModal input[name="id"]').val();
    var name = $('#guidelineNewModal input[name="name"]').val();
    var w_salary_percent = $('#guidelineNewModal input[name="w_salary_percent"]').val();
    var amount = $('#guidelineNewModal input[name="amount"]').val();
    var percent = $('#guidelineNewModal input[name="percent"]').val();
    var amount2 = $('#guidelineNewModal input[name="amount2"]').val();
    var percent2 = $('#guidelineNewModal input[name="percent2"]').val();
    var from = $('#guidelineNewModal select[name="from"] option:selected').val();
    var to = $('#guidelineNewModal select[name="to"] option:selected').val();
    if ($('#guidelineNewModal input[name="grant_separated"]').is(":checked")) {
        var grant_separated = 1;
    } else {
        var grant_separated = 0;
    }
    var form_data = {
        id:id,
        name:name,
        w_salary_percent:w_salary_percent,
        amount:amount,
        percent:percent,
        amount2:amount2,
        percent2:percent2,
        from:from,
        to:to,
        grant_separated:grant_separated
    };
    $.ajax({
        url: base_url+'/hrims/payroll/payrollType/newGuidelineSubmit',
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
                table_guideline();
                $('#modal-primary').modal('hide');
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
});
$(document).off('click', '.editGuideline').on('click', '.editGuideline', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.attr('data-id');
    var url = base_url + '/hrims/payroll/payrollType/editGuideline/'+id;
    var modal = 'primary';
    var modal_size = 'modal-md';
    var form_data = {
        url: url,
        modal: modal,
        modal_size: modal_size,
        static: '',
        w_table: 'wo',
        id:id
    };
    loadModal(form_data, thisBtn);
});
$(document).off('click','#btnSaveEditGuideline').on('click','#btnSaveEditGuideline',function(){
    var thisBtn = $('#btnSaveEditGuideline');
    var id = $('#guidelineEditModal input[name="id"]').val();
    var name = $('#guidelineEditModal input[name="name"]').val();
    var w_salary_percent = $('#guidelineEditModal input[name="w_salary_percent"]').val();
    var amount = $('#guidelineEditModal input[name="amount"]').val();
    var percent = $('#guidelineEditModal input[name="percent"]').val();
    var amount2 = $('#guidelineEditModal input[name="amount2"]').val();
    var percent2 = $('#guidelineEditModal input[name="percent2"]').val();
    var from = $('#guidelineEditModal select[name="from"] option:selected').val();
    var to = $('#guidelineEditModal select[name="to"] option:selected').val();
    if ($('#guidelineEditModal input[name="grant_separated"]').is(":checked")) {
        var grant_separated = 1;
    } else {
        var grant_separated = 0;
    }
    var form_data = {
        id:id,
        name:name,
        w_salary_percent:w_salary_percent,
        amount:amount,
        percent:percent,
        amount2:amount2,
        percent2:percent2,
        from:from,
        to:to,
        grant_separated:grant_separated
    };

    $.ajax({
        url: base_url+'/hrims/payroll/payrollType/editGuidelineSubmit/'+id,
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
                table_guideline();
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
});
$(document).off('click', '.deleteGuideline').on('click', '.deleteGuideline', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.attr('data-id');
    var url = base_url + '/hrims/payroll/payrollType/deleteGuideline/'+id;
    var modal = 'primary';
    var modal_size = 'modal-sm';
    var form_data = {
        url: url,
        modal: modal,
        modal_size: modal_size,
        static: '',
        w_table: 'wo',
        id:id
    };
    loadModal(form_data, thisBtn);
});
$(document).off('click','#btnDeleteGuideline').on('click','#btnDeleteGuideline',function(){
    var thisBtn = $(this);
    var id = thisBtn.data('id');

    var form_data = {
        id:id
    };

    $.ajax({
        url: base_url+'/hrims/payroll/payrollType/deleteGuidelineSubmit/'+id,
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
                table_guideline();
                $('#modal-primary').modal('hide');
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
});
function table_guideline(){
    var thisBtn = $('#tableGuideline1');
    var id = $('#listUpdateModal input[name="id"]').val();
    var form_data = {
        url_table:base_url+'/hrims/payroll/payrollType/tableGuideline/'+id,
        tid:'tableGuideline',
        id:id,
        
    };
    loadDivwDisabled(form_data,thisBtn)
}