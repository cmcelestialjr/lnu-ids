view_school_year();
$(document).on('click', '#programsDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#programsDiv input[name="id"]').val();
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/offerPrograms',
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
                $('#modal-default').modal('hide');
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
$(document).on('click', '#programsDiv .programs', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var val = thisBtn.data('val');
    var text = thisBtn.data('tx');
    var form_data = {
        id:id,
        val:val
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/moveProgram',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            $('#programsDiv .programs').attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            $('#programsDiv .programs').removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                thisBtn.addClass('input-success');
                thisBtn.parent("tr td").remove();
                if(val=='1'){
                    $("#tableClosed > tbody").append('<tr><td>'+
                    '<button class="btn btn-danger btn-danger-scan programs" style="width: 100%" data-id="'+id+'" data-val="2" data-tx="'+text+'">'+
                    '<span class="fa fa-arrow-left"></span> &nbsp;'+
                    text+'</button></td></tr>');
                }else{
                    $("#tableOpen > tbody").append('<tr><td>'+
                    '<button class="btn btn-success btn-success-scan programs" style="width: 100%" data-id="'+id+'" data-val="1" data-tx="'+text+'">'+
                    text+'&nbsp; <span class="fa fa-arrow-right"></span></button></td></tr>');
                }
                toastr.success('Success');
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
            $('#programsDiv .programs').removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
});
$(document).on('click', '#schoolYearDiv #new button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var year_from = $('#schoolYearDiv #new input[name="year_from"]').val();
    var year_to = $('#schoolYearDiv #new input[name="year_to"]').val();
    var grade_period = $('#schoolYearDiv #new select[name="grade_period"] option:selected').val();
    var date_duration = $('#schoolYearDiv #new input[name="date_duration"]').val();
    var date_extension = $('#schoolYearDiv #new input[name="date_extension"]').val();
    var enrollment_duration = $('#schoolYearDiv #new input[name="enrollment_duration"]').val();
    var enrollment_extension = $('#schoolYearDiv #new input[name="enrollment_extension"]').val();
    var add_dropping_duration = $('#schoolYearDiv #new input[name="add_dropping_duration"]').val();
    var add_dropping_extension = $('#schoolYearDiv #new input[name="add_dropping_extension"]').val();
    var x = 0;
    if(x==0){
        var form_data = {
            year_from:year_from,
            year_to:year_to,
            grade_period:grade_period,
            date_duration:date_duration,
            date_extension:date_extension,
            enrollment_duration:enrollment_duration,
            enrollment_extension:enrollment_extension,
            add_dropping_duration:add_dropping_duration,
            add_dropping_extension:add_dropping_extension
        };
        $.ajax({
            url: base_url+'/rims/schoolYear/new',
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
                    view_programs(data.id,thisBtn);
                }else if(data.result=='exists'){
                    toastr.error('School Year and Semester Exists!');
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
$(document).on('change', '#coursesOpenModal select[name="program"]', function (e) {
    var thisBtn = $(this);
    var program = $('#coursesOpenModal select[name="program"] option:selected').val();
    var id = $('#coursesOpenModal input[name="id"]').val();
    var form_data = {
        id:id,
        program:program
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/curriculumSelect',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');            
            thisBtn.addClass('input-loading');
            $('#coursesOpenModal #curriculumOpenDiv select[name="curriculum"]').attr('disabled','disabled'); 
        },
        success : function(data){
            thisBtn.removeAttr('disabled');            
            thisBtn.removeClass('input-loading'); 
            $('#coursesOpenModal #curriculumOpenDiv select[name="curriculum"]').removeAttr('disabled');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');                
            }else{
                thisBtn.addClass('input-error');
                $('#coursesOpenModal #curriculumOpenDiv').html(data);
                $(".select2-programsSelect").select2({
                    dropdownParent: $("#curriculumOpenDiv")
                });
            }
            setTimeout(function() {
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
});
$(document).on('change', '#coursesOpenModal select[name="curriculum"]', function (e) {
    var thisBtn = $(this);
    var curriculum = $('#coursesOpenModal select[name="curriculum"] option:selected').val();
    var id = $('#coursesOpenModal input[name="id"]').val();
    var form_data = {
        id:id,
        curriculum:curriculum
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/curriculumList',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');            
            thisBtn.addClass('input-loading');
            $('#coursesOpenModal #curriculumOpenDiv select[name="program"]').attr('disabled','disabled'); 
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            $('#coursesOpenModal #curriculumOpenDiv select[name="program"]').removeAttr('disabled');
            if(data=='error'){
                toastr.error('Error.');
                thisBtn.addClass('input-error');                
            }else{
                thisBtn.addClass('input-error');
                $('#coursesOpenModal #curriculumListDiv').html(data);
            }
            setTimeout(function() {
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
});
$(document).on('click', '#coursesOpenModal #curriculumListDiv .courseStatus', function (e) {
    var thisBtn = $(this);
    var id = $('#coursesOpenModal input[name="id"]').val();
    var program_id = $('#coursesOpenModal select[name="program"] option:selected').val();
    var curriculum_id = $('#coursesOpenModal select[name="curriculum"] option:selected').val();
    var course_id = thisBtn.data('id');
    var form_data = {
        id:id,
        course_id:course_id,
        program_id:program_id,
        curriculum_id:curriculum_id
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/courseStatus',
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
$(document).on('click', '#courseViewStatusModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#courseViewStatusModal input[name="id"]').val();
    var status_id = $('#courseViewStatusModal select[name="status"] option:selected').val();
    var form_data = {
        id:id,
        status_id:status_id
    };
    $.ajax({
        url: base_url+'/rims/schoolYear/courseViewStatusSubmit',
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
                courses_view_modal();
                $('#modal-info').modal('hide');            
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
$(document).on('change', '#programsDiv select[name="departments"]', function (e) {
    var departments = $(this).val();
    $('#programsDiv .livewire-loader').html('<br><img src="'+base_url+'/assets/images/loader/loader-dots.gif" style="height: 60%;width:60%">');
    $('#programsDiv .livewire-table').addClass('hide');
    Livewire.emit('updatedDepartments', departments);
});
$(document).on('click', '#schoolYearDiv .schoolYearEdit', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/editView';
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
});
$(document).on('click', '#schoolYearDiv .programsViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/programsViewModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/schoolYear/programsViewTable',
        tid:'programsViewTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#programsViewModal .coursesOpenModal', function (e) {
    var thisBtn = $(this);
    var id = $('#programsViewModal input[name="id"]').val();
    var url = base_url+'/rims/schoolYear/coursesOpenModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
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
$(document).on('click', '#programsViewModal .coursesViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/coursesViewModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/schoolYear/curriculumViewList',
        tid:'curriculumViewList',
        id:id,
        type:'modal'
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#coursesViewModal #curriculumViewList .courseStatusModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/schoolYear/courseViewStatusModal';
    var modal = 'info';
    var modal_size = 'modal-md';
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
$(document).on('change', '#coursesViewModal select[name="curriculum"]', function (e) {
    courses_view_modal();
});
$(document).on('click', '#schoolYearDiv #schoolYearList', function (e) {
    view_school_year();
});
function courses_view_modal(){
    var thisBtn = $('#coursesViewModal select[name="curriculum"]');
    var curriculum_id = $('#coursesViewModal select[name="curriculum"] option:selected').val();
    var id = $('#coursesViewModal input[name="id"]').val();
    var form_data = {
        url_table:base_url+'/rims/schoolYear/curriculumViewList',
        tid:'curriculumViewList',
        id:id,
        curriculum_id:curriculum_id,
        type:'select'
    };
    loadDivwLoader(form_data,thisBtn);
}
function view_programs(id,thisBtn){
    var url = base_url+'/rims/schoolYear/programs';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var livewire_emit = 'shoolYearIDs';
    var livewire_value = [id];
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'true',
        w_table:'wo',
        id:id,
        livewire:'w',
        livewire_emit:livewire_emit,
        livewire_value:livewire_value
    };
    loadModal(form_data,thisBtn);
}
function view_school_year(){
    var form_data = {
        url_table:base_url+'/rims/schoolYear/viewTable',
        tid:'viewTable',
        id:''
    };
    loadTable(form_data);
}