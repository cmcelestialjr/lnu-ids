
buildingsTable();
$(document).off('change', 'select[name="buildingsStatus"]').on('change', 'select[name="buildingsStatus"]', function (e) {
    buildingsTable();
});
$(document).off('click', 'button[name="buildingsNewModal"]').on('click', 'button[name="buildingsNewModal"]', function (e) {
    var thisBtn = $(this);
    var url = base_url+'/rims/buildings/buildingsNewModal';
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
$(document).off('click', '.buildingsViewModal').on('click', '.buildingsViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/buildings/buildingsViewModal/'+id;
    var modal = 'default';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/buildings/buildingsRoomsTable/'+id,
        tid:'buildingsRoomsTable',
    };
    loadModalGet(form_data,thisBtn);
});
$(document).off('click', '.buildingsEditModal').on('click', '.buildingsEditModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/buildings/buildingsEditModal/'+id;
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
$(document).off('click', '#buildingsNewModal button[name="submit"]').on('click', '#buildingsNewModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var name = $('#buildingsNewModal input[name="name"]').val();
    var shorten = $('#buildingsNewModal input[name="shorten"]').val();
    var remarks = $('#buildingsNewModal textarea[name="remarks"]').val();
    var x = 0;
    if(name==''){
        $('#buildingsNewModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#buildingsNewModal input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            remarks:remarks
        };
        $.ajax({
            url: base_url+'/rims/buildings/buildingsNewSubmit',
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
                    buildingsTable();
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
$(document).off('click', '#buildingsEditModal button[name="submit"]').on('click', '#buildingsEditModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var id = $('#buildingsEditModal input[name="id"]').val();
    var name = $('#buildingsEditModal input[name="name"]').val();
    var shorten = $('#buildingsEditModal input[name="shorten"]').val();
    var remarks = $('#buildingsEditModal textarea[name="remarks"]').val();
    var status = $('#buildingsEditModal select[name="status"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#buildingsEditModal input[name="name"]').addClass('border-require');
        x++;
    }
    if(shorten==''){
        $('#buildingsEditModal input[name="shorten"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            name:name,
            shorten:shorten,
            remarks:remarks,
            status:status
        };
        $.ajax({
            url: base_url+'/rims/buildings/buildingsEditSubmit/'+id,
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
                    buildingsTable();
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
function buildingsTable(){
    var thisBtn = $('select[name="buildingsStatus"]');
    var status_id = thisBtn.val();
    var form_data = {
        url_table:base_url+'/rims/buildings/buildingsTable',
        tid:'buildingsTable',
        status_id:status_id
    };
    loadTablewLoader(form_data,thisBtn);
}