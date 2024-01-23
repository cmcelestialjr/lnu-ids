employee_table();
$(document).on('click', '.employeeView', function (e) {    
    var thisBtn = $(this);
    dtr_view(thisBtn); 
});
$(document).on('click', '#all_div button[name="submit"]', function (e) {
    employee_table(); 
});
$(document).off('click', '.receiveDTR').on('click', '.receiveDTR', function (e) {
    var thisBtn = $(this);
    receiveDTR(thisBtn);
});
function employee_table(){   
    var thisBtn = $('#all_div button');
    var year = $('#all_div select[name="year"] option:selected').val();
    var month = $('#all_div select[name="month"] option:selected').val();
    var range = $('#all_div select[name="range"] option:selected').val();
    var option = $('#all_div select[name="option1"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/dtr/employeeTable',
        tid:'employeeTable',
        year:year,
        month:month,
        range:range,
        option:option
    };
    loadTablewLoader(form_data,thisBtn);
}
function dtr_view(thisBtn){
    var id_no = thisBtn.data('id');
    var year = $('#all_div select[name="year"] option:selected').val();
    var month = $('#all_div select[name="month"] option:selected').val();
    var range = $('#all_div select[name="range"] option:selected').val();
    var url = base_url+'/hrims/dtr/dtrView';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        id_no:id_no,
        year:year,
        month:month,
        range:range
    };

    $.ajax({
        url: form_data.url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        beforeSend: function() {
          var loaderImage = base_url+"/assets/images/loader/loader-dots.gif";
          var loaderModal = 
                        '<div class="modal-content bg-default">'+
                          '<div id="loader-icon"><div class="overlay">'+
                              '<img src="'+loaderImage+'" alt="IDSLoader" class="loaderModal">'+
                          '</div></div>'+
                          '<div class="modal-header">'+
                            '<h4 class="modal-title"><span class="fa fa-info"></span> </h4>'+
                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                              '<span aria-hidden="true">&times;</span>'+
                            '</button><br><br><br>'+
                          '</div>'+
                      '<div class="modal-footer">'+
                              '<button type="button" class="btn btn-info close-modal" data-dismiss="modal">Close</button>'+
                          '</div>'+
                      '</div>';
            thisBtn.attr('disabled','disabled');
            thisBtn.addClass('input-loading');
            $('#modal-'+form_data.modal).modal('show');
            $("#modal-"+form_data.modal+" .modal-dialog #modal-"+form_data.modal+"-content").html('');
            $("#modal-"+form_data.modal+" .modal-dialog").addClass(form_data.modal_size);
            
            $("#loader-icon").removeClass("hide");
            $("#modal-"+form_data.modal+" .modal-dialog #modal-"+form_data.modal+"-content").html(loaderModal);
        },
        success : function(data){
            $("#loader-icon").addClass('hide');
            thisBtn.removeAttr('disabled'); 
            thisBtn.removeClass('input-loading');
            thisBtn.addClass('input-success');          
            $("#modal-"+form_data.modal+" .modal-dialog #modal-"+form_data.modal+"-content").html(data); 
            $(".select2-"+form_data.modal).select2({
                dropdownParent: $("#modal-"+form_data.modal)
            });
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
function receiveDTR(thisCh){
    var thisBtn = $('.receiveDTR');
    var id = thisCh.data('id');
    var type = thisCh.data('type');
    var year = $('#all_div select[name="year"] option:selected').val();
    var month = $('#all_div select[name="month"] option:selected').val();

    var form_data = {
        id:id,
        type:type,
        year:year,
        month:month
    };

    $.ajax({
        url: base_url+'/hrims/dtr/receiveDTR',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.attr('disabled','disabled'); 
            thisCh.removeClass('input-error');
            thisCh.addClass('input-loading');
            thisCh.removeClass('border-require');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisCh.removeClass('input-loading'); 
            if(data.result=='success'){
                toastr.success('Success');
                thisCh.addClass('input-success');
                thisCh.next('div').html(data.div);
            }else{
                toastr.error('Error.');
                thisCh.addClass('input-error');
            }
            setTimeout(function() {
                thisCh.removeClass('input-success');
                thisCh.removeClass('input-error');
            }, 3000);
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
            thisCh.removeClass('input-success');
            thisCh.removeClass('input-error');
        }
    });
}