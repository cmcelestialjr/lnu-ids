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
        submitLogin(thisBtn);
    }
});
$(document).on('click', '#'+div_id+' button[name="login"]', function (e) {
    var thisBtn = $(this);
    submitLogin(thisBtn);
});
$(document).on('input', '#'+div_id, function (e) {
    checkUser();
});
function submitLogin(thisBtn){
    var role = $('#'+div_id+' select[name="role"] option:selected').val();
    var username = $('#'+div_id+' input[name="username"]').val();
    var password = $('#'+div_id+' input[name="password"]').val();
    var x = checkUser();
    if(x==0){
        var form_data = {
            role:role,
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
                $('#'+div_id+' input[name="username"]').removeClass('border-require');
                $('#'+div_id+' input[name="password"]').removeClass('border-require');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success('Success! You are now login.');
                    thisBtn.addClass('input-success');
                    //setTimeout(function() {
                        window.location.replace(base_url+'/system');
                    //}, 500);
                }else if(data.result=='none' || data.result=='wrong'){
                    toastr.error('Wrong Username or Password!');
                    thisBtn.addClass('input-error');
                    $('#'+div_id+' input[name="username"]').addClass('border-require');
                    $('#'+div_id+' input[name="password"]').addClass('border-require');
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
function checkUser(){
    var username = $('#'+div_id+' input[name="username"]').val();
    var password = $('#'+div_id+' input[name="password"]').val();
    var x = 0;
    $('#'+div_id+' input[name="username"]').removeClass('border-require');
    $('#'+div_id+' input[name="password"]').removeClass('border-require');
    if(username==''){
        $('#'+div_id+' input[name="username"]').addClass('border-require');
        x++;
    }
    if(password==''){
        $('#'+div_id+' input[name="password"]').addClass('border-require');
        x++;
    }
    return x;
}