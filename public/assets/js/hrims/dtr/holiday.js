$(document).on('click', '.holidayLi', function (e) {
    holiday_table(); 
});
$(document).on('click', '#holidayDiv button[name="new"]', function (e) {
    var thisBtn = $(this);
    holiday_new(thisBtn);
});
$(document).on('click', '#holidayNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    holiday_new_submit(thisBtn);
});
function holiday_table(){   
    var thisBtn = $('#holidayDiv button');
    var year = $('#holidayDiv select[name="year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/dtr/holidayTable',
        tid:'holidayTable',
        year:year
    };
    loadTablewLoader(form_data,thisBtn);
}
function holiday_new(thisBtn){
    var url = base_url+'/hrims/dtr/holidayNewModal';
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
function holiday_new_submit(thisBtn){
    var name = $('#holidayNewModal input[name="name"]').val();
    var date = $('#holidayNewModal input[name="date"]').val();
    var type = $('#holidayNewModal select[name="type"] option:selected').val();
    var option = $('#holidayNewModal select[name="option"] option:selected').val();
    var x = 0;
    var form_data = {
        name:name,
        date:date,
        type:type,
        option:option
    };
    if(name==''){
        $('#holidayNewModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(date==''){
        $('#holidayNewModal input[name="date"]').addClass('border-require');
        x++;
    }
    if(x==0){
        $.ajax({
            url: base_url+'/hrims/dtr/holidayNewSubmit',
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
                    thisBtn.addClass('input-success');
                    $('#modal-default').modal('hide');
                    holiday_table();
                }else if(data.result=='exists'){
                    toastr.error('Date already exists!');
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
}