$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('.selectStudyDesign').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_design-error').text('');
    }
});

$('.selectActivityName').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#activity_name-error').text('');
    }
});

// Activity Slotting Master Switch Status Change
$(document).on('change', '.activitySlottingStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/sla-activity-master/view/change-sla-activity-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('SLA activity successfully activated');    
                } else if(option == 0){
                    toastr.success('SLA activity successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('SLA activity status successfully changed');
            }
        }
    });
});