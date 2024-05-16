$(document).ready(function() {
    $(document).off('click', 'button[name="submit"]')
    .on('click', 'button[name="submit"]', function (e) {
        newDTS($(this));
    });
    $(document).off('change', 'input[name="file[]"]')
    .on('change', 'input[name="file[]"]', function (e) {
        var total_files = $('input[name="file[]"]')[0].files.length;
        if(total_files==1){
            var file_selected_count = total_files+' file';
        }else{
            var file_selected_count = total_files+' files';
        }
        $('#file-selected-count').html(file_selected_count+' selected..');
    });
});
function newDTS(thisBtn){
    var type_id = $('select[name="type"] option:selected').val();
    var office_id = $('select[name="office"] option:selected').val();
    var particulars = $('input[name="particulars"]').val();
    var description = $('textarea[name="description"]').val();
    var amount = $('input[name="amount"]').val();
    var remarks = $('textarea[name="remarks"]').val();
    var total_files = $('input[name="file[]"]')[0].files.length;
    var check_x = 0;

    $('#typeDiv').removeClass('border-require');
    $('#officeDiv').removeClass('border-require');
    $('input[name="particulars"]').removeClass('border-require');
    $('textarea[name="description"]').removeClass('border-require');

    if(type_id==''){
        $('#typeDiv').addClass('border-require');
        check_x++;
    }
    if(office_id==''){
        $('#officeDiv').addClass('border-require');
        check_x++;
    }
    if(particulars==''){
        $('input[name="particulars"]').addClass('border-require');
        check_x++;
    }
    if(description==''){
        $('textarea[name="description"]').addClass('border-require');
        check_x++;
    }

    if(check_x==0){
        var form_data = new FormData();

        if(total_files>0){
            for (var x = 0; x < total_files; x++) {
                form_data.append('files'+x, $('input[name="file[]"]')[0].files[x]);
            }
        }

        form_data.append('type_id', type_id);
        form_data.append('office_id', office_id);
        form_data.append('particulars', particulars);
        form_data.append('description', description);
        form_data.append('amount', amount);
        form_data.append('remarks', remarks);
        form_data.append('total_files', total_files);

        $.ajax({
            url: base_url+'/dts/newSubmit',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.attr('disabled','disabled');
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');

                if(data.result=='success'){
                    toastr.success('Success!');
                    thisBtn.addClass('input-success');
                    Swal.fire({
                        title: 'DTS No.: '+data.dts_id,
                        icon: 'success'
                    });
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
}
