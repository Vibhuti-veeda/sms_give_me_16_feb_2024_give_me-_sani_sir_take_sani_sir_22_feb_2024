$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// City Switch Status Change
$(document).on('change', '.cityStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/city/change-city-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('City successfully activated');    
                } else if(option == 0){
                    toastr.success('City successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');
                }
            } else {
                toastr.success('City status successfully changed');
            }
        }
    });
});

// Remove error message for select state
$(document).on('change', '.selectState', function(){
    var name = $('.selectState').val();
    if(name != ''){
        $('#state-error').text('');
    }
});

// City area add modal ajax call
$(document).on('click','#addCityAreaModal',function(){
    var id = $(this).data('id');
    $.ajax({
        url:'/sms-admin/city/add-city-area',
        method:'post',              
        data:{
            id:id,
        },
        success:function(data)
        {
            $('#openCityAreaModal').modal('show');
            $('#showCityAreaModal').html(data);

            $(document).on("input", ".numeric", function() {
                this.value = this.value.replace(/\D/g,'');  
            });

            $("#addCityArea").validate({
                errorElement: 'span',
                rules: {
                    name: {
                        required: true,
                    },
                    pincode: {
                        required: true,
                        minlength: 6,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter area",
                    },
                    pincode: {
                        required: "Please enter pincode",
                        minlength: "Please enter minium 6 digit pincode",
                    }
                },
            });
        },
    });
});

// City area edit modal ajax call
$(document).on('click','#editCityAreaModal',function(){
    var id = $(this).data('id');
    var cityId = $(this).data('city');

    $.ajax({
        url:'/sms-admin/city/edit-city-area',
        method:'post',              
        data:{
            id:id,
            cityId:cityId,
        },
        success:function(data)
        {
            $('#openEditCityAreaModal').modal('show');
            $('#showEditCityAreaModal').html(data);

            $(document).on("input", ".numeric", function() {
                this.value = this.value.replace(/\D/g,'');  
            });

            $("#editCityArea").validate({
                errorElement: 'span',
                rules: {
                    name: {
                        required: true,
                    },
                    pincode: {
                        required: true,
                        minlength: 6,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter area",
                    },
                    pincode: {
                        required: "Please enter pincode",
                        minlength: "Please enter minium 6 digit pincode",
                    }
                },
            });
        },
    });
});

// City area Switch Status Change
$(document).on('change', '.cityAreaStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/city/change-city-area-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('City area successfully activated');    
                } else if(option == 0){
                    toastr.success('City area successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');
                }
            } else {
                toastr.success('City area status successfully changed');
            }
        }
    });
});