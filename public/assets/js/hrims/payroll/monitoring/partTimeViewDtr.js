
viewDtr($('#ptOptions #dtr'));
function viewDtr(thisBtn){
    var sy = $('#overLoadSY option:selected').val();
    var url = base_url+'/hrims/payroll/monitoring/overLoad/update';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        option_id:option_id,
        work_id:work_id,
        sy:sy
    };
    loadDivwLoader(form_data,thisBtn);
}
