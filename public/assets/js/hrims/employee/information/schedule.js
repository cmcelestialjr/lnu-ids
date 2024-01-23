$(document).off('click', '#table .schedNewModal').on('click', '#table .schedNewModal', function (e) {
    var thisBtn = $(this);
    schedNewModal(thisBtn);
});
$(document).off('blur', '#schedNewModal .time_input').on('blur', '#schedNewModal .time_input', function (e) {
    var thisBtn = $(this);
    schedNewDaysList(thisBtn);
});
$(document).off('change', '#schedNewModal input[name="duration"]').on('change', '#schedNewModal input[name="duration"]', function (e) {
    var thisBtn = $(this);
    schedNewDaysList(thisBtn);
});
$(document).off('click', '#schedNewModal button[name="submit"]').on('click', '#schedNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    schedNewSubmit(thisBtn);
});
$(document).off('click', '#table .schedEditModal').on('click', '#table .schedEditModal', function (e) {
    var thisBtn = $(this);
    schedEditModal(thisBtn);
});
$(document).off('blur', '#schedEditModal .time_input').on('blur', '#schedEditModal .time_input', function (e) {
    var thisBtn = $(this);
    schedEditDaysList(thisBtn);
});
$(document).off('change', '#schedEditModal input[name="duration"]').on('change', '#schedEditModal input[name="duration"]', function (e) {
    var thisBtn = $(this);
    schedEditDaysList(thisBtn);
});
$(document).off('click', '#schedEditModal button[name="submit"]').on('click', '#schedEditModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    schedEditSubmit(thisBtn);
});
$(document).off('click', '#table .schedDeleteModal').on('click', '#table .schedDeleteModal', function (e) {
    var thisBtn = $(this);
    schedDeleteModal(thisBtn);
});
$(document).off('click', '#schedDeleteModal button[name="submit"]').on('click', '#schedDeleteModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    schedDeleteSubmit(thisBtn);
});
$(document).off('change', '#table select').on('change', '#table select', function (e) {
    var thisBtn = $(this);
    _information1('schedule',thisBtn,'table_active');
});
function _information1(url,thisBtn,active){    
    var id = thisBtn.data('id');
    var from_sys = thisBtn.data('sys');
    var year = $('#table select[name="year"] option:selected').val();
    var month = $('#table select[name="month"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/'+url,
        tid:'displayDiv',
        id:id,
        active:active,
        from_sys:from_sys,
        year:year,
        month:month
    };
    loadDivwDisabled(form_data,thisBtn);
}
function schedNewModal(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var url = base_url+'/hrims/employee/information/schedule/schedNewModal';
    var modal = 'info';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/hrims/employee/information/schedule/schedNewDaysList',
        tid:'daysList',
        id:id,
        time_from:'none',
        time_to:'none',
        duration:'none'
    };
    loadModal(form_data,thisBtn);
}
function schedNewDaysList(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var duration = $('#schedNewModal input[name="duration"]').val();
    var time_from = $('#schedNewModal input[name="time_from"]').val();
    var time_to = $('#schedNewModal input[name="time_to"]').val();
    var x = 0;
    $('#schedNewModal input[name="time_from"]').removeClass('border-require');
    $('#schedNewModal input[name="time_to"]').removeClass('border-require');
    if (!checkTimeFormat(time_from) && !checkTimeFormat1(time_from)) {
        $('#schedNewModal input[name="time_from"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if (!checkTimeFormat(time_to) && !checkTimeFormat1(time_to)) {
        $('#schedNewModal input[name="time_to"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if(time_from>time_to){
        $('#schedNewModal input[name="time_from"]').addClass('border-require');
        $('#schedNewModal input[name="time_to"]').addClass('border-require');
        toastr.error('Time from must less than time to');
        x++;
    }
    if(x==0){
        $('#schedNewModal input[name="duration"]').removeClass('dateRange');
        var form_data = {
            url_table:base_url+'/hrims/employee/information/schedule/schedNewDaysList',
            tid:'daysList',
            id:id,
            duration:duration,
            time_from:time_from,
            time_to:time_to
        };
        loadDivwLoader(form_data,thisBtn);
    }
}
function schedNewSubmit(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var option = $('#schedNewModal select[name="option"] option:selected').val();
    var duration = $('#schedNewModal input[name="duration"]').val();
    var time_from = $('#schedNewModal input[name="time_from"]').val();
    var time_to = $('#schedNewModal input[name="time_to"]').val();
    var remarks = $('#schedNewModal textarea[name="remarks"]').val();
    var days = [];
    var x = 0;
    $('#schedNewModal input[name="days[]"]:checked').each(function(){
        days.push($(this).val());
    }); 
    if(days==''){
        toastr.error('Please select Day!');
        x++;
    }
    $('#schedNewModal input[name="time_from"]').removeClass('border-require');
    $('#schedNewModal input[name="time_to"]').removeClass('border-require');
    if (!checkTimeFormat(time_from) && !checkTimeFormat1(time_from)) {
        $('#schedNewModal input[name="time_from"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if (!checkTimeFormat(time_to) && !checkTimeFormat1(time_to)) {
        $('#schedNewModal input[name="time_to"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if(time_from>=time_to){
        $('#schedNewModal input[name="time_from"]').addClass('border-require');
        $('#schedNewModal input[name="time_to"]').addClass('border-require');
        toastr.error('Time from must less than time to');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            option:option,
            duration:duration,
            time_from:time_from,
            time_to:time_to,
            days:days,
            remarks:remarks            
        };
        $.ajax({
            url: base_url+'/hrims/employee/information/schedule/schedNewSubmit',
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
                    var url = 'schedule';
                    _information(url,thisBtn,'table_active');
                    $('#modal-info').modal('hide');
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
function schedEditModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/information/schedule/schedEditModal';
    var modal = 'info';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/hrims/employee/information/schedule/schedEditDaysList',
        tid:'daysList',
        id:id,
        time_from:'none',
        time_to:'none',
        duration:'none'
    };
    loadModal(form_data,thisBtn);
}
function schedEditDaysList(thisBtn){
    var id = $('#schedEditModal input[name="time_id"]').val();
    var duration = $('#schedEditModal input[name="duration"]').val();
    var time_from = $('#schedEditModal input[name="time_from"]').val();
    var time_to = $('#schedEditModal input[name="time_to"]').val();
    var x = 0;
    $('#schedEditModal input[name="time_from"]').removeClass('border-require');
    $('#schedEditModal input[name="time_to"]').removeClass('border-require');
    if (!checkTimeFormat(time_from) && !checkTimeFormat1(time_from)) {
        $('#schedEditModal input[name="time_from"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if (!checkTimeFormat(time_to) && !checkTimeFormat1(time_to)) {
        $('#schedEditModal input[name="time_to"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if(time_from>time_to){
        $('#schedEditModal input[name="time_from"]').addClass('border-require');
        $('#schedEditModal input[name="time_to"]').addClass('border-require');
        toastr.error('Time from must less than time to');
        x++;
    }
    if(x==0){
        $('#schedEditModal input[name="duration"]').removeClass('dateRange');
        var form_data = {
            url_table:base_url+'/hrims/employee/information/schedule/schedEditDaysList',
            tid:'daysList',
            id:id,
            time_from:time_from,
            time_to:time_to,
            duration:duration
        };
        loadDivwLoader(form_data,thisBtn);
    }
}
function schedEditSubmit(thisBtn){
    var id = $('#schedEditModal input[name="time_id"]').val();
    var option = $('#schedEditModal select[name="option"] option:selected').val();
    var duration = $('#schedEditModal input[name="duration"]').val();
    var time_from = $('#schedEditModal input[name="time_from"]').val();
    var time_to = $('#schedEditModal input[name="time_to"]').val();
    var remarks = $('#schedEditModal textarea[name="remarks"]').val();
    var days = [];
    var x = 0;
    $('#schedEditModal input[name="days[]"]:checked').each(function(){
        days.push($(this).val());
    }); 
    if(days==''){
        toastr.error('Please select Day!');
        x++;
    }
    $('#schedEditModal input[name="time_from"]').removeClass('border-require');
    $('#schedEditModal input[name="time_to"]').removeClass('border-require');
    if (!checkTimeFormat(time_from) && !checkTimeFormat1(time_from)) {
        $('#schedEditModal input[name="time_from"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if (!checkTimeFormat(time_to) && !checkTimeFormat1(time_to)) {
        $('#schedEditModal input[name="time_to"]').addClass('border-require');
        toastr.error('Please input correct time format!');
        x++;
    }
    if(time_from>=time_to){
        $('#schedEditModal input[name="time_from"]').addClass('border-require');
        $('#schedEditModal input[name="time_to"]').addClass('border-require');
        toastr.error('Time from must less than time to');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            option:option,
            duration:duration,
            time_from:time_from,
            time_to:time_to,
            days:days,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/hrims/employee/information/schedule/schedEditSubmit',
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
                    var url = 'schedule';
                    _information(url,thisBtn,'table_active');
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
function schedDeleteModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/information/schedule/schedDeleteModal';
    var modal = 'info';
    var modal_size = 'modal-sm';
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
function schedDeleteSubmit(thisBtn){
    var id = $('#schedDeleteModal input[name="time_id"]').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/hrims/employee/information/schedule/schedDeleteSubmit',
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
                var url = 'schedule';
                _information(url,thisBtn,'table_active');
                $('#modal-info').modal('hide');
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
function checkTimeFormat(timeString) {
    var timeRegex = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/;
  
    return timeRegex.test(timeString);
}
function checkTimeFormat1(timeString) {
    var timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;
  
    return timeRegex.test(timeString);
}