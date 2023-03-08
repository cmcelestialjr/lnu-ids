viewTableLoad();
function viewTableLoad(){
    var thisBtn = $('.card-header');
    var form_data = {
        tid:'viewTable',
        url_table:base_url+'/users/systemsTable',
    };
    loadTable(form_data,thisBtn);
}
$(document).on('click', '#systemsDiv .edit', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsEdit';    
    var modal = 'default';
    var id = thisBtn.data('id');
    var modal_size = 'modal-md';
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
$(document).on('click', '#systemsDiv #new', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNew';    
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:''
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsDiv .nav', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNav';    
    var modal = 'default';
    var id = thisBtn.data('id');
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsNavDiv #new', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNavNew';    
    var modal = 'primary';
    var id = thisBtn.data('id');
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsNavDiv .edit', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNavEdit';    
    var modal = 'primary';
    var id = thisBtn.data('id');
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsNavDiv .navSub', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNavSub';    
    var modal = 'primary';
    var id = thisBtn.data('id');
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsNavSubDiv #new', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNavSubNew';    
    var modal = 'info';
    var id = thisBtn.data('id');
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsNavSubDiv .edit', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/users/systemsNavSubEdit';    
    var modal = 'info';
    var id = thisBtn.data('id');
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        w_table:'wo',
        url_table:'',
        tid:'',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#systemsEditDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#systemsEditDiv input[name="id"]').val();
    var name = $('#systemsEditDiv input[name="name"]').val();
    var shorten = $('#systemsEditDiv input[name="shorten"]').val();
    var icon = $('#systemsEditDiv input[name="icon"]').val();
    var button = $('#systemsEditDiv input[name="button"]').val();
    var x = 0;
    if(name==''){
        $('#systemsEditDiv input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#systemsEditDiv input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(icon==''){
        $('#systemsEditDiv input[name="icon"]').addClass('border-require');
        x++;
    }
    if(button==''){
        $('#systemsEditDiv input[name="button"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            shorten:shorten,
            icon:icon,
            button:button
        };
        $.ajax({
            url: base_url+'/users/systemEditSubmit',
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
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                }else if(data.result=='exists'){
                    toastr.error('Name or Shorten Exists!');
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
$(document).on('click', '#systemsNewDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var name = $('#systemsNewDiv input[name="name"]').val();
    var shorten = $('#systemsNewDiv input[name="shorten"]').val();
    var icon = $('#systemsNewDiv input[name="icon"]').val();
    var button = $('#systemsNewDiv input[name="button"]').val();
    var x = 0;
    if(name==''){
        $('#systemsNewDiv input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#systemsNewDiv input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(icon==''){
        $('#systemsNewDiv input[name="icon"]').addClass('border-require');
        x++;
    }
    if(button==''){
        $('#systemsNewDiv input[name="button"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            icon:icon,
            button:button
        };
        $.ajax({
            url: base_url+'/users/systemNewSubmit',
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
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                }else if(data.result=='exists'){
                    toastr.error('Name or Shorten Exists!');
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
$(document).on('click', '#systemsNavEditDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#systemsNavEditDiv input[name="id"]').val();
    var name = $('#systemsNavEditDiv input[name="name"]').val();
    var url = $('#systemsNavEditDiv input[name="url"]').val();
    var icon = $('#systemsNavEditDiv input[name="icon"]').val();
    var order = $('#systemsNavEditDiv input[name="order"]').val();
    var x = 0;
    if(name==''){
        $('#systemsNavEditDiv input[name="name"]').addClass('border-require');
        x++;
    }
    if(url==''){
        $('#systemsNavEditDiv input[name="url"]').addClass('border-require');
        x++;
    }
    if(icon==''){
        $('#systemsNavEditDiv input[name="icon"]').addClass('border-require');
        x++;
    }
    if(order==''){
        $('#systemsNavEditDiv input[name="order"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            url:url,
            icon:icon,
            order:order
        };
        $.ajax({
            url: base_url+'/users/systemsNavEditSubmit',
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
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                    systemsNavView(data.id);
                }else if(data.result=='exists'){
                    toastr.error('Name, Url or Order Exists!');
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
$(document).on('click', '#systemsNavNewDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#systemsNavNewDiv input[name="id"]').val();
    var name = $('#systemsNavNewDiv input[name="name"]').val();
    var url = $('#systemsNavNewDiv input[name="url"]').val();
    var icon = $('#systemsNavNewDiv input[name="icon"]').val();
    var order = $('#systemsNavNewDiv input[name="order"]').val();
    var x = 0;
    if(name==''){
        $('#systemsNavNewDiv input[name="name"]').addClass('border-require');
        x++;
    }
    if(url==''){
        $('#systemsNavNewDiv input[name="url"]').addClass('border-require');
        x++;
    }
    if(icon==''){
        $('#systemsNavNewDiv input[name="icon"]').addClass('border-require');
        x++;
    }
    if(order==''){
        $('#systemsNavNewDiv input[name="order"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            url:url,
            icon:icon,
            order:order
        };
        $.ajax({
            url: base_url+'/users/systemsNavNewSubmit',
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
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                    systemsNavView(id);
                }else if(data.result=='exists'){
                    toastr.error('Name, Url or Order Exists!');
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
$(document).on('click', '#systemsNavSubNewDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#systemsNavSubNewDiv input[name="id"]').val();
    var name = $('#systemsNavSubNewDiv input[name="name"]').val();
    var url = $('#systemsNavSubNewDiv input[name="url"]').val();
    var icon = $('#systemsNavSubNewDiv input[name="icon"]').val();
    var order = $('#systemsNavSubNewDiv input[name="order"]').val();
    var x = 0;
    if(name==''){
        $('#systemsNavSubNewDiv input[name="name"]').addClass('border-require');
        x++;
    }
    if(url==''){
        $('#systemsNavSubNewDiv input[name="url"]').addClass('border-require');
        x++;
    }
    if(icon==''){
        $('#systemsNavSubNewDiv input[name="icon"]').addClass('border-require');
        x++;
    }
    if(order==''){
        $('#systemsNavSubNewDiv input[name="order"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            url:url,
            icon:icon,
            order:order
        };
        $.ajax({
            url: base_url+'/users/systemsNavSubNewSubmit',
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
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                    systemsNavSubView(id);
                }else if(data.result=='exists'){
                    toastr.error('Name, Url or Order Exists!');
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
$(document).on('click', '#systemsNavSubEditDiv button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#systemsNavSubEditDiv input[name="id"]').val();
    var name = $('#systemsNavSubEditDiv input[name="name"]').val();
    var url = $('#systemsNavSubEditDiv input[name="url"]').val();
    var icon = $('#systemsNavSubEditDiv input[name="icon"]').val();
    var order = $('#systemsNavSubEditDiv input[name="order"]').val();
    var x = 0;
    if(name==''){
        $('#systemsNavSubEditDiv input[name="name"]').addClass('border-require');
        x++;
    }
    if(url==''){
        $('#systemsNavSubEditDiv input[name="url"]').addClass('border-require');
        x++;
    }
    if(icon==''){
        $('#systemsNavSubEditDiv input[name="icon"]').addClass('border-require');
        x++;
    }
    if(order==''){
        $('#systemsNavSubEditDiv input[name="order"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            url:url,
            icon:icon,
            order:order
        };
        $.ajax({
            url: base_url+'/users/systemsNavSubEditSubmit',
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
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                    systemsNavSubView(data.id);
                }else if(data.result=='exists'){
                    toastr.error('Name, Url or Order Exists!');
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
function systemsNavView(id){
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/users/systemsNavView',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {

        },
        success : function(data){
            if(data=='error'){
                toastr.error('Error');
            }else{
                $('#systemsNavDiv .card-body').html(data);
            }
        },
        error: function (){

        }
    });
}
function systemsNavSubView(id){
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/users/systemsNavSubView',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {

        },
        success : function(data){
            if(data=='error'){
                toastr.error('Error');
            }else{
                $('#systemsNavSubDiv .card-body').html(data);
            }
        },
        error: function (){

        }
    });
}