$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.isMilestone').on('change',function(){

    if(this.checked){
        $('.milestonePercentage').show();
    } else {
        $('.milestonePercentage').hide();
    }

});

// Activity Master Switch Status Change
$(document).on('change', '.activityStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/activity-master/view/change-activity-master-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Activity successfully activated');    
                } else if(option == 0){
                    toastr.success('Activity successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Something Went Wrong!');
            }
        }
    });
});

// Remove Error Message after selecting an option for responsibility
$('.select_responsibility').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#responsibility-error').text('');
    }
});

$('.activity_days').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#activity_days-error').text('');
    }
});

$('.activity_type').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#activity_type-error').text('');
    }
});

$(document).on('change', '.minimumDays', function(){
    var days = parseInt($('.daysRequired').val());
    var minimum = parseInt($(this).val());

    if(days > 0 && days < minimum){
        $('.minimumDays').val('');
        toastr.error('Please enter minimum days less than days required');
    }
    
});

$(document).on('change', '.maximumDays', function(){
    var days = $('.daysRequired').val();
    var maximum = $(this).val();
      
    if(days < maximum){
        $('.maximumDays').val('');
        toastr.error('Please enter maximum days greater than days required');
    }
    
});