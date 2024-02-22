$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var optionName = [];

$(document).ready(function(){
    getOptionValue();
});

//Add input box for multi-select options
$('.attribute_type').on('change',function(){
	var type = $(this).val();
    if(type != ''){
        $('#attribute_type-error').text('');
    }
	if(type == 'multi-select' || type == 'select'){
        $('.boolean_card').hide();
        $('.boolean_card_exist').hide();
        $('.select_card_exist').hide();
		$('.card2_add').empty();
        $('.displayFilter').empty();
        $('.card3_add').empty();
        $('.card2_add').append('<div id="row0" class="options row" data-id="0"><div class="col-lg-11 col-11 mb-3"><div class="form-group"><input type="text" class="form-control multiSelect" data-id="0" name="multiple[0]" data-msg="Please enter the option" placeholder="Enter option" autocomplete="off" required/></div></div><div class="col-lg-1 col-1"><div class="form-group remove0"></div></div></div>');
        $('.displayFilter').append('<label>Display as Filter?</label><div class="form-check form-switch form-switch-md mb-3" dir="ltr"><input class="form-check-input" type="checkbox" id="customSwitch" name="is_display" ><label class="form-check-label" for="customSwitch"></label></div>');
        $('.select_card').show();
        select.push(0);
    } else if(type == 'boolean'){
        $('.select_card').hide();
        $('.select_card_exist').hide();
        $('.boolean_card_exist').hide();
        $('.card3_add').empty();
        $('.card2_add').empty();
        $('.displayFilter').empty();
        $('.card3_add').append('<div id="row0" class="options row" data-id="0"><div class="col-md-6 col-6"><div class="form-group"><label for="formrow-text-input">Value 1</label><input type="text" class="form-control multiSelect" data-id="0" id="value1" name="multiple[]" data-msg="Please enter the value" required autocomplete="off"></div></div><div class="col-md-6 col-6"><div class="form-group"><label for="formrow-text-input">Value 2</label><input type="text" class="form-control multiSelect" data-id="0" id="value2" name="multiple[]" data-msg="Please enter the value" required autocomplete="off" ></div></div></div>');
        $('.boolean_card').show();
    } else {
        $('.card2_add').empty();
    	$('.card3_add').empty();
    	$('.select_card').hide();
        $('.boolean_card').hide();
        $('.select_card_exist').empty();
        $('.boolean_card_exist').empty();
        $('.select_card_exist').hide();
        $('.boolean_card_exist').hide();
        $('.displayFilter').empty();
    }
});

// Remove Error Message after selecting an option for Is Required
$('.is-required').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#is_required-error').text('');
    }
});

//Remove Error Message after selecting an option for Input Validation
$('.input-validation').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#input_validation-error').text('');
    }
});

//Add Options from + button
var i = $('#i').val();
if(i == undefined){
    var i = 1;
}
var select = [];
$(document).ready(function(){
	$(".options").each(function(){
	    select.push($(this).data('id'));
	});
});

// Add option button click event
$(document).on('click','.add_option_button',function(e){
	e.preventDefault();
	              
	var html = '<div id="row'+i+'" class="options row" data-id="'+i+'"><div class="col-lg-11 col-11 mb-3"><div class="form-group"><input type="text" class="form-control multiSelect" data-id="'+i+'" name="multiple['+i+']" data-msg="Please enter the option" placeholder="Enter option" autocomplete="off" value="" required/></div></div><div class="col-lg-1 col-1"><div class="form-group remove'+i+'"><a style="float:right;" href="javascript:void(0);" class="btn btn-danger remove_option_field" id="'+i+'">&#9747</a></div></div></div>';
	$(".card2_add").append(html);
	i++;
});



// Attribute Switch Status Change
$(document).on('change', '.attributeStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');
    $.ajax({
        url: "/sms-admin/attribute/change-attribute-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Attribute successfully activated');    
                } else if(option == 0){
                    toastr.success('Attribute successfully deactivated');    
                } else {
                    toastr.success('Something Went Wrong!');    
                }
            } else {
                toastr.success('Attribute status successfully changed');
            }
        }
    });
});

function getOptionValue(){
    optionName = [];
    $(".multiSelect").each(function(){
        optionName.push($(this).val());
    });
}

$(document).on('focusout','.multiSelect',function(){
    var name = $(this).val();
    var id = $(this).data('id');
    if($.inArray(name, optionName) == -1){
        getOptionValue();
    } else {
        $("div").slice(id).val('');
        $(this).val('');
        toastr['error']('Input value already inserted');
    }
});

//Remove option
$(document).on("click",".remove_option_field", function(e){ 
    e.preventDefault(); 
    var button_id = $(this).attr("id");
    $('#row'+button_id+'').remove(); 
    getOptionValue();
});