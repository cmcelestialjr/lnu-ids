$(document).ready(function() {
    $(".search_student").select2({
        dropdownParent: $("#students"),
        ajax: { 
          url: base_url+'/rims/student/searchStudent',
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