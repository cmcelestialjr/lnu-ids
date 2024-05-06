
coursesDiv();

$(document).off('change', '#coursesDiv select').on('change', '#coursesDiv select', function (e) {
    coursesDiv();
});

$(document).off('click', '#coursesDiv button[name="newCourse"]').on('click', '#coursesDiv button[name="newCourse"]', function (e) {
    var thisBtn = $(this);
    var id = $('#coursesDiv input[name="curriculum_id"]').val();
    var url = base_url+'/rims/programs/newCourse';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTablePre',
        tid:'curriculumTablePre',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});

$(document).off('click', '#coursesDiv .courseUpdate').on('click', '#coursesDiv .courseUpdate', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/courseUpdate';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/courseTablePre',
        tid:'courseTablePre',
        id:id
    };
    loadModal(form_data,thisBtn);
});

$(document).on('click', '#newCourseModal .all', function (e) {
    if (this.checked) {
        $('#newCourseModal .courses').prop('checked', true);
        $('#newCourseModal input[name="pre_name"]').val('All');
    }else{
        $('#newCourseModal .courses').prop('checked', false);
        $('#newCourseModal input[name="pre_name"]').val('None');
    }
});
$(document).on('click', '#newCourseModal .courses', function (e) {
    var courses = [];
    $('#newCourseModal .courses:checked').each(function() {
        courses.push($(this).data('val'));
    });
    if(courses.length==0){
        var courses = 'None';
    }
    $('#newCourseModal input[name="pre_name"]').val(courses);
});
$(document).on('click', '#courseUpdateModal .all', function (e) {
    if (this.checked) {
        $('#courseUpdateModal .courses').prop('checked', true);
        $('#courseUpdateModal input[name="pre_name"]').val('All');
    }else{
        $('#courseUpdateModal .courses').prop('checked', false);
        $('#courseUpdateModal input[name="pre_name"]').val('None');
    }
});
$(document).on('click', '#courseUpdateModal .courses', function (e) {
    var courses = [];
    $('#courseUpdateModal .courses:checked').each(function() {
        courses.push($(this).data('val'));
    });
    if(courses.length==0){
        var courses = 'None';
    }
    $('#courseUpdateModal input[name="pre_name"]').val(courses);
});
$(document).on('input', '#newCourseModal .req', function (e) {
    var code = $('#newCourseModal input[name="code"]').val();
    var name = $('#newCourseModal input[name="name"]').val();
    var units = $('#newCourseModal input[name="units"]').val();
    var pre_name = $('#newCourseModal input[name="pre_name"]').val();
    var lab = $('#newCourseModal input[name="pre_name"]').val();
    $('#newCourseModal input[name="code"]').removeClass('border-require');
    $('#newCourseModal input[name="name"]').removeClass('border-require');
    $('#newCourseModal input[name="units"]').removeClass('border-require');
    $('#newCourseModal input[name="lab"]').removeClass('border-require');
    if(code==''){
        $('#newCourseModal input[name="code"]').addClass('border-require');
    }
    if(name==''){
        $('#newCourseModal input[name="name"]').addClass('border-require');
    }
    if(units=='' || units<=0){
        $('#newCourseModal input[name="units"]').addClass('border-require');
    }
    if(pre_name==''){
        $('#newCourseModal input[name="pre_name"]').addClass('border-require');
    }
    if(lab==''){
        $('#newCourseModal input[name="lab"]').addClass('border-require');
    }
});
$(document).off('change', '#specialization_name_select').on('change', '#specialization_name_select', function (e) {
    var thisBtn = $(this);
    var val = thisBtn.val();
    $('#specialization_name_div').addClass('hide');
    if(val==3){
        $('#specialization_name_div').removeClass('hide');
    }
});

$(document).off('click', '#coursesDiv .courseStatus').on('click', '#coursesDiv .courseStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/courseStatus',
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
            if(data.result=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');
            }else{
                toastr.success('Success');
                thisBtn.addClass('input-success');
                thisBtn.removeClass('btn-danger btn-danger-scan');
                thisBtn.removeClass('btn-success btn-success-scan');
                thisBtn.addClass(data.btn_class);
                thisBtn.html(data.btn_html);
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

$(document).off('click', '#newCourseModal #courseSelectSubmit').on('click', '#newCourseModal #courseSelectSubmit', function (e) {
    var thisBtn = $(this);
    var id = $('#newCourseModal .courseSelect option:selected').val();

    if(!id){
        toastr.error('Please Select a course.');
    }else{
        var form_data = {
            id:id
        };
        $.ajax({
            url: base_url+'/rims/programs/courseInfo',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            beforeSend: function() {
                thisBtn.attr('disabled','disabled');
                thisBtn.addClass('input-loading');
                $('#newCourseModal #courseInfo').addClass('disabled');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                if(data=='error'){
                    toastr.error('Error.');
                    thisBtn.addClass('input-error');
                }else{
                    $('#newCourseModal #courseInfo').removeClass('disabled');
                    $('#newCourseModal #courseInfo').html(data);
                    $(".select2-div").select2({
                        dropdownParent: $("#courseInfo")
                    });
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
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
});
$(document).off('click', '#courseUpdateModal button[name="submit"]').on('click', '#courseUpdateModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#courseUpdateModal input[name="id"]').val();
    var code = $('#courseUpdateModal input[name="code"]').val();
    var name = $('#courseUpdateModal input[name="name"]').val();
    var units = $('#courseUpdateModal input[name="units"]').val();
    var pre_name = $('#courseUpdateModal input[name="pre_name"]').val();
    var lab = $('#courseUpdateModal input[name="lab"]').val();
    var pay_units = $('#courseUpdateModal input[name="pay_units"]').val();
    var specialization_name = $('#courseUpdateModal input[name="specialization_name"]').val();
    var lab_group = $('#courseUpdateModal select[name="lab_group"] option:selected').val();
    var course_type = $('#courseUpdateModal select[name="course_type"] option:selected').val();
    var courses = [];
    $('#courseUpdateModal .courses:checked').each(function() {
        courses.push($(this).val());
    });
    if(code==''){
        $('#courseUpdateModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(name==''){
        $('#courseUpdateModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(units=='' || units<=0){
        $('#courseUpdateModal input[name="units"]').addClass('border-require');
        x++;
    }
    if(pre_name==''){
        $('#courseUpdateModal input[name="pre_name"]').addClass('border-require');
        x++;
    }
    if(lab==''){
        $('#courseUpdateModal input[name="lab"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            code:code,
            name:name,
            units:units,
            pre_name:pre_name,
            courses:courses,
            lab:lab,
            pay_units:pay_units,
            lab_group:lab_group,
            course_type:course_type,
            specialization_name:specialization_name
        };
        $.ajax({
            url: base_url+'/rims/programs/courseUpdateSubmit',
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
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    coursesDiv();
                }else if(data.result=='exists'){
                    toastr.error('Course Code or Descriptive Title already exists!');
                    thisBtn.addClass('input-error');
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
});
$(document).on('click', '#newCourseModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#coursesDiv input[name="curriculum_id"]').val();
    var grade_period = $('#newCourseModal select[name="grade_period"] option:selected').val();
    var year_level = $('#newCourseModal select[name="year_level"] option:selected').val();
    var lab_group = $('#newCourseModal select[name="lab_group"] option:selected').val();
    var course_type = $('#newCourseModal select[name="course_type"] option:selected').val();
    var code = $('#newCourseModal input[name="code"]').val();
    var name = $('#newCourseModal input[name="name"]').val();
    var units = $('#newCourseModal input[name="units"]').val();
    var pre_name = $('#newCourseModal input[name="pre_name"]').val();
    var lab = $('#newCourseModal input[name="lab"]').val();
    var pay_units = $('#newCourseModal input[name="pay_units"]').val();
    var courses = [];
    $('#newCourseModal .courses:checked').each(function() {
        courses.push($(this).val());
    });
    if(code==''){
        $('#newCourseModal input[name="code"]').addClass('border-require');
        x++;
    }
    if(name==''){
        $('#newCourseModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(units=='' || units<= 0){
        $('#newCourseModal input[name="units"]').addClass('border-require');
        x++;
    }
    if(pre_name==''){
        $('#newCourseModal input[name="pre_name"]').addClass('border-require');
        x++;
    }
    if(lab==''){
        $('#newCourseModal input[name="lab"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            grade_period:grade_period,
            year_level:year_level,
            code:code,
            name:name,
            units:units,
            pre_name:pre_name,
            courses:courses,
            lab:lab,
            pay_units:pay_units,
            lab_group:lab_group,
            course_type:course_type
        };
        $.ajax({
            url: base_url+'/rims/programs/newCourseSubmit',
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
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    $('#newCourseModal input[name="code"]').val('');
                    $('#newCourseModal input[name="name"]').val('');
                    $('#newCourseModal input[name="units"]').val('');
                    $('#newCourseModal input[name="pre_name"]').val('None');
                    $('#newCourseModal .courses').prop('checked', false);
                    $('#newCourseModal .all').prop('checked', false);
                    coursesDiv();
                }else if(data.result=='exists'){
                    toastr.error('Course Code or Descriptive Title already exists!');
                    thisBtn.addClass('input-error');
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
});

function coursesDiv(){
    var thisBtn = $('#coursesDiv');
    var id = $('#coursesDiv input[name="curriculum_id"]').val();
    var year_level = $('#coursesDiv select[name="year_level[]"] option:selected').toArray().map(item => item.value);
    var status_course = $('#coursesDiv select[name="status_course[]"] option:selected').toArray().map(item => item.value);
    var form_data = {
        url_table:base_url+'/rims/courses/coursesTable/'+id,
        tid:'coursesTable',
        year_level:year_level,
        status_course:status_course
    };
    loadDivwDisabled(form_data,thisBtn);
}
