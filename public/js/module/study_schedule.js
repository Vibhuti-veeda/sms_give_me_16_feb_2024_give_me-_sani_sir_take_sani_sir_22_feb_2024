$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.select_study').on('change',function(){
    var val = $(this).val();
    if(val != ''){
        $('#study-error').text('');
    }
});

// Date select clicked on update schedule button auto update activity type wise below activities schedule
$(document).on('click', '.calculateDates', function(){
    
    var id = $(this).data('id');
    var activityId = $(this).data('acitivity');
    var studyId = $(this).data('study');
    var SequenceNo = $(this).data('sequence');
    var scheduleDate = $('.scheduleDate_'+id).val();
    var activityType = $(this).data('type');
    var activityVersion = $(this).data('version');
    var activityDays = $(this).data('days');

    // Date formate change function
    var formattedDate = new Date(scheduleDate);
    var d = formattedDate.getDate().toString().padStart(2, "0");
    var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
    var y = formattedDate.getFullYear();

    var date = d + "-" + m + "-" + y;

    // Schedule date change for those which are original activities
    if (activityVersion == 0) {
        $.ajax({
            url:'/sms-admin/study-schedule/view/change-schedule-date',
            /*url:'/sms-admin/study-schedule/view/add-schedule-delay-modal',*/
            method:'post',          
            data:{
                id:id, studyId:studyId, SequenceNo:SequenceNo, date:date, activityId:activityId, activityType:activityType, activityVersion:activityVersion
            },
            success:function(data){
                if (data == 'true') {
                    location.reload();
                    toastr.success('Schedule updated successfully');
                }
                /*$('#openScheduleDelayModal').modal('show');
                $('#showScheduleModal').html(data);*/
            },
        });
    } else {
        // Schedule date change for those which is copied activity
        $.ajax({
            url:'/sms-admin/study-schedule/view/change-schedule-version-date',
            method:'post',          
            data:{
                id:id, studyId:studyId, SequenceNo:SequenceNo, date:date, activityId:activityId, activityType:activityType, activityVersion:activityVersion
            },
            success:function(data){
                if (data == 'true') {
                    location.reload();
                    toastr.success('Schedule updated successfully');
                }
            },
        });
    }
    
});

// When first time any activity type wise date select auto remaining dates count Ajax call
$(document).on('change', '.scheduleDate', function(){
    
    var id = $(this).data('id');
    var activityId = $(this).data('acitivity');
    var studyId = $(this).data('study');
    var SequenceNo = $(this).data('sequence');
    var scheduleDate = $('.scheduleDate_'+id).val();
    var activityType = $(this).data('type');
    var activityVersion = $(this).data('version');
    var activityDays = $(this).data('days');

    // Date formate change function
    var formattedDate = new Date(scheduleDate);
    var d = formattedDate.getDate().toString().padStart(2, "0");
    var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
    var y = formattedDate.getFullYear();

    var date = d + "-" + m + "-" + y;

    // Schedule date change for those which are original activities
    if (activityVersion == 0) {
        $.ajax({
            url: "/sms-admin/study-schedule/view/change-schedule-date",
            method:'POST',
            data:{ 
                id:id, date:date, studyId:studyId, SequenceNo:SequenceNo, activityId:activityId, activityType:activityType, activityVersion:activityVersion
            },
            success: function(data){
                if (data == 'true') {
                    location.reload();
                    toastr.success('Schedule updated successfully');
                }
            }
        });
    } else {
        // Schedule date change for those which is copied activity
        $.ajax({
            url:'/sms-admin/study-schedule/view/change-schedule-version-date',
            method:'post',          
            data:{
                id:id, studyId:studyId, SequenceNo:SequenceNo, date:date, activityId:activityId, activityType:activityType, activityVersion:activityVersion
            },
            success:function(data){
                if (data == 'true') {
                    location.reload();
                    toastr.success('Schedule updated successfully');
                }
            },
        });
    }
    
});

// Remove Error Message after selecting an option for actual end date
$('.actualEndDate').on('change',function(){
    var val = $(this).val();
    var schEndDate = $('.scheduleEndDate').val();
    $('.delayedEndRemark').hide();
    if(val != ''){
        $('#actual_end_date-error').text('');
        if (val > schEndDate) {
            $('.delayedEndRemark').show();
        } else {
            $('.delayedEndRemark').hide();
        }
    }
    
});

// Custom Toggle switch on all 
$('.checkAllSchedule').on('change',function(){

    if(this.checked){
        option = 1;
        $(".selectActivity").trigger("click");
    } else {
        option = 0;
        $(".selectActivity").trigger("click");
    }
    
});

// Custom Switch toggle for cr Activity type 
$('.checkCRActivity').on('change',function(){
    if(this.checked){
        option = 1;
        $(".crActivity_CR").trigger("click");
    }  else {
        option = 0;
        $(".crActivity_CR").trigger("click");
    }
});

// Custom Switch toggle for br Activity type
$('.checkBRActivity').on('change',function(){
    if(this.checked){
        option = 1;
        $(".brActivity_BR").trigger("click");
    }  else {
        option = 0;
        $(".brActivity_BR").trigger("click");
    }
});

// Custom Switch toggle for pb Activity type
$('.checkPBActivity').on('change',function(){
    if(this.checked){
        option = 1;
        $(".pbActivity_PB").trigger("click");
    }  else {
        option = 0;
        $(".pbActivity_PB").trigger("click");
    }
});

// Custom Switch toggle for rw Activity type
$('.checkRWActivity').on('change',function(){
    if(this.checked){
        option = 1;
        $(".rwActivity_RW").trigger("click");
    }  else {
        option = 0;
        $(".rwActivity_RW").trigger("click");
    }
});


// Custom Switch toggle for rw Activity type
$('.checkPSActivity').on('change',function(){
    if(this.checked){
        option = 1;
        $(".psActivity_PS").trigger("click");
    }  else {
        option = 0;
        $(".psActivity_PS").trigger("click");
    }
});

// Items list modal in Order Listing page
$(document).ready(function(){
    $(document).on('click','#myModal',function(){
        var id = $(this).data('id');
        $.ajax({
            url:'/sms-admin/study-schedule-monitoring/view/study-details-modal',
            method:'post',          
            data:{
                id:id,
            },
            success:function(data){
                $('#openStudyDetailsModal').modal('show');
                $('#study_details').html(data);
            },
        });
    });
});

// Actual Start Date
$(document).ready(function(){

    $(document).on('change', '.actualStartDate', function(e){

        e.preventDefault();
        var schedule_start_date = $('#schedule_start_date').val();
        var actualStartDate = $('.actualStartDate').val();
        var startDelayReason = $('#start_delay_reason_id').val();
        var startDelayRemark = $('.startDelayRemark').val();

        var formattedDate = new Date(actualStartDate);
        var d = formattedDate.getDate().toString().padStart(2, "0");
        var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
        
        var y = formattedDate.getFullYear();

        var formattedActualStartDate=y + "-" + m + "-" + d;

        if((actualStartDate != '') && (schedule_start_date < formattedActualStartDate))  {
            // $('#actual_start_date-error').text('');
            $('#startActivityReason').show();

            if((startDelayReason == '0') && (startDelayRemark != '')) {
                // $('#startActivityReason').show();
                $('#startActivityRemark').show();
            }            
        } else {
            $('#startActivityReason').hide();
            $('#startActivityRemark').hide();
        }
    });

    $(document).on('change', '.start_delay_reason_id', function(){
       
        // var schedule_start_date = $('#schedule_start_date').val();
        // var actualStartDate = $('.actualStartDate').val();
        var startDelayReason = $('#start_delay_reason_id').val();
        var startDelayRemark = $('.startDelayRemark').val();

        if (startDelayReason == '0'){
            $('#startActivityRemark').show();
        } else if(startDelayRemark != '' && startDelayReason == '0') {
            $('#startActivityRemark').show();
        } else if(startDelayReason != '0' && startDelayReason == '') {
            $('#startActivityRemark').hide();
        } else {
            $('#startActivityRemark').hide();
        }
    });

});


// Actual End Date
$(document).ready(function(){

    $(document).on('change', '.actualEndDate', function(e){

        e.preventDefault();
        var schedule_end_date = $('#schedule_end_date').val();
        var actualEndDate = $('.actualEndDate').val();
        var endDelayReason = $('#end_delay_reason_id').val();
        var endDelayRemark = $('.endDelayRemark').val();

        var formattedDate = new Date(actualEndDate);
        var d = formattedDate.getDate().toString().padStart(2, "0");
        var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
        
        var y = formattedDate.getFullYear();

        var formattedActualEndDate=y + "-" + m + "-" + d;

        if((actualEndDate != '') && (schedule_end_date < formattedActualEndDate))  {

            $('#endActivityReason').show();

            if((endDelayReason == '0') && (endDelayRemark != '')) {

                $('#endActivityRemark').show();
            }            
        } else {

            $('#endActivityReason').hide();
            $('#endActivityRemark').hide();
        }
    });

    $(document).on('change', '.end_delay_reason_id', function(){
    
        var endDelayReason = $('#end_delay_reason_id').val();
        var endDelayRemark = $('.endDelayRemark').val();

        if (endDelayReason == '0'){

            $('#endActivityRemark').show();
        } else if(endDelayRemark != '' && endDelayReason == '0') {
            
            $('#endActivityRemark').show();
        } else if(endDelayReason != '0' && endDelayReason == '') {
            
            $('#endActivityRemark').hide();
        } else {
            
            $('#endActivityRemark').hide();
        }
    });

});

// Actual start date onclick event
/*$('.saveStartDate').on('click',function(){

    var id = $(this).data('id');
    var schedule_start_date = $('.actualStartDate_'+id).data('ssd');
    var actual_start = $('.actualStartDate_'+id).val();
    var start_delay_reason_id = $('.start_delay_reason_id_'+id).val();
    var start = 'start';

    var formattedDate = new Date(actual_start);
    var d = formattedDate.getDate().toString().padStart(2, "0");
    var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
    
    var y = formattedDate.getFullYear();

    var actual_start_date=y + "-" + m + "-" + d;
    
    var start_delay_remark = $('.startDelayRemark_'+id).val();
    var start_save = $('.saveStart_'+id).data('id');
    var schedule_end_date = $('.actualEndDate_'+id).data('ssd');

    if(actual_start == ''){
        $("span.error").hide();
        $(".error").removeClass("error");
        $('<span class="error">Please select date</span>').
        insertAfter('#actualStartDt'+id);

    } else if(schedule_start_date < actual_start_date ) {
                
        $("span.error").hide();
        $(".error").removeClass("error");

        if (start_delay_reason_id == '') {
            $('<span class="error">Please select reason</span>').
            insertAfter('#startDelayReason'+id);
        } else if (start_delay_reason_id == '0' && start_delay_remark == '') {
            $('<span class="error">Please enter remark</span>').
            insertAfter('#startDelayRemark'+id);
        } else {
            $("span.error").hide();
            $(".error").removeClass("error");
            $.ajax({
                url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
                method:'post',          
                data:{
                    id:id,
                    start:start,
                    actual_start_date:actual_start_date,
                    schedule_start_date:schedule_start_date,
                    start_delay_remark:start_delay_remark,
                    schedule_end_date:schedule_end_date,
                    start_delay_reason_id:start_delay_reason_id,
                 },
                success:function(data){
                    $('.actualStartDate_'+id).html(data.actual_start_date);
                    $('.activityStatus_'+id).html(data.activity_status);
                    $('.actualStartDate_'+id).prop('disabled', true);
                    $('.start_delay_reason_id_'+id).prop('disabled', true);
                    $('.startDelayRemark_'+id).prop('disabled', true);
                    $('.actualEndDate_'+id).prop('disabled', false);
                    $('.endDelayRemark_'+id).prop('disabled', false);
                    $('.end_delay_reason_id_'+id).prop('disabled', false);
                    $('.saveStart_'+id).addClass("disabled");
                    $('.saveEnd_'+id).removeClass("disabled");
                },
            });
        } 

    } else {
        $("span.error").hide();
        $(".error").removeClass("error");
        $.ajax({
            url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
            method:'post',          
            data:{
                id:id,
                start:start,
                actual_start_date:actual_start_date,
                schedule_start_date:schedule_start_date,
                start_delay_remark:start_delay_remark,
                schedule_end_date:schedule_end_date,
             },
            success:function(data){
                $('.actualStartDate_'+id).html(data.actual_start_date);
                $('.activityStatus_'+id).html(data.activity_status);
                $('.actualStartDate_'+id).prop('disabled', true);
                $('.start_delay_reason_id_'+id).prop('disabled', true);
                $('.startDelayRemark_'+id).prop('disabled', true);
                $('.actualEndDate_'+id).prop('disabled', false);
                $('.endDelayRemark_'+id).prop('disabled', false);
                $('.end_delay_reason_id_'+id).prop('disabled', false);
                $('.saveStart_'+id).addClass("disabled");
                $('.saveEnd_'+id).removeClass("disabled");
            },
        });
    }
});*/

// Actual end date onclick event
/*$('.saveEndDate').on('click',function(){

    var id = $(this).data('id');
    var schedule_start_date = $('.actualStartDate_'+id).data('ssd');
    var actual_start = $('.actualStartDate_'+id).val();
    var end_delay_reason_id = $('.end_delay_reason_id_'+id).val();
    var end = 'end';

    var formattedDate = new Date(actual_start);
    var d = formattedDate.getDate().toString().padStart(2, "0");
    var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
    var y = formattedDate.getFullYear();

    var actual_start_date=y + "-" + m + "-" + d;
    var schedule_end_date = $('.actualEndDate_'+id).data('ssd');
    var actual_end = $('.actualEndDate_'+id).val();
    var formattedDate = new Date(actual_end);
    var d = formattedDate.getDate().toString().padStart(2, "0");
    var m =  (formattedDate.getMonth() + 1).toString().padStart(2, "0");
    var y = formattedDate.getFullYear();
    var actual_end_date= y + "-" + m + "-" + d;
    var end_delay_remark = $('.endDelayRemark_'+id).val();
    var is_actual_filled= $('.actualFilled_'+id).val();
    var save_end=$('.saveEnd_'+id).data('id');
    
    if (actual_end == '') {
        $("span.error").hide();
        $(".error").removeClass("error");
        $('<span class="error">Please select date</span>').
        insertAfter('#actualEndDt'+id);

    } else if (schedule_end_date < actual_end_date){
        
        $("span.error").hide();
        $(".error").removeClass("error");

        if (end_delay_reason_id == '') {
            $('<span class="error">Please select reason</span>').
            insertAfter('#endDelayReason'+id);
        } else if (end_delay_reason_id == '0'  && end_delay_remark == '') {
            $('<span class="error">Please enter remark</span>').
            insertAfter('#endDelayRemark'+id);
        } else {
            
            $("span.error").hide();
            $(".error").removeClass("error");
            $.ajax({
                url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
                method:'post',          
                data:{
                    id:id,
                    end:end,
                    actual_start_date:actual_start_date,
                    schedule_start_date:schedule_start_date,
                    actual_end_date:actual_end_date,
                    schedule_end_date:schedule_end_date,
                    end_delay_remark:end_delay_remark,
                    end_delay_reason_id:end_delay_reason_id,
                },
                success:function(data){
                    $('.actualEndDate_'+id).html(data.actual_end_date);
                    $('.activityStatus_'+id).html(data.activity_status);
                    $('.actualEndDate_'+id).prop('disabled', true);
                    $('.end_delay_reason_id_'+id).prop('disabled', true);
                    $('.endDelayRemark_'+id).prop('disabled', true);
                    $('.saveEnd_'+id).addClass("disabled");
                    $('.saveStart_'+id).addClass("disabled");
                    $('.saveEndStart_'+id).addClass("disabled");
                    
                },
            });
        }
        
    } else {
        
        $("span.error").hide();
        $(".error").removeClass("error");
        $.ajax({
            url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
            method:'post',          
            data:{
                id:id,
                end:end,
                actual_start_date:actual_start_date,
                schedule_start_date:schedule_start_date,
                actual_end_date:actual_end_date,
                schedule_end_date:schedule_end_date,
                end_delay_remark:end_delay_remark,
            },
            success:function(data){
                $('.actualEndDate_'+id).html(data.actual_end_date);
                $('.activityStatus_'+id).html(data.activity_status);
                $('.actualEndDate_'+id).prop('disabled', true);
                $('.end_delay_reason_id_'+id).prop('disabled', true);
                $('.endDelayRemark_'+id).prop('disabled', true);
                $('.saveEnd_'+id).addClass("disabled");
                $('.saveStart_'+id).addClass("disabled");
            },
        });
    
    }
});*/

// CR activities required days update Ajax call
$(document).on('change', '.crRequiredDays', function(){
    var id = $(this).data('val');
    var require_days = $('.crRequiredDays_'+id).val();
    $.ajax({
        url:'/sms-admin/study-schedule/view/change-required-days',
        method:'post',
        data:{ id:id ,require_days:require_days},
        success: function(data){
            if(data == 'true'){
                toastr.success('Schedule required days successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// BR activities required days update Ajax call
$(document).on('change', '.brRequiredDays', function(){
    var id = $(this).data('val');
    var require_days = $('.brRequiredDays_'+id).val();
    $.ajax({
        url:'/sms-admin/study-schedule/view/change-required-days',
        method:'post',
        data:{ id:id ,require_days:require_days},
        success: function(data){
            if(data == 'true'){
                toastr.success('Schedule required days successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// RW activities required days update Ajax call
$(document).on('change', '.rwRequiredDays', function(){
    var id = $(this).data('val');
    var require_days = $('.rwRequiredDays_'+id).val();
    $.ajax({
        url:'/sms-admin/study-schedule/view/change-required-days',
        method:'post',
        data:{ id:id ,require_days:require_days},
        success: function(data){
            if(data == 'true'){
                toastr.success('Schedule required days successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// PB activities required days update Ajax call
$(document).on('change', '.pbRequiredDays', function(){
    var id = $(this).data('val');
    var require_days = $('.pbRequiredDays_'+id).val();
    $.ajax({
        url:'/sms-admin/study-schedule/view/change-required-days',
        method:'post',
        data:{ id:id ,require_days:require_days},
        success: function(data){
            if(data == 'true'){
                toastr.success('Schedule required days successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// Pre study activities required days update Ajax call
$(document).on('change', '.psRequiredDays', function(){
    var id = $(this).data('val');
    var require_days = $('.psRequiredDays_'+id).val();
    $.ajax({
        url:'/sms-admin/study-schedule/view/change-required-days',
        method:'post',
        data:{ id:id ,require_days:require_days},
        success: function(data){
            if(data == 'true'){
                toastr.success('Schedule required days successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// Reschedule time remark modal pop up ajax call
$(document).on('click', '#myRemarkModal', function(){
    
    var scheduleId = $(this).data('schedule');
    var studyId = $(this).data('study');
    var activityId = $(this).data('activity');

    $.ajax({
        url:'/sms-admin/study-schedule/view/add-schedule-remark-modal',
        method:'post',
        data:{ scheduleId:scheduleId, studyId:studyId, activityId:activityId },
        success: function(data){
            $('#openScheduleRemarkModal').modal('show');
            $('#showScheduleRemarkModal').html(data);
        }
        
    });
    
});

// Milestone activity on manage study schedule
$(document).on('click', '.milestoneActivity', function(){

    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }

    var id = $(this).data('id');

    $.ajax({
        url:'/sms-admin/study-schedule/view/change-milestone-activity',
        method:'post',
        data:{ id:id, option:option },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study milestone activity successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// Start milestone activity manage study schedule
$(document).on('click', '.startMilestoneActivity', function(){

    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }

    var id = $(this).data('id');

    $.ajax({
        url:'/sms-admin/study-schedule/view/start-milestone-activity',
        method:'post',
        data:{ id:id, option:option },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study start date milestone activity successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

// End milestone activity manage study schedule
$(document).on('click', '.endmilestoneActivity', function(){

    if(this.checked){
        option = 1;
    } else {
        option = 0;
    }

    var id = $(this).data('id');

    $.ajax({
        url:'/sms-admin/study-schedule/view/end-milestone-activity',
        method:'post',
        data:{ id:id, option:option },
        success: function(data){
            if(data == 'true'){
                toastr.success('Study end date milestone activity successfully updated');    
            } else {
                toastr.success('Something went wrong');
            }
        }
        
    });
    
});

function submitAction(){
    var val = $('#schedule_delay_remark').val();

    if(val == ''){
        $('#remark_error').text('Please enter remark');
    } else {
        $('#remark_error').text('');
        document.forms["showScheduleRemarkModal"].submit();
    }
}

function submitCopyActivityType(){
    var val = $('#activity_version_type').val();

    if(val == ''){
        $('#copy_type_error').text('Please select type');
    } else {
        $('#copy_type_error').text('');
        document.forms["showCopyActivityModal"].submit();
    }
}

// Copy study activity
$(document).on('click', '#myCopyActivityModal', function(){
    var id = $(this).data('id');

    $.ajax({
        url:'/sms-admin/study-schedule/view/add-copy-activity-modal',
        method:'post',
        data:{ id:id },
        success: function(data){
            $('#openCopyActivityModal').modal('show');
            $('#showCopyActivityModal').html(data);
        }
        
    });    
});

// Start Date Model open
$(document).ready(function(){
    $(document).on('click','.saveStartDate',function(){
        var id = $(this).data('id');
        
        $.ajax({
            url:'/sms-admin/study-schedule-monitoring/view/study-schedule-actual-start-date-modal/'+id,
            method:'get',
            success: function(data){
                $('#openStartDateModal').modal('show');
                $('#showStartDateModal').html(data);
            }
        });            
    });
});


// End Date Model open
$(document).ready(function(){
    $(document).on('click','.saveEndDate',function(){
        var id = $(this).data('id');
        
        $.ajax({
            url:'/sms-admin/study-schedule-monitoring/view/study-schedule-actual-end-date-modal/'+id,
            method:'get',
            success: function(data){
                $('#openEndDateModal').modal('show');
                $('#showEndDateModal').html(data);
            }
        });         
    });
});


