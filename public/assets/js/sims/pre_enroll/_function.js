function pre_enroll_div(){
    var thisBtn = $('#preEnrollDiv select');
    // var section = $('#preEnrollDiv select[name="section"] option:selected').val();
    var form_data = {
        url_table:base_url+'/sims/pre_enroll/preEnrollCourses',
        tid:'preEnrollCourses',
        // section:section
    };
    loadDivwLoader(form_data,thisBtn);
    setTimeout(function() {
        course_unit_total();
    }, 1500);
}
function program_add_curriculum(){
    var thisBtn = $('#courseAddModal select');
    var program_id = $('#courseAddModal select[name="program"]').val();
    var student_id = $('#preEnrollDiv input[name="id"]').val();
    var curriculum_id_selected = $('#preEnrollDiv input[name="curriculum_id_selected"]').val();
    var section_selected = $('#preEnrollDiv select[name="section"] option:selected').val();    
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
    $('#preEnrollDiv .courseCheck:checked').each(function() {
        units += parseInt($(this).data('u'));
    });
    $('#preEnrollDiv #courseTotalUnits').html(units);
}