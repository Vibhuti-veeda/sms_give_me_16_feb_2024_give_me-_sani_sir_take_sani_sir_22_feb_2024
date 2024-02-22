$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Blog Switch Status Change
$(document).on('change', '.sponsorMasterStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/sponsor-master/view/change-sponsor-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Sponsor successfully activated');
                } else if(option == 0){
                    toastr.success('Sponsor successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Sponsor status successfully changed');
            }
        }
    });
});

// Remove Error Message after selecting an option for sponsor type
$('.sponsorType').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#sponsor_type-error').text('');
    }
});