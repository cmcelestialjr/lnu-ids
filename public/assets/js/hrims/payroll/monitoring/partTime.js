partTimeTable();
$(document).off('change', '#partTimeSY').on('change', '#partTimeSY', function (e) {
    partTimeTable();
});
$(document).off('change', '#partTimeOption').on('change', '#partTimeOption', function (e) {
    partTimeTable();
});
$(document).off('click', '#partTimeSYNew').on('click', '#partTimeSYNew', function (e) {
    newSY();
});
$(document).off('click', '#ptySyNew button[name="submit"]').on('click', '#ptySyNew button[name="submit"]', function (e) {
    e.preventDefault();
    newSySubmit($(this));
});
$(document).off('click', '#partTimeAdd').on('click', '#partTimeAdd', function (e) {
    ptAddEmployee($(this));
});
$(document).off('change', '#ptAdd select[name="type"]').on('change', '#ptAdd select[name="type"]', function (e) {
    var val = $(this).val();
    total_hours('ptAdd');
    $('#ptAdd #nstpDiv').addClass('hide');
    if(val==4){
        $('#ptAdd #nstpDiv').removeClass('hide');
    }
});
$(document).off('change', '#ptAdd select[name="nstp"]').on('change', '#ptAdd select[name="nstp"]', function (e) {
    total_hours('ptAdd');
});
$(document).off('input', '#ptAdd input[name="units"]').on('input', '#ptAdd input[name="units"]', function (e) {
    total_hours('ptAdd');
});
$(document).off('click', '#ptAdd button[name="submit"]').on('click', '#ptAdd button[name="submit"]', function (e) {
    e.preventDefault();
    ptAddSubmit($(this));
});
$(document).off('click', '.ptUpdate').on('click', '.ptUpdate', function (e) {
    ptUpdate($(this));
});
$(document).off('click', '#ptUpdate button[name="submit"]').on('click', '#ptUpdate button[name="submit"]', function (e) {
    e.preventDefault();
    ptUpdateSubmit($(this));
});
$(document).off('change', '#ptUpdate select[name="type"]').on('change', '#ptUpdate select[name="type"]', function (e) {
    var val = $(this).val();
    total_hours('ptUpdate');
    $('#ptUpdate #nstpDiv').addClass('hide');
    if(val==4){
        $('#ptUpdate #nstpDiv').removeClass('hide');
    }
});
$(document).off('change', '#ptUpdate select[name="nstp"]').on('change', '#ptUpdate select[name="nstp"]', function (e) {
    total_hours('ptUpdate');
});
$(document).off('input', '#ptUpdate input[name="units"]').on('input', '#ptUpdate input[name="units"]', function (e) {
    total_hours('ptUpdate');
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
function partTimeTable(){
    var thisBtn = $('.select2-div');
    var sy = $('#partTimeSY option:selected').val();
    var option = $('#partTimeOption').val();
    var thisBtn = $('#partTimeDiv');
    var form_data = {
        url_table:base_url+'/hrims/payroll/monitoring/partTime/index',
        tid:'partTimeDiv',
        sy:sy,
        option:option
    };
    loadDivwLoader(form_data,thisBtn);
}
function newSY(){
    var thisBtn = $('#partTimeSY');
    var url = base_url+'/hrims/payroll/monitoring/partTime/syNew';
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
    var url = base_url+'/hrims/payroll/monitoring/partTime/add';
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
function ptUpdate(thisBtn){
    var id = thisBtn.data('id');
    var option_id = thisBtn.data('o');
    var work_id = thisBtn.data('w');
    var sy = $('#partTimeSY option:selected').val();
    var url = base_url+'/hrims/payroll/monitoring/partTime/update';
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

    var form_data = $('#ptySyNew').serialize();

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/partTime/syNewSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
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
    var sy = $('#partTimeSY option:selected').val();
    var employee = $('#ptAdd select[name="employee"] option:selected').val();
    var type = $('#ptAdd select[name="type"] option:selected').val();
    var nstp = $('#ptAdd select[name="nstp"] option:selected').val();
    var fund_source = $('#ptAdd select[name="fund_source"] option:selected').val();
    var x_count = 0;

    $('#ptAdd #employeeDiv').removeClass('border-require');
    $('#ptAdd #typeDiv').removeClass('border-require');
    $('#ptAdd #nstpDiv').removeClass('border-require');
    $('#ptAdd #fundsourceDiv').removeClass('border-require');

    if(employee==''){
        $('#ptAdd #employeeDiv').addClass('border-require');
        x_count++;
    }

    if(type==''){
        $('#ptAdd #typeDiv').addClass('border-require');
        x_count++;
    }

    if(fund_source==''){
        $('#ptAdd #fundsourceDiv').addClass('border-require');
        x_count++;
    }

    if(type==4 && nstp==''){
        $('#ptAdd #nstpDiv').addClass('border-require');
        x_count++;
    }

    if(x_count>0){
        return;
    }

    var form_data = $('#ptAdd').serializeArray();

    form_data.push({ name: 'sy', value: sy });

    var serialized_data = $.param(form_data);

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/partTime/addSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:serialized_data,
        cache: false,
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
                partTimeTable();
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
    var sy = $('#partTimeSY option:selected').val();
    var type = $('#ptUpdate select[name="type"] option:selected').val();
    var nstp = $('#ptUpdate select[name="nstp"] option:selected').val();

    $('#ptUpdate #nstpDiv').removeClass('border-require');
    if(type==4 && nstp==''){
        $('#ptUpdate #nstpDiv').addClass('border-require');
        return;
    }

    var form_data = $('#ptUpdate').serializeArray();

    form_data.push({ name: 'id', value: id });
    form_data.push({ name: 'sy', value: sy });
    form_data.push({ name: 'work_id', value: work_id });

    var serialized_data = $.param(form_data);

    $.ajax({
        url: base_url+'/hrims/payroll/monitoring/partTime/updateSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:serialized_data,
        cache: false,
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
