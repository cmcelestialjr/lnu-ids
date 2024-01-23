cluster_table();
$(document).off('click', '#cluster button[name="new"]').on('click', '#cluster button[name="new"]', function (e) {
    cluster_new();
});
$(document).off('click', '#clusterNewModal button[name="submit"]').on('click', '#clusterNewModal button[name="submit"]', function (e) {
    cluster_new_submit();
});
$(document).off('click', '#cluster .update').on('click', '#cluster .update', function (e) {
    var thisBtn = $(this);
    cluster_update(thisBtn);
});
$(document).off('click', '#cluster .view').on('click', '#cluster .view', function (e) {
    var thisBtn = $(this);
    cluster_view(thisBtn);
});
$(document).off('click', '#clusterUpdateModal button[name="submit"]').on('click', '#clusterUpdateModal button[name="submit"]', function (e) {
    cluster_update_submit();
});
$(document).off('click', '#sourceLink').on('click', '#sourceLink', function (e) {
    source_table();
});
$(document).off('click', '#source button[name="new"]').on('click', '#source button[name="new"]', function (e) {
    source_new();
});
$(document).off('click', '#sourceNewModal button[name="submit"]').on('click', '#sourceNewModal button[name="submit"]', function (e) {
    source_new_submit();
});
$(document).off('click', '#source .update').on('click', '#source .update', function (e) {
    var thisBtn = $(this);
    source_update(thisBtn);
});
$(document).off('click', '#sourceUpdateModal button[name="submit"]').on('click', '#sourceUpdateModal button[name="submit"]', function (e) {
    source_update_submit();
});
$(document).off('click', '#financingLink').on('click', '#financingLink', function (e) {
    financing_table();
});
$(document).off('click', '#financing button[name="new"]').on('click', '#financing button[name="new"]', function (e) {
    financing_new();
});
$(document).off('click', '#financingNewModal button[name="submit"]').on('click', '#financingNewModal button[name="submit"]', function (e) {
    financing_new_submit();
});
$(document).off('click', '#financing .update').on('click', '#financing .update', function (e) {
    var thisBtn = $(this);
    financing_update(thisBtn);
});
$(document).off('click', '#financingUpdateModal button[name="submit"]').on('click', '#financingUpdateModal button[name="submit"]', function (e) {
    financing_update_submit();
});
$(document).off('click', '#servicesLink').on('click', '#servicesLink', function (e) {
    services_table();
});
$(document).off('click', '#services button[name="new"]').on('click', '#services button[name="new"]', function (e) {
    services_new();
});
$(document).off('click', '#servicesNewModal button[name="submit"]').on('click', '#servicesNewModal button[name="submit"]', function (e) {
    services_new_submit();
});
$(document).off('click', '#services .update').on('click', '#services .update', function (e) {
    var thisBtn = $(this);
    services_update(thisBtn);
});
$(document).off('click', '#servicesUpdateModal button[name="submit"]').on('click', '#servicesUpdateModal button[name="submit"]', function (e) {
    services_update_submit();
});
function cluster_table(){
    var form_data = {
        url_table:base_url+'/fms/accounting/fund/cluster/table',
        tid:'clusterTable'
    };
    loadTable(form_data);
}
function source_table(){
    var form_data = {
        url_table:base_url+'/fms/accounting/fund/source/table',
        tid:'sourceTable'
    };
    loadTable(form_data);
}
function financing_table(){
    var form_data = {
        url_table:base_url+'/fms/accounting/fund/financing/table',
        tid:'financingTable'
    };
    loadTable(form_data);
}
function services_table(){
    var form_data = {
        url_table:base_url+'/fms/accounting/fund/services/table',
        tid:'servicesTable'
    };
    loadTable(form_data);
}
function cluster_new(){
    var thisBtn = $('#cluster button[name="new"]');
    var url = base_url+'/fms/accounting/fund/cluster/newModal';
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
function source_new(){
    var thisBtn = $('#source button[name="new"]');
    var url = base_url+'/fms/accounting/fund/source/newModal';
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
function financing_new(){
    var thisBtn = $('#financing button[name="new"]');
    var url = base_url+'/fms/accounting/fund/financing/newModal';
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
function services_new(){
    var thisBtn = $('#services button[name="new"]');
    var url = base_url+'/fms/accounting/fund/services/newModal';
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
function cluster_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fund/cluster/updateModal';
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
function cluster_view(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fund/cluster/viewModal';
    var modal = 'default';
    var modal_size = 'modal-xl';
    var form_data = {
        url:url,
        modal:modal,
        modal_size:modal_size,
        static:'',
        w_table:'w',
        url_table:base_url+'/fms/accounting/fund/cluster/viewTable',
        tid:'viewClusterTable',
        id:id
    };
    loadModal(form_data,thisBtn);
}
function source_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fund/source/updateModal';
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
function financing_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fund/financing/updateModal';
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
function services_update(thisBtn){
    var id = thisBtn.data('id');
    var url = base_url+'/fms/accounting/fund/services/updateModal';
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
function cluster_new_submit(){
    var thisBtn = $('#clusterNewModal button[name="submit"]');
    //var fund_financing = $('#clusterNewModal select[name="fund_financing"] option:selected').val();
    var name = $('#clusterNewModal input[name="name"]').val();
    var shorten = $('#clusterNewModal input[name="shorten"]').val();
    var code = $('#clusterNewModal input[name="code"]').val();
    var x = 0;
    if(name==''){
        $('#clusterNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Cluster Name');
        x++;
    }
    if(shorten==''){
        $('#clusterNewModal input[name="shorten"]').addClass('border-require');
        toastr.error('Please input Cluster Shorten');
        x++;
    }
    if(code==''){
        $('#clusterNewModal input[name="code"]').addClass('border-require');
        toastr.error('Please input Cluster Code');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            code:code,
            //fund_financing:fund_financing
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/cluster/newSubmit',
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
                    cluster_table();
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
function source_new_submit(){
    var thisBtn = $('#sourceNewModal button[name="submit"]');
    var name = $('#sourceNewModal input[name="name"]').val();
    var shorten = $('#sourceNewModal input[name="shorten"]').val();
    var uacs = $('#sourceNewModal input[name="uacs"]').val();
    var fund_cluster = $('#sourceNewModal select[name="fund_cluster"] option:selected').val();
    var x = 0;
    if(name==''){
        $('#sourceNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Cluster Name');
        x++;
    }
    if(shorten==''){
        $('#sourceNewModal input[name="shorten"]').addClass('border-require');
        toastr.error('Please input Cluster Shorten');
        x++;
    }
    if(uacs==''){
        $('#sourceNewModal input[name="uacs"]').addClass('border-require');
        toastr.error('Please input Cluster Shorten');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            fund_cluster:fund_cluster,
            uacs:uacs
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/source/newSubmit',
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
                    source_table();
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
function financing_new_submit(){
    var thisBtn = $('#financingNewModal button[name="submit"]');
    var name = $('#financingNewModal input[name="name"]').val();
    var x = 0;
    if(name==''){
        $('#financingNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input financing Name');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/financing/newSubmit',
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
                    financing_table();
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
function services_new_submit(){
    var thisBtn = $('#servicesNewModal button[name="submit"]');
    var name = $('#servicesNewModal input[name="name"]').val();
    var shorten = $('#servicesNewModal input[name="shorten"]').val();
    var x = 0;
    if(name==''){
        $('#servicesNewModal input[name="name"]').addClass('border-require');
        toastr.error('Please input services Name');
        x++;
    }
    if(shorten==''){
        $('#servicesNewModal input[name="shorten"]').addClass('border-require');
        toastr.error('Please input services shorten');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/services/newSubmit',
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
                    services_table();
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
function cluster_update_submit(){
    var thisBtn = $('#clusterUpdateModal button[name="submit"]');
    //var fund_financing = $('#clusterUpdateModal select[name="fund_financing"] option:selected').val();
    var name = $('#clusterUpdateModal input[name="name"]').val();
    var shorten = $('#clusterUpdateModal input[name="shorten"]').val();
    var code = $('#clusterUpdateModal input[name="code"]').val();
    var id = $('#clusterUpdateModal input[name="id"]').val();
    var x = 0;
    if(name==''){
        $('#clusterUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Cluster Name');
        x++;
    }
    if(shorten==''){
        $('#clusterUpdateModal input[name="shorten"]').addClass('border-require');
        toastr.error('Please input Cluster Shorten');
        x++;
    }
    if(code==''){
        $('#clusterUpdateModal input[name="code"]').addClass('border-require');
        toastr.error('Please input Cluster Code');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            code:code,
            //fund_financing:fund_financing,
            id:id
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/cluster/updateSubmit',
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
                    cluster_table();
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
function source_update_submit(){
    var thisBtn = $('#sourceUpdateModal button[name="submit"]');
    var name = $('#sourceUpdateModal input[name="name"]').val();
    var shorten = $('#sourceUpdateModal input[name="shorten"]').val();
    var uacs = $('#sourceUpdateModal input[name="uacs"]').val();
    var fund_cluster = $('#sourceUpdateModal select[name="fund_cluster"] option:selected').val();
    var id = $('#sourceUpdateModal input[name="id"]').val();
    var x = 0;
    if(name==''){
        $('#sourceUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input Cluster Name');
        x++;
    }
    if(shorten==''){
        $('#sourceUpdateModal input[name="shorten"]').addClass('border-require');
        toastr.error('Please input Cluster Shorten');
        x++;
    }
    if(uacs==''){
        $('#sourceUpdateModal input[name="uacs"]').addClass('border-require');
        toastr.error('Please input Cluster Shorten');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            fund_cluster:fund_cluster,
            uacs:uacs,
            id:id
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/source/updateSubmit',
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
                    source_table();
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
function financing_update_submit(){
    var thisBtn = $('#financingUpdateModal button[name="submit"]');
    var name = $('#financingUpdateModal input[name="name"]').val();
    var id = $('#financingUpdateModal input[name="id"]').val();
    var x = 0;
    if(name==''){
        $('#financingUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input financing Name');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            id:id
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/financing/updateSubmit',
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
                    financing_table();
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
function services_update_submit(){
    var thisBtn = $('#servicesUpdateModal button[name="submit"]');
    var name = $('#servicesUpdateModal input[name="name"]').val();
    var shorten = $('#servicesUpdateModal input[name="shorten"]').val();
    var id = $('#servicesUpdateModal input[name="id"]').val();
    var x = 0;
    if(name==''){
        $('#servicesUpdateModal input[name="name"]').addClass('border-require');
        toastr.error('Please input services Name');
        x++;
    }
    if(shorten==''){
        $('#servicesUpdateModal input[name="shorten"]').addClass('border-require');
        toastr.error('Please input services shorten');
        x++;
    }
    if(x==0){
        var form_data = {
            name:name,
            shorten:shorten,
            id:id
        };
        $.ajax({
            url: base_url+'/fms/accounting/fund/services/updateSubmit',
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
                    services_table();
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