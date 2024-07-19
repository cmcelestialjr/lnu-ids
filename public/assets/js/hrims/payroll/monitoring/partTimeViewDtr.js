
viewDtr($('#ptOptions #dtr'));
function viewDtr(thisBtn){
    var sy = $('#partTimeSY option:selected').val();
    var url = base_url+'/hrims/payroll/monitoring/partTime/viewDtr';
    var modal = 'default';
    var modal_size = 'modal-sm';

    var form_data = $('#ptOptions').serializeArray();

    form_data.push({ name: 'url', value: url });
    form_data.push({ name: 'modal', value: modal });
    form_data.push({ name: 'modal_size', value: modal_size });
    form_data.push({ name: 'static', value: '' });
    form_data.push({ name: 'w_table', value: 'wo' });

    var serialized_data = $.param(form_data);

    loadDivwLoader(serialized_data,thisBtn);
}
