
roomsTable();
$(document).off('change', 'select[name="roomsStatus"]').on('change', 'select[name="roomsStatus"]', function (e) {
    roomsTable();
});
$(document).off('click', 'button[name="roomsNewModal"]').on('click', 'button[name="roomsNewModal"]', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/rooms/roomsNewModal';
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
});
$(document).off('click', '.roomsEditModal').on('click', '.roomsEditModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/rooms/roomsEditModal/'+id;
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModalGet(form_data,thisBtn);
});
$(document).off('click', '#roomsNewModal button[name="submit"]').on('click', '#roomsNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var building = $('#roomsNewModal select[name="building"] option:selected').val();
    var name = $('#roomsNewModal input[name="name"]').val();
    var shorten = $('#roomsNewModal input[name="shorten"]').val();
    var remarks = $('#roomsNewModal textarea[name="remarks"]').val();
    var x = 0;
    if(name==''){
        $('#roomsNewModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#roomsNewModal input[name="shorten"]').addClass('border-require');
        x++;
    }   
    if(x==0){
        var form_data = {
            building:building,
            name:name,
            shorten:shorten,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/rims/rooms/roomsNewSubmit',
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
                    roomsTable();
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
});
$(document).off('click', '#roomsEditModal button[name="submit"]').on('click', '#roomsEditModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#roomsEditModal input[name="id"]').val();
    var building = $('#roomsEditModal select[name="building"] option:selected').val();
    var name = $('#roomsEditModal input[name="name"]').val();
    var shorten = $('#roomsEditModal input[name="shorten"]').val();
    var remarks = $('#roomsEditModal textarea[name="remarks"]').val();
    var status = $('#roomsEditModal select[name="status"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#roomsEditModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#roomsEditModal input[name="shorten"]').addClass('border-require');
        x++;
    }   
    if(x==0){
        var form_data = {
            id:id,
            building:building,
            name:name,
            shorten:shorten,
            remarks:remarks,
            status:status
        };
        $.ajax({
            url: base_url+'/rims/rooms/roomsEditSubmit/'+id,
            type: 'GET',
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
                    roomsTable();
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
});
function roomsTable(){
    var thisBtn = $('select[name="roomsStatus"]');
    var status_id = thisBtn.val();
    var form_data = {
        url_table:base_url+'/rims/rooms/roomsTable',
        tid:'roomsTable',
        status_id:status_id
    };
    loadTablewLoader(form_data,thisBtn);
}