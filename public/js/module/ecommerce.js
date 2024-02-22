$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Ecommerce portal Switch Status Change
$(document).on('change', '.ecommercePortalStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/ecommerce-portal/change-ecommerce-portal-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Ecommerce portal successfully activated');    
                } else if(option == 0){
                    toastr.success('Ecommerce portal successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');
                }
            } else {
                toastr.success('Ecommerce portal status successfully changed');
            }
        }
    });
});