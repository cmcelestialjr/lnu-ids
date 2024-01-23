devicesTable();
$(document).off('click', '#devicesNewModal').on('click', '#devicesNewModal', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/hrims/devices/devicesNewModal';
    var modal = 'default';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
});
$(document).off('click', '.devicesEditModal').on('click', '.devicesEditModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/devices/devicesEditModal';
    var modal = 'default';
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
$(document).off('click', '#devicesNewModalForm button[name="submit"]').on('click', '#devicesNewModalForm button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var name = $('#devicesNewModalForm input[name="name"]').val();
    var ipaddress = $('#devicesNewModalForm input[name="ipaddress"]').val();
    var port = $('#devicesNewModalForm input[name="port"]').val();
    var remarks = $('#devicesNewModalForm textarea[name="remarks"]').val();
    var x = 0;
    $('#devicesNewModalForm input[name="name"]').removeClass('border-require');
    $('#devicesNewModalForm input[name="ipaddress"]').removeClass('border-require');
    if(name==''){
        $('#devicesNewModalForm input[name="name"]').addClass('border-require');
        toastr.error('Please input name of device');
        x++;
    }
    if(ipaddress=='' || ipaddressValidate(ipaddress)==false){
        $('#devicesNewModalForm input[name="ipaddress"]').addClass('border-require');
        toastr.error('Please input valid ipaddress');
        x++;
    }
    if(port<=0){
        $('#devicesNewModalForm input[name="port"]').addClass('border-require');
        toastr.error('Please input valid port');
        x++;
    }
    var form_data = {
        name:name,
        ipaddress:ipaddress,
        port:port,
        remarks:remarks
    };
    if(x==0){
        $.ajax({
            url: base_url+'/hrims/devices/devicesNewModalSubmit',
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
                    devicesTable();
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    $('#modal-default').modal('hide');
                }else{
                    toastr.error(data.result);
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
$(document).off('click', '#devicesEditModalForm button[name="submit"]').on('click', '#devicesEditModalForm button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#devicesEditModalForm input[name="id"]').val();
    var name = $('#devicesEditModalForm input[name="name"]').val();
    var ipaddress = $('#devicesEditModalForm input[name="ipaddress"]').val();
    var port = $('#devicesEditModalForm input[name="port"]').val();
    var remarks = $('#devicesEditModalForm textarea[name="remarks"]').val();
    var x = 0;
    $('#devicesEditModalForm input[name="name"]').removeClass('border-require');
    $('#devicesEditModalForm input[name="ipaddress"]').removeClass('border-require');
    if(name==''){
        $('#devicesEditModalForm input[name="name"]').addClass('border-require');
        toastr.error('Please input name of device');
        x++;
    }
    if(ipaddress=='' || ipaddressValidate(ipaddress)==false){
        $('#devicesEditModalForm input[name="ipaddress"]').addClass('border-require');
        toastr.error('Please input valid ipaddress');
        x++;
    }
    if(port<=0){
        $('#devicesEditModalForm input[name="port"]').addClass('border-require');
        toastr.error('Please input valid port');
        x++;
    }
    var form_data = {
        id:id,
        name:name,
        ipaddress:ipaddress,
        port:port,
        remarks:remarks
    };
    if(x==0){
        $.ajax({
            url: base_url+'/hrims/devices/devicesEditModalSubmit',
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
                    devicesTable();
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    $('#modal-default').modal('hide');
                }else{
                    toastr.error(data.result);
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
$(document).off('click', '#devicesUpdateStatus').on('click', '#devicesUpdateStatus', function (e) {
    var thisBtn = $(this);    
    $("#devicesTable").css("opacity", 0.5);
    $("#devicesTable").prop("disabled", true);
    var form_data = {
        id:1
    };
    $.ajax({
        url: base_url+'/hrims/devices/devicesUpdateStatus',
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
                if (data.devices && data.devices.length > 0) {
                    $("#devicesTable").css("opacity", 1);
                    $("#devicesTable").prop("disabled", false);
                    $('.devicesStatus').html('');
                    $.each(data.devices, function (index, device) {
                        if(device.status=='On'){
                            var status = '<button class="btn btn-success btn-success-scan"><span class="fa fa-check"></span> On</button>';
                        }else{
                            var status = '<button class="btn btn-danger btn-danger-scan"><span class="fa fa-times"></span> Off</button>';
                        }
                        var dateTime = '';
                        if(device.dateTime!=''){
                            var dateTime = '<button class="btn btn-primary btn-primary-scan btn-sm devicesDateTimeModal"'+
                                            'data-id="'+device.id+'"'+
                                            'data-s="'+device.status+'">'+
                                            '<span class="fa fa-calendar"></span> '+device.dateTime+'</button>';
                        }
                        $('#device_status_'+device.id).html('');
                        $('#device_dateTime_'+device.id).html('');
                        $('#device_status_'+device.id).html(status);
                        $('#device_dateTime_'+device.id).html(dateTime);
                        $('#logs_acquire_'+device.id).attr('data-s', device.status);
                    });
                }
            }else{
                toastr.error(data.result);
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
$(document).off('click', '.devicesDateTimeModal').on('click', '.devicesDateTimeModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var s = thisBtn.data('s');
    if(s!='On'){
        toastr.error('This Device is Off!');
    }else{
        var url = base_url+'/hrims/devices/devicesDateTimeModal';
        var modal = 'default';
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
    }
});
$(document).off('click', '#dateTimeModalSubmit button[name="submit"]').on('click', '#dateTimeModalSubmit button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#dateTimeModalSubmit input[name="id"]').val();
    var date = $('#dateTimeModalSubmit input[name="date"]').val();
    var time = $('#dateTimeModalSubmit input[name="time"]').val();
    var x = 0;
    $('#dateTimeModalSubmit input[name="date"]').removeClass('border-require');
    $('#dateTimeModalSubmit input[name="time"]').removeClass('border-require');
    if(date==''){
        $('#dateTimeModalSubmit input[name="date"]').addClass('border-require');
        toastr.error('Please input valid date!');
        x++;
    }
    if(time==''){
        $('#dateTimeModalSubmit input[name="time"]').addClass('border-require');
        toastr.error('Please input valid time');
        x++;
    }    
    if(x==0){
        var form_data = {
            id:id,
            date:date,
            time:time
        };
        $.ajax({
            url: base_url+'/hrims/devices/dateTimeModalSubmit',
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
                    var dateTime = '<button class="btn btn-primary btn-primary-scan btn-sm devicesDateTimeModal"'+
                                            'data-id="'+data.id+'"'+
                                            'data-s="On">'+
                                            '<span class="fa fa-calendar"></span> '+data.dateTime+'</button>';
                    $('#device_dateTime_'+data.id).html(dateTime);
                    $('#modal-default').modal('hide');
                }else{
                    toastr.error(data.result);
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
$(document).off('click', '.logsClear').on('click', '.logsClear', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/hrims/devices/logsClear',
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
            }else{
                toastr.error(data.result);
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
function devicesTable(){
    var form_data = {
        url_table:base_url+'/hrims/devices/devicesTable',
        tid:'devicesTable'
    };
    loadTable(form_data);
}