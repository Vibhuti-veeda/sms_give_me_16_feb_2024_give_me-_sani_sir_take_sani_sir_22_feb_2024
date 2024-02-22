$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Holiday Master Switch Status Change
$(document).on('change', '.HolidayChangeStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/holiday-master/change-holiday-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Holiday successfully activated');    
                } else if(option == 0){
                    toastr.success('Holiday successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Holiday status successfully changed');
            }
        }
    });
});

// Remove error message for select state
$(document).on('change', '.selectHolidayDate', function(){
    var name = $('.selectHolidayDate').val();
    if(name != ''){
        $('#holiday_date-error').text('');
    }
});
