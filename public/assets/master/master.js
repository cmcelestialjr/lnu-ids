var url = window.location.pathname.split("/");
var base_url = window.location.origin;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "preventOpenDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "300",
    "timeOut": "2000",
    "extendedTimeOut": "800",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    showCloseButton: true
});
$('.select2').select2();
// $('.multiselect').multiselect({
//   enableFiltering: true,
//   includeFilterClearBtn: true,
//   maxHeight: 200,
//   buttonWidth: '100%',
//   filterPlaceholder: 'Search...',
//   nonSelectedText: '',
//   buttonTextAlignment: 'left'
// });
// $('.multiselect-all').multiselect({
//   includeSelectAllOption: true,
//   selectAllText: 'Select All',
//   enableFiltering: true,
//   includeFilterClearBtn: true,
//   maxHeight: 200,
//   buttonWidth: '100%',
//   filterPlaceholder: 'Search...',
//   nonSelectedText: '',
//   buttonTextAlignment: 'left'
// });

$('.datePicker').inputmask('mm/dd/yyyy');
$('.yearpicker').inputmask({'mask': '9999'});
$('.contact').inputmask('XXXXXXXXXX');
$('.datePicker').daterangepicker({
  locale: {
      format: 'MM/DD/YYYY',
  },
  singleDatePicker: true,
  showDropdowns: true,
});
$('.datetimepicker').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
      format: 'YYYY-MM-DD'
    }
});
$('.timepicker').timepicker({
  timeFormat: 'hh:mmp',
  interval: 15,
  minTime: '07',
  maxTime: '11:00pm',
  defaultTime: '07',
  startTime: '07:00',
  dynamic: false,
  dropdown: true,
  scrollbar: true
});
$('.dateRange').daterangepicker();
$('.date-range').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD',
      showDropdowns: true
    }
});
$(document).on('focusin', '.datepicker', function (e) {
  $(this).daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
      format: 'YYYY-MM-DD'
    }
  });
});
$('.yearpicker').datepicker({
  autoclose: true,
  format: 'yyyy',
  viewMode: 'years',
  minViewMode: 'years'
});
function loadModal(form_data,thisBtn){
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
          if(form_data.static=='true'){
            $('#modal-'+form_data.modal).modal({backdrop:false,keyboard:false,show:true});
          }else{
            $('#modal-'+form_data.modal).modal('show');
          }
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
          $(".contact").inputmask('999-999-9999');
          $(".bank_account_no").inputmask('9999-9999-99');
          $(".tin_no").inputmask('999-999-999');
          $(".gsis_bp_no").inputmask('9999999999');
          $(".philhealth_no").inputmask('99-999999999-9');
          $(".sss_no").inputmask('99-9999999-9');
          $(".pagibig_no").inputmask('9999-9999-9999');
          $(".year").inputmask('9999');
          $('.yearpicker').inputmask({'mask': '9999'});
          $('.dateRange').daterangepicker();
          $(".datePicker1").inputmask('mm/dd/yyyy');
          $(".datePicker").inputmask('mm/dd/yyyy');
          $('.datePicker').daterangepicker({
            locale: {
                format: 'MM/DD/YYYY',
            },
            singleDatePicker: true,
            showDropdowns: true
          });
          $('.yearpicker').datepicker({
            autoclose: true,
            format: 'yyyy',
            viewMode: 'years',
            minViewMode: 'years'
          });
          $('.date-range').daterangepicker({
            locale: {
              format: 'YYYY-MM-DD',
              showDropdowns: true
            }
          });
          $('.summernote-modal').summernote({
            height: 200
          });
          setTimeout(function() {
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
          }, 3000);
          if(form_data.w_table=='w'){
            loadTable(form_data);
          }else if(form_data.w_table=='div'){
            loadDivwLoader(form_data,thisBtn);
          }else if(form_data.w_table=='divDisabled'){
            loadDivwDisabled(form_data,thisBtn);
          }
          Livewire.rescan();
          if(form_data.livewire){
            if(form_data.livewire=='w'){
              Livewire.emit(form_data.livewire_emit, form_data.livewire_value);
            }
          }
      },
      error: function (){
        toastr.error('Error!');
        thisBtn.removeAttr('disabled');
        thisBtn.removeClass('input-success');
        thisBtn.removeClass('input-error');
      }
  });
}
function loadingTemplate(message) {
    var img = base_url+"/assets/images/loader/loader_gif_no_bg.gif";
    return '<div class="main-item">'+
              '<div class="animated-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="static-background">'+
                '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="animated-background">'+
                '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="static-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="animated-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="static-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="animated-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="static-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
              '<div class="animated-background">'+
                  '<div class="background-masker btn-divide-left"></div>'+
              '</div>'+
            '</div>';
    //return '<span class="loading-wrap"><img src="'+img+'" alt="Loader" class="loaderTable"></span>';
}
function loadTable(form_data){
  $('#'+form_data.tid).bootstrapTable('destroy')
          .bootstrapTable()
          .bootstrapTable('showLoading');
  $.ajax({
    url: form_data.url_table,
    type: 'POST',
    headers: {
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    data:form_data,
    cache: false,
    dataType: 'json',
    beforeSend: function() {

    },
    success : function(data){
      var $table = $('#'+form_data.tid);
      $table.bootstrapTable('destroy');
       $table.bootstrapTable({
        data:data,
        showFooter: true
      });
      $(".datePicker").inputmask('mm/dd/yyyy');
      if(form_data.dropDownParent){
        $(".select2-table").select2({
          dropdownParent: $("#"+form_data.dropDownParent),
          templateSelection: function(option) {
              if (!option.id) {
                  return option.text;
              }
              var color = $(option.element).data("color");
              return $("<span>").text(option.text).css("color", color);
          },
          templateResult: function(option) {
              if (!option.id) {
                  return option.text;
              }
              var color = $(option.element).data("color");
              return $("<span>").text(option.text).css("color", color);
          }
        });
        var color = $('.select2-table').find('option:selected').data('color');
        $('.select2-container--default').addClass('highlighted-option');
      }
      // $(".select2-table").select2({
      //   dropdownParent: $("#"+form_data.tid)
      // });
      // $('.datePicker').daterangepicker({
      //   locale: {
      //     format: 'MM/DD/YYYY',
      //   },
      //   singleDatePicker: true,
      //   showDropdowns: true
      // });
    },
    error: function (){
      toastr.error('Error!');
    }
  });
}
function loadTablewLoader(form_data,thisBtn){
    $('#'+form_data.tid).bootstrapTable('destroy')
            .bootstrapTable()
            .bootstrapTable('showLoading');
    $.ajax({
      url: form_data.url_table,
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
        $('#'+form_data.tid).bootstrapTable('destroy')
          .bootstrapTable({
          data:data,
          showFooter: true
        });
        $('.dateRange').daterangepicker();
        $(".datePicker").inputmask('mm/dd/yyyy');
        if(form_data.dropDownParent){
          $(".select2-table").select2({
            dropdownParent: $("#"+form_data.dropDownParent),
            templateSelection: function(option) {
                if (!option.id) {
                    return option.text;
                }
                var color = $(option.element).data("color");
                return $("<span>").text(option.text).css("color", color);
            },
            templateResult: function(option) {
                if (!option.id) {
                    return option.text;
                }
                var color = $(option.element).data("color");
                return $("<span>").text(option.text).css("color", color);
            }
          });
          var color = $('.select2-table').find('option:selected').data('color');
          $('.select2-container--default').addClass('highlighted-option');
        }
        thisBtn.removeAttr('disabled');
        thisBtn.removeClass('input-loading');
        thisBtn.addClass('input-success');
        setTimeout(function() {
          thisBtn.removeClass('input-success');
          thisBtn.removeClass('input-error');
        }, 3000);
      },
      error: function (){
        thisBtn.removeAttr('disabled');
        thisBtn.removeClass('input-success');
        thisBtn.removeClass('input-error');
        toastr.error('Error!');
      }
    });
}
function loadDivwLoader(form_data,thisBtn){
  $('#'+form_data.tid).html('<center>'+loadingTemplate('')+'</center>');
  $.ajax({
    url: form_data.url_table,
    type: 'POST',
    headers: {
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    data:form_data,
    cache: false,
    beforeSend: function() {
      thisBtn.attr('disabled','disabled');
      //thisBtn.addClass('input-loading');
    },
    success : function(data){
      $('#'+form_data.tid).html('');
      $('#'+form_data.tid).html(data);
      $(".contact").inputmask('999-999-9999');
      $(".bank_account_no").inputmask('9999-9999-99');
      $(".tin_no").inputmask('999-999-999');
      $(".gsis_bp_no").inputmask('9999999999');
      $(".philhealth_no").inputmask('99-999999999-9');
      $(".sss_no").inputmask('99-9999999-9');
      $(".pagibig_no").inputmask('9999-9999-9999');
      $('.dateRange').daterangepicker();
      $(".datePicker").inputmask('mm/dd/yyyy');
      $('.datePicker').daterangepicker({
        locale: {
          format: 'MM/DD/YYYY',
        },
        singleDatePicker: true,
        showDropdowns: true,
      });
      $(".select2-div").select2({
        dropdownParent: $("#"+form_data.tid)
      });
      thisBtn.removeAttr('disabled');
      //thisBtn.removeClass('input-loading');
      //thisBtn.addClass('input-success');
    //   setTimeout(function() {
    //     thisBtn.removeClass('input-success');
    //     thisBtn.removeClass('input-error');
    //   }, 3000);
    },
    error: function (){
      thisBtn.removeAttr('disabled');
    //   thisBtn.removeClass('input-success');
    //   thisBtn.removeClass('input-error');
      toastr.error('Error!');
    }
  });
}
function loadDivwDisabled(form_data,thisBtn){
  $.ajax({
    url: form_data.url_table,
    type: 'POST',
    headers: {
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    data:form_data,
    cache: false,
    beforeSend: function() {
      $('#'+form_data.tid).addClass('disabled');
      thisBtn.attr('disabled','disabled');
    //   thisBtn.addClass('input-loading');
    },
    success : function(data){
      $('#'+form_data.tid).removeClass('disabled');
      $('#'+form_data.tid).html(data);
      $(".contact").inputmask('999-999-9999');
      $(".bank_account_no").inputmask('9999-9999-99');
      $(".tin_no").inputmask('999-999-999');
      $(".gsis_bp_no").inputmask('9999999999');
      $(".philhealth_no").inputmask('99-999999999-9');
      $(".sss_no").inputmask('99-9999999-9');
      $(".pagibig_no").inputmask('9999-9999-9999');
      $(".datePicker").inputmask('mm/dd/yyyy');
      $('.datePicker').daterangepicker({
        locale: {
          format: 'MM/DD/YYYY',
        },
        singleDatePicker: true,
        showDropdowns: true,
      });
      $(".select2-div").select2({
        dropdownParent: $("#"+form_data.tid)
      });
      thisBtn.removeAttr('disabled');
    //   thisBtn.removeClass('input-loading');
    //   thisBtn.addClass('input-success');
    //   setTimeout(function() {
    //     thisBtn.removeClass('input-success');
    //     thisBtn.removeClass('input-error');
    //   }, 3000);
    },
    error: function (){
      thisBtn.removeAttr('disabled');
      thisBtn.removeClass('input-success');
      thisBtn.removeClass('input-error');
      toastr.error('Error!');
    }
  });
}
function loadModalGet(form_data,thisBtn){
  $.ajax({
      url: form_data.url,
      type: 'GET',
      headers: {
          'X-CSRF-TOKEN': CSRF_TOKEN
      },
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
          if(form_data.static=='true'){
            $('#modal-'+form_data.modal).modal({backdrop:false,keyboard:false,show:true});
          }else{
            $('#modal-'+form_data.modal).modal('show');
          }
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
          $(".contact").inputmask('999-999-9999');
          $(".bank_account_no").inputmask('9999-9999-99');
          $(".tin_no").inputmask('999-999-999');
          $(".gsis_bp_no").inputmask('9999999999');
          $(".philhealth_no").inputmask('99-999999999-9');
          $(".sss_no").inputmask('99-9999999-9');
          $(".pagibig_no").inputmask('9999-9999-9999');
          $(".year").inputmask('9999');
          $('.yearpicker').inputmask({'mask': '9999'});
          $('.dateRange').daterangepicker();
          $(".datePicker1").inputmask('mm/dd/yyyy');
          $(".datePicker").inputmask('mm/dd/yyyy');
          $('.datePicker').daterangepicker({
            locale: {
                format: 'MM/DD/YYYY',
            },
            singleDatePicker: true,
            showDropdowns: true
          });
          $('.yearpicker').datepicker({
            autoclose: true,
            format: 'yyyy',
            viewMode: 'years',
            minViewMode: 'years'
          });
          $('.date-range').daterangepicker({
            locale: {
              format: 'YYYY-MM-DD',
              showDropdowns: true
            }
          });
          $('.summernote-modal').summernote({
            height: 200
          });
          setTimeout(function() {
            thisBtn.removeClass('input-success');
            thisBtn.removeClass('input-error');
          }, 3000);
          if(form_data.w_table=='w'){
            loadTableGet(form_data);
          }else if(form_data.w_table=='div'){
            loadDivwLoaderGet(form_data,thisBtn);
          }
          Livewire.rescan();
          if(form_data.livewire){
            if(form_data.livewire=='w'){
              Livewire.emit(form_data.livewire_emit, form_data.livewire_value);
            }
          }
      },
      error: function (){
        toastr.error('Error!');
        thisBtn.removeAttr('disabled');
        thisBtn.removeClass('input-success');
        thisBtn.removeClass('input-error');
      }
  });
}
function loadTableGet(form_data){
  $('#'+form_data.tid).bootstrapTable('destroy')
          .bootstrapTable()
          .bootstrapTable('showLoading');
  $.ajax({
    url: form_data.url_table,
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    data:form_data,
    cache: false,
    dataType: 'json',
    beforeSend: function() {

    },
    success : function(data){
      var $table = $('#'+form_data.tid);
      $table.bootstrapTable('destroy');
       $table.bootstrapTable({
        data:data,
        showFooter: true
      });
      $(".datePicker").inputmask('mm/dd/yyyy');
      if(form_data.dropDownParent){
        $(".select2-table").select2({
          dropdownParent: $("#"+form_data.dropDownParent),
          templateSelection: function(option) {
              if (!option.id) {
                  return option.text;
              }
              var color = $(option.element).data("color");
              return $("<span>").text(option.text).css("color", color);
          },
          templateResult: function(option) {
              if (!option.id) {
                  return option.text;
              }
              var color = $(option.element).data("color");
              return $("<span>").text(option.text).css("color", color);
          }
        });
        var color = $('.select2-table').find('option:selected').data('color');
        $('.select2-container--default').addClass('highlighted-option');
      }
      // $(".select2-table").select2({
      //   dropdownParent: $("#"+form_data.tid)
      // });
      // $('.datePicker').daterangepicker({
      //   locale: {
      //     format: 'MM/DD/YYYY',
      //   },
      //   singleDatePicker: true,
      //   showDropdowns: true
      // });
    },
    error: function (){
      toastr.error('Error!');
    }
  });
}
function loadDivwLoaderGet(form_data,thisBtn){
  $('#'+form_data.tid).html('<center>'+loadingTemplate('')+'</center>');
  $.ajax({
    url: form_data.url_table,
    type: 'GET',
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
      $('#'+form_data.tid).html('');
      $('#'+form_data.tid).html(data);
      $(".contact").inputmask('999-999-9999');
      $(".bank_account_no").inputmask('9999-9999-99');
      $(".tin_no").inputmask('999-999-999');
      $(".gsis_bp_no").inputmask('9999999999');
      $(".philhealth_no").inputmask('99-999999999-9');
      $(".sss_no").inputmask('99-9999999-9');
      $(".pagibig_no").inputmask('9999-9999-9999');
      $('.dateRange').daterangepicker();
      $(".datePicker").inputmask('mm/dd/yyyy');
      $('.datePicker').daterangepicker({
        locale: {
          format: 'MM/DD/YYYY',
        },
        singleDatePicker: true,
        showDropdowns: true,
      });
      $(".select2-div").select2({
        dropdownParent: $("#"+form_data.tid)
      });
      thisBtn.removeAttr('disabled');
      thisBtn.removeClass('input-loading');
      thisBtn.addClass('input-success');
      setTimeout(function() {
        thisBtn.removeClass('input-success');
        thisBtn.removeClass('input-error');
      }, 3000);
    },
    error: function (){
      thisBtn.removeAttr('disabled');
      thisBtn.removeClass('input-success');
      thisBtn.removeClass('input-error');
      toastr.error('Error!');
    }
  });
}
function ipaddressValidate(ipaddress){
  var validate = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;
  if (validate.test(ipaddress)) {
      return true;
  }else{
      return false;
  }
}
function isYear(value) {
    var regex = /^\d{4}$/;
    return regex.test(value);
}
