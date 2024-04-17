student_table();
$(document).on('change', '#studentDiv #list select', function (e) {
    var option = $('#studentDiv #list select[name="option"] option:selected').val();
    $('#studentDiv #list #date_graduate').addClass('hide');
    $('#studentDiv #list #school_year').removeClass('hide');
    if(option=='Graduated'){
        $('#studentDiv #list #date_graduate').removeClass('hide');
        $('#studentDiv #list #school_year').addClass('hide');
    }
    student_table();
});
$(document).on('change', '#studentTORModal select[name="level"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#studentTORModal select[name="level"] option:selected').val();    
    torDiv(id,program_level,thisBtn);
});
$(document).off('change', '#studentCourseAddModal input[name="in_list"]').on('change', '#studentCourseAddModal input[name="in_list"]', function (e) {
    var thisBtn = $(this);
    $('#studentCourseAddModal #inList').removeClass('hide');
    $('#studentCourseAddModal #notInList').addClass('hide');
    if(thisBtn.val()=='Yes'){
        $('#studentCourseAddModal #inList').addClass('hide');
        $('#studentCourseAddModal #notInList').removeClass('hide');
    }
});
$(document).off('change', '#studentCourseAddModal .statuses').on('change', '#studentCourseAddModal .statuses', function (e) {
    var thisBtn = $(this);
    var n = thisBtn.data('n');
    var option = thisBtn.find(':selected').data('option');
    $('#studentCourseAddModal #rating_'+n).prop('readonly', true);
    $('#studentCourseAddModal #rating_'+n).val('');
    if(option==1){
        $('#studentCourseAddModal #rating_'+n).prop('readonly', false);
    }
});
$(document).off('click', '#studentCourseAddModal .remove').on('click', '#studentCourseAddModal .remove', function (e) {
    $(this).closest("tr").remove();
});
$(document).off('change', '#studentCourseAddModal input[name="year_from"]').on('change', '#studentCourseAddModal input[name="year_from"]', function (e) {
    var val = parseInt($(this).val());
    $('#studentCourseAddModal input[name="year_to"]').val(val+1);
});
$(document).off('change', '#studentCurriculumModal #studentCurriculumDiv .courses_curriculum').on('change', '#studentCurriculumModal #studentCurriculumDiv .courses_curriculum', function (e) {
    var checkboxesCurriculum = $('#studentCurriculumModal #studentCurriculumDiv .courses_curriculum');
    var checkboxesOther = $('#studentCurriculumModal #studentCurriculumDiv .courses_other');
    checkboxesCurriculum.not(this).prop('disabled', $(this).prop('checked'));
    $('#studentCurriculumModal #studentCurriculumDiv #courses_other_td').addClass('hide');
    $('#studentCurriculumModal #studentCurriculumDiv #courses_main_table').css('width', '100%');
    if ($(this).is(':checked')) {
        $('#studentCurriculumModal #studentCurriculumDiv #courses_main_table').css("width","65%").css("float", "left");
        $('#studentCurriculumModal #studentCurriculumDiv #courses_other_td').css("width","35%").css("float", "right");
        $('#studentCurriculumModal #studentCurriculumDiv #courses_other_td').removeClass('hide');
    }
    if (checkboxesCurriculum.is(':checked') && checkboxesOther.is(':checked')) {
        var curriculumCourseID = $(this).data('id');
        var courseOtherID = checkboxesOther.filter(':checked').data('id');
        course_credit(curriculumCourseID, courseOtherID);
    }
});
$(document).off('click', '#studentCurriculumModal #studentCurriculumDiv #close_courses_other').on('click', '#studentCurriculumModal #studentCurriculumDiv #close_courses_other', function (e) {
    $('#studentCurriculumModal #studentCurriculumDiv #courses_other_td').addClass('hide');
    $('#studentCurriculumModal #studentCurriculumDiv .courses_curriculum').prop('checked', false).prop('disabled', false);
});

$(document).off('change', '#studentCurriculumModal #studentCurriculumDiv .courses_other').on('change', '#studentCurriculumModal #studentCurriculumDiv .courses_other', function (e) {
    var checkboxesCurriculum = $('#studentCurriculumModal #studentCurriculumDiv .courses_curriculum');
    var checkboxesOther = $('#studentCurriculumModal #studentCurriculumDiv .courses_other');
    checkboxesOther.not(this).prop('disabled', $(this).prop('checked'));
    if (checkboxesCurriculum.is(':checked') && checkboxesOther.is(':checked')) {
        var curriculumCourseID = checkboxesCurriculum.filter(':checked').data('id');
        var courseOtherID = $(this).data('id');
        course_credit(curriculumCourseID, courseOtherID);
    }
});
$(document).off('click', '#studentCurriculumModal #studentCurriculumDiv .studentCreditRemove').on('click', '#studentCurriculumModal #studentCurriculumDiv .studentCreditRemove', function (e) {
    var thisBtn = $(this);
    studentCreditRemove(thisBtn);
});
$(document).off('click', '#studentCourseAddModal .add').on('click', '#studentCourseAddModal .add', function (e) {
    var thisBtn = $(this);
    studendCourseAddModal(thisBtn);
});
$(document).off('change', '#studentShiftModal select[name="shift_to"]').on('change', '#studentShiftModal select[name="shift_to"]', function (e) {
    var thisBtn = $(this);
    var val = thisBtn.val();    
    var form_data = {
        val:val
    };
    
    $.ajax({
        url: base_url+'/rims/student/studentShiftModalCurriculum',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                thisBtn.addClass('input-success');
                if(data.datas!=''){
                    var datasArray = data.datas;
                    $.each(datasArray, function(index, item) {
                        var option = $('<option>').val(item.value).text(item.text);
                        $('#studentShiftModal select[name="curriculum"]').append(option);
                    });
                }
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
            }
            setTimeout(function() {
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
});
