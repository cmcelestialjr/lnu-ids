function students_table(){
    var thisBtn = $('#studentsDiv #list select');
    var school_year = $('#studentsDiv #list select[name="school_year"] option:selected').val();  
    var level = [];
    $('#studentsDiv #list select[name="level[]"] option:selected').each(function () {
        level.push($(this).val());
    }); 
    var form_data = {
        url_table:base_url+'/fis/students/studentsTable',
        tid:'studentsTable',
        school_year:school_year,
        level:level        
    };
    loadTablewLoader(form_data,thisBtn);
}
function grade_level(){
    var thisBtn = $('#studentsDiv #list select');
    var school_year = $('#studentsDiv #list select[name="school_year"] option:selected').val();  
    var form_data = {
        school_year:school_year
    };
    $.ajax({
        url: base_url+'/fis/students/gradeLevel',
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
                $('#studentsDiv #list #gradeLevelDiv').html(data);
                $(".select2-gradeLevel").select2({
                    dropdownParent: $("#gradeLevelDiv")
                });
                students_table();
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
function student_view(id,thisBtn){    
    var url = base_url+'/fis/students/studentViewModal';
    var modal = 'default';
    var modal_size = 'modal-xxxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/fis/students/studentSchoolYearTable',
        tid:'studentSchoolYearTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function curriculumModal(id,program_level,curriculum,thisBtn){
    var url = base_url+'/rims/student/studentCurriculumModal';
    var modal = 'primary';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/student/studentCurriculumDiv',
        tid:'studentCurriculumDiv',
        id:id,
        program_level:program_level,
        curriculum:curriculum
    };
    loadModal(form_data,thisBtn);
}
function students_list(){
    
}