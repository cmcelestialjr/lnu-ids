monitoringDiv();
$(document).off('change', '#monitoringOption').on('change', '#monitoringOption', function (e) {
    monitoringDiv();
});
function monitoringDiv(){
    var thisBtn = $('#monitoringDiv');
    var option = $('#monitoringOption option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/payroll/monitoring/monitoring',
        tid:'monitoringDiv',
        option:option
    };
    loadDivwDisabled(form_data,thisBtn);
}
