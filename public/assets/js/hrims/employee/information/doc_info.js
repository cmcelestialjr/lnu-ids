$(document).ready(function() {
    $(document).off('click', '#docDiv .small-box')
    .on('click', '#docDiv .small-box', function (e) {
        var option = $(this).data('o');
        var options = ['pds','saln','ipcr','school','eligibility','work_exp','training','voluntary','awards'];
        if (options.includes(option)) {
            docView($(this),option);
        }
    });
});
function docView(thisBtn,option){
    var id = $('#employeeInformationModal input[name="id_no"]').val();
    var url = base_url+'/hrims/employee/doc/'+option+'Info';
    var modal = 'success';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
}
