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
    var form_data = {
        id:id
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