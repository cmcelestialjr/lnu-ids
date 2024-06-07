$(document).off('click', '#employeeDiv .deduction').on('click', '#employeeDiv .deduction', function (e) {
    var thisBtn = $(this);
    deduction_modal(thisBtn);
});
$(document).off('click', '#listNewModal button[name="submit"]').on('click', '#listNewModal button[name="submit"]', function (e) {
    list_new_submit();
});
$(document).off('click', '#deductionModal #deductionLi').on('click', '#deductionModal #deductionLi', function (e) {
    deduction_table();
    $('#deductionModal input[name="li"]').val('deduction');
});
$(document).off('click', '#deductionModal #allowanceLi').on('click', '#deductionModal #allowanceLi', function (e) {
    allowance_table();
    $('#deductionModal input[name="li"]').val('allowance');
});
$(document).off('click', '#deductionModal .docs').on('click', '#deductionModal .docs', function (e) {
    var thisBtn = $(this);
    deduction_docs(thisBtn);
});
$(document).off('change', '#deductionModal select[name="payroll_type"]').on('change', '#deductionModal select[name="payroll_type"]', function (e) {
    var li = $('#deductionModal input[name="li"]').val();
    if(li=='allowance'){
        allowance_table();
    }else{
        deduction_table();
    }
});
$(document).off('change', '#deductionModal select[name="emp_stat"]').on('change', '#deductionModal select[name="emp_stat"]', function (e) {
    var li = $('#deductionModal input[name="li"]').val();
    if(li=='allowance'){
        allowance_table();
    }else{
        deduction_table();
    }
});
$(document).off('blur', '#deductionModal .input').on('blur', '#deductionModal .input', function (e) {
    var thisBtn = $(this);
    deduction_input(thisBtn);
});
$(document).off('click', '#docsModal #new button[name="submit"]').on('click', '#docsModal #new button[name="submit"]', function (e) {
    var thisBtn = $(this);
    e.preventDefault();
    deduction_docs_submit(thisBtn);
});
$(document).off('click', '#docsModal #docs button[name="submit"]').on('click', '#docsModal #docs button[name="submit"]', function (e) {
    docs_table();
});
$(document).off('click', '#docsModal #docs .viewDoc').on('click', '#docsModal #docs .viewDoc', function (e) {
    var thisBtn = $(this);
    docs_view_modal(thisBtn);
});
$(document).off('input', '#docsModal #new input[name="files[]"]').on('input', '#docsModal #new input[name="files[]"]', function (e) {
    var files = $('#docsModal #new input[name="files[]"]')[0].files;
    if(files.length>0){
        $('#docsModal #new .file-message').html(files.length+' file/s selected');
    }
});
function deduction_modal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/deduction/deductionModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/employee/deduction/deductionTable',
        tid:'deductionTable',
        id:id,
        payroll_type:1,
        emp_stat:'None'
    };
    loadModal(form_data,thisBtn);
}
function deduction_table(){
    var id = $('#deductionModal input[name="id"]').val();
    var payroll_type = $('#deductionModal select[name="payroll_type"] option:selected').val();
    var emp_stat = $('#deductionModal select[name="emp_stat"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/deduction/deductionTable',
        tid:'deductionTable',
        id:id,
        payroll_type:payroll_type,
        emp_stat:emp_stat
    };
    loadTable(form_data);
}
function allowance_table(){
    var id = $('#deductionModal input[name="id"]').val();
    var payroll_type = $('#deductionModal select[name="payroll_type"] option:selected').val();
    var emp_stat = $('#deductionModal select[name="emp_stat"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/allowance/table',
        tid:'allowanceTable',
        id:id,
        payroll_type:payroll_type,
        emp_stat:emp_stat
    };
    loadTable(form_data);
}
function deduction_docs(thisBtn){
    var id = $('#deductionModal input[name="id"]').val();
    var payroll_type = $('#deductionModal select[name="payroll_type"] option:selected').val();
    var emp_stat = $('#deductionModal select[name="emp_stat"] option:selected').val();
    var did = thisBtn.data('id');
    var url = base_url+'/hrims/employee/deduction/docsModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/employee/deduction/docsTable',
        tid:'docsTable',
        id:id,
        payroll_type:payroll_type,
        emp_stat:emp_stat,
        did:did
    };
    loadModal(form_data,thisBtn);
}
function docs_table(){
    var id = $('#deductionModal input[name="id"]').val();
    var payroll_type = $('#deductionModal select[name="payroll_type"] option:selected').val();
    var emp_stat = $('#deductionModal select[name="emp_stat"] option:selected').val();
    var year = $('#docsModal #docs select[name="year"] option:selected').val();
    var did = $('#docsModal #docs input[name="id"]').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/deduction/docsTable',
        tid:'docsTable',
        id:id,
        payroll_type:payroll_type,
        emp_stat:emp_stat,
        did:did,
        year:year
    };
    loadTable(form_data);
}
function docs_view_modal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/deduction/docsViewModal';
    var modal = 'info';
    var modal_size = 'modal-xl';
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
function deduction_input(thisBtn){
    var id = $('#deductionModal input[name="id"]').val();
    var payroll_type = $('#deductionModal select[name="payroll_type"] option:selected').val();
    var emp_stat = $('#deductionModal select[name="emp_stat"] option:selected').val();
    var val = thisBtn.data('val');
    var did = thisBtn.data('id');
    var value = thisBtn.val();
    var form_data = {
        id:id,
        did:did,
        val:val,
        value:value,
        payroll_type:payroll_type,
        emp_stat:emp_stat
    };
    $.ajax({
        url: base_url+'/hrims/employee/deduction/deductionUpdate',
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
                if(data.check_amount==null){
                    $('#date_from'+did).val('');
                    $('#date_to'+did).val('');
                    $('#remarks'+did).val('');
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
}
function deduction_docs_submit(thisBtn){
    var thisForm = $('#docsModal #new #form');
    var x = 0;
    var files = $('#docsModal #new input[name="files[]"]')[0].files;
    var account_no = $('#docsModal #new input[name="account_no"]').val();
    var total_amount = $('#docsModal #new input[name="total_amount"]').val();
    var amount = $('#docsModal #new input[name="amount"]').val();
    // if(files.length<=0){
    //     toastr.error('Please select a file');
    //     x++;
    // }
    $('#docsModal #new input[name="account_no"]').removeClass('border-require');
    $('#docsModal #new input[name="total_amount"]').removeClass('border-require');
    $('#docsModal #new input[name="amount"]').removeClass('border-require');
    if(account_no==''){
        toastr.error('Please input application no.');
        $('#docsModal #new input[name="account_no"]').addClass('border-require');
        x++;
    }
    if(total_amount<=0 || total_amount==''){
        toastr.error('Please input total loan');
        $('#docsModal #new input[name="total_amount"]').addClass('border-require');
        x++;
    }
    if(amount<=0 || amount==''){
        toastr.error('Please input monthly');
        $('#docsModal #new input[name="amount"]').addClass('border-require');
        x++;
    }
    if(x==0){
        var id = $('#deductionModal input[name="id"]').val();
        var payroll_type = $('#deductionModal select[name="payroll_type"] option:selected').val();
        var emp_stat = $('#deductionModal select[name="emp_stat"] option:selected').val();

        var form_data = new FormData(thisForm[0]);
        form_data.append('id', id);
        form_data.append('payroll_type', payroll_type);
        form_data.append('emp_stat', emp_stat);
        var progressBar = $('#docsModal #new #progress-bar');
        var progressText = $('#docsModal #new #progress-bar span')
        $.ajax({
            url: base_url+'/hrims/employee/deduction/docsSubmit',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                  if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 35);
                    progressBar.css('width', percent + '%').attr('aria-valuenow', percent);
                    progressText.text(percent + '%');
                  }
                });
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
                    progressBar.css('width', '100%').attr('aria-valuenow', 100);
                    progressText.text('100%');
                    $('#docsModal #new input[name="files[]"]').val('');
                    $('#docsModal #new input[name="account_no"]').val('');
                    $('#docsModal #new input[name="amount"]').val('');
                    $('#docsModal #new input[name="total_amount"]').val('');
                    $('#docsModal #new input[name="date_from"]').val('');
                    $('#docsModal #new input[name="date_to"]').val('');

                    $('#docsModal #new .file-message').html('or drag and drop file here');
                }else{
                    toastr.error(data.result);
                    thisBtn.addClass('input-error');
                }
                setTimeout(function() {
                    thisBtn.removeClass('input-success');
                    thisBtn.removeClass('input-error');
                    progressBar.css('width', '0%').attr('aria-valuenow', 0);
                    progressText.text('0%');

                }, 3000);
            },
            error: function (xhr, status, error){
                toastr.error(xhr.responseText);
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }
        });
    }
}
