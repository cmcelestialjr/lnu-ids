$(document).off('click', '#schoolYearDiv .programsViewModal').on('click', '#schoolYearDiv .programsViewModal', function (e) {
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
        dropDownParent:'programsViewTable',
        id:id,
        branch:1
    };
    loadModal(form_data,thisBtn);
});
$(document).off('change', '#programsViewModal select[name="branch"]').on('change', '#programsViewModal select[name="branch"]', function (e) {
    var thisBtn = $(this);
    programTable(thisBtn);
});
$(document).on('change', '#programsDiv select[name="departments"]', function (e) {
    var departments = $(this).val();
    $('#programsDiv .livewire-loader').html('<br><img src="'+base_url+'/assets/images/loader/loader-dots.gif" style="height: 60%;width:60%">');
    $('#programsDiv .livewire-table').addClass('hide');
    Livewire.emit('updatedDepartments', departments);
});
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
function programTable(thisBtn){
    var id = $('#programsViewModal input[name="id"]').val();
    var branch = $('#programsViewModal select[name="branch"] option:selected').val();
    var form_data = {
        url_table:base_url+'/rims/schoolYear/programsViewTable',
        tid:'programsViewTable',
        dropDownParent:'programsViewTable',
        id:id,
        branch:branch
    };
    loadTablewLoader(form_data,thisBtn);
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