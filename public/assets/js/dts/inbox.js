$(document).ready(function() {
    $(document).off('click', '.docs-option')
    .on('click', '.docs-option', function (e) {
        var id = $(this).data('id');
        var option = $(this).data('o');
        if(option=='Receive'){
            receiveDoc(id,$(this));
        }else if(option=='Forward'){
            forwardDoc(id,$(this));
        }else if(option=='Document'){
            historyDoc(id);
        }
    });
});
function updateDisplayedContent(data) {
    $('#table-body-pagination').html('');
    var x = 1;
    if(data.current_page>1){
        var x = parseInt(data.current_page)*10-10+1;
    }
    $.each(data.list, function(index, item) {
        var receive = '<span class="span-paginate fa fa-caret-square-o-down btn btn-primary btn-primary-scan docs-option" title="Receive" data-id="'+item.id+'" data-o="Receive"></span> ';
        var forward = '<span class="span-paginate fa fa-forward btn btn-info btn-info-scan docs-option" title="Forward" data-id="'+item.id+'" data-o="Forward"></span> ';
        var view = '<span class="span-paginate fa fa-search btn btn-success btn-success-scan docs-option" title="History" data-id="'+item.id+'" data-o="History"></span> ';
        var doc = '<span class="span-paginate fa fa-file-o btn btn-secondary btn-secondary-scan docs-option" title="Document" data-id="'+item.dts_id+'" data-o="Document"></span> ';
        var edit = '<span class="span-paginate fa fa-edit btn btn-warning btn-warning-scan docs-option" title="Edit" data-id="'+item.id+'" data-o="Edit"></span> ';
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
                if(data.office_id==item.latest.office_id){
                    var options = forward+view+doc;
                }else{
                    var options = view+doc;
                }
            }else{
                var option = ' ('+option+' to) ';
                if(data.office_id==item.latest.action_office_id){
                    var options = forward+view+doc;
                }else{
                    var options = receive+view+doc;
                }
            }
            var latest_action = item.latest.action_office.shorten+option+item.latest.office.shorten+'<br>'+dateTime;
        }else{
            var latest_action = '';
            var duration = getDuration(item.created_at,'');
            if(data.office_id==item.office_id){
                var options = forward+view+doc;
            }else{
                var options = view+doc;
            }
        }
        // if(data.office_id==item.office_id){
        //     var options = options+edit;
        // }
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
function historyDoc(id){

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
