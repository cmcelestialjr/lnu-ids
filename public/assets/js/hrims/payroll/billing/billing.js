listTable();
$(document).off('change', '#list select[name="year"]').on('change', '#list select[name="year"]', function (e) {
    listTable();
});
$(document).off('click', '#list .update').on('click', '#list .update', function (e) {
    show($(this));
});
$(document).off('click', '.assign').on('click', '.assign', function (e) {
    assign($(this));
});
$(document).off('click', '#assignSubmit').on('click', '#assignSubmit', function (e) {
    assignSubmit($(this));
});
function listTable(){
    var thisBtn = $('#list select[name="year"]');
    var year = $('#list select[name="year"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/payroll/billing/table',
        tid:'listTable',
        year:year
    };
    loadTablewLoader(form_data,thisBtn);
}
function show(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/payroll/billing/show';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/payroll/billing/showTable',
        tid:'showTable',
        id:id,
    };
    loadModal(form_data,thisBtn);
}
function showTable(id,thisBtn){
    var form_data = {
        url_table:base_url+'/hrims/payroll/billing/showTable',
        tid:'showTable',
        id:id
    };
    loadTablewLoader(form_data,thisBtn);
}
function assign(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/payroll/billing/assign';
    var modal = 'primary';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
    };
    loadModal(form_data,thisBtn);
}
function assignSubmit(thisBtn){
    var id = thisBtn.data('id');
    var employee = $('#employee option:selected').val();
    $('#employeeSearch').removeClass('border-require');
    if(employee==''){
        $('#employeeSearch').addClass('border-require');
        return;
    }
    var form_data = {
        id:id,
        employee:employee
    };
    $.ajax({
        url: base_url+'/hrims/payroll/billing/assignSubmit',
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
                showTable(data.id,thisBtn);
                $('#modal-primary').modal('hide');

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
