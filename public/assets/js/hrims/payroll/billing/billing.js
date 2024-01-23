list_table();
$(document).off('change', '#list select[name="year"]').on('change', '#list select[name="year"]', function (e) {
    list_table();
});
function list_table(){
    var thisBtn = $('#list select[name="year"]');
    var year = $('#list select[name="year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/payroll/billing/table',
        tid:'listTable',
        year:year
    };
    loadTablewLoader(form_data,thisBtn);
}
