$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Drug Master Switch Status Change
$(document).on('change', '.drugMasterStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/drug-master/view/change-drug-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Drug successfully activated');    
                } else if(option == 0){
                    toastr.success('Drug successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');
                }
            } else {
                toastr.success('Drug status successfully changed');
            }
        }
    });
});