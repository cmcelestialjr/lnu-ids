list_table();
$(document).off('click', '#list button[name="new"]').on('click', '#list button[name="new"]', function (e) {
    list_new();
});
$(document).off('click', '#listNewModal button[name="submit"]').on('click', '#listNewModal button[name="submit"]', function (e) {
    list_new_submit();
});
$(document).off('change', '#listNewModal select[name="computation"]').on('change', '#listNewModal select[name="computation"]', function (e) {
    var thisBtn = $(this);
    select_computation(thisBtn);
});
$(document).off('click', '#listNewModal button[name="add_computation"]').on('click', '#listNewModal button[name="add_computation"]', function (e) {
    var thisBtn = $(this);
    add_computation(thisBtn);
});
function list_table(){
    var form_data = {
        url_table:base_url+'/hrims/deduction/list/table',
        tid:'listTable'
    };
    loadTable(form_data);
}
function list_new(){
    var thisBtn = $('#source button[name="new"]');
    var url = base_url+'/hrims/deduction/list/newModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function list_new_submit(){
    var thisBtn = $('#listNewModal button[name="submit"]');
    var name = $('#listNewModal input[name="name"]').val();
    var group = $('#listNewModal select[name="group"] option:selected').val();
    var x = 0;
    $('#listNewModal input[name="name"]').removeClass('border-require');
    if(name==''){
        $('#listNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Deduction Name');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            group:group
        };
        $.ajax({
            url: base_url+'/hrims/deduction/list/newSubmit',
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
                    list_table();
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
    }
}
function select_computation(thisBtn){
    var val = thisBtn.val();
    $('#listNewModal #Percent').addClass('hide');
    $('#listNewModal #Add').addClass('hide');
    $('#listNewModal #Subtract').addClass('hide');
    $('#listNewModal #Multiply').addClass('hide');
    $('#listNewModal #Divide').addClass('hide');
    $('#listNewModal #'+val).removeClass('hide');
    $('#listNewModal button[name="add_computation"]').removeClass('hide');
    if(val=='None'){
        $('#listNewModal button[name="add_computation"]').addClass('hide');
    }
}
function add_computation(thisBtn){
    thisBtn.attr('disabled','disabled'); 
    thisBtn.addClass('input-loading');
    var val = $('#listNewModal select[name="computation"] option:selected').val();
    setTimeout(function(){
        thisBtn.removeAttr('disabled');
        thisBtn.removeClass('input-loading'); 
        thisBtn.addClass('input-success');

        if(val=='None'){
            $('#listNewModal #computation_list').empty();
        }else if(val=='Percent'){
            percent_computation();
        }
        
        setTimeout(function() {
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }, 3000);
    },700); 
}
function percent_computation(){
    var percent = $('#listNewModal input[name="percent"]').val();
    var percent_of = $('#listNewModal select[name="percent_of"] option:selected').val();
    if(percent_of=='Amount'){
        var percent_amount = $('#listNewModal input[name="percent_amount"]').val();
    }else{
        var percent_amount = '';
    }
    var percent_ceiling_amount = $('#listNewModal input[name="percent_ceiling_amount"]').val();
    if(percent=='' || percent<=0){
        toastr.error('Please input Percent!');
        $('#listNewModal input[name="percent"]').addClass('border-require');
    }else{
        $('#listNewModal #computation_list').append('<tr>'+
            '<td>Percent - '+percent+'%'+
                '<input type="hidden" name="type[]" value="Percent">'+
                '<input type="hidden" name="val[]" value="'+percent+'">'+
            '</td>'+
            '<td>Of - '+percent_of+
                '<input type="hidden" name="of[]" value="'+percent_of+'">'+
            '</td>'+
            '<td>Amount - '+percent_amount+
                '<input type="hidden" name="amount[]" value="'+percent_amount+'">'+
            '</td>'+
            '<td>Ceiling - '+percent_ceiling_amount+
                '<input type="hidden" name="ceiling[]" value="'+percent_ceiling_amount+'">'+
            '</td>'+
            '</tr>');
    }
}