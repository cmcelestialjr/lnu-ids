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
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_conflict'+course_id).removeClass('btn-danger btn-danger-scan');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_conflict'+course_id).addClass('btn-primary btn-primary-scan');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).removeClass('hide');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).attr('checked', 'checked');
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).data('id', course_id_another);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_checked'+course_id).data('cid', course_id_another1);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_code'+course_id).html('<br>'+data.code);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_name'+course_id).html('<br>'+data.name);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_units'+course_id).html('<br>'+data.units);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_pre_name'+course_id).html('<br>'+data.pre_name);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_schedule'+course_id).html('<br>'+data.schedule);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_room'+course_id).html('<br>'+data.room);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_instructor'+course_id).html('<br>'+data.instructor);
                    $('#enrollModal #studentInformationDiv #programCoursesDiv #course_status'+course_id).html('<br>'+data.status);
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
                    $('#enrollModal #studentInformationDiv #courseAddedDiv').removeClass('hide');
                    $('#modal-primary').modal('hide');
                    $.each(data.query, function(i, val) {
                        $('#enrollModal #studentInformationDiv #courseAddedDiv').append('<tr>'+
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
$(document).on('click', '#enrollModal #studentInformationDiv #programCoursesDiv button[name="submit_curriculum"]', function (e) {
    var thisBtn = $(this);    
    var student_id = $('#enrollModal select[name="student"] option:selected').val();
    var program_id = $('#enrollModal #studentInformationDiv select[name="program"] option:selected').val();
    var curriculum_id = $('#enrollModal #studentInformationDiv #programCurriculumDiv select[name="program_curriculum"] option:selected').val();
    var section = $('#enrollModal #studentInformationDiv #programSectionDiv select[name="program_section"] option:selected').val();
    var courses = [];
    var cid = [];
    $('#enrollModal .courseCheck:checked').each(function () {
        courses.push($(this).data('id'));
        cid.push($(this).data('cid'));
    }); 
    if(courses!=''){
        var form_data = {
            student_id:student_id,
            program_id:program_id,
            curriculum_id:curriculum_id,
            section:section,
            courses:courses,
            cid:cid
        };
        $.ajax({
            url: base_url+'/rims/enrollment/enrollSubmit',
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
                    enrollment();
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
$(document).on('click', '#enrollModal #studentInformationDiv #programCoursesDiv button[name="submit_advisement"]', function (e) {
    var thisBtn = $(this);    
    var courses = [];
    var cid = [];
    $('#enrollModal .advisedCourseCheck:checked').each(function () {
        courses.push($(this).data('id'));
        cid.push($(this).data('cid'));
    }); 
    if(courses!=''){
        var form_data = {
            courses:courses,
            cid:cid
        };
        $.ajax({
            url: base_url+'/rims/enrollment/enrollAdvisedSubmit',
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
                    enrollment();
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