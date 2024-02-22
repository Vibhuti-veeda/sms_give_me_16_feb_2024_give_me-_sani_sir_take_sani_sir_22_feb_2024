$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //custom validation method
    $.validator.addMethod("customemail", 
        function(value, element) {
            if(value == ""){
                return true;
            } else {
                return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);    
            }
            
        }, 
        "Please enter email id along with domain name."
    );

    $(document).on("input", ".numeric", function() {
        this.value = this.value.replace(/\D/g,'');  
    });

    $('input.width').keyup(function() {

        match = (/(\d{0,40})[^.]*((?:\.\d{0,2})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
        this.value = match[1] + match[2];
    });

    jQuery.validator.addMethod('ckrequired', function (value, element, params) {
        var idname = jQuery(element).attr('id');
        var messageLength =  jQuery.trim ( CKEDITOR.instances[idname].getData() );
        return !params  || messageLength.length !== 0;
    }, "Image field is required");

    $.validator.addMethod("greaterThan", 
        function (value, element, param) {
            var $otherElement = $(param);
            return parseInt(value, 10) > parseInt($otherElement.val(), 10);
        }, 
    );

    $.validator.addMethod(
        "regexGst",
        function(value, element, regexp) {
            var gstinformat = new RegExp('^[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}Z[0-9A-Za-z]{1}$');
            if (gstinformat.test(value)) {
              return true;
            } else {
                return false;
            }
        },
        "Please add valid GSTIN"
    );

    $.validator.addMethod(
        "regexIfsc",
        function(value, element, regexp) {
            var ifscformat = new RegExp('^[A-Za-z]{4}[0][A-Za-z0-9]{6}$');
            if (ifscformat.test(value)) {
              return true;
            } else {
                return false;
            }
        },
        "Please add valid IFSC code"
    );

    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var passwordformat = new RegExp('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$');
            if (passwordformat.test(value)) {
              return true;
            } else {
                return false;
            }
        },
        "Please add valid password"
    );

    jQuery.validator.addMethod('COMP_WORD', function (value, element, param) { 
        var string = $('#post_title').val();
        return string.indexOf(value) !== -1 ? true : false;
    }, "Enter the highlighted string which is a part of the Post Title string.");

    $("#loginForm").validate({
        errorElement : 'div',
        rules: {
            email: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8
            },
        },
        messages: {
            email: {
                required: 'Please enter user name',
            },
            password: {
                required: 'Please enter password',
                minlength: 'Password must have at least 8 characters'
            },
        }
    });

    $("#forgotPassword").validate({
        errorElement : 'div',
        rules: {
            email: {
                required: true,
                customemail:true
            },
        },
        messages: {
            email: {
                required: 'Please enter email',
            },
        }
    });

    $("#resetPassword").validate({
        errorElement : 'div',
        rules: {
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation : {
                required: true,
                minlength: 8,
                equalTo: "#password"
            }

        },
        messages: {
            password: {
                required: "Enter new password",
                minlength: "Your password must be at least 8 characters long",
            },
            password_confirmation : {
                required: "Enter confirm password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Confirm password does not match with new password"
            }
        }
    });
    
    $("#changePassword").validate({
        errorElement : 'div',
        rules: {
            old_password: {
                required: true,
                minlength: 8
            },
            new_password: {
                required: true,
                minlength: 8
            },
            confirm_password : {
                required: true,
                minlength: 8,
                equalTo: "#new_password"
            }

        },
        messages: {
            old_password: {
                required: "Please enter current password",
            },
            new_password:{
                required: "Please enter new password",
                minlength: "Your password must be at least 8 characters long",
            },
            confirm_password:{
                required: "Please enter confirm new password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Confirm password does not match with new password"
            }
        }
    });
   
    // Update profile form 
    $("#adminProfile").validate({
        errorElement: 'span',
        rules: {
            name: {
                required: true,
            },
            mobile_number: {
                required: true,
                minlength: 10,
                maxlength: 10
            },
            email: {
                required: true,
                email: true,
                customemail : true,
            },
        },
        messages: {
            name: {
                required: 'Please enter name',
            },
            mobile_number: {
                required: 'Please enter mobile number',
                minlength:"Please enter 10 digit mobile number",
                maxlength:"Please enter 10 digit mobile number",
            },
            email: {
                required: 'Please enter email id',
                email: 'Please enter valid email id',
            },
        }
    });
    
    // Add team member form validation
    $("#addTeamMember").validate({
        errorElement: 'span',
        rules: {
            full_name: {
               required: true,
            },
            /*role_id: {
                required: true,
            },
            location_id: {
                required: true,
            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10
            },*/
            email: {
                required: true,
                email: true,
                customemail : true,
                remote: {
                    data:{'id' : $("#id").val()},
                    url: "/sms-admin/team-member/view/check-team-member-email-exists",
                    method: "post"
                },
            },
            login_id: {
                required: true,
            },
            employee_code: {
                required: true,
            },
            department: {
                required: true,
            },
            department_no: {
                required: true,
            },
            designation: {
                required: true,
            },
            designation_no: {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'location_id'){ 
                error.insertAfter('#location');
            } else {
                error.insertAfter( element );
            }
        },
        messages: {
            full_name: {
                required: 'Please enter full name',
            },
            /*role_id: {
                required: 'Please select role',
            },
            location_id: {
                required: 'Please select location'
            },
            mobile: {
                required: 'Please enter mobile number',
                minlength:"Please enter 10 digit mobile number",
                maxlength:"Please enter 10 digit mobile number",
            },*/
            email: {
                required: 'Please enter email id',
                email: 'Please enter valid email id',
                remote:'Email id already exists'
            },
            login_id: {
                required: "Please enter login id"
            },
            employee_code: {
                required: "Please enter employee code"
            },
            department: {
                required: "Please enter department"
            },
            department_no: {
                required: "Please enetr department no"
            },
            designation: {
                required: "Please enter designation"
            },
            designation_no: {
                required: "Please enter designation no"
            },
        }
    });

    // Edit team member form validation
    $("#editTeamMember").validate({
        errorElement: 'span',
        rules: {
            full_name: {
                required: true,
            },
            /*role_id: {
                required: true,
            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10
            },
            location_id: {
                required: true,
            },*/
            email: {
                required: true,
                email: true,
                customemail : true,
                remote: {
                    data:{'id' : $("#id").val()},
                    url: "/sms-admin/team-member/view/check-team-member-email-exists",
                    method: "post"
                },
            },
            login_id: {
                required: true,
            },
            employee_code: {
                required: true,
            },
            department: {
                required: true,
            },
            department_no: {
                required: true,
            },
            designation: {
                required: true,
            },
            designation_no: {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'location_id'){ 
                error.insertAfter('#location');
            } else {
                error.insertAfter( element );
            }
        },
        messages: {
            full_name: {
                required: 'Please enter full name',
            },
            /*role_id: {
                required: 'Please select role',
            },
            mobile: {
                required: 'Please enter mobile number',
                minlength:"Please enter 10 digit mobile number",
                maxlength:"Please enter 10 digit mobile number",
            },
            location_id: {
                required: 'Please select location'
            },*/
            email: {
                required: 'Please enter email id',
                email: 'Please enter valid email id',
                remote:'Email id already exists'
            },
            login_id: {
                required: "Please enter login id"
            },
            employee_code: {
                required: "Please enter employee code"
            },
            department: {
                required: "Please enter department"
            },
            department_no: {
                required: "Please enetr department no"
            },
            designation: {
                required: "Please enter designation"
            },
            designation_no: {
                required: "Please enter designation no"
            },
        }
    });

    // Add role form validation
    $("#addRole").validate({
        errorElement: 'span',
        rules: {
            role_name: {
                required: true,
                remote: {
                    data:{'id' : $("#id").val()},
                    url: "/sms-admin/role/view/check-role-exists",
                    method: "post"
                },
            },
            'role_modules[]': {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'role_modules[]'){ 
                error.insertAfter('#role_modules_error');
            } else {
                error.insertAfter( element );
            }
        },
        messages: {
            role_name: {
                required: 'Please enter role',
                remote:'Role already exists'
            },
            'role_modules[]':{
                required: 'Please select module',
            },
        }
    });

    // Add/Edit activity form validation
    $("#addActivity").validate({
        errorElement: 'span',
        rules: {
            activity_name: {
                required: true,
            },
            days_required: {
                required: true,
            },
            minimum_days_allowed: {
                required: true,
            },
            maximum_days_allowed: {
                required: true,
            },
            buffer_days: {
                required: true,
            },
            responsibility: {
                required: true,
            },
            activity_days: {
                required: true,
            },
            activity_type: {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'responsibility'){ 
                error.insertAfter('#selectResponsibility');
            } else if (element.attr("name") == 'activity_days'){
                error.insertAfter('#activityDays');
            } else if (element.attr("name") == 'activity_type'){
                error.insertAfter('#activityType');
            } else {
                error.insertAfter( element );
            }
        },
        messages: {
            activity_name: {
                required: 'Please enter activity name',
            },
            days_required:{
                required: 'Please enter days required',
            },
            minimum_days_allowed: {
                required: "Please enter minimum days allowed",
            },
            maximum_days_allowed: {
                required: "Please enter maximum days allowed"
            },
            buffer_days: {
                required: "Please enter buffer days",
            },
            responsibility: {
                required: "Please select responsibility",
            },
            activity_days: {
                required: "Please select activity days type",
            },
            activity_type: {
                required: "Please select activity type",
            },
        }
    });

    // Add study form validation
    $("#addProject").validate({
        ignore: '.noValidate',
        errorElement: 'span',
        rules: {
            sponsor: {
                required: true,
            },
            dosage_form: {
                required: true,
            },
            /*study_text: {
                required: true,
            },*/
            'scope[]': {
                required: true,
            },
            study_design: {
                required: true,
            },
            study_sub_type: {
                required: true,
            },
            subject_type: {
                required: true,
            },
            blinding_status: {
                required: true,
            },
            no_of_subject: {
                required: true,
            },
            no_of_male_subjects: {
                required: true,
            },
            no_of_female_subjects: {
                required: true,
            },
            /*washout_period: {
                required: true,
            },*/
            cr_location: {
                required: true,
            },
            /*clinical_word_location: {
                required: true,
            },
            additional_requirement: {
                required: true,
            },*/
            quotation_amount: {
                required: true,
            },
            study_no: {
                required: true,
                remote: {
                    data:{'id' : $("#id").val()},
                    url: "/sms-admin/study-master/view/check-study-no-exist",
                    method: "post",
                },                
            },
            sponsor_study_no: {
                required: true,
            },
            drug: {
                required: true,
            },
            dosage: {
                required: true,
            },
            uom: {
                required: true,
            },
            'regulatory_submission[]': {
                required: true,
            },
            study_type: {
                required: true,
            },
            complexity: {
                required: true,
            },
            study_condition: {
                required: true,
            },
            /*priority: {
                required: true,
            },*/
            no_of_groups: {
                required: true,
            },
            no_of_periods: {
                required: true,
            },
            /*total_housing: {
                required: true,
            },*/
            /*pre_housing: {
                required: true,
            },
            post_housing: {
                required: true,
            },*/
            br_location: {
                required: true,
            },
            /*study_no_allocation_date: {
                required: true,
            },
            tentative_study_start_date: {
                required: true,
            },
            tentative_study_end_date: {
                required: true,
            },
            tentative_imp_date: {
                required: true,
            },*/
            project_manager: {
                required: true,
            },
            principle_investigator: {
                required: true,
            },
            /*bioanalytical_investigator: {
                required: true,
            },*/
            /*study_result: {
                required: true,
            },*/
            /*total_sponsor_queries: {
                required: true,
            },
            open_sponsor_queries: {
                required: true,
            },*/
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'sponsor'){ 
                error.insertAfter('#selectSponsor');
            } else if(element.attr("name") == 'dosage_form'){ 
                error.insertAfter('#selectDosageForm');
            } else if(element.attr("name") == 'scope[]'){ 
                error.insertAfter('#selectScope');
            } else if(element.attr("name") == 'study_design'){ 
                error.insertAfter('#selectStudyDesign');
            } else if(element.attr("name") == 'study_sub_type'){ 
                error.insertAfter('#selectStudySubType');
            } else if(element.attr("name") == 'subject_type'){ 
                error.insertAfter('#selectSubjectType');
            } else if(element.attr("name") == 'blinding_status'){ 
                error.insertAfter('#selectBlindingStatus');
            } else if(element.attr("name") == 'cr_location'){ 
                error.insertAfter('#selectCrLocation');
            /*} else if(element.attr("name") == 'clinical_word_location'){ 
                error.insertAfter('#selectClinicalWordLocation');*/
            } else if(element.attr("name") == 'dosage'){ 
                error.insertAfter('#selectDosage');
            } else if(element.attr("name") == 'uom'){ 
                error.insertAfter('#selectUOM');
            } else if(element.attr("name") == 'regulatory_submission[]'){ 
                error.insertAfter('#selectRegulatorySubmission');
            }  else if(element.attr("name") == 'study_type'){ 
                error.insertAfter('#selectStudyType');
            }  else if(element.attr("name") == 'complexity'){ 
                error.insertAfter('#selectComplexity');
            }  else if(element.attr("name") == 'study_condition'){ 
                error.insertAfter('#selectStudyCondition');
            /*}  else if(element.attr("name") == 'priority'){ 
                error.insertAfter('#selectPriority');*/
            }  else if(element.attr("name") == 'br_location'){ 
                error.insertAfter('#selectBrLocation');
            }  else if(element.attr("name") == 'project_manager'){ 
                error.insertAfter('#projectManager');
            }  else if(element.attr("name") == 'principle_investigator'){ 
                error.insertAfter('#selectPrinciple');
            /*}  else if(element.attr("name") == 'bioanalytical_investigator'){ 
                error.insertAfter('#selectBioanalytical');
            }  else if(element.attr("name") == 'study_result'){ 
                error.insertAfter('#studyResult');*/
            }  else {
                error.insertAfter( element );
            }
        },
        messages: {
            sponsor: {
                required: 'Please select sponsor',
            },
            dosage_form:{
                required: 'Please select dosage form',
            },
            /*study_text: {
                required: "Please enter study text",
            },*/
            'scope[]': {
                required: "Please select scope",
            },
            study_design: {
                required: "Please select study design",
            },
            study_sub_type: {
                required: "Please select study sub type",
            },
            subject_type: {
                required: "Please select subject type",
            },
            blinding_status: {
                required: "Please select blinding status",
            },
            no_of_subject: {
                required: "Please enter no of subject",
            },
            no_of_male_subjects: {
                required: "Please enter no of male subjects",
            },
            no_of_female_subjects: {
                required: "Please enter no of female subjects",
            },
            /*washout_period: {
                required: "Please enter washout period",
            },*/
            cr_location: {
                required: "Please select cr location",
            },
            /*clinical_word_location: {
                required: "Please select clinical word location"
            },
            additional_requirement: {
                required: "Please enter additional requirement",
            },*/
            quotation_amount: {
                required: "Please enter quotation amount",
            },
            study_no: {
                required: "Please enter study no",
                remote: "Study no already exists",
            },
            sponsor_study_no: {
                required: "Please enter sponsor study no"
            },
            drug: {
                required: "Please enter drug"
            },
            dosage: {
                required: "Please select dosage"
            },
            uom: {
                required: "Please select uom"
            },
            'regulatory_submission[]': {
                required: "Please select regulatory submission"
            },
            study_type: {
                required: "Please select study type",
            },
            complexity: {
                required: "Please select complexity",
            },
            study_condition: {
                required: "Please select study condition",
            },
            /*priority: {
                required: "Please select priority",
            },*/
            no_of_groups: {
                required: "Please enter no of groups"
            },
            no_of_periods: {
                required: "Please enter no of periods",
            },
            /*total_housing: {
                required: "Please enter total housing",
            },*/
            /*pre_housing: {
                required: "Please enter pre housing"
            },
            post_housing: {
                required: "Please enter post housing"
            },*/
            br_location: {
                required: "Please select br location"
            },
            /*study_no_allocation_date: {
                required: "Please select study no allocation date"
            },
            tentative_study_start_date: {
                required: "Please select tentative study start date"
            },
            tentative_study_end_date: {
                required: "Please select tentative study end date"
            },
            tentative_imp_date: {
                required: "Please select tentative imp date"
            },*/
            project_manager: {
                required: "Please select project manager"
            },
            principle_investigator: {
                required: "Please select principle investigator"
            },
            /*bioanalytical_investigator: {
                required: "Please select bioanalytical investigator"
            },*/
            /*study_result: {
                required: "Please select study result"
            },*/
            /*total_sponsor_queries: {
                required: "Please enter total sponsor queries"
            },
            open_sponsor_queries: {
                required: "Please enter open sponsor queries"
            },*/
        }
    });


    $("#addStudySchedule").validate({
        errorElement: 'span',
        rules: {
            study: {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            if(element.attr("name") == 'study'){ 
                error.insertAfter('#selectStudy');
            } else {
                error.insertAfter( element );
            }
        },
        messages: {
            study: {
                required: 'Please select study',
            },
        }
    });

    // Add study schedule activity status form validation
    $("#addStudyScheduleActivityStatus").validate({
        errorElement: 'span',
        rules: {
            actual_start_date: {
                required: true,
            },
            /*actual_end_date: {
                required: true,
            },*/
        },
        messages: {
            actual_start_date: {
                required: 'Please enter actual start date',
            },
            /*actual_end_date:{
                required: 'Please enter actual end date',
            },*/
        }
    });

    

    // Add & edit drug form validation
    $("#addDrug").validate({
        errorElement: 'span',
        rules: {
            drug_name: {
                required: true,
            },
            drug_type: {
                required: true,
            },
        },
        messages: {
            drug_name: {
                required: 'Please enter drug name',
            },
            drug_type:{
                required: 'Please enter drug type',
            },
        }
    });

    // Add & edit Para master form validation
    $("#addParaMaster").validate({
        errorElement: 'span',
        rules: {
            para_code: {
                required: true,
            },
            para_description: {
                required: true,
            },
        },
        messages: {
            para_code: {
                required: 'Please enter para value',
            },
            para_description: {
                required: 'Please enter para description'
            },
        }
    });

    // Add & edit para code master form validation
    $("#addParaCodeMaster").validate({
        errorElement: 'span',
        rules: {
            para_value: {
                required: true,
            }
        },
        messages: {
            para_value: {
                required: 'Please enter para value',
            }
        }
    });

    // Add & edit sponsor form validation
    $("#addSponsorMaster").validate({
        errorElement: 'span',
        rules: {
            sponsor_name: {
                required: true,
            },
            /*sponsor_type: {
                required: true,
            },*/
            contact_email_1: {
                customemail:true,
            },
            contact_email_2: {
                customemail:true,
            },
            contact_mobile_1: {
                minlength: 10,
                maxlength: 10,
            },
            contact_mobile_2: {
                minlength: 10,
                maxlength: 10,  
            },
        },
        /*errorPlacement: function(error, element) {
            if(element.attr("name") == 'sponsor_type'){ 
                error.insertAfter('#sponsorType');
            } else {
                error.insertAfter( element );
            }
        },*/
        messages: {
            sponsor_name: {
                required: 'Please enter sponsor name',
            },
            /*sponsor_type: {
                required: 'Please enter sponsor type',
            },*/
            contact_mobile_1: {
                minlength:"Please enter 10 digit mobile number",
                maxlength:"Please enter 10 digit mobile number",
            },
            contact_mobile_2: {
                minlength:"Please enter 10 digit mobile number",
                maxlength:"Please enter 10 digit mobile number",
            },
        }
    });

    // Add & edit holiday master form validation
    $("#addHolidayMaster").validate({
        errorElement: 'span',
        rules: {
            holiday_year: {
                required: true,
            },
            holiday_name:{
                required: true,
            },
            holiday_type:{
                required: true,
            },
            holiday_date:{
                required: true,
                remote: {
                    data:{'id' : $("#id").val()},
                    url: "/sms-admin/holiday-master/view/check-holiday-master-date-exist",
                    method: "post",
                },
            },
        },
        messages: {
            holiday_year: {
                required: 'Please enter year',
            },
            holiday_name: {
                required: 'Please enter holiday name',
            },
            holiday_type: {
                required: 'Please select holiday type',
            },
            holiday_date: {
                required: 'Please enter holiday date',
                remote:'Holiday date already exists',
            },
        }
    });

    // Add & edit location master form validation
    $("#addLocationMaster").validate({
        errorElement: 'span',
        rules: {
            location_name: {
                required: true,
            },
            location_type:{
                required: true,
            },
        },
        messages: {
            location_name: {
                required: 'Please enter location name ',
            },
            location_type: {
                required: 'Please select location type ',
            },
        }
    });

    // Add/edit activity slotting
    $("#addActivitySlotting").validate({
        errorElement: 'span',
        rules: {
            activity_name: {
                required: true,
            },
            study_design: {
                required: true,
            },
            no_from_subject: {
                required: true,
            },
            no_to_subject: {
                required: true,
            },
            no_of_days: {
                required: true,
            },
            
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'activity_name'){ 
                error.insertAfter('#selectActivityName');
            } else if (element.attr("name") == 'study_design'){
                error.insertAfter('#selectStudyDesign');
            } else if (element.attr("name") == 'is_cdisc'){
                error.insertAfter('#checkCDISC');
            }  else {
                error.insertAfter( element );
            }
        },
        messages: {
            activity_name: {
                required: 'Please select activity name',
            },
            study_design:{
                required: 'Please selecy study design',
            },
            no_from_subject: {
                required: "Please enter no of from subjects",
            },
            no_to_subject: {
                required: "Please enter no of to subjects"
            },
            no_of_days: {
                required: "Please enter no of days",
            },
        }
    });

    // Reason Master Validation
    $("#addReasonMaster").validate({
        errorElement: 'span',
        rules: {
            activity_type_id: {
                required: true,
            },
            activity_id: {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            
            if(element.attr("name") == 'activity_type_id'){ 
                error.insertAfter('#selectActivityType');
            } else if (element.attr("name") == 'activity_id'){
                error.insertAfter('#selectActivity');
            }  else {
                error.insertAfter( element );
            }
        },
        messages: {
            activity_type_id: {
                required: 'Please select activity type',
            },
            activity_id:{
                required: 'Please selecy activity',
            },
        }
    });

    // Add Activity Metadata Form
    $('#addActivityMetadata').validate({
        errorElement: 'div',
        ignore: ".noValidate",
        rules: {
            activity_id: {
                required: true
            },
            control_id: {
                required: true
            },
            is_mandatory: {
                required: true
            },
            is_activity: {
                required: true
            },
            input_validation: {
                required: true
            },
            source_question: {
                required: true
            }
        },
        errorPlacement: function(error, element) {
            if(element.attr("name") == 'activity_id'){
                error.insertAfter('#activityError');
            } else if (element.attr("name") == 'control_id') {
                error.insertAfter('#controlError');
            } else if (element.attr("name") == 'is_mandatory') {
                error.insertAfter('#isMandatoryError');
            } else if (element.attr("name") == 'is_activity') {
                error.insertAfter('#activityForError');
            } else if (element.attr("name") == 'input_validation') {
                error.insertAfter('#dataValidationError');
            } else {
                error.insertAfter(element);
            }
        },
       messages: {
            activity_id: {
                required: 'Please select activity'
            },
            control_id: {
                required: 'Please select control'
            },
            is_mandatory: {
                required: 'Please choose option'
            },
            is_activity: {
                required: 'Please select activity for'
            },
            input_validation: {
                required: 'Please select data validation'
            },
            source_question: {
                required: 'Please enter source question'
            }
        }
    });

});


