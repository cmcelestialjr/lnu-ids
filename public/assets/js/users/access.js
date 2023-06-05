$(document).on('click', '.access', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/accessView';    
    var modal = 'default';
    var id = thisBtn.data('id');
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        statis:'',
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('change', '#usersAccessDiv .accessSelect', function (e) {
    var thisBtn = $(this);
    var id = $('#usersAccessDiv input[name="id"]').val();
    var level_id = thisBtn.val();
    var system_id = thisBtn.data('id');
    var val = thisBtn.data('val');
    var form_data = {
        id:id,
        level_id:level_id,
        system_id:system_id,
        val:val
    };
    $.ajax({
        url: base_url+'/users/accessUpdate',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            $('#usersAccessDiv .accessSelect').attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            $('#usersAccessDiv .accessSelect').removeAttr('disabled');
            thisBtn.removeClass('input-loading');
            if(data.result=='success'){
                toastr.success('Success.');
                thisBtn.addClass('input-success');
                var form_data = {
                    id:id,
                    system_id:data.system_id,
                    val:val,
                    from:data.from
                };
                accessListNav(form_data);
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
            $('#usersAccessDiv .accessSelect').removeAttr('disabled');
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
        }
    });
});
$(document).on('click', '#usersAccessDiv .accessList', function (e) {
    var thisBtn = $(this);
    var id = $('#usersAccessDiv input[name="id"]').val();
    var system_id = thisBtn.data('id');
    var val = thisBtn.data('val');    
    if(val=='system'){
        var form_data = {
            id:id,
            system_id:system_id,
            val:val,
            from:'system'
        };
        accessListNav(form_data);
        
    }else if(val=='nav'){
        var form_data = {
            id:id,
            system_id:system_id,
            val:val,
            from:'nav'
        };
        accessListNavSub(form_data);
    }
    
});
function accessListSystem(form_data){
    $.ajax({
        url: base_url+'/users/accessListSystem',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            $('#usersAccessDiv #mainNavDiv').css({ opacity: 0.5 });
            $('#usersAccessDiv #subNavDiv').css({ opacity: 0.5 });
        },
        success : function(data){            
            $('#usersAccessDiv #mainNavDiv').css({ opacity: 1 });
            $('#usersAccessDiv #mainNavDiv').html(data);
        },
        error: function (){
            toastr.error('Error!');
        }
    });
}
function accessListNav(form_data){
    $.ajax({
        url: base_url+'/users/accessListNav',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            $('#usersAccessDiv #mainNavDiv').css({ opacity: 0.5 });
            $('#usersAccessDiv #subNavDiv').css({ opacity: 0.5 });
            accessListNavSub(form_data);
        },
        success : function(data){            
            $('#usersAccessDiv #mainNavDiv').css({ opacity: 1 });
            $('#usersAccessDiv #mainNavDiv').html(data);
        },
        error: function (){
            toastr.error('Error!');
        }
    });
}
function accessListNavSub(form_data){
    $.ajax({
        url: base_url+'/users/accessListNavSub',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
            $('#usersAccessDiv #subNavDiv').css({ opacity: 0.5 });
        },
        success : function(data){
            $('#usersAccessDiv #subNavDiv').css({ opacity: 1 });
            $('#usersAccessDiv #subNavDiv').html(data);
        },
        error: function (){
            toastr.error('Error!');
        }
    });
}