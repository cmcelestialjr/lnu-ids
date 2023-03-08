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
            console.log(data);
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
function view_programs(id,thisBtn){
    var url = base_url+'/rims/schoolYear/programs';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'true',
        w_table:'wo',
        // url_table:base_url+'/rims/schoolYear/viewTable',
        // tid:'viewTable',
        id:id
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