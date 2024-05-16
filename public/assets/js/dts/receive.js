$(document).ready(function() {
    $(document).off('click', '.docs-option')
    .on('click', '.docs-option', function (e) {
        var id = $(this).data('id');
        var option = $(this).data('o');
        if(option=='Receive'){
            receiveDoc(id,$(this));
        }else if(option=='Forward'){
            forwardDoc(id,$(this));
        }else if(option=='History'){
            historyDoc(id);
        }
    });
    $(document).off('click', '#receive-link')
    .on('click', '#receive-link', function (e) {
        receiveTab();
    });
    $(document).off('click', '#received-link')
    .on('click', '#received-link', function (e) {
        receivedTab();
    });
    if ($('#received-tab').length>0) {
        receiveTab();
    }
});
function historyDoc(id){
    window.location.href = base_url+'/ids/dts/search/n/'+id;
}
function receiveDoc(id,thisBtn){
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/dts/receive',
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
            if(data.result=='success'){
                paginate(1);
                toastr.success('Success!');
            }else{
                toastr.error('Error!');
            }
        },
        error: function (){

        }
    });
}
function receiveTab(){
    $.ajax({
        url: base_url+'/dts/receiveTab',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        cache: false,
        beforeSend: function() {
            $('#received-tab').html('');
        },
        success : function(data){
            $('#receive-tab').html(data);
        },
        error: function (){

        }
    });
}
function receivedTab(){
    $.ajax({
        url: base_url+'/dts/receivedTab',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        cache: false,
        beforeSend: function() {
            $('#receive-tab').html('');
        },
        success : function(data){
            $('#received-tab').html(data);
        },
        error: function (){

        }
    });
}
