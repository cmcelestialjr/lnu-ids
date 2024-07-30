dtr_option();
$(document).off('click', '#dtrDiv button[name="submit"]').on('click', '#dtrDiv button[name="submit"]', function (e) {
    dtr_table();
});
$(document).off('click', '.dtrInput').on('click', '.dtrInput', function (e) {
    var thisBtn = $(this);
    dtr_input(thisBtn);
});
$(document).off('change', '#dtrInputModal select[name="time_type"]').on('change', '#dtrInputModal select[name="time_type"]', function (e) {
    var thisBtn = $(this);
    dtr_input_table(thisBtn);
});
$(document).off('click', '#dtrInputModal button[name="submit"]').on('click', '#dtrInputModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    dtr_input_submit(thisBtn);
});
$(document).off('click', '#dtrDiv button[name="fill_duration"]').on('click', '#dtrDiv button[name="fill_duration"]', function (e) {
    var thisBtn = $(this);
    dtr_input_duration(thisBtn);
});
$(document).off('click', '#dtrDiv button[name="schedule"]').on('click', '#dtrDiv button[name="schedule"]', function (e) {
    var thisBtn = $(this);
    schedule(thisBtn);
});
$(document).off('blur', '#dtrInputDurationModal input').on('blur', '#dtrInputDurationModal input', function (e) {
    dtr_input_duration_check();
});
$(document).off('click', '#dtrInputDurationModal button[name="submit"]').on('click', '#dtrInputDurationModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    dtr_input_duration_submit(thisBtn);
});
$(document).off('click', '#dtrDiv button[name="department"]').on('click', '#dtrDiv button[name="department"]', function (e) {
    var thisBtn = $(this);
    department(thisBtn);
});
$(document).off('click', '#departmentModal button[name="submit"]').on('click', '#departmentModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    department_submit(thisBtn);
});
$(document).off('click', '.dtrPrint').on('click', '.dtrPrint', function (e) {
    var thisBtn = $(this);
    dtrPrint(thisBtn);
});
$(document).off('click', '#dtrInputModal #dtrInputTable .change_travel').on('click', '#dtrInputModal #dtrInputTable .change_travel', function (e) {
    var thisBtn = $(this);
    var val = thisBtn.data('val');
    var id = thisBtn.data('id');
    var n = thisBtn.data('n');
    if(val=='travel'){
        $('#dtrInputModal #dtrInputTable #'+id).html('<div class="input-group mb-3"><input type="time" class="form-control" name="'+n+'">'+
                    '<div class="input-group-prepend">'+
                        '<button type="button" class="btn btn-info btn-info-scan change_travel" data-val="time" data-id="'+id+'">'+
                            '<span class="fa fa-refresh"></span></button>'+
                    '</div></div>');
    }else{
        $('#dtrInputModal #dtrInputTable #'+id).html('<div class="input-group mb-3">'+
                '<input type="text" class="form-control" name="'+n+'" value="Travel" readonly>'+
                    '<div class="input-group-prepend">'+
                        '<button type="button" class="btn btn-info btn-info-scan change_travel" data-val="travel" data-id="'+id+'">'+
                            '<span class="fa fa-refresh"></span></button>'+
                    '</div></div>');

    }
});
$(document).off('click', '#dtrInputModal #dtrInputTable .change_vacant').on('click', '#dtrInputModal #dtrInputTable .change_vacant', function (e) {
    var thisBtn = $(this);
    var val = thisBtn.data('val');
    var id = thisBtn.data('id');
    var n = thisBtn.data('n');
    if(val=='vacant'){
        $('#dtrInputModal #dtrInputTable #'+id).html('<div class="input-group mb-3"><input type="time" class="form-control" name="'+n+'">'+
                    '<div class="input-group-prepend">'+
                        '<button type="button" class="btn btn-info btn-info-scan change_vacant" data-val="time" data-id="'+id+'">'+
                            '<span class="fa fa-refresh"></span></button>'+
                    '</div></div>');
    }else{
        $('#dtrInputModal #dtrInputTable #'+id).html('<div class="input-group mb-3">'+
                '<input type="text" class="form-control" name="'+n+'" value="Vacant" readonly>'+
                    '<div class="input-group-prepend">'+
                        '<button type="button" class="btn btn-info btn-info-scan change_vacant" data-val="vacant" data-id="'+id+'">'+
                            '<span class="fa fa-refresh"></span></button>'+
                    '</div></div>');

    }
});
function dtr_option(){
    var thisBtn = $('#dtrDiv button');
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var id_no = $('#dtrDiv input[name="id_no"]').val();
    var form_data = {
        year:year,
        month:month,
        id_no:id_no,
    };
    $.ajax({
        url: base_url+'/hrims/dtr/individualOption',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            if(data.result=='success'){
                $('#select-individual-option').empty();
                $.each(data.options, function(index, option) {
                    $('#select-individual-option').append('<option value="' + option.id + '">' + option.name + '</option>');
                });
                $('#select-individual-option').select2();
                dtr_table();
            }else{
                toastr.error(data.result);
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
        }
    });
}
function dtr_table(){
    var thisBtn = $('#dtrDiv button');
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var option = $('#dtrDiv select[name="option"] option:selected').val();
    var range = $('#dtrDiv select[name="range"] option:selected').val();
    var id_no = $('#dtrDiv input[name="id_no"]').val();
    var dtr_type = $('#dtrDiv input[name="dtr_type"]').val();
    var form_data = {
        year:year,
        month:month,
        option:option,
        range:range,
        id_no:id_no,
        dtr_type:dtr_type
    };

    $.ajax({
        url: base_url+'/hrims/dtr/individual',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#dtrDiv #not-found').addClass('hide');
            $('#dtrDiv #previewDiv').removeClass('hide');
            $('#dtrDiv #previewDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
                $('#dtrDiv #previewDiv').addClass('hide');
                $('#dtrDiv #not-found').removeClass('hide');
            }else{
                toastr.success('Success');
                thisBtn.addClass('input-success');
                $('#dtrDiv #previewDiv').removeClass('opacity6');
                $('#dtrDiv #previewDiv').html(data);
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
function dtr_input(thisBtn){
    var day = thisBtn.data('d');
    var time_type = thisBtn.data('time_type');
    var id_no = $('input[name="id_no"]').val();
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var url = base_url+'/hrims/dtr/dtrInputModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/hrims/dtr/dtrInputTable',
        tid:'dtrInputTable',
        day:day,
        time_type:time_type,
        id_no:id_no,
        year:year,
        month:month
    };
    loadModal(form_data,thisBtn);
}
function dtr_input_table(thisBtn){
    var day = $('#dtrInputModal input[name="day"]').val();
    var time_type = thisBtn.val();
    var id_no = $('input[name="id_no"]').val();
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/dtr/dtrInputTable',
        tid:'dtrInputTable',
        day:day,
        time_type:time_type,
        id_no:id_no,
        year:year,
        month:month
    };
    loadDivwLoader(form_data,thisBtn);
}
function dtr_input_submit(thisBtn){
    var id_no = $('input[name="id_no"]').val();
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var day = $('#dtrInputModal input[name="day"]').val();
    var time_type = $('#dtrInputModal select[name="time_type"] option:selected').val();
    var time_in_am = $('#dtrInputModal #dtrInputTable input[name="time_in_am"]').val();
    var time_out_am = $('#dtrInputModal #dtrInputTable input[name="time_out_am"]').val();
    var time_in_pm = $('#dtrInputModal #dtrInputTable input[name="time_in_pm"]').val();
    var time_out_pm = $('#dtrInputModal #dtrInputTable input[name="time_out_pm"]').val();
    var x = 0;
    var form_data = {
        id_no:id_no,
        year:year,
        month:month,
        day:day,
        time_type:time_type,
        time_in_am:time_in_am,
        time_out_am:time_out_am,
        time_in_pm:time_in_pm,
        time_out_pm:time_out_pm
    };
    if(time_in_am==''){
        $('#dtrInputModal #dtrInputTable input[name="time_in_am"]').addClass('border-require');
        toastr.error('Please input arrival (AM)');
        x++;
    }
    if(time_out_am==''){
        $('#dtrInputModal #dtrInputTable input[name="time_out_am"]').addClass('border-require');
        toastr.error('Please input departure (AM)');
        x++;
    }
    if(time_in_pm==''){
        $('#dtrInputModal #dtrInputTable input[name="time_in_pm"]').addClass('border-require');
        toastr.error('Please input arrival (PM)');
        x++;
    }
    if(time_out_pm==''){
        $('#dtrInputModal #dtrInputTable input[name="time_out_pm"]').addClass('border-require');
        toastr.error('Please input arrival (PM)');
        x++;
    }
    if(x==0){
        $.ajax({
            url: base_url+'/hrims/dtr/dtrInputSubmit',
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
                    dtr_table();
                    $('#modal-primary').modal('hide');
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
function dtr_input_duration(thisBtn){
    var id_no = $('input[name="id_no"]').val();
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var url = base_url+'/hrims/dtr/dtrInputDurationModal';
    var modal = 'primary';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id_no:id_no,
        year:year,
        month:month
    };
    loadModal(form_data,thisBtn);
}
function schedule(thisBtn){
    var id_no = $('input[name="id_no"]').val();
    var url = base_url+'/hrims/dtr/schedule';
    var modal = 'primary';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id_no:id_no
    };
    loadModal(form_data,thisBtn);
}
function department(thisBtn){
    var id = $('input[name="user_information"]').val();
    var url = base_url+'/hrims/dtr/department';
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
}
function dtr_input_duration_check(){
    var year = $('#select-individual-year option:selected').val();
    var month = $('#select-individual-month option:selected').val();
    var day_from = parseInt($('#dtrInputDurationModal input[name="day_from"]').val());
    var day_to = parseInt($('#dtrInputDurationModal input[name="day_to"]').val());
    var lastDay = getLastDayOfMonth(year, month);

    var x = 0;
    $('#dtrInputDurationModal input[name="day_from"]').removeClass('border-require');
    $('#dtrInputDurationModal input[name="day_to"]').removeClass('border-require');
    if(day_from>lastDay){
        $('#dtrInputDurationModal input[name="day_from"]').val(lastDay);
        var day_from = lastDay;
    }
    if(day_to>lastDay){
        $('#dtrInputDurationModal input[name="day_to"]').val(lastDay);
        var day_to = lastDay;
    }
    if(day_from<1){
        $('#dtrInputDurationModal input[name="day_from"]').val(1);
        var day_from = 1;
    }
    if(day_to<1){
        $('#dtrInputDurationModal input[name="day_from"]').val(1);
        var day_to = 1;
    }
    if(day_from>day_to){
        $('#dtrInputDurationModal input[name="day_from"]').addClass('border-require');
        $('#dtrInputDurationModal input[name="day_to"]').addClass('border-require');
        toastr.error('Day From must be lower than Day To');
        x++;
    }
    return x;
}
function getLastDayOfMonth(year, month) {
    return new Date(year, month + 1, 0).getDate();
}
function dtr_input_duration_submit(thisBtn){
    var id_no = $('input[name="id_no"]').val();
    var year = $('#dtrDiv select[name="year"] option:selected').val();
    var month = $('#dtrDiv select[name="month"] option:selected').val();
    var day_from = $('#dtrInputDurationModal input[name="day_from"]').val();
    var day_to = $('#dtrInputDurationModal input[name="day_to"]').val();
    var time_type = $('#dtrInputDurationModal select[name="time_type"] option:selected').val();
    var form_data = {
        id_no:id_no,
        year:year,
        month:month,
        day_from:day_from,
        day_to:day_to,
        time_type:time_type
    };
    var x = dtr_input_duration_check();
    if(x==0){
        $.ajax({
            url: base_url+'/hrims/dtr/dtrInputDurationSubmit',
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
                    dtr_table();
                    $('#modal-primary').modal('hide');
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
function department_submit(thisBtn){
    var id = $('input[name="user_information"]').val();
    var department = $('#departmentModal select[name="department"] option:selected').val();
    var form_data = {
        id:id,
        department:department
    };
    $.ajax({
        url: base_url+'/hrims/dtr/departmentSubmit',
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
                $('#modal-primary').modal('hide');
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
function dtrPrint(thisBtn){
    var id_no = $('input[name="id_no"]').val();
    var option = thisBtn.data('id');
    var year = thisBtn.data('y');
    var month = thisBtn.data('m');
    var range = thisBtn.data('r');
    var form_data = {
        id_no:id_no,
        option:option,
        year:year,
        month:month,
        range:range
    };
    $.ajax({
        url: base_url+'/hrims/employee/dtrSubmit',
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
                // var url = base_url+'/rims/student/certificationDisplay';
                // var modal = 'info';
                // var modal_size = 'modal-lg';
                // var form_data = {
                //     url:url,
                //     modal:modal,
                //     modal_size:modal_size,
                //     static:'',
                //     w_table:'wo',
                //     src:data.url
                // };
                // loadModal(form_data,thisBtn);
                window.open(base_url+'/'+data.url, '_blank');
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
