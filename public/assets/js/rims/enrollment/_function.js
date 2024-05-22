function enrollment(){
    var id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
    var by = $('#enrollmentDiv select[name="by"] option:selected').val();
    var date = $('#enrollmentDiv select[name="date"] option:selected').val();

    $('#enrollmentDiv #enrollmentDivprogram').addClass('hide');
    $('#enrollmentDiv #enrollmentDivdate').addClass('hide');
    $('#enrollmentDiv #enrollmentDiv'+by).removeClass('hide');
    var tid = 'enrollmentTable'+by;
    var form_data = {
        url_table:base_url+'/rims/enrollment/enrollmentTable',
        tid:tid,
        id:id,
        by:by,
        date:date
    };
    loadTable(form_data);
}
function date_list(){
    var thisBtn = $('#enrollModal select');
    var school_year_id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
    var form_data = {
        school_year_id:school_year_id
    };
    $.ajax({
        url: base_url+'/rims/enrollment/dateList',
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
                thisBtn.addClass('input-success');
                $('#enrollmentDiv select[name="date"]').empty();
                $.each(data.dates, function(index, option) {
                    var newOption = new Option(option.text, option.id);
                    $('#enrollmentDiv select[name="date"]').append(newOption);
                });
                // After appending new options, trigger the 'change' event to update Select2's UI
                $('#enrollmentDiv select[name="date"]').trigger('change');
                enrollment();
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
function program_code(){
    var thisBtn = $('#enrollModal select');
    var program_id = $('#enrollModal #studentInformationDiv select[name="program"] option:selected').val();
    var school_year_id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
    var form_data = {
        program_id:program_id,
        school_year_id:school_year_id
    };
    $.ajax({
        url: base_url+'/rims/enrollment/programCodeDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#enrollModal #studentInformationDiv #programCoursesDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');
                $('#enrollModal #studentInformationDiv #programCodeDiv').html(data);
                program_curriculum();
                $(".select2-program").select2({
                    dropdownParent: $("#programCodeDiv")
                });
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
function program_curriculum(){
    var thisBtn = $('#enrollModal select');
    var program_code_id = $('#enrollModal #studentInformationDiv #programCodeDiv select[name="program_code"] option:selected').val();
    var student_id = $('#enrollModal select[name="student"] option:selected').val();
    var form_data = {
        program_code_id:program_code_id,
        student_id:student_id
    };
    $.ajax({
        url: base_url+'/rims/enrollment/programCurriculumDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#enrollModal #studentInformationDiv #programCoursesDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');
                $('#enrollModal #studentInformationDiv #programCurriculumDiv').html(data);
                program_section();
                $(".select2-curriculum").select2({
                    dropdownParent: $("#programCurriculumDiv")
                });
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
function program_section(){
    var thisBtn = $('#enrollModal select');
    var curriculum_id = $('#enrollModal #studentInformationDiv #programCurriculumDiv select[name="program_curriculum"] option:selected').val();
    var student_id = $('#enrollModal select[name="student"] option:selected').val();
    var form_data = {
        curriculum_id:curriculum_id,
        student_id:student_id
    };
    $.ajax({
        url: base_url+'/rims/enrollment/programSectionDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#enrollModal #studentInformationDiv #programCoursesDiv').addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');
                $('#enrollModal #studentInformationDiv #programSectionDiv').html(data);
                program_courses();
                $(".select2-section").select2({
                    dropdownParent: $("#programSectionDiv")
                });
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
function program_courses(){
    var thisBtn = $('#enrollModal #studentInformationDiv #programCoursesDiv');
    var curriculum_id = $('#enrollModal #studentInformationDiv #programCurriculumDiv select[name="program_curriculum"] option:selected').val();
    var section = $('#enrollModal #studentInformationDiv #programSectionDiv select[name="program_section"] option:selected').val();
    var student_id = $('#enrollModal select[name="student"] option:selected').val();
    var program_code_id = $('#enrollModal #studentInformationDiv #programCodeDiv select[name="program_code"] option:selected').val();
    var form_data = {
        curriculum_id:curriculum_id,
        section:section,
        student_id:student_id,
        program_code_id:program_code_id
    };
    $.ajax({
        url: base_url+'/rims/enrollment/programCoursesDiv',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('opacity6');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('opacity6');
            if(data=='error'){
                toastr.error('Error.');
            }else{
                toastr.success('Success');
                $('#enrollModal #studentInformationDiv #programCoursesDiv').html(data);
                $('#enrollModal #studentInformationDiv #advisedTable').bootstrapTable();
                course_unit_total();
            }
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('opacity6');
        }
    });
}
function program_add_curriculum(){
    var thisBtn = $('#courseAddModal select');
    var program_id = $('#courseAddModal select[name="program"]').val();
    var student_id = $('#enrollModal select[name="student"] option:selected').val();
    var curriculum_id_selected = $('#enrollModal #studentInformationDiv #programCurriculumDiv select[name="program_curriculum"] option:selected').val();
    var section_selected = $('#enrollModal #studentInformationDiv #programSectionDiv select[name="program_section"] option:selected').val();
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
                var school_year_id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
                var courses = [];
                $('#enrollModal #studentInformationDiv #courseAddedDiv .courseCheck:checked').each(function () {
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
                $('#courseAddModal #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
            }else if(data.result=='Unavailable'){
                toastr.error('Unavailable.');
                $('#courseAddModal #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
            }else{
                toastr.error('Error.');
                $('#courseAddModal #programAddCourseDiv').html('<br><br><br><br><br><br><br><br><br><br><br>');
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
    $('#enrollModal #studentInformationDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    $('#enrollModal #studentInformationDiv #courseTotalUnits').html(units);
}
function student_information(){
    var thisBtn = $('#enrollModal select[name="student"]');
    var id = thisBtn.val();
    var school_year_id = $('#enrollmentDiv select[name="school_year"] option:selected').val();
    var form_data = {
        id:id,
        school_year_id:school_year_id
    };
    $.ajax({
        url: base_url+'/rims/enrollment/studentInformationDiv',
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
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                thisBtn.addClass('input-success');
                $('#enrollModal #courseAddModal').removeClass('hide');
                $('#enrollModal #studentInformationDiv').html(data);
                program_curriculum();
                $(".select2-student").select2({
                    dropdownParent: $("#studentInformationDiv")
                });
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
function student_list(){
    var thisBtn = $('#enrollmentViewModal select');
    var id = $('#enrollmentViewModal input[name="id"]').val();
    var curriculum = $('#enrollmentViewModal select[name="curriculum"] option:selected').val();
    var section = $('#enrollmentViewModal select[name="section"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/enrollment/enrollmentViewTable',
        tid:'enrollmentViewTable',
        id:id,
        curriculum:curriculum,
        section:section
    };
    loadTablewLoader(form_data,thisBtn);
}
function regIreg_list(){
    var thisBtn = $('#regIreg select[name="type"]');
    var id = $('#enrollmentViewModal input[name="id"]').val();
    var curriculum = $('#regIreg select[name="type"] option:selected').val();
    var section = $('#enrollmentViewModal select[name="section"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/enrollment/enrollmentViewTable',
        tid:'enrollmentViewTable',
        id:id,
        curriculum:curriculum,
        section:section
    };
    loadTablewLoader(form_data,thisBtn);
}
