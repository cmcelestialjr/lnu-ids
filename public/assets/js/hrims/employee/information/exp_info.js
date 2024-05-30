exp_table();
$(document).ready(function() {
    $(document).off('click', '#employeeInformationModal .doc-exp')
    .on('click', '#employeeInformationModal .doc-exp', function (e) {
        exp_doc($(this));
    });
});
function exp_table(){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/expTable',
        tid:'expTable',
        id:id
    };
    loadTable(form_data);
}
function exp_doc(thisBtn){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var fid = thisBtn.data('id');
    var url = base_url+'/hrims/employee/expDoc';
    var modal = 'success';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        fid:fid
    };
    loadModal(form_data,thisBtn);
}
