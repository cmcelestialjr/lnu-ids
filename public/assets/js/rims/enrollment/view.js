enrollment();
$(document).on('change', '#enrollModal select[name="student"]', function (e) {
    var thisBtn = $(this);
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
});
$(document).on('change', '#enrollModal #studentInformationDiv select[name="program"]', function (e) {
    program_code();
});
$(document).on('change', '#enrollModal #studentInformationDiv #programCodeDiv select[name="program_code"]', function (e) {
    program_curriculum();
});
$(document).on('change', '#enrollModal #studentInformationDiv #programCurriculumDiv select[name="program_curriculum"]', function (e) {
    program_section();
});
$(document).on('change', '#enrollModal #studentInformationDiv #programSectionDiv select[name="program_section"]', function (e) {
    program_courses();
});
$(document).on('change', '#courseAddModal select[name="program"]', function (e) {
    program_add_curriculum();
});
$(document).on('click', '#enrollModal #studentInformationDiv #programCoursesDiv .year_check', function (e) {    
    var thisBtn = $(this);
    var val = thisBtn.val();    
    if (this.checked) {
        $('#enrollModal #studentInformationDiv #programCoursesDiv .course_check'+val).prop('checked', true);
    }else{
        $('#enrollModal #studentInformationDiv #programCoursesDiv .course_check'+val).prop('checked', false);
    }
});
