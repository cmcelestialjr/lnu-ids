scheduleTable();
$(document).off('change', '#schedule select').on('change', '#schedule select', function (e) {
    var thisBtn = $(this);
    scheduleTable(thisBtn);
});
function scheduleTable(){
    var thisBtn = $('#schedule select');
    var school_year = $('#schedule select[name="school_year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/sims/courses/scheduleTable',
        tid:'scheduleTable',
        school_year:school_year
    };
    loadDivwLoader(form_data,thisBtn);
}