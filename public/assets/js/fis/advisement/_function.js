function student_info(){
    var thisBtn = $('#advisementDiv select');
    var school_year = $('#advisementDiv select[name="school_year"] option:selected').val();
    var student_id = $('#advisementDiv select[name="student"] option:selected').val();
    var form_data = {
        school_year:school_year,
        student_id:student_id
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
            $('#advisementDiv #studentAdvisement').html('<center>'+loadingTemplate('')+'</center>');
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
    var code = $('#advisementDiv select[name="code"] option:selected').val();
    var form_data = {
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
}
function advisement_table(){
    var thisBtn = $('#advisementDiv select');
    var school_year = $('#advisementDiv select[name="school_year"]').val();
    var student_id = $('#advisementDiv select[name="student"]').val();
    var code = $('#advisementDiv select[name="code"] option:selected').val();
    var curriculum = $('#advisementDiv select[name="curriculum"] option:selected').val();
    var section = $('#advisementDiv select[name="section"] option:selected').val();
    var form_data = {
        url_table:base_url+'/fis/advisement/studentAdvisement',
        tid:'studentAdvisement',
        school_year:school_year,
        student_id:student_id,
        code:code,
        curriculum:curriculum,
        section:section
    };
    loadDivwLoader(form_data,thisBtn);
    setTimeout(function() {
        course_unit_total();
    }, 1500);
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