$(document).ready(function() {
    $(".studentSearch").select2({
        dropdownParent: $("#studentSearch"),
        ajax: { 
        url: base_url+'/search/studentSearch',
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