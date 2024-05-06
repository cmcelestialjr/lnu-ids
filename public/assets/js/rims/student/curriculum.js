fetchCurriculumList();
$(document).off('change', '#studentCurriculumModal select[name="level"]').on('change', '#studentCurriculumModal select[name="level"]', function (e) {
    fetchCurriculumList();
});
$(document).off('change', '#studentCurriculumModal select[name="curriculum"]').on('change', '#studentCurriculumModal select[name="curriculum"]', function (e) {
    curriculumLoadDiv();
});
function fetchCurriculumList(){
    var thisBtn = $('#studentCurriculumModal select');
    var id = $('#studentViewModal input[name="id"]').val();
    var level = $('#studentCurriculumModal select[name="level"] option:selected').val();
    var form_data = {
        id:id,
        level:level
    };
    $.ajax({
        url: base_url+'/rims/student/studentCurriculumList',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#studentCurriculumModal select[name="program"]').empty();
            $('#studentCurriculumModal select[name="curriculum"]').empty();
            $('#studentCurriculumModal select[name="branch"]').empty();
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            $('#studentCurriculumModal select[name="program"]').append(new Option(data.program_text, data.program_value));
            $.each(data.curriculums, function(index, item) {
                var option = new Option(item.text, item.value);
                if (item.value === data.curriculum_id) {
                    option.selected = true;
                }
                $('#studentCurriculumModal select[name="curriculum"]').append(option);
            });
            $.each(data.branches, function(index, item) {
                $('#studentCurriculumModal select[name="branch"]').append(new Option(item.text, item.value));
            });
            $('#studentCurriculumModal select[name="curriculum"]').trigger('change');
            $('#studentCurriculumModal select[name="branch"]').trigger('change');
            curriculumLoadDiv();
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}
function curriculumLoadDiv(){
    var thisBtn = $('#studentCurriculumModal select');
    var id = $('#studentViewModal input[name="id"]').val();
    var level = $('#studentCurriculumModal select[name="level"] option:selected').val();
    var curriculum = $('#studentCurriculumModal select[name="curriculum"] option:selected').val();
    var form_data = {
        id:id,
        level:level,
        curriculum:curriculum
    };
    var form_data = {
        url_table:base_url+'/rims/student/studentCurriculumLoad',
        tid:'studentCurriculumDiv',
        id:id,
        level:level,
        curriculum:curriculum
    };
    loadDivwLoader(form_data,thisBtn);
}
