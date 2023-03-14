view_programs();
$(document).on('change', '#programsDiv select[name="status"]', function (e) {
    view_programs();
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
    $('#newCourseModal input[name="code"]').removeClass('border-require');
    $('#newCourseModal input[name="name"]').removeClass('border-require');
    $('#newCourseModal input[name="units"]').removeClass('border-require');
    if(code==''){
        $('#newCourseModal input[name="code"]').addClass('border-require');
    }
    if(name==''){
        $('#newCourseModal input[name="name"]').addClass('border-require');
    }
    if(units==''){
        $('#newCourseModal input[name="units"]').addClass('border-require');
    }
    if(pre_name==''){
        $('#newCourseModal input[name="pre_name"]').addClass('border-require');
    }
});
$(document).on('click', '#curriculumModal .curriculumStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/curriculumStatus',
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
                thisBtn.removeClass('btn-danger btn-danger-scan');
                thisBtn.removeClass('btn-success btn-success-scan');
                thisBtn.addClass(data.btn_class);
                thisBtn.html(data.btn_html);
                curriculum_div(thisBtn);
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
});
$(document).on('click', '#newCourseModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var grade_period = $('#newCourseModal select[name="grade_period"] option:selected').val();
    var year_level = $('#newCourseModal select[name="year_level"] option:selected').val();
    var code = $('#newCourseModal input[name="code"]').val();
    var name = $('#newCourseModal input[name="name"]').val();
    var units = $('#newCourseModal input[name="units"]').val();
    var pre_name = $('#newCourseModal input[name="pre_name"]').val();
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
    if(units==''){
        $('#newCourseModal input[name="units"]').addClass('border-require');
        x++;
    }
    if(pre_name==''){
        $('#newCourseModal input[name="pre_name"]').addClass('border-require');
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
            courses:courses
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
                    curriculum_div(thisBtn);
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
$(document).on('click', '#courseUpdateModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var x = 0;
    var id = $('#courseUpdateModal input[name="id"]').val();
    var code = $('#courseUpdateModal input[name="code"]').val();
    var name = $('#courseUpdateModal input[name="name"]').val();
    var units = $('#courseUpdateModal input[name="units"]').val();
    var pre_name = $('#courseUpdateModal input[name="pre_name"]').val();
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
    if(units==''){
        $('#courseUpdateModal input[name="units"]').addClass('border-require');
        x++;
    }
    if(pre_name==''){
        $('#courseUpdateModal input[name="pre_name"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            code:code,
            name:name,
            units:units,
            pre_name:pre_name,
            courses:courses
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
                    curriculum_div(thisBtn);
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
$(document).on('click', '#curriculumDiv #curriculumTable .courseStatus', function (e) {
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
$(document).on('click', '#curriculumNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumNewModal input[name="id"]').val();
    var year_from = $('#curriculumNewModal input[name="year_from"]').val();
    var year_to = $('#curriculumNewModal input[name="year_to"]').val();
    var remarks = $('#curriculumNewModal textarea[name="remarks"]').val();
    var form_data = {
        id:id,
        year_from:year_from,
        year_to:year_to,
        remarks:remarks
    };
    $.ajax({
        url: base_url+'/rims/programs/curriculumNewSubmit',
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
                toastr.success('Success');
                thisBtn.addClass('input-success');
                $('#curriculumModal #curriculumDiv #curriculumTable').html('');
                $('#curriculumModal #curriculumDiv #curriculums').html(data);
                $(".select2-curriculum").select2({
                    dropdownParent: $("#curriculumModal #curriculumDiv #curriculums")
                });
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
$(document).on('click', '#programStatusModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#programStatusModal input[name="id"]').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/programs/programStatusSubmit',
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
                $('#modal-primary').modal('hide');
                $('#programsDiv #programStatus'+id).removeClass('btn-success btn-success-scan');
                $('#programsDiv #programStatus'+id).removeClass('btn-danger btn-danger-scan');
                $('#programsDiv #programStatus'+id).addClass(data.btn_class);
                $('#programsDiv #programStatus'+id).html(data.btn_html);
                
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
});
$(document).on('click', '#curriculumModal #curriculumDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    curriculum_div(thisBtn);
});
$(document).on('click', '#curriculumDiv #curriculumTable .courseUpdate', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/courseUpdate';
    var modal = 'primary';
    var modal_size = 'modal-lg';
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
$(document).on('click', '#programsDiv .viewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/viewModal';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:'All',
        status:'All'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#curriculumModal #curriculumDiv button[name="newCourse"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
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
$(document).on('click', '#programsDiv .programStatus', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/programs/programStatusModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#curriculumModal button[name="curriculumNew"]', function (e) {
    var thisBtn = $(this);
    var id = $('#curriculumModal input[name="id"]').val();
    var url = base_url+'/rims/programs/curriculumNewModal';
    var modal = 'primary';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
});
function curriculum_div(thisBtn){
    var id = $('#curriculumModal #curriculumDiv select[name="curriculum"] option:selected').val();
    var year_level = $('#curriculumModal #curriculumDiv select[name="year_level[]"] option:selected').toArray().map(item => item.value);
    var status_course = $('#curriculumModal #curriculumDiv select[name="status_course[]"] option:selected').toArray().map(item => item.value);
    var form_data = {
        url_table:base_url+'/rims/programs/curriculumTable',
        tid:'curriculumTable',
        id:id,
        level:year_level,
        status:status_course
    };
    loadDivwLoader(form_data,thisBtn);
}
function view_programs(){
    var thisBtn = $('#programsDiv select[name="status"]');
    var status_id = thisBtn.val();
    var form_data = {
        url_table:base_url+'/rims/programs/viewTable',
        tid:'viewTable',
        status_id:status_id
    };
    loadTablewLoader(form_data,thisBtn);
}