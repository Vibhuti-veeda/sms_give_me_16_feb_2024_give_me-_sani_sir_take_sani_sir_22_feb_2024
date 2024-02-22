$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change', '.reasonMasterStatus', function(){
    if(this.checked){
        status = 1;
    } else {
        status = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/reason-master/view/change-reason-master-status",
        method:'POST',
        data:{ status: status, id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(status == 1){
                    toastr.success('Reason master successfully activated');    
                } else if(status == 0){
                    toastr.success('Reason master successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.error('Something Went Wrong!');
            }
        }
    });
});
