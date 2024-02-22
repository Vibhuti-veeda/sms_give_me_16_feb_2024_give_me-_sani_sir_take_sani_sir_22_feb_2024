$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {

    // Added statically width to the select2 container to do not lose their width 
    $('.select2-container').css('width', '100%');

    // Remove error message on selection of activity name
    $(document).on('change', '#activity_id', function () {

        var activityId = $('#activity_id').val();

        if (activityId != '') {
            $('#activity_id-error').text('');
        }
    });

    // Add row section when control is radio, option, and multiselect like checkbox
    $(document).on('change', '#control_id', function (e) {

        var controlId = $('#control_id').val();
        var controlType = $(this).find(':selected').attr('data-control-type');

        if (controlId != '') {

            $('#control_id-error').text('');

            var input = '<div class="row mx-2">' +
                            '<div class="col-10">' +
                                '<div class="form-group">' +
                                    "<input type='text' class='form-control noValidate sourceValue' name='source_value[]' placeholder='Enter source value' maxlength='100' required>" +
                                '</div>' +
                                '<span class="message" style="color: red; display: none;">Please enter source value</span>' +
                            '</div>' +
                            '<div class="col-1">' +
                                '<div class="form-group" style="margin-top: 5px;">' +
                                    '<button class="btn btn-primary btn-sm addNew">+</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

            var input2 = '<div class="row mx-2">' +
                            '<div class="col-10 pb-2">' +
                                '<div class="form-group">' +
                                    "<input type='text' class='form-control noValidate sourceValue' name='source_value[]' placeholder='Enter source value' maxlength='100' required>" +
                                '</div>' +
                                '<span class="message" style="color: red; display: none;">Please enter source value</span>' +
                            '</div>' +
                            '<div class="col-1">' +
                                '<div class="form-group" style="margin-top: 5px;">' +
                                    '<button class="btn btn-primary btn-sm addNew">+</button>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-10">' +
                                '<div class="form-group">' +
                                    "<input type='text' class='form-control noValidate sourceValue' name='source_value[]' placeholder='Enter source value' maxlength='100' required>" +
                                '</div>' +
                                '<span class="message" style="color: red; display: none;">Please enter source value</span>' +
                            '</div>' +
                        '</div>';

            if(controlType == 'select'){
                $('.hideColumn').css('display', 'block');
                $('.newInputs').html(input);             
            } else if(controlType == 'radio' || controlType == 'checkbox') {
                $('.hideColumn').css('display', 'block');
                $('.newInputs').html(input2);
            } else {
                $('.hideColumn').css('display', 'none');
                $('.newInputs').empty();
            }
        } else {
            $('.hideColumn').css('display', 'none');
            $('.newInputs').empty();
        }
 
    });

    // Add new input form
    $(document).on('click', '.addNew', function(e){

        e.preventDefault();
        
        var newInput = '<div class="row mx-2">' +
                            '<div class="col-10">' +
                                '<div class="form-group" style="margin-top: 5px;">' +
                                    "<input type='text' class='form-control noValidate sourceValue' name='source_value[]' placeholder='Enter source value' maxlength='100' required>" +
                                '</div>' +
                                '<span class="message" style="color: red; display: none;">Please enter source value</span>' +
                            '</div>' +
                            '<div class="col-1">' +
                                '<div class="form-group" style="margin-top: 10px;">' +
                                    "<button class='btn btn-danger btn-sm remove'>X</button>" +
                                '</div>' +
                            '</div>' +
                        '</div>';
        
        $('.newInputs').append(newInput);
    });

    // Remove specific newly added input
    $(document).on('click', '.remove', function(e){

        e.preventDefault();
        $(this).closest('.row').remove();
    });

    // Remove error message for is_activity selection
    $(document).on('change', '#is_activity', function () {

        var activityType = $('#is_activity').val();

        if (activityType != '') {
            $('#is_activity-error').text('');
        }
    });

    // Remove error message for input_validation selection
    $(document).on('change', '#input_validation', function () {

        var validationName = $('#input_validation').val();

        if (validationName != '') {
            $('#input_validation-error').text('');
        }
    });

    // Check all inputs are filled or not and submit form
    $(document).on('click', '#btn_submit', function(e) {

        var isAllValid = false;
        var isFormValid = false;

        if($('#addActivityMetadata').valid()){
            isFormValid = true;
        }

        if($('input').hasClass('sourceValue')){

            $('input.sourceValue').each(function(){

                if($(this).val() === ''){
    
                    $(this).parent().next("span").show();
                    isAllValid = false;
                    isFormValid = false;
                } else {
    
                    $(this).parent().next("span").hide();
                    isAllValid = true;
                }
            });
        }

        if(isFormValid){
            $('#addActivityMetadata').submit();
        } else {
            $('#addActivityMetadata').validate();
            e.preventDefault();
        }
    });
    
    // Hide and show each input validation error
    $(document).on('input', '.sourceValue', function(e){

        var value = $(this).val();

        if(value != ''){
            $(this).parent().next("span").hide();
        } else {
            $(this).parent().next("span").show();
        }
    });

    // Change status of activity metadata
    $(document).on('change', '.activityMetadataStatus', function(){

        if(this.checked){
            option = 1;
        } else {
            option = 0;
        }
        var id = $(this).data('id');
    
        $.ajax({
            url: "/sms-admin/activity-metadata/view/change-activity-metadata-status",
            method:'POST',
            data:{ option: option,id:$(this).data('id') },
            success: function(data){
                if(data == 'true'){
                    if(option == 1){
                        toastr.success('Activity metadata successfully activated');
                    } else if(option == 0){
                        toastr.success('Activity metadata successfully deactivated');
                    } else {
                        toastr.error('Something Went Wrong!');
                    }
                } else {
                    toastr.success('Activity metadata status successfully changed');
                }
            }
        });
    });

});

// Show and close accordian filter
$(document).ready(function () {
    $('.accordion-button').on('click', function(event){
        event.preventDefault();
        
        // toggle accordion link show class
        $(this).next('#activityMetadataCollapseFilter').toggleClass("show");
    });
});
