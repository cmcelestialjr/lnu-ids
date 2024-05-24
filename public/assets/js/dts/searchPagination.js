$(document).ready(function() {
    paginate(1);
    $(document).off('click', '.btn-paginate')
    .on('click', '.btn-paginate', function (e) {
        paginate($(this).val());
    });
});
function paginate(page) {
    var value = $('#searchDiv input[name="search"]').val();
    var form_data = {
        value:value,
        page:page
    };
    $.ajax({
        url: base_url+'/dts/searchPaginate',
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
    $.each(data.list, function(index, item) {
        var view = '<span class="span-paginate fa fa-search btn btn-success btn-success-scan docs-option" title="History" data-id="'+item.dts_id+'" data-o="History"></span> ';
        var doc = '<span class="span-paginate fa fa-file-o btn btn-secondary btn-secondary-scan docs-option" title="Document" data-id="'+item.id+'" data-o="Document"></span> ';
        var date_from = getDateTime(item.created_at);
        if (item.latest){
            var dateTime = getDateTime(item.latest.created_at);
            var duration = getDuration(item.created_at,item.latest.created_at);
            var option = item.latest.option.name;
            if(item.latest.is_return=='Y' && item.latest.option_id==2){
                var option = 'Returned';
            }
            if(item.latest.option_id==1){
                var option = ' ('+option+' by) ';
            }else{
                var option = ' ('+option+' to) ';
            }
            var latest_action = item.latest.action_office.shorten+option+item.latest.office.shorten+'<br>'+dateTime;
        }else{
            var latest_action = '';
            var duration = getDuration(item.created_at,'');

        }
        var options = view+doc;

        var row = '<tr>';
        row += '<td class="center">' + x + '</td>';
        row += '<td>' + item.dts_id + '</td>';
        row += '<td class="center">' + item.office.shorten + '</td>';
        row += '<td>' + item.dts_id + '</td>';
        row += '<td>' + item.particulars + '</td>';
        row += '<td>' + item.description + '</td>';
        row += '<td class="center">' + date_from + '</td>';
        row += '<td class="center">' + duration + '</td>';
        row += '<td class="center latest_action" id="latest_action_'+x+'">' + latest_action + '</td>';
        row += '<td class="center"><button class="' + item.status.btn + ' btn-xs">' + item.status.name + '</button></td>';
        row += '<td class="center">'+options+'</td>';
        row += '</tr>';
        $('#table-body-pagination').append(row);
        x++;
    });
}
function getDateTime(date){
    var currentDate = new Date(date);

    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var month = months[currentDate.getMonth()];
    var day = currentDate.getDay();
    var date = currentDate.getDate();
    var year = currentDate.getFullYear();
    var hours = currentDate.getHours();
    var minutes = currentDate.getMinutes();

    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;

    var formattedDate = month + ' ' + date + ', ' + year + ' ' + hours + ':' + minutes + ' ' + ampm;

    return formattedDate;
}
function getDuration(date_from,date_to){
    var date_from = new Date(date_from);
    if(date_to==''){
        var date_to = new Date();
    }else{
        var date_to = new Date(date_to);
    }

    var duration = date_to - date_from;

    var days = Math.floor(duration / (1000 * 60 * 60 * 24));
    var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));

    var days_view = '';
    var hours_view = '';
    var minutes_view = '';

    if(days==1){
        var days_view = days+'day ';
    }else if(days>1){
        var days_view = days+'days ';
    }

    if(hours==1){
        var hours_view = hours+'hr ';
    }else if(hours>1){
        var hours_view = hours+'hrs ';
    }

    if(minutes==1){
        var minutes_view = minutes+'min ';
    }else if(minutes>1){
        var minutes_view = minutes+'mins ';
    }

    return days_view+hours_view+minutes_view;
}



