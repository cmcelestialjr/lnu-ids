$(document).ready(function() {
    paginate(1);
    $(document).on('input', '#search-pagination', function() {
        if ($(this).val().trim() !== '') {
            $('.clear-search-pagination').show();
        } else {
            $('.clear-search-pagination').hide();
        }
        paginate(1);
    });

    $(document).on('click', '.clear-search-pagination', function() {
        $('#search-pagination').val('');
        $(this).hide();
        paginate(1);
    });

    $(document).on('click', '.btn-paginate', function() {
        paginate($(this).val());
    });
});

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
            updatePaginationButtons(data);

            updateDisplayedContent(data);

        },
        error: function (){

        }
    });
}
function updatePaginationButtons(data) {
    $('#pagination').html('');

    $.each(data.links, function(index, item) {
        if(item.page_number==data.current_page){
            var button = '<button class="btn-paginate active" value="' + item.page_number + '">' + item.page_number + '</button>';
        }else{
            var button = '<button class="btn-paginate btn-primary" value="' + item.page_number + '">' + item.page_number + '</button>';
        }

        $('#pagination').append(button);
    });

    var currentPage = parseInt(data.current_page);
    var totalPages = parseInt(data.total_pages);

    var prevPage = currentPage - 5;
    var nextPage = currentPage + 5;

    var showing_from = parseInt(data.current_page)*10-10+1;
    var showing_to = parseInt(data.current_page)*10;
    if(showing_to>=data.total_query){
        var showing_to = data.total_query;
    }
    $('#pagination-info').html('');
    $('#pagination-info').html('Showing '+showing_from+' to '+showing_to+' of '+data.total_query+' rows');

    if (prevPage > 0) {
        var prevButton = '<button class="btn-paginate btn-info" value="' + prevPage + '"><span class="fa fa-angle-left"></span></button>';
        var firstButton = '<button class="btn-paginate btn-info" value="1"><span class="fa fa-angle-double-left"></span></button>';
        $('#pagination').prepend(prevButton);
        $('#pagination').prepend(firstButton);
    }

    if (nextPage <= totalPages) {
        var nextButton = '<button class="btn-paginate btn-info" value="' + nextPage + '"><span class="fa fa-angle-right"></span></button>';
        var lastButton = '<button class="btn-paginate btn-info" value="' + data.total_pages + '"><span class="fa fa-angle-double-right"></span></button>';
        $('#pagination').append(nextButton);
        $('#pagination').append(lastButton);
    }
}



