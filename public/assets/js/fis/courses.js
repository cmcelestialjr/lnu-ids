grade_level();
$(document).off('change', '#subjectsDiv #list select[name="school_year"]').on('change', '#subjectsDiv #list select[name="school_year"]', function (e) {
    subjects_table();
});
$(document).off('change', '#subjectsDiv #list select[name="level[]"]').on('change', '#subjectsDiv #list select[name="level[]"]', function (e) {
    subjects_table();
});
$(document).off('change', '#studentsListModal .selectStatus').on('change', '#studentsListModal .selectStatus', function (e) {
    var thisBtn = $(this);
    var option = thisBtn.find(':selected').data('option');
    var x = thisBtn.find(':selected').data('x');
    $('#studentsListModal #studentGrade'+x).prop('readonly', true);
    $('#studentsListModal #studentGrade'+x).val('');
    if(option==1){
        $('#studentsListModal #studentGrade'+x).prop('readonly', false);
    }else{
        studentGrade(thisBtn);
    }
});
$(document).off('blur', '#studentsListModal .inputGrade').on('blur', '#studentsListModal .inputGrade', function (e) {
    var thisBtn = $(this);
    var x = thisBtn.data('x');
    var option = $('#studentsListModal #studentSelect'+x).find(':selected').data('option');
    if(option==1){
        studentGrade1(thisBtn);
    }
});
$(document).off('click', '#studentsDiv #list .studentView').on('click', '#studentsDiv #list .studentView', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    student_view(id,thisBtn);
});
$(document).off('click', '#studentViewModal #curriculum').on('click', '#studentViewModal #curriculum', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var program_level = $('#studentViewModal input[name="program_level"]').val();
    var curriculum = $('#studentViewModal input[name="curriculum"]').val();
    curriculumModal(id,program_level,curriculum,thisBtn);
});
$(document).off('click', '#studentViewModal .studentCoursesModal').on('click', '#studentViewModal .studentCoursesModal', function (e) {
    var thisBtn = $(this);
    var school_year_id = thisBtn.data('id');
    var id = $('#studentViewModal input[name="id"]').val();
    var url = base_url+'/rims/student/studentCoursesModal';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/student/studentCoursesTable',
        tid:'studentCoursesTable',        
        id:id,
        school_year_id:school_year_id
    };
    loadModal(form_data,thisBtn);
});
$(document).off('click', '#subjectsDiv .studentsListModal').on('click', '#subjectsDiv .studentsListModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/fis/courses/studentsListModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/fis/courses/studentsListTable',
        tid:'studentsListTable',
        dropDownParent:'studentsListTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});
function grade_level(){
    var thisBtn = $('#subjectsDiv #list select');
    var school_year = $('#subjectsDiv #list select[name="school_year"] option:selected').val();  
    var form_data = {
        school_year:school_year
    };
    $.ajax({
        url: base_url+'/fis/students/gradeLevel',
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
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');                
            }else{
                thisBtn.addClass('input-success');
                $('#subjectsDiv #list #gradeLevelDiv').html(data);
                $(".select2-gradeLevel").select2({
                    dropdownParent: $("#gradeLevelDiv")
                });
                subjects_table();
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
function subjects_table(){
    var thisBtn = $('#subjectsDiv #list select');
    var school_year = $('#subjectsDiv #list select[name="school_year"] option:selected').val();  
    var level = [];
    $('#subjectsDiv #list select[name="level[]"] option:selected').each(function () {
        level.push($(this).val());
    }); 
    if(school_year=='all'){
        all_list(thisBtn,school_year,level);
    }else{
        school_year_list(thisBtn,school_year,level);
    }
    
}
function school_year_list(thisBtn,school_year,level){
    $('#allTableDiv').addClass('hide');
    $('#coursesTableDiv').removeClass('hide');
    var form_data = {
        url_table:base_url+'/fis/courses/subjectsTable',
        tid:'subjectsTable',
        school_year:school_year,
        level:level        
    };
    loadTablewLoader(form_data,thisBtn);
}
function all_list(thisBtn,school_year,level){
    $('#allTableDiv').removeClass('hide');
    $('#coursesTableDiv').addClass('hide');
    var form_data = {
        url_table:base_url+'/fis/courses/allTable',
        tid:'allTable',
        school_year:school_year,
        level:level        
    };
    loadTablewLoader(form_data,thisBtn);
}
function studentGrade(thisBtn){
    var val = thisBtn.find(':selected').val();
    var id = thisBtn.find(':selected').data('id');
    var option = thisBtn.find(':selected').data('option');
    var form_data = {
        val:val,
        id:id,
        option:option,
        value:''
    };
    $.ajax({
        url: base_url+'/fis/courses/studentGradeUpdate',
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
                toastr.success('Success!');
                thisBtn.addClass('input-success');
            }else{
                toastr.error('Error.');
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
function studentGrade1(thisBtn){
    var val = thisBtn.val();
    var id = thisBtn.data('id');
    var x = thisBtn.data('x');
    var option = $('#studentsListModal #studentSelect'+x+' option:selected').data('option');
    var value = $('#studentsListModal #studentSelect'+x+' option:selected').val();
    var form_data = {
        val:val,
        id:id,
        option:option,
        value:value
    };
    if(val<=0){
        
    }else{
        $.ajax({
            url: base_url+'/fis/courses/studentGradeUpdate',
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
                    toastr.success('Success!');
                    thisBtn.addClass('input-success');
                }else{
                    toastr.error('Error.');
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
}