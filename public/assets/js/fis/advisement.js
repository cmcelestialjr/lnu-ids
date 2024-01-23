$(document).on('change', '#advisementDiv select[name="student"]', function (e) {
    student_info();
});
$(document).on('change', '#advisementDiv select[name="code"]', function (e) {
    curriculum_select();
});
$(document).on('change', '#advisementDiv select[name="curriculum"]', function (e) {
    section_select();
});
$(document).on('change', '#advisementDiv select[name="section"]', function (e) {
    advisement_table();
});
$(document).on('change', '#advisementDiv select[name="grade_level[]"]', function (e) {
    advisement_table();
});
$(document).on('change', '#courseAddModal select[name="program"]', function (e) {
    program_add_curriculum();
});
$(document).on('click', '#advisementDiv .courseCheck', function (e) {
    course_unit_total();
});
$(document).on('click', '#advisementDiv button[name="remove"]', function (e) {
    $(this).closest('tr').remove();
    var units = 0;
    $('#advisementDiv #courseAddedDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    if(units<=0){
        $('#advisementDiv #courseAddedDiv').addClass('hide');
    }
    course_unit_total();
});
$(document).on('click', '#advisementDiv button[name="remove"]', function (e) {
    $(this).closest('tr').remove();
    var units = 0;
    $('#advisementDiv #courseAddedDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    if(units<=0){
        $('#advisementDiv #courseAddedDiv').addClass('hide');
    }
    course_unit_total();
});
$(document).on('click', '#advisementDiv .year_check', function (e) {    
    var thisBtn = $(this);
    var val = thisBtn.val();    
    if (this.checked) {
        $('#advisementDiv .course_check'+val).prop('checked', true);
    }else{
        $('#advisementDiv .course_check'+val).prop('checked', false);
    }
    course_unit_total();
});
$(document).on('click', '#courseAddModal #programAddCourseDiv .year_check', function (e) {    
    var thisBtn = $(this);
    var val = thisBtn.val();    
    if (this.checked) {
        $('#courseAddModal #programAddCourseDiv .course_check'+val).prop('checked', true);
    }else{
        $('#courseAddModal #programAddCourseDiv .course_check'+val).prop('checked', false);
    }
});
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
$(document).on('click', '#advisementDiv button[name="submit_advisement"]', function (e) {
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
            cid:cid,
            option:'Advised by'
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
$(document).on('click', '#advisementDiv #courseAddModal', function (e) {
    var thisBtn = $(this);
    var id = $('#advisementDiv select[name="student"] option:selected').val();
    if(id!=''){
        var id = $('#advisementDiv select[name="school_year"] option:selected').val();        
        var url = base_url+'/rims/enrollment/courseAddModal';
        var modal = 'primary';
        var modal_size = 'modal-xl';
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
});
$(document).on('click', '#advisementDiv .courseAnotherModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/enrollment/courseAnotherModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/enrollment/courseAnotherTable',
        tid:'courseAnotherTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});
function student_info(){
    var thisBtn = $('#advisementDiv select');
    var school_year = $('#advisementDiv select[name="school_year"] option:selected').val();
    var student_id = $('#advisementDiv select[name="student"] option:selected').val();
    var optionAddDrop = '';
    if($('#advisementDiv select[name="optionAddDrop"] option:selected').length > 0){
        var optionAddDrop = $('#advisementDiv select[name="optionAddDrop"] option:selected').val();
        $('#advisementDiv #addDropAdd').removeClass('hide');
        $('#advisementDiv #addDropDrop').addClass('hide');
        $('#advisementDiv #addDropDrop').html('');
    }
    var form_data = {
        school_year:school_year,
        student_id:student_id,
        optionAddDrop:optionAddDrop
    };
    $.ajax({
        url: base_url+'/fis/advisement/studentInfo',
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
            $('#advisementDiv input[name="program"]').val('');
            $('#advisementDiv input[name="level"]').val('');
            $('#advisementDiv select[name="code"]').empty();
            $('#advisementDiv select[name="curriculum"]').empty();
            $('#advisementDiv select[name="section"]').empty();
            if(optionAddDrop=='Drop'){
                $('#advisementDiv #addDropAdd').addClass('hide');
                $('#advisementDiv #addDropDrop').removeClass('hide');
                $('#advisementDiv #addDropDrop').html('');
                $('#advisementDiv #addDropDrop').html('<center>'+loadingTemplate('')+'</center>');
            }else{
                $('#advisementDiv #addDropAdd').removeClass('hide');
                $('#advisementDiv #addDropDrop').addClass('hide');
                $('#advisementDiv #addDropDrop').html('');
                $('#advisementDiv #studentAdvisement').html('<center>'+loadingTemplate('')+'</center>');
            }
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                thisBtn.addClass('input-success');
                $('#advisementDiv input[name="program"]').val(data.program);
                $('#advisementDiv input[name="level"]').val(data.level);
                $.each(data.code, function(index, value) {
                    $('#advisementDiv select[name="code"]').append($('<option>', {
                        value: value['id'],
                        text: value['name']
                    }));
                });
                curriculum_select();
            }else{
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function curriculum_select(){
    var thisBtn = $('#advisementDiv select');
    var id = $('#advisementDiv select[name="student"] option:selected').val();
    var code = $('#advisementDiv select[name="code"] option:selected').val();
    var form_data = {
        id:id,
        code:code
    };
    $.ajax({
        url: base_url+'/fis/advisement/curriculumSelect',
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
            $('#advisementDiv select[name="curriculum"]').empty();
            $('#advisementDiv select[name="section"]').empty();
            $('#advisementDiv #studentAdvisement').html('<center>'+loadingTemplate('')+'</center>');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                thisBtn.addClass('input-success');
                $.each(data.curriculum, function(index, value) {
                    $('#advisementDiv select[name="curriculum"]').append($('<option>', {
                        value: value['id'],
                        text: value['name']
                    }));
                });
                section_select();
            }else{
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function section_select(){
    var thisBtn = $('#advisementDiv select');
    var curriculum = $('#advisementDiv select[name="curriculum"] option:selected').val();
    var optionAddDrop = '';
    if($('#advisementDiv select[name="optionAddDrop"] option:selected').length > 0){
        var optionAddDrop = $('#advisementDiv select[name="optionAddDrop"] option:selected').val();
    }
    var form_data = {
        curriculum:curriculum
    };
    $.ajax({
        url: base_url+'/fis/advisement/sectionSelect',
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
            $('#advisementDiv select[name="section"]').empty();
            $('#advisementDiv #studentAdvisement').html('<center>'+loadingTemplate('')+'</center>');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                thisBtn.addClass('input-success');
                $.each(data.section, function(index, value) {
                    $('#advisementDiv select[name="section"]').append($('<option>', {
                        value: value['id'],
                        text: value['name']
                    }));
                });
                $('#advisementDiv select[name="grade_level[]"]').empty();
                $.each(data.grade_level, function(index, value) {
                    $('#advisementDiv select[name="grade_level[]"]').append($('<option>', {
                        value: value['id'],
                        text: value['name']
                    }));
                });
                if(optionAddDrop=='Drop'){
                    courses_table();
                }else{
                    advisement_table();
                }
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
function advisement_table(){
    var thisBtn = $('#advisementDiv select');
    var school_year = $('#advisementDiv select[name="school_year"]').val();
    var student_id = $('#advisementDiv select[name="student"]').val();
    var code = $('#advisementDiv select[name="code"] option:selected').val();
    var curriculum = $('#advisementDiv select[name="curriculum"] option:selected').val();
    var section = $('#advisementDiv select[name="section"] option:selected').val();
    var grade_level = [];
    $('#advisementDiv select[name="grade_level[]"] option:selected').each(function () {
        grade_level.push($(this).val());
    }); 
    var form_data = {
        url_table:base_url+'/fis/advisement/studentAdvisement',
        tid:'studentAdvisement',
        school_year:school_year,
        student_id:student_id,
        code:code,
        curriculum:curriculum,
        section:section,
        grade_level:grade_level
    };
    loadDivwLoader(form_data,thisBtn);
    setTimeout(function() {
        course_unit_total();
    }, 1500);
}
function courses_table(){
    $('#advisementDiv #addDropAdd').addClass('hide');
    $('#advisementDiv #addDropDrop').removeClass('hide');
    $('#advisementDiv #addDropDrop').html('');
    var thisBtn = $('#advisementDiv select');
    var school_year = $('#advisementDiv select[name="school_year"]').val();
    var student_id = $('#advisementDiv select[name="student"]').val();
    var optionAddDrop = $('#advisementDiv select[name="optionAddDrop"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/addDrop/dropDiv',
        tid:'addDropDrop',
        school_year:school_year,
        student_id:student_id,
        optionAddDrop:optionAddDrop
    };
    loadDivwLoader(form_data,thisBtn);
}
function program_add_curriculum(){
    var thisBtn = $('#courseAddModal select');
    var program_id = $('#courseAddModal select[name="program"]').val();
    var student_id = $('#advisementDiv select[name="student"] option:selected').val();
    var curriculum_id_selected = $('#advisementDiv select[name="curriculum"] option:selected').val();
    var section_selected = $('#advisementDiv select[name="section"] option:selected').val();    
    var form_data = {
        program_id:program_id,
        student_id:student_id,
        curriculum_id_selected:curriculum_id_selected
    };
    $.ajax({
        url: base_url+'/rims/enrollment/programAddSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            $('#courseAddModal button[name="submit"]').addClass('hide');
            $('#courseAddModal select[name="curriculum"]').empty();
            $('#courseAddModal select[name="section"]').empty();
            $('#courseAddModal #programAddCourseDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            if(data.result=='success'){                             
                $.each(data.curriculum, function(index, value) {
                    $('#courseAddModal select[name="curriculum"]').append($('<option>', {
                        value: value['id'],
                        text: value['name']
                    }));
                });
                $.each(data.section, function(index, value) {
                    $('#courseAddModal select[name="section"]').append($('<option>', {
                        value: value['section'],
                        text: value['section']
                    }));
                });
                var school_year_id = $('#advisementDiv select[name="school_year"] option:selected').val();
                var courses = [];
                $('#advisementDiv .courseCheck:checked').each(function () {
                    courses.push($(this).data('id'));
                }); 
                var form_data = {
                    student_id:student_id,
                    school_year_id:school_year_id,
                    curriculum_id_selected:curriculum_id_selected,
                    section_selected:section_selected,
                    curriculum_id:data.curriculum_id,
                    section:1,
                    courses:courses      
                };
                program_add_courses(form_data);
            }else if(data.result=='blank'){
                $('#advisementDiv #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
            }else if(data.result=='Unavailable'){
                toastr.error('Unavailable.'); 
                $('#advisementDiv #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
            }else{
                toastr.error('Error.'); 
                $('#advisementDiv #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
        }
    });
}
function program_add_courses(form_data){
    $.ajax({
        url: base_url+'/rims/enrollment/programAddCourseDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            $('#courseAddModal #programAddCourseDiv').addClass('opacity6');            
        },
        success : function(data){
            $('#courseAddModal #programAddCourseDiv').removeClass('opacity6');
            if(data=='error'){
                toastr.error('Error.');
                $('#courseAddModal #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
            }else{
                toastr.success('Success');
                $('#courseAddModal #programAddCourseDiv').html(data);
                $('#courseAddModal #programAddCourseDiv input[type="checkbox"]').prop("checked", false);
                $('#courseAddModal button[name="submit"]').removeClass('hide');
            }
        },
        error: function (){
            toastr.error('Error!');
            $('#courseAddModal #programAddCourseDiv').removeClass('opacity6');
        }
    });
}
function course_unit_total(){
    var units = 0;
    $('#advisementDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    $('#advisementDiv #courseTotalUnits').html(units);
}