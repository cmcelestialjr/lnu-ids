$(document).ready(function() {
    $('#search-pagination').on('input', function() {
        if ($(this).val().trim() !== '') {
            $('.clear-search').show();
        } else {
            $('.clear-search').hide();
        }
        search();
    });

    $('.clear-search').on('click', function() {
        $('#search-pagination').val('');
        $(this).hide();
        search();
    });
});
function search() {
    var value = $('#search-pagination').val();
    var form_data = {
        value:value
    };
    $.ajax({
        url: base_url+'/pagination/search',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {

        },
        success : function(data){
            $('#table-body-pagination').html('');
            var x = 1;
            $.each(data, function(index, item) {
                var row = '<tr>';
                row += '<td class="center">' + x + '</td>';
                row += '<td>' + item.dts_id + '</td>';
                row += '<td>' + item.name + '</td>';
                row += '<td>' + item.particulars + '</td>';
                row += '<td class="center">' + item.office.shorten + '</td>';
                row += '<td class="center">' + item.status.name + '</td>';
                row += '</tr>';
                $('#table-body-pagination').append(row);
                x++;
            });

        },
        error: function (){
        }
    });
}

function paginate(page) {
    var value = $('#search-pagination').val();
    var form_data = {
        value:value,
        page:page
    };
    $.ajax({
        url: base_url+'/pagination/paginate',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {

        },
        success : function(data){
            $('#pagination').html('');
            $.each(data.links, function(index, item) {
                var button = '<button onclick="paginate(' + item.page_number + ')">' + item.page_number + '</button>';
                $('#pagination').append(button);
            });

            var currentPage = data.current_page;
            var totalPages = data.total_pages;

            var prevPage = currentPage - 5;
            var nextPage = currentPage + 5;

            if (prevPage > 0) {
                var prevButton = '<button onclick="paginate(' + prevPage + ')">&lt;</button>';
                $('#pagination').prepend(prevButton);
            }

            if (nextPage <= totalPages) {
                var nextButton = '<button onclick="paginate(' + nextPage + ')">&gt;</button>';
                $('#pagination').prepend(nextButton);
                document.getElementById('pagination').innerHTML += nextButton;
            }

        },
        error: function (){
        }
    });
}
search();
paginate(1);
