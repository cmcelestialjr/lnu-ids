function student_table(){
    var thisBtn = $('#studentDiv #list select');
    var option = $('#studentDiv #list select[name="option"] option:selected').val();  
    var school_year = $('#studentDiv #list select[name="school_year"] option:selected').val();  
    var level = [];
    $('#studentDiv #list select[name="level[]"] option:selected').each(function () {
        level.push($(this).val());
    }); 
    var form_data = {
        url_table:base_url+'/rims/student/studentTable',
        tid:'studentTable',
        option:option,
        level:level,
        school_year:school_year
    };
    loadTablewLoader(form_data,thisBtn);
}
function student_view(id,thisBtn){    
    var url = base_url+'/rims/student/studentViewModal';
    var modal = 'default';
    var modal_size = 'modal-xxxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/student/studentSchoolYearTable',
        tid:'studentSchoolYearTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function tor(id,program_level,thisBtn){
    var url = base_url+'/rims/student/studentTORModal';
    var modal = 'primary';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/student/studentTORDiv',
        tid:'studentTORDiv',
        id:id,
        program_level:program_level
    };
    loadModal(form_data,thisBtn);
}
function torDiv(id,program_level,thisBtn){
    var form_data = {
        url_table:base_url+'/rims/student/studentTORDiv',
        tid:'studentTORDiv',
        id:id,
        program_level:program_level
    };
    loadDivwLoader(form_data,thisBtn);
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