$(document).ready(function() {
    $(document).off('change', '#files')
    .on('change', '#files', function (e) {
        filesInfo($(this));
    });
    $(document).off('click', '#submit-import')
    .on('click', '#submit-import', function (e) {
        submitImport($(this));
    });
});
function filesInfo(thisBtn){
    var total_files = $('#files')[0].files.length;
    if(total_files==1){
        var file_selected_count = total_files+' file';
    }else{
        var file_selected_count = total_files+' files';
    }
    $('#file-selected-count').html(file_selected_count+' selected..');
}
function submitImport(thisBtn){
    var option = $('#option option:selected').val();
    var total_files = $('#files')[0].files.length;

    if(total_files<=0){
        toastr.error('Please select a excel file.');
        return;
    }

    var form_data = new FormData();
    form_data.append('files', $('#files')[0].files[0]);
    form_data.append('option', option);

    $.ajax({
        url: base_url+'/hrims/import/import',
        type: 'POST',
        headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percent = (e.loaded / e.total) * 80;
                    $('#progress-bar .progress-bar').css('width', percent + '%');
                    $('#progress-bar .progress-bar span').html(percent + '% Complete');
                }
            }, false);
            return xhr;
        },
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
                $('#progress-bar .progress-bar').css('width', '100%');
                $('#progress-bar .progress-bar span').html('100% Complete');
            }else{
                toastr.error(data.result);
                thisBtn.addClass('input-error');
            }
            setTimeout(function() {
                thisBtn.removeClass('input-loading input-success input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading input-success input-error');
        }
    });
}
