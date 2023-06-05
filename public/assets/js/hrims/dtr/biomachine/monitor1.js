var base_url = window.location.origin;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

setInterval(function() {
    //display();   
    Livewire.emit('refreshMonitor1');
}, 1000);


// function display(){
//     $.ajax({
//         url: base_url+'/monitor1/display',
//         type: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': CSRF_TOKEN
//         },
//         cache: false,
//         beforeSend: function() {

//         },
//         success : function(data){
//             $('#display').html(data);
//         },
//         error: function (){

//         }
//     });
// }