$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Para Master Switch Status Change
$(document).on('change', '.paraMasterStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/para-master/view/change-para-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Para master successfully activated');    
                } else if(option == 0){
                    toastr.success('Para master successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Para master status successfully changed');
            }
        }
    });
});

// Para Code Master Switch Status Change
$(document).on('change', '.paraCodeMasterStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/para-master/view/change-para-code-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Para code master successfully activated');    
                } else if(option == 0){
                    toastr.success('Para code master successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Para code master status successfully changed');
            }
        }
    });
});