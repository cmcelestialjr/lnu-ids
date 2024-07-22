
viewDtr($('#ptOptions #viewDtr'));
function viewDtr(thisBtn){
    var id = $('#ptOptions input[name="id"]').val();
    var year = $('#ptOptions input[name="year"]').val();
    var month = $('#ptOptions input[name="month"]').val();
    var url_table = base_url+'/hrims/payroll/monitoring/partTime/viewDtr';
    var tid = 'viewDtr';
    var form_data = {
        url_table:url_table,
        tid:tid,
        id:id,
        year:year,
        month:month
    };
    loadDivwLoader(form_data,thisBtn);
}
