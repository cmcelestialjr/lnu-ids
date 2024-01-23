listTable();
$(document).off('click', '.listCourseView').on('click', '.listCourseView', function (e) {
    var thisBtn = $(this);
    listCourseView(thisBtn);
});
$(document).off('change', '#list select').on('change', '#list select', function (e) {
    listTable();
});
function listTable(){
    var thisBtn = $('#list select');
    var status = $('#list select[name="status"] option:selected').val();
    var program_level = $('#list select[name="program_level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/sims/courses/listTable',
        tid:'listTable',
        status:status,
        program_level:program_level
    };
    loadTablewLoader(form_data,thisBtn);
}
function listCourseView(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/sims/courses/listCourseView';
    var modal = 'default';
    var modal_size = 'modal-lg';
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