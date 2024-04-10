office_div();
$(document).off('change', '#officeDiv select[name="office_type"]').on('change', '#officeDiv select[name="office_type"]', function (e) {
    office_table();
});

function office_div(){
    var thisBtn = $('#officeDiv');
    $.ajax({
        url: base_url+'/hrims/office/office',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        cache: false,
        beforeSend: function() {
            thisBtn.attr('disabled','disabled');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.html(data);
            $(".select2-div").select2({
                dropdownParent: thisBtn
            });
            office_table();
        },
        error: function (){
            toastr.error('Error!');
            thisBtn.removeAttr('disabled');
        }
    });
}
function office_table(){
    var office_type = $('#officeDiv select[name="office_type"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/office/officeTable',
        tid:'officeTable',
        id:office_type
    };
    loadTable(form_data);
}