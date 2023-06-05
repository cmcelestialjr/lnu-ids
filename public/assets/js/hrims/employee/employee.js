employee_table();
$(document).off('change', '#employeeDiv select[name="option"]').on('change', '#employeeDiv select[name="option"]', function (e) {
    var thisBtn = $('#employeeDiv select[name="option"]');
    var val = $(this).val();    
    employee_stat(thisBtn,val);    
});
$(document).off('click', '#employeeDiv .btn-group button').on('click', '#employeeDiv .btn-group button', function (e) {
    var thisBtn = $(this);
    var status = $(this).data('id');
    employee_fetch(thisBtn,status);
});
$(document).off('click', '#employeeViewModal #employee_status').on('click', '#employeeViewModal #employee_status', function (e) {
    var thisBtn = $(this);
    employee_status(thisBtn);
});
$(document).off('click', '#workDiv button[name="newModal"]').on('click', '#workDiv button[name="newModal"]', function (e) {
    var thisBtn = $(this);
    workNewModal(thisBtn,status);
});
$(document).off('click', '#workDiv .workEditModal').on('click', '#workDiv .workEditModal', function (e) {
    var thisBtn = $(this); 
    workEditModal(thisBtn);   
});
function employee_table(){
    var thisBtn = $('#employeeDiv select[name="option"]');
    var status = 'all';
    employee_fetch(thisBtn,status);
}
function employee_status(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/employeeStatus';
    var modal = 'primary';
    var modal_size = 'modal-md';
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
function employee_fetch(thisBtn,status){
    var option = $('#employeeDiv select[name="option"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/employee/employeeTable',
        tid:'employeeTable',
        option:option,
        status:status
    };
    loadTablewLoader(form_data,thisBtn);
}
function employee_stat(thisBtn,val){
    var status = 'all';
                employee_fetch(thisBtn,status);
    // var form_data = {
    //     val:val
    // };
    // $.ajax({
    //     url: base_url+'/hrims/employee/employeeStat',
    //     type: 'POST',
    //     headers: {
    //         'X-CSRF-TOKEN': CSRF_TOKEN
    //     },
    //     data:form_data,
    //     cache: false,
    //     dataType: 'json',
    //     beforeSend: function() {
    //         thisBtn.attr('disabled','disabled'); 
    //         thisBtn.addClass('input-loading');
    //     },
    //     success : function(data){
    //         thisBtn.removeAttr('disabled');
    //         thisBtn.removeClass('input-loading');
    //         if(data.result=='success'){
    //             thisBtn.addClass('input-success');
    //             var status = 'all';
    //             employee_fetch(thisBtn,status);
    //         }else{
    //             toastr.error('Error.');
    //             thisBtn.addClass('input-error');
    //         }
    //         setTimeout(function() {
    //             thisBtn.removeClass('input-success');
    //             thisBtn.removeClass('input-error');
    //         }, 3000);
    //     },
    //     error: function (){
    //         toastr.error('Error!');
    //         thisBtn.removeAttr('disabled');
    //         thisBtn.removeClass('input-success');
    //         thisBtn.removeClass('input-error');
    //     }
    // });
}
function workNewModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/work/newModal';
    var modal = 'primary';
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
function workEditModal(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/work/editModal';
    var modal = 'primary';
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