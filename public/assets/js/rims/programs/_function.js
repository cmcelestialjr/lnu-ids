function view_program_code(id){
    var form_data = {
        url_table:base_url+'/rims/programs/programCodesList',
        tid:'programCodesList',
        id:id
    };
    loadTable(form_data);
}
function curriculum_div(thisBtn){
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var year_level = $('#curriculumModal #curriculumDiv select[name="year_level[]"] option:selected').toArray().map(item => item.value);
    var status_course = $('#curriculumModal #curriculumDiv select[name="status_course[]"] option:selected').toArray().map(item => item.value);
    var form_data = {
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:year_level,
        status:status_course
    };
    loadDivwLoader(form_data,thisBtn);
}
function view_programs(){
    var thisBtn = $('#programsDiv select[name="status"]');
    var status_id = thisBtn.val();
    var form_data = {
        url_table:base_url+'/rims/programs/viewTable',
        tid:'viewTable',
        status_id:status_id
    };
    loadTablewLoader(form_data,thisBtn);
}