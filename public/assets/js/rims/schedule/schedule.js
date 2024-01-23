$(document).off('click', '.scheduleCourseModal').on('click', '.scheduleCourseModal', function (e) {   
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schedule/scheduleCourseModal';
    var modal = 'info';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
          var loaderImage = base_url+"/assets/images/loader/loader-dots.gif";
          var loaderModal = 
                        '<div class="modal-content bg-default">'+
                          '<div id="loader-icon"><div class="overlay">'+
                              '<img src="'+loaderImage+'" alt="IDSLoader" class="loaderModal">'+
                          '</div></div>'+
                          '<div class="modal-header">'+
                            '<h4 class="modal-title"><span class="fa fa-info"></span> </h4>'+
                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                              '<span aria-hidden="true">&times;</span>'+
                            '</button><br><br><br>'+
                          '</div>'+
                      '<div class="modal-footer">'+
                              '<button type="button" class="btn btn-info close-modal" data-dismiss="modal">Close</button>'+
                          '</div>'+
                      '</div>';
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#modal-'+form_data.modal).modal('show');
            $("#modal-"+form_data.modal+" .modal-dialog #modal-"+form_data.modal+"-content").html('');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-xxl');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-xl');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-lg');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-md');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-sm');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-xs');
            $("#modal-"+form_data.modal+" .modal-dialog").addClass(form_data.modal_size);
            
            $("#loader-icon").removeClass("hide");
            $("#modal-"+form_data.modal+" .modal-dialog #modal-"+form_data.modal+"-content").html(loaderModal);
        },
        success : function(data){
            $("#loader-icon").addClass('hide');
            thisBtn.removeAttr('disabled'); 
            thisBtn.removeClass('input-loading');
            thisBtn.addClass('input-success');          
            $("#modal-"+form_data.modal+" .modal-dialog #modal-"+form_data.modal+"-content").html(data); 
            $(".select2-"+form_data.modal).select2({
                dropdownParent: $("#modal-"+form_data.modal)
            }); 
            setTimeout(function() {
              thisBtn.removeClass('input-success');
              thisBtn.removeClass('input-error');
            }, 3000);

            scheduleCourseTime();            
        },
        error: function (){
          toastr.error('Error!');
          thisBtn.removeAttr('disabled');         
          thisBtn.removeClass('input-success');
          thisBtn.removeClass('input-error');
        }
    });
});
$(document).off('change', '#scheduleModal select[name="schedule"]').on('change', '#scheduleModal select[name="schedule"]', function (e) { 
    selectSchedule();
});
$(document).off('click', '#scheduleModal button[name="save"]').on('click', '#scheduleModal button[name="save"]', function (e) { 
    scheduleCourseUpdate();
});
$(document).off('change', '#scheduleModal select[name="room"]').on('change', '#scheduleModal select[name="room"]', function (e) { 
    scheduleCourseTableRe(); 
});
$(document).off('change', '#scheduleModal select[name="instructor"]').on('change', '#scheduleModal select[name="instructor"]', function (e) { 
    scheduleCourseTableRe(); 
});
$(document).off('change', '#scheduleModal select[name="hours"]').on('change', '#scheduleModal select[name="hours"]', function (e) { 
    var start = $('#scheduleModal input[name="start"]').val();
    if(start==1){
        $('#scheduleModal select[name="time"] option:selected').empty();
        selectDetailsList('daysDiv','selectDay','select[name="days[]"]');
        selectDetailsList('timeDiv','selectTime','select[name="time"]');
        selectDetailsList('roomDiv','selectRoom','select[name="room"]');
        selectDetailsList('instructorDiv','selectInstructor','select[name="instructor"]');
    }
});
$(document).off('change', '#scheduleModal select[name="minutes"]').on('change', '#scheduleModal select[name="minutes"]', function (e) { 
    var start = $('#scheduleModal input[name="start"]').val();
    if(start==1){
        $('#scheduleModal select[name="time"] option:selected').empty();
        selectDetailsList('daysDiv','selectDay','select[name="days[]"]');
        selectDetailsList('timeDiv','selectTime','select[name="time"]');
        selectDetailsList('roomDiv','selectRoom','select[name="room"]');
        selectDetailsList('instructorDiv','selectInstructor','select[name="instructor"]');
    }
});
$(document).off('change', '#scheduleModal select[name="days[]"]').on('change', '#scheduleModal select[name="days[]"]', function (e) { 
    selectDetailsList('daysDiv','selectDay','select[name="days[]"]');
    selectDetailsList('timeDiv','selectTime','select[name="time"]');
    selectDetailsList('roomDiv','selectRoom','select[name="room"]');
    selectDetailsList('instructorDiv','selectInstructor','select[name="instructor"]');
});
$(document).off('change', '#scheduleModal select[name="time"]').on('change', '#scheduleModal select[name="time"]', function (e) { 
    selectDetailsList('daysDiv','selectDay','select[name="days[]"]');
    selectDetailsList('timeDiv','selectTime','select[name="time"]');
    selectDetailsList('roomDiv','selectRoom','select[name="room"]');
    selectDetailsList('instructorDiv','selectInstructor','select[name="instructor"]');  
});
$(document).off('click', '#scheduleModal .scheduleRemoveDay').on('click', '#scheduleModal .scheduleRemoveDay', function (e) { 
    var thisB = $(this);
    scheduleRemoveDay(thisB);
});
$(document).off('click', '#scheduleModal button[name="delete"]').on('click', '#scheduleModal button[name="delete"]', function (e) { 
    scheduleRemove();    
});

function scheduleCourseTable(){
    var id = $('#scheduleModal input[name="id"]').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var thisBtn = $('#scheduleModal select');
    var form_data = {
        url_table:base_url+'/rims/schedule/scheduleCourseTable',
        tid:'scheduleCourseTable',
        id:id,
        schedule_id:schedule_id
    };
    loadDivwDisabled(form_data,thisBtn);    
}
function scheduleCourseTableRe(){
    var id = $('#scheduleModal input[name="id"]').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var instructor_id = $('#scheduleModal select[name="instructor"] option:selected').val();
    var room_id = $('#scheduleModal select[name="room"] option:selected').val();
    var thisBtn = $('#scheduleModal select');
    var form_data = {
        url_table:base_url+'/rims/schedule/scheduleCourseTableRe',
        tid:'scheduleCourseTable',
        id:id,
        schedule_id:schedule_id,
        instructor_id:instructor_id,
        room_id:room_id
    };
    loadDivwDisabled(form_data,thisBtn);    
}
function selectDetailsList(div,url,select){
    $(document).ready(function() {
        var select_days = [];
        var id = $('#scheduleModal input[name="id"]').val();
        var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
        var select_time = $('#scheduleModal select[name="time"] option:selected').val();
        var select_hours = $('#scheduleModal select[name="hours"] option:selected').val();
        var select_minutes = $('#scheduleModal select[name="minutes"] option:selected').val();
        var instructor_id = $('#scheduleModal select[name="instructor"] option:selected').val();
        var room_id = $('#scheduleModal select[name="room"] option:selected').val();
        $('#scheduleModal select[name="days[]"] option:selected').each(function () {
            select_days.push($(this).val());
        });
        $('#scheduleModal '+select).select2({
            dropdownParent: $("#scheduleModal #"+div),
            ajax: { 
            url: base_url+'/rims/schedule/'+url,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    _token: CSRF_TOKEN,
                    id:id,
                    schedule_id:schedule_id,
                    select_days:select_days,
                    select_time:select_time,
                    select_hours:select_hours,
                    select_minutes:select_minutes,
                    instructor_id:instructor_id,
                    room_id:room_id,
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
    });
}
function selectSchedule(){
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    if(schedule_id=='New'){
        $('#scheduleModal select[name="room"]').empty();
        $('#scheduleModal select[name="instructor"]').empty();
        $('#scheduleModal select[name="days[]"]').empty();
        $('#scheduleModal select[name="time"]').empty();
        $('#scheduleModal select[name="room"]').append('<option value="TBA">TBA</option>');        
        $('#scheduleModal select[name="instructor"]').append('<option value="TBA">TBA</option>');        
        $('#scheduleModal select[name="time"]').append('<option value="TBA">TBA</option>');
    }else{
        var start = $('#scheduleModal input[name="start_sched"]').val();
        if(start==1){
            $('#scheduleModal input[name="start"]').val(0);
            scheduleCourseDetails();
        }
    }
}
function scheduleCourseTime(){
    var thisBtn = $('#scheduleModal select[name="schedule"]');
    var id = $('#scheduleModal input[name="id"]').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/schedule/scheduleCourseTime',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            $('#scheduleModal select').attr('disabled','disabled');
        },
        success : function(data){     
            $('#scheduleModal select').removeAttr('disabled');
            if(data.result=='error'){
                toastr.success('Error');
            }else{
                if(data.schedules){
                    thisBtn.empty();
                    thisBtn.append('<option value="New">New</option>');
                    $.each(data.schedules, function(key, value) {
                        thisBtn.append('<option value="' + value.id + '">' + value.text + '</option>');
                    });
                    thisBtn.val(data.schedule_id).change();
                }
                $('#scheduleModal input[name="start_sched"]').val(1);
                scheduleCourseDetails();
            }
        },
        error: function (){
            toastr.error('Error!');
            $('#scheduleModal select').removeAttr('disabled');
        }
    });
}

function scheduleCourseDetails(){
    var id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/schedule/scheduleCourseDetails',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            $('#scheduleModal select').attr('disabled','disabled');
        },
        success : function(data){     
            $('#scheduleModal select').removeAttr('disabled');
            if(data.result=='error'){
                toastr.success('Error');
            }else{
                $('#scheduleModal #scheduleRemoveDiv').html('');
                $('#scheduleModal select[name="days[]"]').empty();
                $('#scheduleModal select[name="time"]').empty();
                $('#scheduleModal select[name="time"]').append('<option value="TBA">TBA</option>');
                var x = 0;
                if(data.days){                    
                    $.each(data.days, function(key, value) {
                        $('#scheduleModal select[name="days[]"]').append('<option value="' + value.id + '" selected>' + value.text + '</option>');
                    });
                    x++;
                }
                if(data.time){
                    $('#scheduleModal select[name="time"]').append('<option value="' + data.time + '" selected>' + data.time + '</option>');
                    x++;
                }
                if(x==2){
                    $('#scheduleModal #scheduleRemoveDiv').html('<button class="btn btn-danger btn-danger-scan" name="delete" style="width:100%"><span class="fa fa-trash"></span> Remove this Schedule</button>');
                }
                if(data.hours){
                    $('#scheduleModal select[name="hours"]').val(data.hours).change();
                }
                if(data.minutes){
                    $('#scheduleModal select[name="minutes"]').val(data.minutes).change();
                }
                if (data.type === 'Lab') {
                    $('#scheduleModal #laboratory').prop('checked', true);
                } else {
                    $('#scheduleModal #lecture').prop('checked', true);
                }
                $('#scheduleModal input[name="start"]').val(1);
                scheduleCourseTable();
            }
        },
        error: function (){
            toastr.error('Error!');
            $('#scheduleModal select').removeAttr('disabled');
        }
    });
}
function scheduleCourseUpdate(){
    var thisBtn = $('#scheduleModal button[name="save"]');
    var select_days = [];
    var id = $('#scheduleModal input[name="id"]').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var room_id = $('#scheduleModal select[name="room"] option:selected').val();
    var instructor_id = $('#scheduleModal select[name="instructor"] option:selected').val();    
    var select_time = $('#scheduleModal select[name="time"] option:selected').val();
    var select_hours = $('#scheduleModal select[name="hours"] option:selected').val();
    var select_minutes = $('#scheduleModal select[name="minutes"] option:selected').val();
    var select_type = $('#scheduleModal input[name="type"]:checked').val();
    $('#scheduleModal select[name="days[]"] option:selected').each(function () {
        select_days.push($(this).val());
    });
    var form_data = {
        id:id,
        schedule_id:schedule_id,
        room_id:room_id,
        instructor_id:instructor_id,        
        select_hours:select_hours,
        select_minutes:select_minutes,
        select_days:select_days,
        select_time:select_time,
        select_type:select_type
    };
    $.ajax({
        url: base_url+'/rims/schedule/scheduleCourseUpdate',
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
            $('#scheduleModal select').attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#scheduleModal select').removeAttr('disabled');
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                if(schedule_id=='New'){
                    if(data.schedule_id=='New'){
                        $('#scheduleModal select[name="schedule"]').val(data.schedule_id).change();
                    }else{
                        $('#scheduleModal select[name="schedule"]').append('<option value="' + data.schedule_id + '" selected>' + data.schedule_time + '</option>');
                        $('#scheduleModal #scheduleRemoveDiv').html('<button class="btn btn-danger btn-danger-scan" name="delete" style="width:100%"><span class="fa fa-trash"></span> Remove this Schedule</button>');
                    }
                }else{
                    $('#scheduleModal select[name="schedule"]').val(data.schedule_id).change();
                    $('#scheduleModal #scheduleRemoveDiv').html('<button class="btn btn-danger btn-danger-scan" name="delete" style="width:100%"><span class="fa fa-trash"></span> Remove this Schedule</button>');
                }
                labels(data.labels);
                scheduleCourseTable();
                setTimeout(function() {
                    thisBtn.removeClass('input-success');
                    thisBtn.removeClass('input-error');
                }, 3000);
                
            }else{
                toastr.error(data.result);
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#scheduleModal select').removeAttr('disabled');
        }
    });
}
function scheduleRemove(){
    var thisBtn = $('#scheduleModal button[name="delete"]');
    var id = $('#scheduleModal input[name="id"]').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var form_data = {
        id:id,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/schedule/scheduleRemove',
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
                scheduleCourseTable();
                $('#scheduleModal .bg-success').html('&nbsp;&nbsp;');
                $('#scheduleModal .bg-success').removeClass('bg-success');
                var list_x = data.list_x;
                if(list_x.length === 0){
                    $('#scheduleModal #scheduleRemoveDayTr').addClass('hide');                      
                }else{
                    $('#scheduleModal #scheduleRemoveDayTr').removeClass('hide');
                    $.each(list_x, function(index, val) {                    
                        var split = val.split("_");
                        $('#scheduleModal #dayTime'+split[0]).removeClass('btn-no-design');
                        $('#scheduleModal #dayTime'+split[0]).addClass('bg-success btn-no-design');
                        $('#scheduleModal #dayTime'+split[0]).html(split[1]);
                    });                    
                }
                if(data.schedule_id=='New'){
                    $('#scheduleModal #scheduleRemoveDiv').html('');
                }
                $('#scheduleModal select[name="schedule"] option[value="' + schedule_id + '"]').remove();
                $('#scheduleModal select[name="schedule"]').val(data.schedule_id).change();
                labels(data.labels);
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
function scheduleRemoveDay(thisB){
    var thisBtn = $('#scheduleModal .scheduleRemoveDay');
    var id = $('#scheduleModal input[name="id"]').val();
    var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
    var d = thisB.data('d');
    var form_data = {
        id:id,
        d:d,
        schedule_id:schedule_id
    };
    $.ajax({
        url: base_url+'/rims/schedule/scheduleRemoveDay',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisB.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisB.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success');
                thisB.addClass('input-success');
                scheduleCourseTable();
                $('#scheduleModal .bg-success').html('&nbsp;&nbsp;');
                $('#scheduleModal .bg-success').addClass('blank');
                $('#scheduleModal .bg-success').removeClass('bg-success');
                var list_x = data.list_x;
                if(list_x.length === 0){
                    $('#scheduleModal #scheduleRemoveDayTr').addClass('hide');                      
                }else{
                    $('#scheduleModal #scheduleRemoveDayTr').removeClass('hide');
                    $.each(list_x, function(index, val) {
                        var split = val.split("_");
                        $('#scheduleModal #dayTime'+split[0]).removeClass('bg-success-light btn-no-design');
                        $('#scheduleModal #dayTime'+split[0]).addClass('bg-success btn-no-design');
                        $('#scheduleModal #dayTime'+split[0]).html(split[1]);
                    });                    
                }
                if(data.schedule_id=='New'){
                    $('#scheduleModal #scheduleRemoveDiv').html('');
                }
                if(data.schedule_id!=schedule_id){
                    $('#scheduleModal select[name="schedule"] option[value="'+schedule_id+'"]').remove();
                }
                $('#scheduleModal select[name="schedule"]').val(data.schedule_id).change();
                labels(data.labels);
                console.log(data.labels);
            }else{
                toastr.error('Error.');
                thisB.addClass('input-error');
            }
            setTimeout(function() {
                thisB.removeClass('input-success');
                thisB.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisB.removeClass('input-success');
            thisB.removeClass('input-error');
        }
    });
}
function labels(labels){
    $('#scheduleModal #scheduleLabel').html(labels.scheduleLabel); 
    $('#scheduleModal #roomLabel').html(labels.roomLabel); 
    $('#scheduleModal #instructorLabel').html(labels.instructorLabel);

    $('#courseSchedule'+labels.id).html('<u>'+labels.scheduleLabel+'</u>'); 
    $('#courseRoom'+labels.id).html('<u>'+labels.roomLabel+'</u>'); 
    $('#courseInstructor'+labels.id).html('<u>'+labels.instructorLabel+'</u>');
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
                // setTimeout(function() {
                //     $(".timepicker-course").inputmask('99:99aa');
                //     $('.timepicker-course').timepicker({
                //         dropdownParent: $("#timeDiv"),
                //         timeFormat: 'hh:mma',
                //         interval: 15,
                //         minTime: '07',
                //         maxTime: '11:00pm',
                //         startTime: '07:30',
                //         dynamic: false,
                //         dropdown: true,
                //         scrollbar: true,
                //         zindex: 9999999
                //     });
                // }, 1000);
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

// function selectRoom(){
//     $(document).ready(function() {
//         var course_id = $('#courseSchedRmModal input[name="id"]').val();
//         var instructor_id = $('#courseSchedRmModal #rm_instructor select[name="instructor"] option:selected').val();
//         var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
//         var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
//         $('#courseSchedRmModal #rm_instructor select[name="room"]').select2({
//             dropdownParent: $("#rm_instructor"),
//             ajax: { 
//             url: base_url+'/rims/schedule/selectRoom',
//             type: "post",
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     _token: CSRF_TOKEN,
//                     course_id:course_id,
//                     instructor_id:instructor_id,
//                     schedule_id:schedule_id,
//                     room_id:room_id,
//                     search: params.term
//                 };
//             },
//             processResults: function (response) {
//                 return {
//                 results: response
//                 };
//             },
//             cache: true
//             }
//         });
//     });
// }
// function selectInstructor(){
//     $(document).ready(function() {
//         var course_id = $('#courseSchedRmModal input[name="id"]').val();
//         var instructor_id = $('#courseSchedRmModal #rm_instructor select[name="instructor"] option:selected').val();
//         var schedule_id = $('#courseSchedRmModal #schedule select[name="schedule"] option:selected').val();
//         var room_id = $('#courseSchedRmModal #rm_instructor select[name="room"] option:selected').val();
//         $('#courseSchedRmModal #rm_instructor select[name="instructor"]').select2({
//             dropdownParent: $("#rm_instructor"),
//             ajax: { 
//             url: base_url+'/rims/schedule/selectInstructor',
//             type: "post",
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     _token: CSRF_TOKEN,
//                     course_id:course_id,
//                     instructor_id:instructor_id,
//                     schedule_id:schedule_id,
//                     room_id:room_id,
//                     search: params.term
//                 };
//             },
//             processResults: function (response) {
//                 return {
//                 results: response
//                 };
//             },
//             cache: true
//             }
//         });
//     });
// }
// function select_day(){
//     var get_time = [];
//     var get_day = [];
//     var select_days = [];
//     var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
//     var select_time = $('#scheduleModal select[name="time"] option:selected').val();
//     $('#scheduleModal .schedDayTimeInput').each(function () {
//         get_time.push($(this).data('t'));
//         get_day.push($(this).data('d'));
//     }); 
//     $('#scheduleModal select[name="days[]"] option:selected').each(function () {
//         select_days.push($(this).val());
//     });
//     $('#scheduleModal select[name="days[]"]').select2({
//         dropdownParent: $("#scheduleModal #daysDiv"),
//         ajax: { 
//         url: base_url+'/rims/schedule/selectDays',
//         type: "post",
//         dataType: 'json',
//         delay: 250,
//         data: function (params) {
//             return {
//                 _token: CSRF_TOKEN,
//                 schedule_id:schedule_id,
//                 get_day:get_day,
//                 get_time:get_time,                
//                 select_days:select_days,
//                 select_time:select_time,
//                 search: params.term
//             };
//         },
//         processResults: function (response) {
//             return {
//             results: response
//             };
//         },
//         cache: true
//         }
//     });
// }
// function select_time(){
//     var get_time = [];
//     var get_day = [];
//     var select_days = [];
//     var schedule_id = $('#scheduleModal select[name="schedule"] option:selected').val();
//     $('#scheduleModal .schedDayTimeInput').each(function () {
//         get_time.push($(this).data('t'));
//         get_day.push($(this).data('d'));
//     }); 
//     $('#scheduleModal select[name="days[]"] option:selected').each(function () {
//         select_days.push($(this).val());
//     });
//     $('#scheduleModal select[name="time"]').select2({
//         dropdownParent: $("#scheduleModal #timeDiv"),
//         ajax: { 
//         url: base_url+'/rims/schedule/selectTime',
//         type: "post",
//         dataType: 'json',
//         delay: 250,
//         data: function (params) {
//             return {
//                 _token: CSRF_TOKEN,
//                 schedule_id:schedule_id,
//                 get_day:get_day,
//                 select_days:select_days,
//                 get_time:get_time,
//                 search: params.term
//             };
//         },
//         processResults: function (response) {
//             return {
//             results: response
//             };
//         },
//         cache: true
//         }
//     });
// }