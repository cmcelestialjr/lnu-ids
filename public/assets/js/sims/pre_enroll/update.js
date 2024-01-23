$(document).on('click', '#courseAnotherModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var course_id = $('#courseAnotherModal input[name="course_id"]').val();
    var course_id_another = $('.anotherCourseSelected:checked').val();
    var course_id_another1 = $('.anotherCourseSelected:checked').data('id');

    if(course_id_another){
        var form_data = {
            course_id:course_id,
            course_id_another:course_id_another
        };
        $.ajax({
            url: base_url+'/rims/enrollment/courseAnotherSubmit',
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
                    $('#modal-primary').modal('hide');
                    $('#preEnrollDiv #course_conflict'+course_id).removeClass('btn-danger btn-danger-scan');
                    $('#preEnrollDiv #course_conflict'+course_id).addClass('btn-primary btn-primary-scan');
                    $('#preEnrollDiv #course_checked'+course_id).removeClass('hide');
                    $('#preEnrollDiv #course_checked'+course_id).attr('checked', 'checked');
                    $('#preEnrollDiv #course_checked'+course_id).data('id', course_id_another);
                    $('#preEnrollDiv #course_checked'+course_id).data('cid', course_id_another1);
                    $('#preEnrollDiv #course_code'+course_id).html('<br>'+data.code);
                    $('#preEnrollDiv #course_name'+course_id).html('<br>'+data.name);
                    $('#preEnrollDiv #course_units'+course_id).html('<br>'+data.units);
                    $('#preEnrollDiv #course_pre_name'+course_id).html('<br>'+data.pre_name);
                    $('#preEnrollDiv #course_schedule'+course_id).html('<br>'+data.schedule);
                    $('#preEnrollDiv #course_room'+course_id).html('<br>'+data.room);
                    $('#preEnrollDiv #course_instructor'+course_id).html('<br>'+data.instructor);
                    $('#preEnrollDiv #course_status'+course_id).html('<br>'+data.status);
                    course_unit_total();
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
    }else{
        toastr.error('Please select a course');
    }
});
$(document).on('click', '#courseAddModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var courses = [];
    $('#courseAddModal .courseCheck:checked').each(function () {
        courses.push($(this).data('id'));
    }); 
    if(courses!=''){
        var form_data = {
            courses:courses
        };
        $.ajax({
            url: base_url+'/rims/enrollment/courseAddSubmit',
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
                    $('#preEnrollDiv #courseAddedDiv').removeClass('hide');
                    $('#modal-primary').modal('hide');
                    $.each(data.query, function(i, val) {
                        $('#preEnrollDiv #courseAddedDiv').append('<tr>'+
                            '<td class="center">'+val['program']+'</td>'+
                            '<td class="center">'+val['section']+'</td>'+
                            '<td class="center">'+val['code']+'</td>'+
                            '<td class="center"><span class="courseUnits">'+val['units']+'</span></td>'+
                            '<td class="center">'+val['schedule']+'</td>'+
                            '<td class="center">'+val['room']+'</td>'+
                            '<td class="center">'+val['instructor']+'</td>'+
                            '<td class="center"><input type="checkbox" class="form-control courseCheck" data-id="'+val['id']+'" data-u="'+val['units']+'" data-cid="" checked></td>'+
                            '<td class="center"><button class="btn btn-danger btn-danger-scan btn-xs" name="remove">Remove</button></td>'+
                            '</tr>');
                    });
                    course_unit_total();
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
    }else{
        toastr.error('Please select a course');
    }
});
$(document).on('click', '#preEnrollDiv button[name="submit_advisement"]', function (e) {
    var thisBtn = $(this);    
    var school_year_id = $('#preEnrollDiv input[name="school_year_id"]').val();
    var student_id = $('#preEnrollDiv input[name="id"]').val();
    var code = $('#preEnrollDiv input[name="code"]').val();
    var curriculum_id = $('#preEnrollDiv input[name="curriculum"]').val();
    var section = $('#preEnrollDiv select[name="section"] option:selected').val();
    var courses = [];
    var cid = [];
    var course_id = [];
    var course_option = [];
    $('#preEnrollDiv .courseCheck:checked').each(function () {
        courses.push($(this).data('id'));
        cid.push($(this).data('cid'));
        course_id.push($(this).data('ci'));
        course_option.push($(this).data('op'));
    });
    if(courses!=''){
        var form_data = {
            school_year_id:school_year_id,
            student_id:student_id,
            code:code,
            curriculum_id:curriculum_id,
            section:section,
            courses:courses,
            cid:cid,
            option:'Pre-enroll by',
            course_id:course_id,
            course_option:course_option
        };
        $.ajax({
            url: base_url+'/sims/pre_enroll/preenrollSubmit',
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
                    pre_enroll_div();
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
    }else{
        toastr.error('Please select a course');
    }
});