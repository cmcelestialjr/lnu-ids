function course_sched_rm_table(){
    var thisBtn = $('#courseSchedRmModal .schdrm');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();    
    var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
    var instructor_id = $('#courseSchedRmModal #rm_instructor select[name="instructor"] option:selected').val(); 
    var form_data = {
        url_table:base_url+'/rims/sections/courseSchedRmTable',
        tid:'courseSchedRmTable',
        id:id,
        schedule_id:schedule_id,
        room_id:room_id,
        instructor_id:instructor_id
    };
    loadDivwLoader(form_data,thisBtn);
}
function course_sched_rm_details(){
    var thisBtn = $('#courseSchedRmModal #details');
    var id = $('#courseSchedRmModal input[name="id"]').val();    
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/sections/courseSchedRmDetails',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() { 
            thisBtn.addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeClass('opacity6');
            if(data=='error'){
                toastr.success('Error');
            }else{
                $('#courseSchedRmModal #details').html(data);
                setTimeout(function() {
                    $(".timepicker-course").inputmask('99:99aa');
                    $('.timepicker-course').timepicker({
                        dropdownParent: $("#timeDiv"),
                        timeFormat: 'hh:mma',
                        interval: 15,
                        minTime: '07',
                        maxTime: '11:00pm',
                        startTime: '07:30',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        zindex: 9999999
                    });
                }, 1000);
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeClass('opacity6');
        }
    });
}
function course_sched_rm_schedule(){
    var thisBtn = $('#courseSchedRmModal .schdrm');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var form_data = {
        id:id,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/sections/courseSchedRmSchedule',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() { 
            thisBtn.addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeClass('opacity6');
            if(data=='error'){
                toastr.success('Error');
            }else{
                $('#courseSchedRmModal #schedule').html(data);
                $(".select2-schedule").select2({
                    dropdownParent: $("#schedule")
                });
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeClass('opacity6');
        }
    });
}
function course_sched_rm_rm_instructor(){
    var thisBtn = $('#courseSchedRmModal .schdrm');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();    
    var form_data = {
        id:id,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/sections/courseSchedRmInstructor',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() { 
            thisBtn.attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            if(data=='error'){
                toastr.success('Error');
            }else{
                $('#courseSchedRmModal #rm_instructor').html(data);
                $(".select2-rm_instructor").select2({
                    dropdownParent: $("#rm_instructor")
                });
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
        }
    });
}
function view_sections_by_program(){
    var thisBtn = $('#sectionDiv #programsSelectDiv select[name="program"]');
    var program_id = thisBtn.val();
    var id = $('#sectionDiv select[name="school_year"]').val();
    var form_data = {
        url_table:base_url+'/rims/sections/viewTable',
        tid:'viewTable',
        id:id,
        program_id:program_id
    };
    loadTablewLoader(form_data,thisBtn);
}
function view_sections(){
    var thisBtn = $('#sectionDiv select[name="school_year"]');
    var id = thisBtn.val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/sections/programsSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
            $('#sectionDiv #programsSelectDiv select[name="program"]').attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#sectionDiv #programsSelectDiv select[name="program"]').removeAttr('disabled');
            if(data=='error'){
                toastr.success('Error');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');                
                $('#sectionDiv #programsSelectDiv').html(data);
                var program_id = $('#sectionDiv #programsSelectDiv select[name="program"]').val();
                $(".select2-programsSelect").select2({
                    dropdownParent: $("#programsSelectDiv")
                });
                var form_data = {
                    url_table:base_url+'/rims/sections/viewTable',
                    tid:'viewTable',
                    id:id,
                    program_id:program_id
                };
                loadTablewLoader(form_data,thisBtn);
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
function rm_instructor_update(){
    var thisBtn = $('#courseSchedRmModal #rm_instructor .select2-rm_instructor');
    var id = $('#courseSchedRmModal input[name="id"]').val();
    var instructor_id = $('#courseSchedRmModal #rm_instructor select[name="instructor"] option:selected').val();
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
    var hours = parseInt($('#courseSchedRmModal #rm_instructor input[name="hours"]').val());
    var minutes = $('#courseSchedRmModal #rm_instructor select[name="minutes"] option:selected').val();
    var time = $('#courseSchedRmModal #rm_instructor select[name="time"] option:selected').val();
    var type = $('#courseSchedRmModal #rm_instructor input[name="type"]:checked').val();
    var days = [];
    var x = 0;
    if(hours<0 || hours>=8){
        $('#courseSchedRmModal #rm_instructor input[name="hours"]').addClass('border-require');
        toastr.error('Hours must be lower than 8');
        x++;
    }
    if(x==0){
        $('#courseSchedRmModal #rm_instructor select[name="days[]"] option:selected').each(function () {
            days.push($(this).val());
        }); 
        var form_data = {
            id:id,
            instructor_id:instructor_id,
            room_id:room_id,
            schedule_id:schedule_id,
            hours:hours,
            minutes:minutes,
            days:days,
            time:time,
            type:type
        };
        $.ajax({
            url: base_url+'/rims/sections/courseSchedRmInstructorUpdate',
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
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    if(data.schedule_id=='new'){
                        $('#courseSchedRmModal #rm_instructor select[name="time"]').val("TBA");
                        $('#courseSchedRmModal #schedule select[name="schedule"]').val("new");
                    }
                    $('#courseSchedRmModal #schedule select[name="schedule"] option[value="' + data.schedule_id + '"]').remove();
                    $('#courseSchedRmModal #schedule select[name="schedule"]').append($('<option value="'+data.schedule_id+'" selected>'+data.sched_name+'</option>'));
                    course_sched_rm_details();
                    course_sched_rm_schedule();
                    setTimeout(function() {
                        thisBtn.removeAttr('disabled');
                        course_sched_rm_table();
                    }, 1000)
                }else if(data.result=='error'){
                    thisBtn.removeAttr('disabled');
                    toastr.error('Error.');
                    thisBtn.addClass('input-error');                
                }else{
                    thisBtn.removeAttr('disabled');
                    toastr.error(data.result);
                    thisBtn.addClass('input-error');
                    course_sched_rm_table();
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
function course_view_table(id,thisBtn){
    var form_data = {
        url_table:base_url+'/rims/sections/courseViewTable',
        tid:'courseViewTable',
        id:id
    };
    loadTable(form_data,thisBtn);
}
function select_day(){
    var get_time = [];
    var get_day = [];
    var select_days = [];
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    var select_time = $('#courseSchedRmModal #rm_instructor select[name="time"] option:selected').val();
    $('#courseSchedRmModal #courseSchedRmTable .schedDayTimeInput').each(function () {
        get_time.push($(this).data('t'));
        get_day.push($(this).data('d'));
    }); 
    $('#courseSchedRmModal #rm_instructor select[name="days[]"] option:selected').each(function () {
        select_days.push($(this).val());
    });
    $('#courseSchedRmModal #rm_instructor select[name="days[]"]').select2({
        dropdownParent: $("#rm_instructor"),
        ajax: { 
        url: base_url+'/rims/schedule/selectDays',
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                _token: CSRF_TOKEN,
                schedule_id:schedule_id,
                get_day:get_day,
                get_time:get_time,                
                select_days:select_days,
                select_time:select_time,
                search: params.term
            };
        },
        processResults: function (response) {
            return {
            results: response
            };
        },
        cache: true
        }
    });
}
function select_time(){
    var get_time = [];
    var get_day = [];
    var select_days = [];
    var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
    $('#courseSchedRmModal #courseSchedRmTable .schedDayTimeInput').each(function () {
        get_time.push($(this).data('t'));
        get_day.push($(this).data('d'));
    }); 
    $('#courseSchedRmModal #rm_instructor select[name="days[]"] option:selected').each(function () {
        select_days.push($(this).val());
    });
    $('#courseSchedRmModal #rm_instructor select[name="time"]').select2({
        dropdownParent: $("#rm_instructor"),
        ajax: { 
        url: base_url+'/rims/schedule/selectTime',
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                _token: CSRF_TOKEN,
                schedule_id:schedule_id,
                get_day:get_day,
                select_days:select_days,
                get_time:get_time,
                search: params.term
            };
        },
        processResults: function (response) {
            return {
            results: response
            };
        },
        cache: true
        }
    });
}