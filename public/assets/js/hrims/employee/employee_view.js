
$(document).off('click', '.employeeView').on('click', '.employeeView', function (e) {
    var thisBtn = $(this);       
    employee_view(thisBtn);    
});
$(document).off('change', '#staff_img_upload').on('change', '#staff_img_upload', function (e) {
    readURL(this,'#staff_open_img_upload');
    $("#staff_img_upload_submit").removeClass("hide");
});
$(document).off('click', '#staff_open_img_upload').on('click', '#staff_open_img_upload', function (e) {
    $("#staff_img_upload").trigger("click");
});
$(document).off('click', '#staff_img_upload_submit').on('click', '#staff_img_upload_submit', function (e) {
    var thisBtn = $(this); 
    employee_image_upload(thisBtn);   
});
$(document).off('click', '#employeeInformation').on('click', '#employeeInformation', function (e) {
    var thisBtn = $(this); 
    employee_information(thisBtn);   
});
function readURL(input,imgID) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $(imgID).attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
}
function employee_view(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/employeeView';
    var modal = 'default';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/employee/workTable',
        tid:'workTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function employee_information(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/employee/employeeInformation';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/hrims/employee/personalInfo',
        tid:'displayDiv',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function employee_image_upload(thisBtn){
    var id = thisBtn.data('id');    
    var form_data = new FormData();
    var ins = document.getElementById('staff_img_upload').files.length;
    if(ins>0){
        form_data.append("file", document.getElementById('staff_img_upload').files[0]);
        form_data.append('id', id);
        $.ajax({
            url: base_url+"/hrims/employee/uploadImage",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                thisBtn.attr('disabled','disabled'); 
                $('#staff_open_img_upload').addClass('hide');
                $("#staff_loader_img").removeClass("hide");
            },
            success : function(data){
                setTimeout(function() {
                    thisBtn.removeAttr('disabled');
                    $('#staff_loader_img').addClass('hide');
                    $("#staff_open_img_upload").removeClass("hide");
                    if(data.result=='error'){
                        toastr.error('Error!');                    
                    }else{
                        toastr.success('Success!');
                        $('#staff_img_upload').val('');
                    }
                }, 800);
            },
            error: function (){
                thisBtn.removeAttr('disabled');
                $('#staff_loader_img').addClass('hide');
                toastr.error('Error!');
            }
        });
    }
}