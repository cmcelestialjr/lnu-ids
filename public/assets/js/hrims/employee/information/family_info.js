table();

function table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/familyTable',
        tid:'familyTable',
        id:id
    };
    loadTable(form_data);
}
