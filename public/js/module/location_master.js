$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change', '.changeLocationMasterStatus', function(){
    var option = $(this).prop('checked') === true ? 1 : 0;
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/location-master/change-location-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Location successfully activated');    
                } else if(option == 0){
                    toastr.success('Location successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Location status successfully changed');
            }
        }
    });
});
