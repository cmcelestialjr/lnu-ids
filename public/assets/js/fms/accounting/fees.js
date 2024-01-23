
fees_table();
$(document).off('change', '#fees select').on('change', '#fees  select', function (e) {
    fees_table();
});
$(document).off('click', '#fees button[name="new"]').on('click', '#fees button[name="new"]', function (e) {
    fees_new();
});
$(document).off('click', '#feesNewModal button[name="submit"]').on('click', '#feesNewModal button[name="submit"]', function (e) {
    fees_new_submit();
});
$(document).off('blur', '#fees .period').on('blur', '#fees .period', function (e) {
    fees_period_update($(this));
});
$(document).off('blur', '#fees .allPeriod').on('blur', '#fees .allPeriod', function (e) {
    fees_all_period_update($(this));
});
$(document).off('click', '#fees button[name="lab_fee"]').on('click', '#fees button[name="lab_fee"]', function (e) {
    lab_fee();
});
$(document).off('click', '#labFeesModal #labCourses').on('click', '#labFeesModal #labCourses', function (e) {
    lab_courses();
});
$(document).off('click', '#labFeesModal #labGroup').on('click', '#labFeesModal #labGroup', function (e) {
    lab_group();
});
$(document).off('click', '#labFeesModal #lab_group button[name="new"]').on('click', '#labFeesModal #lab_group button[name="new"]', function (e) {
    lab_group_new();
});
$(document).off('click', '#newGroupModal button[name="submit"]').on('click', '#newGroupModal button[name="submit"]', function (e) {
    lab_group_new_submit();
});
$(document).off('click', '#labFeesModal #lab_group .update').on('click', '#labFeesModal #lab_group .update', function (e) {
    lab_group_update($(this));
});
$(document).off('click', '#labFeesModal #lab_group .courses').on('click', '#labFeesModal #lab_group .courses', function (e) {
    lab_group_courses($(this));
});
$(document).off('click', '#updateGroupModal button[name="submit"]').on('click', '#updateGroupModal button[name="submit"]', function (e) {
    lab_group_update_submit();
});
$(document).off('click', '#groupCoursesModal button[name="add"]').on('click', '#groupCoursesModal button[name="add"]', function (e) {
    lab_group_courses_add($(this));
});
$(document).off('click', '#groupCoursesModal .remove').on('click', '#groupCoursesModal .remove', function (e) {
    lab_group_courses_remove($(this));
});
$(document).off('blur', '.labCoursesAmount').on('blur', '.labCoursesAmount', function (e) {
    lab_courses_amount($(this));
});
$(document).off('click', '#listLink').on('click', '#listLink', function (e) {
    list_table();
});
$(document).off('click', '#list button[name="new"]').on('click', '#list button[name="new"]', function (e) {
    list_new();
});
$(document).off('click', '#listNewModal button[name="submit"]').on('click', '#listNewModal button[name="submit"]', function (e) {
    list_new_submit();
});
$(document).off('click', '#list .update').on('click', '#list .update', function (e) {
    list_update($(this));
});
$(document).off('click', '#listUpdateModal button[name="submit"]').on('click', '#listUpdateModal button[name="submit"]', function (e) {
    list_update_submit();
});
$(document).off('click', '#discountLink').on('click', '#discountLink', function (e) {
    discount_table();
});
$(document).off('click', '#discount button[name="new"]').on('click', '#discount button[name="new"]', function (e) {
    discount_new();
});
$(document).off('change', '#discountNewModal select[name="option"]').on('change', '#discountNewModal select[name="option"]', function (e) {
    discount_option_select($(this));
});
$(document).off('change', '#discountNewModal input[name="all"]').on('change', '#discountNewModal input[name="all"]', function (e) {
    if(this.checked){
        $('#discountNewModal .programs').prop('checked', true);
    }else{
        $('#discountNewModal .programs').prop('checked', false);
    }
});
$(document).off('change', '#discountUpdateModal input[name="all"]').on('change', '#discountUpdateModal input[name="all"]', function (e) {
    if(this.checked){
        $('#discountUpdateModal .programs').prop('checked', true);
    }else{
        $('#discountUpdateModal .programs').prop('checked', false);
    }
});
$(document).off('change', '#discountNewModal #discountLevelSelect').on('change', '#discountNewModal #discountLevelSelect', function (e) {
    discount_level_select($(this));
});
$(document).off('click', '#discountAddStudent').on('click', '#discountAddStudent', function (e) {
    discount_student_add($(this));
});
$(document).off('click', '#discountNewModal button[name="submit"]').on('click', '#discountNewModal button[name="submit"]', function (e) {
    discount_new_submit($(this));
});
$(document).off('click', '#discount .update').on('click', '#discount .update', function (e) {
    discount_update($(this));
});
$(document).off('click', '#discountUpdateModal button[name="submit"]').on('click', '#discountUpdateModal button[name="submit"]', function (e) {
    discount_update_submit($(this));
});
$(document).off('click', '#discount .discountStatus').on('click', '#discount .discountStatus', function (e) {
    discount_status($(this));
});
function fees_table(){
    var thisBtn = $('#fees select');
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/fees/table',
        tid:'feesDiv',
        branch:branch,
        level:level
    };
    loadDivwLoader(form_data,thisBtn);
}
function list_table(){
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/list/table',
        tid:'listTable'
    };
    loadTable(form_data);
}
function lab_group(){
    var level = $('#fees select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/lab/tableGroup',
        tid:'labGroupTable',
        level:level
    };
    loadTable(form_data);
}
function lab_courses(){
    var level = $('#fees select[name="level"] option:selected').val();
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/lab/tableCourses',
        tid:'labCoursesTable',
        level:level
    };
    loadTable(form_data);
}
function lab_group_courses_table(){
    var id = $('#groupCoursesModal input[name="id"]').val();
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/lab/tableGroupCourses',
        tid:'labGroupCourseTable',
        id:id
    };
    loadTable(form_data);
}
function discount_table(){
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/discount/table',
        tid:'discountTable'
    };
    loadTable(form_data);
}
function discount_option_select(thisBtn){
    var option = thisBtn.val();
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/discount/programOption',
        tid:'discountOptionDiv',
        option:option
    };
    loadDivwLoader(form_data,thisBtn);
}
function discount_level_select(thisBtn){
    var level = thisBtn.val();
    var form_data = {
        url_table:base_url+'/fms/accounting/fees/discount/programList',
        tid:'discountProgramList',
        level:level
    };
    loadDivwLoader(form_data,thisBtn);
}
function fees_new(){
    var thisBtn = $('#fees button[name="new"]');
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var url = base_url+'/fms/accounting/fees/fees/newModal';
    var modal = 'default';
    var modal_size = 'modal-lg';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        branch:branch,
        level:level
    };
    loadModal(form_data,thisBtn);
}
function lab_group_new(){
    var thisBtn = $('#labFeesModal #lab_group button[name="new"]');
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var url = base_url+'/fms/accounting/fees/lab/newGroupModal';
    var modal = 'primary';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo',
        branch:branch,
        level:level
    };
    loadModal(form_data,thisBtn);
}
function lab_group_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fees/lab/updateGroupModal';
    var modal = 'primary';
    var modal_size = 'modal-md';
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
function lab_fee(){
    var thisBtn = $('#fees button[name="lab_fee"]');
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var url = base_url+'/fms/accounting/fees/fees/labFeeModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/fms/accounting/fees/lab/tableCourses',
        tid:'labCoursesTable',
        branch:branch,
        level:level
    };
    loadModal(form_data,thisBtn);
}
function list_new(){
    var thisBtn = $('#list button[name="new"]');
    var url = base_url+'/fms/accounting/fees/list/newModal';
    var modal = 'default';
    var modal_size = 'modal-md';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'wo'
    };
    loadModal(form_data,thisBtn);
}
function list_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fees/list/updateModal';
    var modal = 'default';
    var modal_size = 'modal-md';
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
function lab_group_courses(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fees/lab/groupCoursesModal';
    var modal = 'primary';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/fms/accounting/fees/lab/tableGroupCourses',
        tid:'labGroupCourseTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function discount_new(){
    var thisBtn = $('#discount button[name="new"]');
    var url = base_url+'/fms/accounting/fees/discount/newModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'div',
        url_table:base_url+'/fms/accounting/fees/discount/programOption',
        tid:'discountOptionDiv',
        option:1
    };
    loadModal(form_data,thisBtn);
}
function discount_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fees/discount/updateModal';
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
function fees_new_submit(){
    var thisBtn = $('#feesNewModal button[name="submit"]');
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var fees = $('#feesNewModal select[name="fees"] option:selected').val();
    var x = 0;
    if(x==0){
        var form_data = {
            fees:fees,
            branch:branch,
            level:level
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/fees/newSubmit',
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
                    var selectFees = document.getElementById('feesNewModalSelect');
                    selectFees.removeChild(selectFees.querySelector('option[value="'+fees+'"]'));
                    fees_table();
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
function fees_period_update(thisBtn){
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var fees = thisBtn.data('id');
    var period = thisBtn.data('period');
    var value = thisBtn.val();
    var x = 0;
    $('#fees .period').removeClass('border-require');
    if(value<0){
        thisBtn.addClass('border-require');
        toastr.error('Please input a valid amount');
        x++;
    }
    if(x==0){
        var form_data = {
            fees:fees,
            branch:branch,
            level:level,
            period:period,
            value:value
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/fees/feesSubmit',
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
function fees_all_period_update(thisBtn){
    var branch = $('#fees select[name="branch"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var fees = thisBtn.data('id');
    var value = thisBtn.val();
    var x = 0;
    $('#fees .allPeriod').removeClass('border-require');
    if(value<=0){
        thisBtn.addClass('border-require');
        toastr.error('Please input a valid amount');
        x++;
    }
    if(x==0){
        var form_data = {
            fees:fees,
            branch:branch,
            level:level,
            value:value
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/fees/feesAllSubmit',
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
                    var period_list = data.period_list;
                    for (let i = 0; i < period_list.length; ++i) {
                        $('#fees .'+period_list[i]).val(value);
                    }
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
function list_new_submit(){
    var thisBtn = $('#listNewModal button[name="submit"]');
    var name = $('#listNewModal input[name="name"]').val();
    var type = $('#listNewModal select[name="type"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#listNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Fee Name');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            type:type
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/list/newSubmit',
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
                    list_table();
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
function list_update_submit(){
    var thisBtn = $('#listUpdateModal button[name="submit"]');
    var id = $('#listUpdateModal input[name="id"]').val();
    var name = $('#listUpdateModal input[name="name"]').val();
    var type = $('#listUpdateModal select[name="type"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#listUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Fee Name');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            type:type,
            id:id
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/list/updateSubmit',
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
                    list_table();
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
function lab_group_new_submit(){
    var thisBtn = $('#newGroupModal button[name="submit"]');
    var name = $('#newGroupModal input[name="name"]').val();
    var remarks = $('#newGroupModal input[name="remarks"]').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#newGroupModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Lab Group');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            remarks:remarks,
            level:level
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/lab/newGroupModalSubmit',
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
                    lab_group();
                    $('#modal-primary').modal('hide');
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
function lab_group_update_submit(){
    var thisBtn = $('#updateGroupModal button[name="submit"]');
    var id = $('#updateGroupModal input[name="id"]').val();
    var name = $('#updateGroupModal input[name="name"]').val();
    var remarks = $('#updateGroupModal input[name="remarks"]').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#newGroupModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Lab Group');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            remarks:remarks,
            id:id,
            level:level
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/lab/updateGroupModalSubmit',
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
                    lab_group();
                    $('#modal-primary').modal('hide');
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
function lab_group_courses_add(thisBtn){
    var id = $('#groupCoursesModal input[name="id"]').val();
    var course_code = $('#groupCoursesModal select[name="course"] option:selected').val();
    var level = $('#fees select[name="level"] option:selected').val();
    var form_data = {
        id:id,
        course_code:course_code,
        level:level
    };
    $.ajax({
        url: base_url+'/fms/accounting/fees/lab/groupCourseAdd',
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
                lab_group_courses_table();
                $('#groupCoursesModal select[name="course"]').empty();
            }else{
                toastr.error(data.result);
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
function lab_group_courses_remove(thisBtn){
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/fms/accounting/fees/lab/groupCourseRemove',
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
                lab_group_courses_table();
            }else{
                toastr.error(data.result);
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
function lab_courses_amount(thisBtn){
    var id = thisBtn.data('id');
    var val = thisBtn.val();
    thisBtn.addClass('border-require');
    if(val>0){
        var form_data = {
            id:id,
            val:val
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/lab/labCoursesAmount',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.removeClass('border-require');
                thisBtn.attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){                    
                    toastr.success('Success');
                    thisBtn.addClass('input-success');
                }else{
                    toastr.error(data.result);
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
function discount_student_add(thisBtn){
    var val = $('#discountStudentSelected option:selected').val();
    var students = [];
    $('.discountStudentsList').each(function() {
        var dataId = $(this).data('id');
        students.push(dataId);
    });

    $('#studentSearch').removeClass('border-require');
    if(val==''){
        $('#studentSearch').addClass('border-require');
    }
    
    if(val!=''){
        var form_data = {
            val:val,
            students:students
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/discount/studentAdd',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.removeClass('border-require');
                thisBtn.attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    thisBtn.addClass('input-success');
                    var student = data.datas;
                    var newRow = '<tr>' +
                          '<td class="center">' + student.id_no + '</td>' +
                          '<td>' + student.name + '</td>' +
                          '<td class="center">' + student.program + '</td>' +
                          '<td class="center"><button class="btn btn-danger btn-danger-scan btn-xs discountStudentsList" data-id="' + student.id + '"><span class="fa fa-minus"></span></button></td>' +
                          '</tr>';                  
                    $('#studentList').append(newRow);
                }else{
                    toastr.error(data.result);
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
function discount_new_submit(thisBtn){
    var name = $('#discountNewModal input[name="name"]').val();
    var percent = $('#discountNewModal input[name="percent"]').val();
    var option = $('#discountNewModal select[name="option"] option:selected').val();
    var fees_type = [];
    var programs = [];
    var students = [];
    var x = 0;

    $('#discountNewModal select[name="fees_type[]"] option:selected').each(function() {
        var value = $(this).val();
        fees_type.push(value);
    });

    $('#discountNewModal input[name="name"]').removeClass('border-require');
    $('#discountNewModal input[name="percent"]').removeClass('border-require');
    if(name==''){
        $('#discountNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input name');
        x++;
    }
    if(percent<0 || percent=='' || percent>100){
        $('#discountNewModal input[name="percent"]').addClass('border-require');
        toastr.error('Please input percentage 1 to 100 only');
        x++;
    }
    if(fees_type==''){
        toastr.error('Please select fees to discount');
        x++;
    }
    
    if(option==1){
        $('#discountNewModal .programs:checked').each(function() {
            var value = $(this).val();
            programs.push(value);
        });
        if(programs==''){
            toastr.error('Please select program');
            x++;
        }
    }else if(option==2){
        $('#discountNewModal .discountStudentsList').each(function() {
            var value = $(this).data('id');
            students.push(value);
        });
        if(students==''){
            toastr.error('Please select student');
            x++;
        }
    }else{
        x++;
    }
    
    if(x==0){
        var form_data = {
            name:name,
            percent:percent,
            option:option,
            fees_type:fees_type,
            programs:programs,
            students:students
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/discount/newSubmit',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.removeClass('border-require');
                thisBtn.attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success(data.result);
                    thisBtn.addClass('input-success');
                    discount_table();
                    $('#modal-default').modal('hide');
                }else{
                    toastr.error(data.result);
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
function discount_update_submit(thisBtn){
    var id = $('#discountUpdateModal input[name="id"]').val();
    var name = $('#discountUpdateModal input[name="name"]').val();
    var percent = $('#discountUpdateModal input[name="percent"]').val();
    var option = $('#discountUpdateModal input[name="option"]').val();
    

    var fees_type = [];
    var programs = [];
    var students = [];
    var x = 0;

    $('#discountUpdateModal select[name="fees_type[]"] option:selected').each(function() {
        var value = $(this).val();
        fees_type.push(value);
    });

    $('#discountUpdateModal input[name="name"]').removeClass('border-require');
    $('#discountUpdateModal input[name="percent"]').removeClass('border-require');
    if(name==''){
        $('#discountUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input name');
        x++;
    }
    if(percent<0 || percent=='' || percent>100){
        $('#discountUpdateModal input[name="percent"]').addClass('border-require');
        toastr.error('Please input percentage 1 to 100 only');
        x++;
    }
    if(fees_type==''){
        toastr.error('Please select fees to discount');
        x++;
    }
    
    if(option==1){
        $('#discountUpdateModal .programs:checked').each(function() {
            var value = $(this).val();
            programs.push(value);
        });
        if(programs==''){
            toastr.error('Please select program');
            x++;
        }
    }else if(option==2){
        $('#discountUpdateModal .discountStudentsList').each(function() {
            var value = $(this).data('id');
            students.push(value);
        });
        if(students==''){
            toastr.error('Please select student');
            x++;
        }
    }else{
        x++;
    }

    if(x==0){
        var form_data = {
            id:id,
            name:name,
            percent:percent,
            option:option,
            fees_type:fees_type,
            programs:programs,
            students:students
        };
        $.ajax({
            url: base_url+'/fms/accounting/fees/discount/updateSubmit',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            data:form_data,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                thisBtn.removeClass('border-require');
                thisBtn.attr('disabled','disabled'); 
                thisBtn.addClass('input-loading');
            },
            success : function(data){
                thisBtn.removeAttr('disabled');
                thisBtn.removeClass('input-loading'); 
                if(data.result=='success'){
                    toastr.success(data.result);
                    thisBtn.addClass('input-success');
                    discount_table();
                }else{
                    toastr.error(data.result);
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
function discount_status(thisBtn){
    var id = thisBtn.data('id');
    var form_data = {
        id:id
    };
    $.ajax({
        url: base_url+'/fms/accounting/fees/discount/statusUpdate',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data:form_data,
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            thisBtn.removeClass('border-require');
            thisBtn.attr('disabled','disabled'); 
            thisBtn.addClass('input-loading');
        },
        success : function(data){
            thisBtn.removeAttr('disabled');
            thisBtn.removeClass('input-loading'); 
            if(data.result=='success'){
                var datas = data.datas;
                toastr.success(data.result);
                thisBtn.addClass('input-success');
                thisBtn.removeClass('btn-success btn-success-scan');
                thisBtn.removeClass('btn-danger btn-danger-scan');
                thisBtn.addClass(datas.class);
                thisBtn.html(datas.span);
            }else{
                toastr.error(data.result);
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