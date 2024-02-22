$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).on("input", ".numeric", function() {
    this.value = this.value.replace(/\D/g,''); 
});

//Add/Remove Modules with Access
$(document).on('change','.role_module',function(){
    
    var modules = $(this).val(); 
    if(modules != ''){
        $('#role_modules-error').text('');
    }
    $.ajax({
        type: 'post',
        url: '/sms-admin/role/view/get-removed-temp-array',
        data: {
            'id': modules
        },
        success: function(data) {
            
        }
    });

    if (modules != '') {
        $.ajax({
            url:'/sms-admin/role/view/module-access-change',
            method:'post',              
            data:{
                modules:modules,
            },
            success:function(data)
            {
                $('.module_func_body').empty();
                $('.module_func_body').append(data.html);
                $('.module_func').show();
            },
        });
    } else if(modules == '') {
        $('.module_func_body').empty();
        $('.module_func').css('display','none');
    }
});

//Check View option when any other access checked
$(document).on('change','.add_func',function(){
    var id = $(this).data('id');
    $('#view_'+id).prop('checked', true);   
});

//Role Switch Status Change
$(document).on('change', '.roleStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');
    $.ajax({
        url: "/sms-admin/role/view/role-status-change",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Role successfully activated');    
                }else if(option == 0){
                    toastr.success('Role successfully deactivated');    
                }else{
                    toastr.success('Something Went Wrong!');    
                }
            }else{
                toastr.success('Role status successfully changed');
            }
        }
    });
});

var add_option = [];
var edit_option = [];
var delete_option = [];
var view_option = [];

//Store Session Variable with Access - Edit Role
$(document).ready(function(){
    $(".add_value").each(function(){
        var id = $(this).data('id');
        var val = $(this).val();
        if ($('#add_'+id).is(":checked"))
        {
          add_option[id] = val;
        }
    });

    $(".edit_value").each(function(){
        var id = $(this).data('id');
        var val = $(this).val();
        if ($('#edit_'+id).is(":checked"))
        {
          edit_option[id] = val;
        }
    });

    $(".delete_value").each(function(){
        var id = $(this).data('id');
        var val = $(this).val();
        if ($('#delete_'+id).is(":checked"))
        {
          delete_option[id] = val;
        }
    });

    $(".view_value").each(function(){
        var id = $(this).data('id');
        var val = $(this).val();
        if ($('#view_'+id).is(":checked"))
        {
          view_option[id] = val;
        }
    });
    
    $.ajax({
        url: "/sms-admin/role/view/session-store",
        method:'POST',
        data:{ add_option: add_option,edit_option: edit_option, delete_option: delete_option, view_option: view_option},
        success: function(data){
            
        }
    });
});

//Store Session Variable with Access - Add Role
$(document).on('change','.add_func',function(){

    var id = $(this).data('id');
    var val = $(this).val();
    
    if(id in add_option){
        add_option.splice($.inArray(id, add_option), 1);
    }
    if ($('#add_'+id).is(":checked"))
    {
        add_option[id] = val;
    }

    if(id in edit_option){
        edit_option.splice($.inArray(id, edit_option), 1);
    }
    if ($('#edit_'+id).is(":checked"))
    {
        edit_option[id] = val;
    }

    if(id in delete_option){
        delete_option.splice($.inArray(id, delete_option), 1);
    }
    if ($('#delete_'+id).is(":checked"))
    {
        delete_option[id] = val;
    }

    if(id in view_option){
        view_option.splice($.inArray(id, view_option), 1);
    }
    if ($('#view_'+id).is(":checked"))
    {
        view_option[id] = val;
    }
    
    $.ajax({
        url: "/sms-admin/role/view/session-store",
        method:'POST',
        data:{ add_option: add_option,edit_option: edit_option, delete_option: delete_option, view_option: view_option},
        success: function(data){
            
        }
    });

});