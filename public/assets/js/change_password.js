$(document).ready(function() {
    var url = window.location.pathname.split("/");
    var base_url = window.location.origin;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $('#togglePassword').on('mouseenter', function() {
        var passwordField = $('#password');
        passwordField.attr('type', 'text');
        $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }).on('mouseleave', function() {
        var passwordField = $('#password');
        passwordField.attr('type', 'password');
        $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
    });
    $(document).off('input', '#password').on('input', '#password', function (e) {
        var password = $(this).val();
        var allValid = true;
        var policies = {
            '1': /.{8,}/,
            '2': /[a-z]/,
            '3': /[A-Z]/,
            '4': /\d/,
            '5': /[!@#$%^&*(),.?":{}|<>]/
        };

        $.each(policies, function(id, regex) {
            if (regex.test(password)) {
                $('#policy-' + id).removeClass('text-require').addClass('text-success');
                $('#policy-span-' + id).removeClass('fa fa-times').addClass('fa fa-check');
                $('#policy-span-' + id).html('');
            } else {
                $('#policy-' + id).removeClass('text-success').addClass('text-require');
                $('#policy-span-' + id).removeClass('fa fa-check').addClass('fa fa-times');
                $('#policy-span-' + id).html('');
                allValid = false;
            }
        });

        if (allValid) {
            $('#submit').removeAttr('disabled');
        } else {
            $('#submit').attr('disabled', 'disabled');
        }
    });
    $(document).off('click', '#submit').on('click', '#submit', function (e) {
        var thisBtn = $(this);
        var password = $('#password').val();
        var policies = {
            '1': /.{8,}/,
            '2': /[a-z]/,
            '3': /[A-Z]/,
            '4': /\d/,
            '5': /[!@#$%^&*(),.?":{}|<>]/
        };
        var allValid = true;
        $.each(policies, function(id, regex) {
            if (regex.test(password)) {
            } else {
                allValid = false;
            }
        });

        if(allValid==false){
            return;
        }

        var form_data = {
            password:password
        };
        $.ajax({
            url: base_url+'/update_password',
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
                if(data.result=='success'){
                    toastr.success('Success. You will be redirected to systems page!');
                    setTimeout(function() {
                        window.location.replace(base_url+'/systems');
                    }, 3000);
                }else{
                    thisBtn.removeAttr('disabled');
                    thisBtn.removeClass('input-loading');
                    toastr.error('Error.');
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
    });
});
