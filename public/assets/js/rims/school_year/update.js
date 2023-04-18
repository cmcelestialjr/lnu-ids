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
                if(data.program!=''){
                    var form_data = {
                        url_table:base_url+'/rims/schoolYear/programsViewTable',
                        tid:'programsViewTable',
                        id:id
                    };
                    loadTable(form_data);
                }
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