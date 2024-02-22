$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Dashboard view Change
$(document).on('change', '.dashboardView', function(){
    
    var id = $(this).val();
    $.ajax({
        url: "/sms-admin/dashboard/view/change-dashboard-view",
        method:'POST',
        data:{ id: id },
        success: function(data){
            if (id == 'ALL') {
                $('.allView').show();
                $('.personalView').hide();
            } else {
                $('.allView').hide();
                $('.personalView').show();
                $('.personalView').empty().append(data.html);
            }
        }
    });
});