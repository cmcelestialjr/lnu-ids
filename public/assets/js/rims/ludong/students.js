table();
ludongStudentSearch();
$(document).off('change', '#ludongStudent select[name="student"]').on('change', '#ludongStudent select[name="student"]', function (e) {
    table();   
});
function table(){
    var thisBtn = $('#ludongStudent select[name="student"] option:selected');
    var id = thisBtn.val();
    var form_data = {
        url_table:base_url+'/rims/ludong/studentTable',
        tid:'studentTable',
        id:id
    };
    loadTablewLoader(form_data,thisBtn);
}