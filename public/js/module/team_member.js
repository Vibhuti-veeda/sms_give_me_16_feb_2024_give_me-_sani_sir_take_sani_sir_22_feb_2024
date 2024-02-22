$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Occasion Switch Status Change
$(document).on('change', '.teamMemberStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/team-member/view/change-team-member-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Team member successfully activated');    
                } else if(option == 0){
                    toastr.success('Team member successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Team member status successfully changed');
            }
        }
    });
});


// Remove error message for select location
$(document).on('change', '#location_id', function(){
    var value = $('#location_id').val();
    if(value != ''){
        $('#location_id-error').text('');
    }
});

// filter for pagination
$(document).on('click', '.page-link', function(){
    var pageNo = $(this).data('page');
    $('#page').val(pageNo);
    $('.save_button').trigger('click');
});

$(document).on('change', '#statusId', function(){
    $('#page').val(1);
});

$(document).on('change', '#role', function(){
    $('#page').val(1);
});