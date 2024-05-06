function student_table(){
    var thisBtn = $('#studentDiv #list select');
    var option = $('#studentDiv #list select[name="option"] option:selected').val();
    var school_year = $('#studentDiv #list select[name="school_year"] option:selected').val();
    var year_graduate = $('#studentDiv #list select[name="date_graduate"] option:selected').val();
    var level = [];
    $('#studentDiv #list select[name="level[]"] option:selected').each(function () {
        level.push($(this).val());
    });
    var form_data = {
        url_table:base_url+'/rims/student/studentTable',
        tid:'studentTable',
        option:option,
        level:level,
        school_year:school_year,
        year_graduate:year_graduate
    };
    loadTablewLoader(form_data,thisBtn);
}
function student_view(id,thisBtn){
    var url = base_url+'/rims/student/studentViewModal';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/student/studentSchoolYearTable',
        tid:'studentSchoolYearTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function tor(id,program_level,thisBtn){
    var url = base_url+'/rims/student/studentTORModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/student/studentTORDiv',
        tid:'studentTORDiv',
        id:id,
        program_level:program_level
    };
    loadModal(form_data,thisBtn);
}
function torDiv(id,program_level,thisBtn){
    var form_data = {
        url_table:base_url+'/rims/student/studentTORDiv',
        tid:'studentTORDiv',
        id:id,
        program_level:program_level
    };
    loadDivwLoader(form_data,thisBtn);
}
function curriculumModal(id,program_level,curriculum,thisBtn){
    var url = base_url+'/rims/student/studentCurriculumModal';
    var modal = 'primary';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        url_table:base_url+'/rims/student/studentCurriculumDiv',
        tid:'studentCurriculumDiv',
        id:id,
        program_level:program_level,
        curriculum:curriculum
    };
    loadModal(form_data,thisBtn);
}
function course_credit(curriculumCourseID, courseOtherID){
    var id = $('#studentViewModal input[name="id"]').val();
    var thisBtn = $('#studentCurriculumModal #studentCurriculumDiv input');
    var level = $('#studentCurriculumModal select[name="level"] option:selected').val();
    var program = $('#studentCurriculumModal select[name="program"] option:selected').val();
    var curriculum = $('#studentCurriculumModal select[name="curriculum"] option:selected').val();
    var branch = $('#studentCurriculumModal select[name="branch"] option:selected').val();
    var form_data = {
        id:id,
        curriculumCourseID:curriculumCourseID,
        courseOtherID:courseOtherID,
        level:level,
        program:program,
        curriculum:curriculum,
        branch:branch
    };
    $.ajax({
        url: base_url+'/rims/student/studentCourseCreditSubmit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                fetchCurriculum(thisBtn);
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
}
function studentCreditRemove(thisBtn){
    var id = $('#studentViewModal input[name="id"]').val();
    var cid = thisBtn.data('id');
    var crid = thisBtn.data('crid');
    var thisBtn = $('#studentCurriculumModal #studentCurriculumDiv .studentCreditRemove');
    var form_data = {
        id:id,
        cid:cid,
        crid:crid
    };
    $.ajax({
        url: base_url+'/rims/student/studentCreditRemove',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success');
                thisBtn.addClass('input-success');
                fetchCurriculum(thisBtn);
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
}
function fetchCurriculum(thisBtn){
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#studentViewModal input[name="program_level"]').val();
    var curriculum = $('#studentViewModal input[name="curriculum"]').val();
    var form_data = {
        url_table:base_url+'/rims/student/studentCurriculumDiv',
        tid:'studentCurriculumDiv',
        id:id,
        program_level:program_level,
        curriculum:curriculum
    };
    loadDivwDisabled(form_data,thisBtn);
}
function studendCourseAddModal(thisBtn){
    var length = $('#studentCourseAddModal .course_code').length+1;
    var form_data = {
        length:length
    };
    $.ajax({
        url: base_url+'/rims/student/studentCourseAddTr',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            $("#studentCourseAddModal #courseInfoTable tbody").append(data);
            $(".select2-tr"+length).select2({
                dropdownParent: $("#studentCourseAddModal")
              });
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
}



