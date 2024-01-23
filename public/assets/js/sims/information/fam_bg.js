$(document).off('click', '#newFam').on('click', '#newFam', function (e) {
    var thisBtn = $(this);
    famNew(thisBtn);
});
$(document).off('click', '.editFam').on('click', '.editFam', function (e) {
    var thisBtn = $(this);
    famEdit(thisBtn);
});
$(document).off('click', '.deleteFam').on('click', '.deleteFam', function (e) {
    var thisBtn = $(this);
    famDelete(thisBtn);
});
$(document).off('click', '#famNewSubmit').on('click', '#famNewSubmit', function (e) {
    var thisBtn = $(this);
    famNewSubmit(thisBtn);
});
$(document).off('click', '#famEditSubmit').on('click', '#famEditSubmit', function (e) {
    var thisBtn = $(this);
    famEditSubmit(thisBtn);
});
$(document).off('click', '#famDeleteSubmit').on('click', '#famDeleteSubmit', function (e) {
    var thisBtn = $(this);
    famDeleteSubmit(thisBtn);
});
function famNew(thisBtn){
    var url = base_url+'/sims/information/famNew';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:''
    };
    loadModal(form_data,thisBtn);
}
function famEdit(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/sims/information/famEdit';
    var modal = 'info';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function famDelete(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/sims/information/famDelete';
    var modal = 'info';
    var modal_size = 'modal-sm';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function famNewSubmit(thisBtn){
    var relation = $('#famRelation option:selected').val();
    var lastname = $('#famLastname').val();
    var firstname = $('#famFirstname').val();
    var middlename = $('#famMiddlename').val();
    var extname = $('#famExtname').val();
    var dob = $('#famDOB').val();
    var contact = $('#famContact').val();
    var email = $('#famEmail').val();
    var occupation = $('#famOccupation').val();
    var employer = $('#famEmployer').val();
    var employer_address = $('#famEmployerAddress').val();
    var employer_contact = $('#famEmployerContact').val();
    var x = 0;

    $('#famLastname').removeClass('border-require');
    $('#famFirstname').removeClass('border-require');

    if(lastname==''){
        toastr.error('Input Lastname');
        $('#famLastname').addClass('border-require');
        x++;
    }

    if(firstname==''){
        toastr.error('Input Firstname');
        $('#famFirstname').addClass('border-require');
        x++;
    }

    if(x==0){
        var form_data = {
            relation:relation,
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            dob:dob,
            contact:contact,
            email:email,
            occupation:occupation,
            employer:employer,
            employer_address:employer_address,
            employer_contact:employer_contact
        };

        $.ajax({
            url: base_url+'/sims/information/famNewSubmit',
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
                thisBtn.removeAttr('disabled');
                    thisBtn.removeClass('input-loading'); 
                    if(data.result=='success'){
                        toastr.success('Success!');
                        $('#modal-info').modal('hide');
                        var id = 'familyBgEdit';
                        informationEditDiv(id);
                        informationFamBg();
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
}
function famEditSubmit(thisBtn){
    var id = $('#famID').val();
    var relation = $('#famRelation option:selected').val();
    var lastname = $('#famLastname').val();
    var firstname = $('#famFirstname').val();
    var middlename = $('#famMiddlename').val();
    var extname = $('#famExtname').val();
    var dob = $('#famDOB').val();
    var contact = $('#famContact').val();
    var email = $('#famEmail').val();
    var occupation = $('#famOccupation').val();
    var employer = $('#famEmployer').val();
    var employer_address = $('#famEmployerAddress').val();
    var employer_contact = $('#famEmployerContact').val();
    var x = 0;

    $('#famLastname').removeClass('border-require');
    $('#famFirstname').removeClass('border-require');

    if(lastname==''){
        toastr.error('Input Lastname');
        $('#famLastname').addClass('border-require');
        x++;
    }

    if(firstname==''){
        toastr.error('Input Firstname');
        $('#famFirstname').addClass('border-require');
        x++;
    }

    if(x==0){
        var form_data = {
            id:id,
            relation:relation,
            lastname:lastname,
            firstname:firstname,
            middlename:middlename,
            extname:extname,
            dob:dob,
            contact:contact,
            email:email,
            occupation:occupation,
            employer:employer,
            employer_address:employer_address,
            employer_contact:employer_contact
        };

        $.ajax({
            url: base_url+'/sims/information/famEditSubmit',
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
                thisBtn.removeAttr('disabled');
                    thisBtn.removeClass('input-loading'); 
                    if(data.result=='success'){
                        toastr.success('Success!');
                        $('#modal-info').modal('hide');
                        var id = 'familyBgEdit';
                        informationEditDiv(id);
                        informationFamBg();
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
}
function famDeleteSubmit(thisBtn){
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };

    $.ajax({
        url: base_url+'/sims/information/famDeleteSubmit',
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
            thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success('Success!');
                    $('#modal-info').modal('hide');
                    var id = 'familyBgEdit';
                    informationEditDiv(id);
                    informationFamBg();
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
function informationFamBg(){
    var thisBtn = $('#informationFamBg');
    $.ajax({
        url: base_url+'/sims/information/informationFamBg',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        cache: false,
        beforeSend: function() {
            thisBtn.addClass('disabled'); 
        },
        success : function(data){
            thisBtn.removeClass('disabled'); 
            thisBtn.html(data);            
        },
        error: function (){
            thisBtn.removeClass('disabled');
        }
    });
}