viewTableLoad();
$(document).on('change', '#listDiv select[name="option"]', function (e) {
    viewTableLoad();
});
function viewTableLoad(){
    var thisBtn = $('#listDiv select[name="option"]');
    var val = $('#listDiv select[name="option"] option:selected').val();
    var form_data = {
        tid:'viewTable',
        url_table:base_url+'/users/table',
        val:val
    };
    loadTable(form_data,thisBtn);
}