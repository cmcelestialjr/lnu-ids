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
                    $('#advisementDiv #course_conflict'+course_id).removeClass('btn-danger btn-danger-scan');
                    $('#advisementDiv #course_conflict'+course_id).addClass('btn-primary btn-primary-scan');
                    $('#advisementDiv #course_checked'+course_id).removeClass('hide');
                    $('#advisementDiv #course_checked'+course_id).attr('checked', 'checked');
                    $('#advisementDiv #course_checked'+course_id).data('id', course_id_another);
                    $('#advisementDiv #course_checked'+course_id).data('cid', course_id_another1);
                    $('#advisementDiv #course_code'+course_id).html('<br>'+data.code);
                    $('#advisementDiv #course_name'+course_id).html('<br>'+data.name);
                    $('#advisementDiv #course_units'+course_id).html('<br>'+data.units);
                    $('#advisementDiv #course_pre_name'+course_id).html('<br>'+data.pre_name);
                    $('#advisementDiv #course_schedule'+course_id).html('<br>'+data.schedule);
                    $('#advisementDiv #course_room'+course_id).html('<br>'+data.room);
                    $('#advisementDiv #course_instructor'+course_id).html('<br>'+data.instructor);
                    $('#advisementDiv #course_status'+course_id).html('<br>'+data.status);
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
                    $('#advisementDiv #courseAddedDiv').removeClass('hide');
                    $('#modal-primary').modal('hide');
                    $.each(data.query, function(i, val) {
                        $('#advisementDiv #courseAddedDiv').append('<tr>'+
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
$(document).on('click', '#advisementDiv #studentAdvisement button[name="submit_advisement"]', function (e) {
    var thisBtn = $(this);    
    var school_year_id = $('#advisementDiv select[name="school_year"] option:selected').val();
    var student_id = $('#advisementDiv select[name="student"] option:selected').val();
    var code = $('#advisementDiv select[name="code"] option:selected').val();
    var curriculum_id = $('#advisementDiv select[name="curriculum"] option:selected').val();
    var section = $('#advisementDiv select[name="section"] option:selected').val();
    var courses = [];
    var cid = [];
    $('#advisementDiv .courseCheck:checked').each(function () {
        courses.push($(this).data('id'));
        cid.push($(this).data('cid'));
    });
    if(courses!=''){
        var form_data = {
            school_year_id:school_year_id,
            student_id:student_id,
            code:code,
            curriculum_id:curriculum_id,
            section:section,
            courses:courses,
            cid:cid
        };
        $.ajax({
            url: base_url+'/fis/advisement/advisementSubmit',
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
                    advisement_table();
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