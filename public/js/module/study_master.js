$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// filter with pagination
$(document).on('click', '.page-link', function(){
    var pageNo = $(this).data('page');
   $('#page').val(pageNo);
    $('.save_button').trigger('click');
});

$(document).on('change', '#cr_location', function() {
    $('#page').val(1);
});

$(document).on('change', '#start_date', function() {
    $('#page').val(1);
});

$(document).on('change', '#end_date', function() {
    $('#page').val(1);
});
