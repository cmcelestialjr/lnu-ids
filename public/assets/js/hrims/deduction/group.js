$(document).off('click', '#groupLink').on('click', '#groupLink', function (e) {
    group_table();
});
$(document).off('click', '#group button[name="new"]').on('click', '#group button[name="new"]', function (e) {
    group_new();
});
$(document).off('click', '#groupNewModal button[name="submit"]').on('click', '#groupNewModal button[name="submit"]', function (e) {
    group_new_submit();
});
$(document).off('click', '#group .view').on('click', '#group .view', function (e) {
    var thisBtn = $(this);
    group_view(thisBtn);
});
$(document).off('click', '#group .update').on('click', '#group .update', function (e) {
    var thisBtn = $(this);
    group_update(thisBtn);
});
$(document).off('click', '#groupUpdateModal button[name="submit"]').on('click', '#groupUpdateModal button[name="submit"]', function (e) {
    group_update_submit();
});
function group_table(){
    var form_data = {
        url_table:base_url+'/hrims/deduction/group/table',
        tid:'groupTable'
    };
    loadTable(form_data);
}
function group_new(){
    var thisBtn = $('#deduction button[name="new"]');
    var url = base_url+'/hrims/deduction/group/newModal';
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
function group_new_submit(){
    var thisBtn = $('#groupNewModal button[name="submit"]');
    var name = $('#groupNewModal input[name="name"]').val();
    var x = 0;
    $('#groupNewModal input[name="name"]').removeClass('border-require');
    if(name==''){
        $('#groupNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Group Name');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name
        };
        $.ajax({
            url: base_url+'/hrims/deduction/group/newSubmit',
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
                    group_table();
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
function group_view(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/deduction/group/viewModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/deduction/group/viewModalTable',
        tid:'groupViewModalTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function group_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/deduction/group/updateModal';
    var modal = 'default';
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
}
function group_update_submit(){
    var thisBtn = $('#groupUpdateModal button[name="submit"]');
    var id = $('#groupUpdateModal input[name="id"]').val();
    var name = $('#groupUpdateModal input[name="name"]').val();
    var x = 0;
    $('#groupUpdateModal input[name="name"]').removeClass('border-require');
    if(name==''){
        $('#groupUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Group Name');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name
        };
        $.ajax({
            url: base_url+'/hrims/deduction/group/updateSubmit',
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
                    group_table();
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