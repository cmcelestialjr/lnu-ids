search();
$(document).ready(function() {
    $(document).off('click', '#searchDiv button[name="submit"]')
    .on('click', '#searchDiv button[name="submit"]', function (e) {
        search();
    });
    $(document).off('click', '#searchResult #docStatus')
    .on('click', '#searchResult #docStatus', function (e) {
        docStatusModal($(this));
    });
    $(document).off('click', '#statusModal button[name="submit"]')
    .on('click', '#statusModal button[name="submit"]', function (e) {
        statusSubmit($(this));
    });
    $(document).off('click', '.docs-option')
    .on('click', '.docs-option', function (e) {
        var id = $(this).data('id');
        var option = $(this).data('o');
        if(option=='History'){
            $('#searchDiv input[name="search"]').val(id);
            search();
        }
    });
});
function search(){
    var thisBtn = $('#searchDiv button[name="submit"]');
    var search = $('#searchDiv input[name="search"]').val();
    if(search!=''){
        var form_data = {
            search:search
        };
        $.ajax({
            url: base_url+'/dts/search',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            beforeSend: function() {
                thisBtn.attr('disabled','disabled');
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');

                $('#searchResult').html(data);

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
function docStatusModal(thisBtn){
    var dts_id = thisBtn.data('id');
    var url = base_url+'/dts/status';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        dts_id:dts_id
    };
    loadModal(form_data,thisBtn);
}
function statusSubmit(thisBtn){
    var dts_id = thisBtn.data('id');
    var status_id = $('#statusModal select[name="status"] option:selected').val();
    var form_data = {
        dts_id:dts_id,
        status_id:status_id
    };
    $.ajax({
        url: base_url+'/dts/statusSubmit',
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
                $('#searchResult #docStatus').removeClass(data.oldClass);
                $('#searchResult #docStatus').addClass(data.newClass);
                $('#searchResult #docStatus').html(data.html);
                $('#searchResult #statusChangeAt').html(data.change_at);
                $('#searchResult #statusChangeBy').html(data.change_by);
                $('#searchResult #duration').html(data.duration);
                toastr.success('Success!');
                thisBtn.addClass('input-success');
                $("#modal-default").modal('hide');
            }else{
                toastr.error('Error!');
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
