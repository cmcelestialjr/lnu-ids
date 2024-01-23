$(document).ready(function() {
    $(".courseSelect").select2({
        dropdownParent: $("#courseSelect"),
        ajax: { 
        url: base_url+'/search/courseSelectID',
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                _token: CSRF_TOKEN,
                search: params.term
            };
        },
        processResults: function (response) {
            return {
            results: response
            };
        },
        cache: true
        }
    });
});