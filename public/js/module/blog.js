$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Blog Switch Status Change
$(document).on('change', '.blogStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/blog/change-blog-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Blog successfully activated');    
                } else if(option == 0){
                    toastr.success('Blog successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Blog status successfully changed');
            }
        }
    });
});

// Page title on blog title keyup function
$(document).on('keyup', '.blogTitle', function(){
    $('#page_title').val($(this).val());
});

// Remove error message for select category
$(document).on('change', '#blog_category', function(){
    var value = $('#blog_category').val();
    if(value != ''){
        $('#blog_category-error').text('');
    }
});

// Remove error message on typing from cke editor
$(document).on('keyup', '.cke_inner', function(){
    $('#details-error').text('');
    $('#details_error').text('');
});

// Answer validation - Add Blog
$(document).on('submit', '#addBlog', function(e){
    e.preventDefault();
    var val = $('#details').val();

    if(val == ''){
        e.preventDefault();
        $('#details_error').text('Please enter blog details');
    } else {
        $('#details-error').text('');
        $('#addBlog')[0].submit();
    }
});

// Answer validation - Edit Blog
$(document).on('submit', '#editBlog', function(e){
    e.preventDefault();
    var val = $('#details').val();

    if(val == ''){
        e.preventDefault();
        $('#details_error').text('Please enter blog details');
    } else {
        $('#details-error').text('');
        $('#editBlog')[0].submit();
    }
});
