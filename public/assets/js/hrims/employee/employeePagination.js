$(document).ready(function() {
    paginate(1,0,'asc');
    $(document).off('input', '#search-pagination')
    .on('input', '#search-pagination', function (e) {
        if ($(this).val().trim() !== '') {
            $('.clear-search-pagination').show();
        } else {
            $('.clear-search-pagination').hide();
        }
        paginate(1,0,'asc');
    });

    $(document).off('click', '.clear-search-pagination')
    .on('click', '.clear-search-pagination', function (e) {
        $('#search-pagination').val('');
        $(this).hide();
        paginate(1,0,'asc');
    });

    $(document).off('click', '.btn-paginate')
    .on('click', '.btn-paginate', function (e) {
        var column = $('.th-paginate.active').data('column');
        var direction = $('.th-paginate.active').data('sort');
        if(!column){
            var column = 0;
        }
        if(direction=='asc'){
            var direction = 'desc';
        }else{
            var direction = 'asc'
        }
        paginate($(this).val(),column,direction);
    });

    $(document).off('click', '.th-paginate')
    .on('click', '.th-paginate', function (e) {
        var column = $(this).data('column');
        var direction = $(this).data('sort');

        $('.th-paginate').removeClass('active');
        $('.sort-paginate').removeClass('active');
        $(this).addClass('active');

        if(direction=='asc'){
            $(this).find('.sort-paginate.fa.fa-long-arrow-up').addClass('active');
            $(this).data('sort', 'desc');
        }else{
            $(this).find('.sort-paginate.fa.fa-long-arrow-down').addClass('active');
            $(this).data('sort', 'asc');
        }
        paginate(1,column,direction);
    });

    $(document).off('click', '.btn-status')
    .on('click', '.btn-status', function (e) {
        var column = $('.th-paginate.active').data('column');
        var direction = $('.th-paginate.active').data('sort');
        $('#selectionDiv .btn-status').removeClass('active-btn');
        $(this).addClass('active-btn');
        if(!column){
            var column = 0;
        }
        if(direction=='asc'){
            var direction = 'desc';
        }else{
            var direction = 'asc'
        }
        paginate(1,column,direction);
    });

    $(document).off('change', '#selectionDiv .select2')
    .on('change', '#selectionDiv .select2', function (e) {
        var column = $('.th-paginate.active').data('column');
        var direction = $('.th-paginate.active').data('sort');
        if(!column){
            var column = 0;
        }
        if(direction=='asc'){
            var direction = 'desc';
        }else{
            var direction = 'asc'
        }
        paginate(1,column,direction);
    });
});

function paginate(page,column,direction) {
    var value = $('#search-pagination').val();
    var status = $('#selectionDiv .btn-status.active-btn').data('id');
    var option = $('#selectionDiv .select2 option:selected').val();
    var form_data = {
        value:value,
        page:page,
        option:option,
        status:status,
        column:column,
        direction:direction
    };
    $.ajax({
        url: base_url+'/hrims/employee/paginate',
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
            updatePaginationButtons(data);
            updateDisplayedContent(data);
            getCounts();

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

    var showing_from = parseInt(data.current_page)*data.perPage-data.perPage+1;
    var showing_to = parseInt(data.current_page)*data.perPage;
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
function updateDisplayedContent(data) {
    $('#table-body-pagination').html('');
    var x = 1;
    if(data.current_page>1){
        var x = parseInt(data.current_page)*data.perPage-data.perPage+1;
    }
    if(data.list.length<=0){
        var row = '<tr>';
            row += '<td colspan="11" class="center" style="padding-top:50px;padding-bottom:50px"><h4>No data found...</h4></td>';
            row += '</tr>';
            $('#table-body-pagination').append(row);
    }else{
        $.each(data.list, function(index, item) {
            var row = '<tr>';
            row += '<td class="center">' + x + '</td>';
            row += '<td class="center">' + item.id_no + '</td>';
            row += '<td class="center">' + item.name +'</td>';
            row += '<td class="center">' + item.position + '</td>';
            row += '<td class="center">' + item.salary + '</td>';
            row += '<td class="center">' + item.emp_stat + '</td>';
            row += '<td class="center">' + item.fund_service + '</td>';
            row += '<td class="center"><button class="btn btn-primary btn-primary-scan btn-sm employeeView" data-id="'+item.id+'">'+
                    '<span class="fa fa-eye"></span> View</button></td>';
            row += '<td class="center"><button class="btn btn-info btn-info-scan btn-sm deduction" data-id="'+item.id+'">'+
                    '<span class="fa fa-calculator"></span></button></td>';
            row += '</tr>';
            $('#table-body-pagination').append(row);
            x++;
        });
    }
}
function getCounts(){
    var option = $('#selectionDiv .select2 option:selected').val();
    var form_data = {
        option:option
    };
    $.ajax({
        url: base_url+'/hrims/employee/counts',
        data:form_data,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() {

        },
        success : function(data){
            $('#selectionDiv #btn-all').html(data.total);
            $('#selectionDiv #btn-active').html(data.total_active);
            $('#selectionDiv #num_all').html(data.all);
            $('#selectionDiv #num_1').html(data.permanent);
            $('#selectionDiv #num_3').html(data.temporary);
            $('#selectionDiv #num_2').html(data.casual);
            $('#selectionDiv #num_4').html(data.job_order);
            $('#selectionDiv #num_5').html(data.part_time);
            $('#selectionDiv #num_sep').html(data.separated);
        },
        error: function (){

        }
    });
}



