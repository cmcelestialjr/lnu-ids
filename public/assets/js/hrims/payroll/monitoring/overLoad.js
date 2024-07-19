overLoadTable();
$(document).off('change', '#overLoadSY').on('change', '#overLoadSY', function (e) {
    overLoadTable();
});
$(document).off('change', '#overLoadOption').on('change', '#overLoadOption', function (e) {
    overLoadTable();
});
$(document).off('click', '#overLoadSYNew').on('click', '#overLoadSYNew', function (e) {
    newSY();
});
$(document).off('click', '#olySyNew button[name="submit"]').on('click', '#olySyNew button[name="submit"]', function (e) {
    e.preventDefault();
    newSySubmit($(this));
});
$(document).off('click', '#overLoadAdd').on('click', '#overLoadAdd', function (e) {
    olAddEmployee($(this));
});
$(document).off('change', '#olAdd select[name="type"]').on('change', '#olAdd select[name="type"]', function (e) {
    var val = $(this).val();
    total_hours('olAdd');
    $('#olAdd #nstpDiv').addClass('hide');
    if(val==4){
        $('#olAdd #nstpDiv').removeClass('hide');
    }
});
$(document).off('change', '#olAdd select[name="nstp"]').on('change', '#olAdd select[name="nstp"]', function (e) {
    total_hours('olAdd');
});
$(document).off('input', '#olAdd input[name="units"]').on('input', '#olAdd input[name="units"]', function (e) {
    total_hours('olAdd');
});
$(document).off('click', '#olAdd button[name="submit"]').on('click', '#olAdd button[name="submit"]', function (e) {
    e.preventDefault();
    olAddSubmit($(this));
});
$(document).off('click', '.olUpdate').on('click', '.olUpdate', function (e) {
    olUpdate($(this));
});
$(document).off('click', '.olRemove').on('click', '.olRemove', function (e) {
    olRemove($(this));
});
$(document).off('click', '#olUpdate button[name="submit"]').on('click', '#olUpdate button[name="submit"]', function (e) {
    e.preventDefault();
    olUpdateSubmit($(this));
});
$(document).off('click', '#olRemove button[name="submit"]').on('click', '#olRemove button[name="submit"]', function (e) {
    e.preventDefault();
    olRemoveSubmit($(this));
});
$(document).off('change', '#olUpdate select[name="type"]').on('change', '#olUpdate select[name="type"]', function (e) {
    var val = $(this).val();
    total_hours('olUpdate');
    $('#olUpdate #nstpDiv').addClass('hide');
    if(val==4){
        $('#olUpdate #nstpDiv').removeClass('hide');
    }
});
$(document).off('change', '#olUpdate select[name="nstp"]').on('change', '#olUpdate select[name="nstp"]', function (e) {
    total_hours('olUpdate');
});
$(document).off('input', '#olUpdate input[name="units"]').on('input', '#olUpdate input[name="units"]', function (e) {
    total_hours('olUpdate');
});
function total_hours(formID){
    var units = $('#'+formID+' input[name="units"]').val();
    var option_id = $('#'+formID+' select[name="type"] option:selected').val();
    var nstp_id = $('#'+formID+' select[name="nstp"] option:selected').val();
    var total_hours = 0;
    if(option_id==4){
        if(nstp_id==1){
            var total_hours = 88;
        }else if(nstp_id==2){
            var total_hours = 64;
        }else if(nstp_id==3){
            var total_hours = 64;
        }
    }else{
        var total_hours = units*18;
    }
    $('#'+formID+' input[name="total_hours"]').val(total_hours);
}
function overLoadTable(){
    var thisBtn = $('.select2-div');
    var sy = $('#overLoadSY option:selected').val();
    var option = $('#overLoadOption').val();
    var thisBtn = $('#overLoadDiv');
    var form_data = {
        url_table:base_url+'/hrims/payroll/monitoring/overLoad/index',
        tid:'overLoadDiv',
        sy:sy,
        option:option
    };
    loadDivwLoader(form_data,thisBtn);
}
function newSY(){
    var thisBtn = $('#overLoadSY');
    var url = base_url+'/hrims/payroll/monitoring/overLoad/syNew';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function ptAddEmployee(thisBtn){
    var option = $('#overLoadOption').val();
    var url = base_url+'/hrims/payroll/monitoring/overLoad/add';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        option:option
    };
    loadModal(form_data,thisBtn);
}
function ptUpdate(thisBtn){
    var id = thisBtn.data('id');
    var option_id = thisBtn.data('o');
    var work_id = thisBtn.data('w');
    var sy = $('#overLoadSY option:selected').val();
    var url = base_url+'/hrims/payroll/monitoring/overLoad/update';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        option_id:option_id,
        work_id:work_id,
        sy:sy
    };
    loadModal(form_data,thisBtn);
}
function ptRemove(thisBtn){
    var id = thisBtn.data('id');
    var option_id = thisBtn.data('o');
    var work_id = thisBtn.data('w');
    var sy = $('#overLoadSY option:selected').val();
    var url = base_url+'/hrims/payroll/monitoring/overLoad/remove';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        option_id:option_id,
        work_id:work_id,
        sy:sy
    };
    loadModal(form_data,thisBtn);
}
function newSySubmit(thisBtn){

    var form_data = $('#olySyNew').serialize();

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/overLoad/syNewSubmit',
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
function ptAddSubmit(thisBtn){
    var sy = $('#overLoadSY option:selected').val();
    var employee = $('#olAdd select[name="employee"] option:selected').val();
    var type = $('#olAdd select[name="type"] option:selected').val();
    var nstp = $('#olAdd select[name="nstp"] option:selected').val();
    var fund_source = $('#olAdd select[name="fund_source"] option:selected').val();
    var department = $('#olAdd select[name="department"] option:selected').val();
    var x_count = 0;

    $('#olAdd #employeeDiv').removeClass('border-require');
    $('#olAdd #typeDiv').removeClass('border-require');
    $('#olAdd #nstpDiv').removeClass('border-require');
    $('#olAdd #fundsourceDiv').removeClass('border-require');
    $('#olAdd #departmentDiv').removeClass('border-require');

    if(employee==''){
        $('#olAdd #employeeDiv').addClass('border-require');
        x_count++;
    }

    if(type==''){
        $('#olAdd #typeDiv').addClass('border-require');
        x_count++;
    }

    if(fund_source==''){
        $('#olAdd #fundsourceDiv').addClass('border-require');
        x_count++;
    }

    if(department==''){
        $('#olAdd #departmentDiv').addClass('border-require');
        x_count++;
    }

    if(type==4 && nstp==''){
        $('#olAdd #nstpDiv').addClass('border-require');
        x_count++;
    }

    if(x_count>0){
        return;
    }

    var form_data = $('#olAdd').serializeArray();

    form_data.push({ name: 'sy', value: sy });

    var serialized_data = $.param(form_data);

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/overLoad/addSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:serialized_data,
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
                $('#modal-default').modal('hide');
                overLoadTable();
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
function ptUpdateSubmit(thisBtn){
    var id = thisBtn.data('id');
    var work_id = thisBtn.data('w');
    var sy = $('#overLoadSY option:selected').val();
    var fund_source = $('#olUpdate select[name="fund_source"] option:selected').val();
    var department = $('#olUpdate select[name="department"] option:selected').val();
    var x_count = 0;

    $('#olUpdate #fundsourceDiv').removeClass('border-require');
    $('#olUpdate #departmentDiv').removeClass('border-require');
    $('#olUpdate #nstpDiv').removeClass('border-require');

    if(fund_source==''){
        $('#olUpdate #fundsourceDiv').addClass('border-require');
        x_count++;
    }

    if(department==''){
        $('#olUpdate #departmentDiv').addClass('border-require');
        x_count++;
    }

    if(x_count>0){
        return;
    }

    var form_data = $('#olUpdate').serializeArray();

    form_data.push({ name: 'id', value: id });
    form_data.push({ name: 'sy', value: sy });
    form_data.push({ name: 'work_id', value: work_id });

    var serialized_data = $.param(form_data);

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/overLoad/updateSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:serialized_data,
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
function ptRemoveSubmit(thisBtn){
    var sy = $('#overLoadSY option:selected').val();

    var form_data = $('#olRemove').serializeArray();

    form_data.push({ name: 'sy', value: sy });

    var serialized_data = $.param(form_data);

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/overLoad/removeSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:serialized_data,
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
                $('#modal-default').modal('hide');
                overLoadTable();
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
