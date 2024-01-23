position_table();
$(document).off('change', '#positionDiv select').on('change', '#positionDiv select', function (e) {
    position_table();
});
$(document).off('click', '#positionDiv button[name="new"]').on('click', '#positionDiv button[name="new"]', function (e) {
    position_new();
});
$(document).off('click', '#newModal button[name="submit"]').on('click', '#newModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var div = 'newModal';
    var url = 'positionNewSubmit';
    position_submit(thisBtn,div,url);
});
$(document).off('click', '#positionDiv .positionEditModal').on('click', '#positionDiv .positionEditModal', function (e) {
    var thisBtn = $(this);
    position_edit(thisBtn);
});
$(document).off('click', '#editModal button[name="submit"]').on('click', '#editModal button[name="submit"]', function (e) {
    var thisBtn = $(this);
    var div = 'editModal';
    var url = 'positionEditSubmit';
    position_submit(thisBtn,div,url);
});
$(document).off('click', '#positionDiv .positionViewModal').on('click', '#positionDiv .positionViewModal', function (e) {
    var thisBtn = $(this);
    position_view(thisBtn);
});

function position_table(){
    var thisBtn = $('#positionDiv select');
    var type = $('#positionDiv select[name="type"] option:selected').val();
    var status = $('#positionDiv select[name="status"] option:selected').val();
    var form_data = {
        url_table:base_url+'/hrims/position/positionTable',
        tid:'positionTable',
        type:type,
        status:status
    };
    loadTablewLoader(form_data,thisBtn);
}
function position_new(){
    var thisBtn = $('#positionDiv button[name="new"]');
    var url = base_url+'/hrims/position/positionNew';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function position_edit(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/position/positionEdit';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function position_view(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/hrims/position/positionView';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/hrims/position/positionViewTable',
        tid:'positionViewTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function position_submit(thisBtn,div,url){
    var thisBtn = $('#'+div+' button[name="submit"]');
    var id = $('#'+div+' input[name="id"]').val();
    var item_no = $('#'+div+' input[name="item_no"]').val();
    var name = $('#'+div+' input[name="name"]').val();
    var shorten = $('#'+div+' input[name="shorten"]').val();
    var salary = $('#'+div+' input[name="salary"]').val();
    var sg = $('#'+div+' input[name="sg"]').val();
    var level = $('#'+div+' input[name="designation_listlevel"]').val();
    var date_created = $('#'+div+' input[name="date_created"]').val();
    var remarks = $('#'+div+' textarea[name="remarks"]').val();
    var designation = $('#'+div+' select[name="designation"] option:selected').val();
    var emp_stat = $('#'+div+' select[name="emp_stat"] option:selected').val();
    var fund_source = $('#'+div+' select[name="fund_source"] option:selected').val();
    var fund_services = $('#'+div+' select[name="fund_services"] option:selected').val();
    var role = $('#'+div+' select[name="role"] option:selected').val();
    var status = $('#'+div+' select[name="status"] option:selected').val();
    var sched = $('#'+div+' select[name="sched"] option:selected').val();
    var gov_service = $('#'+div+' select[name="gov_service"] option:selected').val();
    var x = 0;
    if(item_no==''){
        $('#'+div+' input[name="item_no"]').addClass('border-require');
        toastr.error('Please input Item No.');
        x++;
    }
    if(name==''){
        $('#'+div+' input[name="name"]').addClass('border-require');
        toastr.error('Please input Position Title');
        x++;
    }
    if(shorten==''){
        $('#'+div+' input[name="shorten"]').addClass('border-require');
        toastr.error('Please input Position Shorten');
        x++;
    }
    if(salary==''){
        $('#'+div+' input[name="salary"]').addClass('border-require');
        toastr.error('Please input Salary');
        x++;
    }
    if(sg==''){
        $('#'+div+' input[name="sg"]').addClass('border-require');
        toastr.error('Please input SG');
        x++;
    }
    if(level==''){
        $('#'+div+' input[name="level"]').addClass('border-require');
        toastr.error('Please input position level');
        x++;
    }
    if(date_created==''){
        $('#'+div+' input[name="date_created"]').addClass('border-require');
        toastr.error('Please input Date of Creation');
        x++;
    }
    if(x==0){
        var form_data = {
            id:id,
            item_no:item_no,
            name:name,
            shorten:shorten,
            salary:salary,
            sg:sg,
            level:level,
            date_created:date_created,
            remarks:remarks,
            designation:designation,
            emp_stat:emp_stat,
            fund_source:fund_source,
            fund_services:fund_services,
            role:role,
            status:status,
            sched:sched,
            gov_service:gov_service
        };
        $.ajax({
            url: base_url+'/hrims/position/'+url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){                    
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                    position_table();
                    $('#modal-default').modal('hide');
                }else{
                    toastr.error('Error.');
                    thisBtn.addClass('input-error');
                }
                setTimeout(function() {
                    thisBtn.removeClass('input-success');
                    thisBtn.removeClass('input-error');
                }, 3000);
            },
            error: function (){
                toastr.error('Error!');
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-success');
                thisBtn.removeClass('input-error');
            }
        });
    }
}