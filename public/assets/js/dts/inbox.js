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
});

function historyDoc(id){
    window.location.href = base_url+'/ids/dts/search/n/'+id;
}

