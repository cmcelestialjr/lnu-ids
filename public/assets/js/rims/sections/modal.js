$(document).on('click', '#sectionDiv .sectionNewModal', function (e) {
    var thisBtn = $(this);
    var program_id = $('#sectionDiv #programsSelectDiv select[name="program"]').val();
    var branch_id = $('#sectionDiv #programsSelectDiv select[name="branch"]').val();
    var id = $('#sectionDiv select[name="school_year"]').val();
    var url = base_url+'/rims/sections/sectionNewModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id,
        program_id:program_id,
        branch_id:branch_id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#sectionDiv .sectionViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var level = thisBtn.data('level');
    var url = base_url+'/rims/sections/sectionViewModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/sections/sectionViewTable',
        tid:'sectionViewTable',
        id:id,
        level:level
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#sectionViewModal .courseViewModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/sections/courseViewModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/rims/sections/courseViewTable',
        tid:'courseViewTable',
        id:id
    };
    loadModal(form_data,thisBtn);
});
$(document).on('click', '#courseViewModal .minMaxModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/sections/minMaxModal';
    var modal = 'info';
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
});
$(document).on('click', '#courseViewModal .courseSchedRmModal', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var url = base_url+'/rims/sections/courseSchedRmModal';
    var modal = 'info';
    var modal_size = 'modal-xxl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/rims/sections/courseSchedRmTable',
        tid:'courseSchedRmTable',
        id:id
    };
    $.ajax({
        url: url,
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
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-xxl');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-xl');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-lg');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-md');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-sm');
            $("#modal-"+form_data.modal+" .modal-dialog").removeClass('modal-xs');
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
            $(".datePicker").inputmask('mm/dd/yyyy');
            $('.datePicker').daterangepicker({
              locale: {
                  format: 'MM/DD/YYYY',
              },
              singleDatePicker: true,
              showDropdowns: true
            });  
            setTimeout(function() {
              thisBtn.removeClass('input-success');
              thisBtn.removeClass('input-error');
            }, 3000);
            if(form_data.w_table=='div'){
                loadDivwLoader(form_data,thisBtn);
            }
            course_sched_rm_details();
            course_sched_rm_schedule();
            course_sched_rm_rm_instructor();
        },
        error: function (){
          toastr.error('Error!');
          thisBtn.removeAttr('disabled');         
          thisBtn.removeClass('input-success');
          thisBtn.removeClass('input-error');
        }
    });
});