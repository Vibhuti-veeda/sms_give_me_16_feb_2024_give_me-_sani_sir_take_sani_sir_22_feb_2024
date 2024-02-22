$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Study Switch Status Change
$(document).on('change', '.studyStatus', function(){
    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }
    var id = $(this).data('id');

    $.ajax({
        url: "/sms-admin/study-master/view/change-study-status",
        method:'POST',
        data:{ option: option,id:$(this).data('id')},
        success: function(data){
            if(data == 'true'){
                if(option == 1){
                    toastr.success('Study successfully activated');    
                } else if(option == 0){
                    toastr.success('Study successfully deactivated');    
                } else {
                    toastr.error('Something Went Wrong!');    
                }
            } else {
                toastr.success('Study status successfully changed');
            }
        }
    });
});

// Remove Error Message after selecting an option for sponsor
$('.select_sponsor').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#sponsor-error').text('');
    }
});

/*$('.select_dosage_form').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#dosage_form-error').text('');
    }
});*/

$('.selectScope').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#scope-error').text('');
    }
});

$('.selectStudyDesign').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_design-error').text('');
    }
});

$('.selectStudySubType').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_sub_type-error').text('');
    }
});

$('.selectSubjectType').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#subject_type-error').text('');
    }
});

$('.selectBlindingStatus').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#blinding_status-error').text('');
    }
});

$('.selectCrLocation').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#cr_location-error').text('');
    }

    /*$.ajax({
        url: "/sms-admin/study-master/view/select-clinical-ward-location",
        method:'POST',
        data:{ id:val},
        success: function(data){
            $('.selectClinicalWordLocation').empty();
            $.each(data, function (key, val) {
                $('.selectClinicalWordLocation').append('<option value="">Select Clinical Word Location</option><option value="'+val.id+'">'+val.ward_name+'</option>')
            });
        }
    });*/
});

/*$('.selectClinicalWordLocation').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#clinical_word_location-error').text('');
    }
});*/

/*$('.selectDosage').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#dosage-error').text('');
    }
});

$('.selectUOM').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#uom-error').text('');
    }
});*/

$('.selectRegulatorySubmission').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#regulatory_submission-error').text('');
    }
});

$('.selectStudyType').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_type-error').text('');
    }
});

$('.selectComplexity').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#complexity-error').text('');
    }
});

$('.selectStudyCondition').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_condition-error').text('');
    }
});

/*$('.selectPriority').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#priority-error').text('');
    }
});*/

$('.selectBrLocation').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#br_location-error').text('');
    }
});

/*$('.study_no_allocation_date').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_no_allocation_date-error').text('');
    }
});

$('.tentative_study_start_date').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#tentative_study_start_date-error').text('');
    }
});

$('.tentative_study_end_date').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#tentative_study_end_date-error').text('');
    }
});

$('.tentative_imp_date').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#tentative_imp_date-error').text('');
    }
});*/

$('.principleInvestigator').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#principle_investigator-error').text('');
    }
});

$('.projectManager').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#project_manager-error').text('');
    }
});

/*$('.bioanalyticalInvestigator').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#bioanalytical_investigator-error').text('');
    }
});

$('.studyResult').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study_result-error').text('');
    }
});*/

$(document).on('click','.addNewDrug',function(){

    var id = $(this).data('id');
    var value = $(this).data('value');

    $.ajax({
        url: "/sms-admin/study-master/view/select-drug-details",
        method:'POST',
        data:{ value:value, id:id },
        success: function(data){
            $('.new_drug_details').append(data.html);
            $('.select2').select2();
            $('input.width').keyup(function() {
                match = (/(\d{0,40})[^.]*((?:\.\d{0,10})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
                this.value = match[1] + match[2];
            });
        }
    });

    //$('.new_drug_details').append(pro);
    id++; value++;
    $(this).data('value',value);
    $(this).data('id',id);
});

$(document).on('click','.remove',function(){ 
    var id = $('#drug_id_'+$(this).data('id')).val();
    $(this).closest('.removeRow').remove();
});

/*$(document).on('change','.select_drug',function(){ 
    
    var value = $('.select_drug').val(); 
    
    if(value != ''){ 
        $(this).css('color', 'blue');
    } else { 
        $(this).css('color', 'black');
    }
});

$(document).on('change','.select_dosage_form',function(){ 
    var value = $('.select_dosage_form').val(); 

    if(value != ''){ 
        $(this).css('color', 'blue');
    } else { 
        $(this).css('color', 'black');
    }
});
*/

$(document).on('change', '.select_drug', function(){ 

    var drugName = $(this).val();

    if(drugName != '') {
        $(this).css('color', 'blue');
        $(this).next('span').hide();
    } else {
        $(this).css('color', 'black');
        $(this).next('span').show();
    }
});

$(document).on('change', '.select_dosage_form', function(){ 

    var dosageFrom = $(this).val();

    if(dosageFrom != ''){ 
        $(this).css('color', 'blue');
        $(this).next('span').hide();
    } else { 
        $(this).css('color', 'black');
        $(this).next('span').show();
    }
});

$(document).on('input', '.dosage', function(){ 

    var dosage = $(this).val().trim();

    if(dosage != ''){ 
        $(this).css('color', 'blue');
        $(this).next('span.dosage_error').hide();
    } else { 
        $(this).css('color', 'black');
        $(this).next('span.dosage_error').show();
    }
});

$(document).on('change', '.selectUOM', function(){ 

    var uom = $(this).val();

    if(uom != ''){ 
        $(this).css('color', 'blue');
        $(this).next('span.select_uom_error').hide();
    } else { 
        $(this).css('color', 'black');
        $(this).next('span.select_uom_error').show();
    }
});

$(document).on('change', '.selectType', function(){ 

    var type = $(this).val();

    if(type != ''){ 
        $(this).css('color', 'blue');
        $(this).next('span.select_type_error').hide();
    } else { 
        $(this).css('color', 'black');
        $(this).next('span.select_type_error').show();
    }
});

$(document).on('input', '.manufacture', function(){ 

    var manufacture = $(this).val().trim();

    if(manufacture != ''){ 
        $(this).css('color', 'blue');
        $(this).next('span.manufacture_error').hide();
    } else { 
        $(this).css('color', 'black');
        $(this).next('span.manufacture_error').show();
    }
});

$(document).on('input', '.remark', function(){ 

    var remark = $(this).val().trim();

    if(remark != ''){ 
        $(this).css('color', 'blue');
        $(this).next('span.remark_error').hide();
    } else { 
        $(this).css('color', 'black');
        $(this).next('span.remark_error').show();
    }
});

$(document).on('change','.studyResult',function(){
    var id = $(this).data('id');
    var result = $(this).val();
    
    $.ajax({
        url: "/sms-admin/study-master/view/study-result",
        method:'POST',
        data:{ id:id, result:result },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study result successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
    });
});

$(document).on('change','.studyHoldStatus',function(){
    var id = $(this).data('id');
    var status = $(this).val();
    
    $.ajax({
        url: "/sms-admin/study-master/view/study-status",
        method:'POST',
        data:{ id:id, status:status },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study status successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
    });
});

$(document).on('change','.studyProjected',function(){
    var id = $(this).data('id');
    var projected = $(this).val();
    
    $.ajax({
        url: "/sms-admin/study-master/view/study-projected",
        method:'POST',
        data:{ id:id, projected:projected },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study slotted successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
    });
});

$(document).on('change','.tentativeClinicalDate',function(){
    var id = $(this).data('id');
    var date = $(this).val();
    
    $.ajax({
        url: "/sms-admin/study-master/view/study-tentative-clinical-date",
        method:'POST',
        data:{ id:id, date:date },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study tentative clinical date successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
    });
});

$(document).on('click','.deleteBtn',function(e){
    e.preventDefault();
    var delete_id = $(this).val();
    $('#study_id').val(delete_id);
    $('#openDeleteStudyModal').modal('show');
});

$("#addProject").submit(function(e){

    var totalSubjects = parseInt($('.totalSubject').val());
    var femaleSubjects = parseInt($('.femaleSubject').val());
    var maleSubjects = parseInt($('.maleSubject').val());
    var maleFemale = maleSubjects + femaleSubjects;
    var isFormValid = true;

    if(totalSubjects == maleFemale){
    } else {
        $('.maleSubject').val('');
        $('.femaleSubject').val('');
        toastr.error('Please enter correct male & female subjects');
    }
    
    $('.select_drug').each(function(){
        if($(this).val() != '') {
            $(this).next('span.select_drug_error').hide();
        } else {
            isFormValid = false;
            $(this).next('span.select_drug_error').show();
        }
    });

    $('.select_dosage_form').each(function(){
        if($(this).val() != '') {
            $(this).next('span.select_dosage_form_error').hide();
        } else { 
            isFormValid = false;
            $(this).next('span.select_dosage_form_error').show();
        }
    });

    $('.dosage').each(function(){
        if($(this).val().trim() != '') {
            $(this).next('span.dosage_error').hide();
        } else { 
            isFormValid = false;
            $(this).next('span.dosage_error').show();
        }
    });

    $('.selectUOM').each(function(){
        if($(this).val() != '') {
            $(this).next('span.select_uom_error').hide();
        } else { 
            isFormValid = false;
            $(this).next('span.select_uom_error').show();
        }
    });

    $('.selectType').each(function(){
        if($(this).val() != '') {
            $(this).next('span.select_type_error').hide();
        } else {
            isFormValid = false;
            $(this).next('span.select_type_error').show();
        }
    });

    $('.manufacture').each(function(){
        if($(this).val().trim() != '') {
            $(this).next('span.manufacture_error').hide();
        } else {
            isFormValid = false;
            $(this).next('span.manufacture_error').show();
        }
    });

    if(!isFormValid) {
        e.preventDefault();
        $(window).scrollTop(0);
    }
});

$(document).on('change','.projectionStatus',function(){
    var studyId = $(this).data('id');
    var status = $(this).val();

    $.ajax({
        url: "/sms-admin/study-master/view/pre-study-projection-status",
        method:'POST',
        data:{ study_id:studyId, projection_status:status },
        success: function(data){
            if(data == 'true'){
                toastr.success('Pre study status successfully updated');
            } else {
                toastr.success('Something went wrong');
            }
        }
    });
});

$(document).on('click', '.page-link', function(){
    var pageNo = $(this).data('page');
    $('#page').val(pageNo);
    $('.save_button').trigger('click');
});

$(document).on('change', '#study_no', function(){
    $('#page').val(1);
});

$(document).on('change', '#project_manager', function(){
    $('#page').val(1);
});

$(document).on('change', '#drug_name', function(){
    $('#page').val(1);
});

$(document).on('change', '#dosage_form_id', function(){
    $('#page').val(1);
});

$(document).on('change', '#uom_id', function(){
    $('#page').val(1);
});

$(document).on('change', '#drug_type', function(){
    $('#page').val(1);
});

$(document).on('change', '#complexity', function(){
    $('#page').val(1);
});

$(document).on('change', '#study_design', function(){
    $('#page').val(1);
});

$(document).on('change', '#study_sub_type', function(){
    $('#page').val(1);
});

$(document).on('change', '#study_type', function(){
    $('#page').val(1);
});

$(document).on('change', '#study_condition', function(){
    $('#page').val(1);
});

$(document).on('change', '#subject_type', function(){
    $('#page').val(1);
});

$(document).on('change', '#priority', function(){
    $('#page').val(1);
});

$(document).on('change', '#blinding_status', function(){
    $('#page').val(1);
});

$(document).on('change', '#regulatory_submission', function(){
    $('#page').val(1);
});

$(document).on('change', '#sponsor_id', function(){
    $('#page').val(1);
});

$(document).on('change', '#cr_location', function(){
    $('#page').val(1);
});

$(document).on('change', '#br_location', function(){
    $('#page').val(1);
});

$(document).on('change', '#scope', function(){
    $('#page').val(1);
});

$(document).on('change', '#principle_investigator', function(){
    $('#page').val(1);
});

$(document).on('change', '#bioanalytical_investigator', function(){
    $('#page').val(1);
});

$(document).on('change', '#special_notes', function(){
    $('#page').val(1);
});

$(document).on('change', '#no_of_subject', function(){
    $('#page').val(1);
});

$(document).on('change', '#study_result', function(){
    $('#page').val(1);
});

$(document).on('change', '#study_status', function(){
    $('#page').val(1);
});

$(document).on('change', '#sponsor_study_no', function(){
    $('#page').val(1);
});

$(document).on('change', '#clinical_ward_location', function(){
    $('#page').val(1);
});

$(document).on('change', '#total_group', function(){
    $('#page').val(1);
});

$(document).on('change', '#no_of_periods', function(){
    $('#page').val(1);
});

$(document).on('change', '#pre_housing', function(){
    $('#page').val(1);
});

$(document).on('change', '#post_housing', function(){
    $('#page').val(1);
})