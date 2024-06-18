const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", function () {

  // toggle the type attribute
  const type = password.getAttribute("type") === "password" ? "text" : "password";
  password.setAttribute("type", type);
  // toggle the eye icon
  this.classList.toggle('fa-eye');
  this.classList.toggle('fa-eye-slash');
});
var div_id = 'login-form';
$(document).on('keypress', '#'+div_id,function(e) {
    if(e.which == 13) {
        var thisBtn = $('#'+div_id+' button[name="login"]');
        loginSubmit(thisBtn);
    }
});
$(document).on('click', '#'+div_id+' button[name="login"]', function (e) {
    var thisBtn = $(this);
    loginSubmit(thisBtn);
});
$(document).on('input', '#'+div_id, function (e) {
    checkUser();
});
$(document).on('click', '#forgot-password', function (e) {
    $('#'+div_id).addClass('hide');
    $('#forgot-password-form').removeClass('hide');
});
$(document).on('click', '#back-login', function (e) {
    $('#'+div_id).removeClass('hide');
    $('#forgot-password-form').addClass('hide');
});
$(document).on('click', '#forgot-password-submit', function (e) {
    forgotPasswordSubmit($(this));
});
function loginSubmit(thisBtn){
    //var role = $('#'+div_id+' select[name="role"] option:selected').val();
    var username = $('#'+div_id+' input[name="username"]').val();
    var password = $('#'+div_id+' input[name="password"]').val();
    var x = checkUser();
    if(x==0){
        var form_data = {
            //role:role,
            username:username,
            password:password
        };

        // $.ajax({
        //     url: base_url+'/users/accessView',
        //     type: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': CSRF_TOKEN
        //     },
        //     data:form_data,
        //     cache: false,
        //     beforeSend: function() {
        //     },
        //     success : function(data){
        //         console.log(data);
        //     },
        //     error: function (){

        //     }
        // });
        $.ajax({
            url: base_url+'/login',
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
                $('#'+div_id+' #username-field').removeClass('input-field-border-require');
                $('#'+div_id+' #password-field').removeClass('input-field-border-require');
                $('#'+div_id+' #username-field label').removeClass('input-field-text-require');
                $('#'+div_id+' #password-field label').removeClass('input-field-text-require');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                if(data.result=='success'){
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                    //setTimeout(function() {
                        window.location.replace(base_url+'/'+data.page);
                    //}, 500);
                }else if(data.result=='none' || data.result=='wrong'){
                    toastr.error('Wrong Username or Password!');
                    thisBtn.addClass('input-error');
                    $('#'+div_id+' #username-field').addClass('input-field-border-require');
                    $('#'+div_id+' #password-field').addClass('input-field-border-require');
                    $('#'+div_id+' #username-field label').addClass('input-field-text-require');
                    $('#'+div_id+' #password-field label').addClass('input-field-text-require');
                }else if(data.result=='On-hold' || data.result=='Inactive'){
                    toastr.warning('Your account is '+data.result+'.');
                    thisBtn.addClass('input-error');
                }else{
                    toastr.error(data.result);
                    thisBtn.addClass('input-error');
                }
                setTimeout(function() {
                    thisBtn.removeClass('input-success');
                    thisBtn.removeClass('input-error');
                }, 3000);
            },
            error: function (xhr){
                if (xhr.status === 429) {
                    toastr.error('Too many requests! Please try again after a minute.');
                } else {
                    toastr.error('An error occurred: ' + xhr.statusText);
                }
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                thisBtn.addClass('input-error');
                setTimeout(function () {
                    thisBtn.removeClass('input-error');
                }, 3000);
            }
        });
    }
}
function forgotPasswordSubmit(thisBtn){
    var id_no = $('#forgot-id_no').val();
    if(id_no==''){
        $('#forgot-id_no-field').addClass('input-field-border-require');
        $('#forgot-id_no-field label').addClass('input-field-text-require');
    }else{
        var form_data = {
            id_no:id_no
        };
        $.ajax({
            url: base_url+'/forgotPassword',
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
                $('#forgot-id_no-field').removeClass('input-field-border-require');
                $('#forgot-id_no-field label').removeClass('input-field-text-require');
                $('#forgot-message').removeClass('text-success');
                $('#forgot-message').removeClass('text-require');
                $('#forgot-message').html('');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                if(data.result=='success'){
                    toastr.success('Success.');
                    thisBtn.addClass('input-success');
                    $('#forgot-message').addClass('text-success');
                    $('#forgot-message').html(data.message);
                }else{
                    toastr.error(data.result);
                    $('#forgot-message').addClass('text-require');
                    $('#forgot-message').html(data.message);
                    thisBtn.addClass('input-error');
                    $('#forgot-id_no-field').addClass('input-field-border-require');
                    $('#forgot-id_no-field label').addClass('input-field-text-require');
                }
                setTimeout(function() {
                    thisBtn.removeClass('input-success');
                    thisBtn.removeClass('input-error');
                }, 3000);
            },
            error: function (xhr){
                if (xhr.status === 429) {
                    toastr.error('Too many requests! Please try again after a minute.');
                } else {
                    toastr.error('An error occurred: ' + xhr.statusText);
                }
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading');
                thisBtn.addClass('input-error');
                setTimeout(function () {
                    thisBtn.removeClass('input-error');
                }, 3000);
            }
        });
    }
}
function checkUser(){
    var username = $('#'+div_id+' input[name="username"]').val();
    var password = $('#'+div_id+' input[name="password"]').val();
    var x = 0;
    $('#'+div_id+' #username-field').removeClass('input-field-border-require');
    $('#'+div_id+' #password-field').removeClass('input-field-border-require');
    $('#'+div_id+' #username-field label').removeClass('input-field-text-require');
    $('#'+div_id+' #password-field label').removeClass('input-field-text-require');
    if(username==''){
        $('#'+div_id+' #username-field').addClass('input-field-border-require');
        $('#'+div_id+' #username-field label').addClass('input-field-text-require');
        x++;
    }
    if(password==''){
        $('#'+div_id+' #password-field').addClass('input-field-border-require');
        $('#'+div_id+' #password-field label').addClass('input-field-text-require');
        x++;
    }
    return x;
}
