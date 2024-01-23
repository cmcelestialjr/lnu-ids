function view_schedule(){
    var thisBtn = $('#scheduleDiv select[name="school_year"]');
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
            $('#scheduleDiv #programsSelectDiv select[name="program"]').attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#scheduleDiv #programsSelectDiv select[name="program"]').removeAttr('disabled');
            if(data=='error'){
                toastr.success('Error');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');                
                $('#scheduleDiv #programsSelectDiv').html(data);
                $(".select2-programsSelect").select2({
                    dropdownParent: $("#programsSelectDiv")
                });
                view_by_program();
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
function view_search_div(){
    var thisBtn = $('#scheduleDiv #search select');
    var school_year = $('#scheduleDiv #search select[name="school_year_search"] option:selected').val();
    var option = $('#scheduleDiv #search select[name="option"] option:selected').val();    
    var form_data = {
        option:option,
        school_year:school_year
    };
    $.ajax({
        url: base_url+'/rims/schedule/searchDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
            $('#scheduleDiv #search #schedSearchDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#scheduleDiv #search #schedSearchDiv').removeClass('opacity6');
            if(data=='error'){
                toastr.success('Error');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');                
                $('#scheduleDiv #search #schedSearchDiv').html(data);                
                $(".select2-search").select2({
                    dropdownParent: $("#schedSearchDiv")
                });                
                courseSearch(school_year);
                sched_search_div();
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
function sched_search_div(){
    var thisBtn = $('#scheduleDiv #search select');
    var school_year = $('#scheduleDiv #search select[name="school_year_search"] option:selected').val();
    var option = $('#scheduleDiv #search select[name="option"] option:selected').val();
    var option_select = $('#scheduleDiv #search #schedSearchDiv select[name="option_select"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schedule/searchTable',
        tid:'searchTable',
        school_year:school_year,
        option:option,
        option_select:option_select
    };
    loadTablewLoader(form_data,thisBtn);
}
function sched_wo_table(){
    var thisBtn = $('#scheduleDiv #wo select');
    var school_year = $('#scheduleDiv #wo select[name="school_year_wo"] option:selected').val();
    var option = [];
    $('#scheduleDiv #wo select[name="option_wo[]"] option:selected').each(function() {
        option.push($(this).val());
    });
    var form_data = {
        url_table:base_url+'/rims/schedule/schedWoTable',
        tid:'schedWoTable',
        school_year:school_year,
        option:option
    };
    loadTablewLoader(form_data,thisBtn);
}
function view_by_program(){
    var thisBtn = $('#scheduleDiv #view select');
    var school_year = $('#scheduleDiv #view select[name="school_year"] option:selected').val();
    var program = $('#scheduleDiv #view select[name="program"] option:selected').val();
    var branch = $('#scheduleDiv #view select[name="branch"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schedule/viewTable',
        tid:'viewTable',
        school_year:school_year,
        program:program,
        branch:branch
    };
    loadTablewLoader(form_data,thisBtn);
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
                    selectRoom();
                    selectInstructor();
                    setTimeout(function() {
                        thisBtn.removeAttr('disabled');
                        course_sched_rm_table();
                        view_by_program();
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
