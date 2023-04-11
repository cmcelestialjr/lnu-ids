function view_program_list(id){
    var form_data = {
        url_table:base_url+'/rims/departments/programsList',
        tid:'programsList',
        id:id
    };
    loadTable(form_data);
}
function view_departments(){
    var form_data = {
        url_table:base_url+'/rims/departments/viewTable',
        tid:'viewTable'
    };
    loadTable(form_data);
}