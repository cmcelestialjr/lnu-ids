view_departments();
$(document).on('input', '#newModal', function (e) {
    var name = $('#newModal input[name="name"]').val();
    var shorten = $('#newModal input[name="shorten"]').val();
    var code = $('#newModal input[name="code"]').val();
    $('#newModal input[name="name"]').removeClass('border-require');
    $('#newModal input[name="shorten"]').removeClass('border-require');
    $('#newModal input[name="code"]').removeClass('border-require');
    if(name==''){
        $('#newModal input[name="name"]').addClass('border-require');
    }
    if(shorten==''){
        $('#newModal input[name="shorten"]').addClass('border-require');
    }
    if(code==''){
        $('#newModal input[name="code"]').addClass('border-require');
    }
});
$(document).on('input', '#editModal', function (e) {
    var name = $('#newModal input[name="name"]').val();
    var shorten = $('#newModal input[name="shorten"]').val();
    var code = $('#newModal input[name="code"]').val();
    $('#newModal input[name="name"]').removeClass('border-require');
    $('#newModal input[name="shorten"]').removeClass('border-require');
    $('#newModal input[name="code"]').removeClass('border-require');
    if(name==''){
        $('#newModal input[name="name"]').addClass('border-require');
    }
    if(shorten==''){
        $('#newModal input[name="shorten"]').addClass('border-require');
    }
    if(code==''){
        $('#newModal input[name="code"]').addClass('border-require');
    }
});