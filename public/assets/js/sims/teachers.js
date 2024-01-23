teachersTable();
$(document).off('change', '#teachers select').on('change', '#teachers select', function (e) {
    teachersTable();
});
$(document).off('click', '#teachers .courseView').on('click', '#teachers .courseView', function (e) {
    var thisBtn = $(this);
    courseViewModal(thisBtn);
});
$(document).off('change', '#courseViewModal select').on('change', '#courseViewModal select', function (e) {
    courseViewTable();
});
function teachersTable(){
    var thisBtn = $('#teachers select');
    var program_level = $('#teachers select[name="program_level"] option:selected').val();
    var school_year = $('#teachers select[name="school_year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/sims/teachers/teachersTable',
        tid:'teachersTable',
        program_level:program_level,
        school_year:school_year
    };
    loadTablewLoader(form_data,thisBtn);
}
function courseViewModal(thisBtn){
    var id = thisBtn.data('id');
    var program_level = $('#teachers select[name="program_level"] option:selected').val();
    var school_year = $('#teachers select[name="school_year"] option:selected').val();
    var url = base_url+'/sims/teachers/courseViewModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/sims/teachers/courseViewTable',
        tid:'courseViewTable',
        id:id,
        program_level:program_level,
        school_year:school_year
    };
    loadModal(form_data,thisBtn);
}
function courseViewTable(){
    var thisBtn = $('#courseViewModal select');
    var id = $('#courseViewModal input[name="id"]').val();
    var program_level = $('#courseViewModal select[name="program_level"] option:selected').val();
    var school_year = $('#courseViewModal select[name="school_year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/sims/teachers/courseViewTable',
        tid:'courseViewTable',
        id:id,
        program_level:program_level,
        school_year:school_year
    };
    loadTablewLoader(form_data,thisBtn);
}