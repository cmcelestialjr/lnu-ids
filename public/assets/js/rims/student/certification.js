$(document).off('click', '#studentViewModal #certification').on('click', '#studentViewModal #certification', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var url = base_url+'/rims/student/studentCertificationModal';
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
});
$(document).off('change', '#certificationModal select[name="program_level"]').on('change', '#certificationModal select[name="program_level"]', function (e) {
    var thisBtn = $(this);
    var id = $('#studentViewModal input[name="id"]').val();
    var selectOption = $('#certificationModal .select2-primary');
    var program_level = thisBtn.val();
    var form_data = {
        id:id,
        program_level:program_level
    };
    $.ajax({
        url: base_url+'/rims/student/certificationSYperiod',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            selectOption.attr('disabled','disabled');
            $('#certificationModal #program_level').removeClass('border-require');
        },
        success : function(data){
            selectOption.removeAttr('disabled');
            if(data.result=='success'){
                $('#certificationModal select[name="school_year"]').empty();
                $('#certificationModal select[name="period"]').empty();
                $('#certificationModal select[name="school_year"]').append('<option value="">Please select School Year</option>');
                $('#certificationModal select[name="period"]').append('<option value="">Please select Period</option>');
                $.each(data.school_years, function(index, item) {
                    $('#certificationModal select[name="school_year"]').append('<option value="' + item + '">' + item + '</option>');
                });
                $.each(data.period, function(index, item) {
                    $('#certificationModal select[name="period"]').append('<option value="' + item.id + '">' + item.name_no + '</option>');
                });
            }else{
                toastr.error(data.result);
            }

        },
        error: function (){
            toastr.error('Error!');
            selectOption.removeAttr('disabled');
        }
    });
});
$(document).off('click', '#certificationModal #certificationSubmit').on('click', '#certificationModal #certificationSubmit', function (e) {
    var thisBtn = $(this);
    var id = thisBtn.data('id');
    var certification = $('#certificationModal select[name="certification"] option:selected').val();

    var x = 0;

    $('#certificationModal #certification').removeClass('border-require');

    if (!$.isNumeric(id)) {
        toastr.error('Error');
        x++;
    }
    if (certification=='') {
        $('#certificationModal #certification').addClass('border-require');
        toastr.error('Please select Certification!');
        x++;
    }

    var form_data = getformdata(id,certification);
    var x = x+form_data.x;

    if(x==0 && form_data!=0){

        $.ajax({
            url: base_url+'/rims/student/certificationSubmit',
            type: 'POST',
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
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                if(data.result=='success'){
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    var url = base_url+'/rims/student/certificationDisplay';
                    var modal = 'info';
                    var modal_size = 'modal-lg';
                    var form_data = {
                        url:url,
                        modal:modal,
                        modal_size:modal_size,
                        static:'',
                        w_table:'wo',
                        src:data.url
                    };
                    loadModal(form_data,thisBtn);
                    //window.open(base_url+'/'+data.url, '_blank');
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
});
function getformdata(id,certification){
    var form_data = 0;
    var x = 0;
    if(certification=='scholasticReport'){
        var program_level = $('#certificationModal #scholasticReport select[name="program_level"] option:selected').val();
        var school_year = $('#certificationModal #scholasticReport select[name="school_year"] option:selected').val();
        var period = $('#certificationModal #scholasticReport select[name="period"] option:selected').val();
        var program = $('#certificationModal #scholasticReport input[name="program"]').val();
        var year = $('#certificationModal #scholasticReport input[name="year"]').val();

        $('#certificationModal #scholasticReport #program_level').removeClass('border-require');
        $('#certificationModal #scholasticReport #school_year').removeClass('border-require');
        $('#certificationModal #scholasticReport #period').removeClass('border-require');

        if (!$.isNumeric(program_level)) {
            $('#certificationModal #scholasticReport #program_level').addClass('border-require');
            toastr.error('Please select Program Level!');
            x++;
        }
        if (school_year=='') {
            $('#certificationModal #scholasticReport #school_year').addClass('border-require');
            toastr.error('Please select School Year!');
            x++;
        }
        if (!$.isNumeric(period)) {
            $('#certificationModal #scholasticReport #period').addClass('border-require');
            toastr.error('Please select Period!');
            x++;
        }
        var form_data = {
            id:id,
            certification:certification,
            program_level:program_level,
            school_year:school_year,
            period:period,
            program:program,
            year:year,
            x:x
        };
    }
    return form_data;
}
