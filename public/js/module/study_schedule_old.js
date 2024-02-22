$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// On switch change for select activity date field disable or enable
$(document).on('click', '.calculateDates', function(){
    
    var id = $(this).data('id');
    var activityId = $(this).data('acitivity');
    var studyId = $(this).data('study');
    var SequenceNo = $(this).data('sequence');
    var date = $('.scheduleDate_'+id).val();
    //alert(studyId);

    $.ajax({
        url:'/sms-admin/study-schedule/view/add-schedule-delay-modal',
        method:'post',          
        data:{
            id:id, studyId:studyId, SequenceNo:SequenceNo, date:date, activityId:activityId
        },
        success:function(data){
            $('#openScheduleDelayModal').modal('show');
            $('#showScheduleModal').html(data);
        },
    });
    
});

$(document).on('change', '.scheduleDate', function(){
    
    var id = $(this).data('id');
    var activityId = $(this).data('acitivity');
    var studyId = $(this).data('study');
    var SequenceNo = $(this).data('sequence');
    var date = $('.scheduleDate_'+id).val();

    $.ajax({
        url: "/sms-admin/study-schedule/view/change-schedule-date",
        method:'POST',
        data:{ id:id, date:date, studyId:studyId, SequenceNo:SequenceNo, activityId:activityId },
        success: function(data){
            if (data == 'true') {
                location.reload();
            }
        }
    });
    
});

// Remove Error Message after selecting an option for actual start date
/*$('.actualStartDate').on('change',function(){
    var val = $(this).val();
    var schStartDate = $('.scheduleStartDate').val();
    $('.delayedStartRemark').hide();
    if(val != ''){
        $('#actual_start_date-error').text('');        
        if (val > schStartDate) {
            $('.delayedStartRemark').show();
        } else {
            $('.delayedStartRemark').hide();
        }
    }
});*/

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

$('.checkAllSchedule').on('change',function(){

    if(this.checked){
        option = 1;
        $(".selectActivity").trigger("click");
    } else {
        option = 0;
        $(".selectActivity").trigger("click");
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


/*$('.saveDate').on('click',function(){

    var id = $(this).data('id');
    var schedule_start_date = $('.actualStartDate_'+id).data('ssd');
    var actual_start_date = $('.actualStartDate_'+id).val();
    var start_delay_remark = $('.startDelayRemark_'+id).val();
    var schedule_end_date = $('.actualEndDate_'+id).data('ssd');
    var actual_end_date = $('.actualEndDate_'+id).val();
    var end_delay_remark = $('.endDelayRemark_'+id).val();

    if(actual_end_date == ''){
        if (schedule_start_date < actual_start_date) {
            
            if (start_delay_remark == '') {
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
                        actual_start_date:actual_start_date,
                        schedule_start_date:schedule_start_date,
                        start_delay_remark:start_delay_remark,
                    },
                    success:function(data){
                        $('.actualStartDate_'+id).html(data.actual_start_date);
                        $('.activityStatus_'+id).html(data.activity_status);
                        $('.actualStartDate_'+id).prop('disabled', true);
                        $('.startDelayRemark_'+id).prop('disabled', true);
                    },
                });
            }
            
        } else {

            $.ajax({
                url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
                method:'post',          
                data:{
                    id:id,
                    actual_start_date:actual_start_date,
                    schedule_start_date:schedule_start_date,
                    start_delay_remark:start_delay_remark,
                },
                success:function(data){
                    $('.actualStartDate_'+id).html(data.actual_start_date);
                    $('.activityStatus_'+id).html(data.activity_status);
                    $('.actualStartDate_'+id).prop('disabled', true);
                    $('.startDelayRemark_'+id).prop('disabled', true);
                },
            });

        }
    } else {
        if (schedule_end_date < actual_end_date) {
            
            if (end_delay_remark == '') {
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
                        schedule_start_date:schedule_start_date,
                        actual_start_date:actual_start_date,
                        actual_end_date:actual_end_date,
                        schedule_end_date:schedule_end_date,
                        end_delay_remark:end_delay_remark,
                    },
                    success:function(data){
                        $('.actualEndDate_'+id).html(data.actual_end_date);
                        $('.activityStatus_'+id).html(data.activity_status);
                        $('.actualEndDate_'+id).prop('disabled', true);
                        $('.endDelayRemark_'+id).prop('disabled', true);
                    },
                });
            }
            
        } else {

            $.ajax({
                url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
                method:'post',          
                data:{
                    id:id,
                    schedule_start_date:schedule_start_date,
                    actual_start_date:actual_start_date,
                    actual_end_date:actual_end_date,
                    schedule_end_date:schedule_end_date,
                    end_delay_remark:end_delay_remark,
                },
                success:function(data){
                    $('.actualEndDate_'+id).html(data.actual_end_date);
                        $('.activityStatus_'+id).html(data.activity_status);
                        $('.actualEndDate_'+id).prop('disabled', true);
                        $('.endDelayRemark_'+id).prop('disabled', true);
                },
            });

        }
    }

});*/

$('.saveDate').on('click',function(){

    var id = $(this).data('id');
    var schedule_start_date = $('.actualStartDate_'+id).data('ssd');
    var actual_start_date = $('.actualStartDate_'+id).val();
    var start_delay_remark = $('.startDelayRemark_'+id).val();
    var schedule_end_date = $('.actualEndDate_'+id).data('ssd');
    var actual_end_date = $('.actualEndDate_'+id).val();
    var end_delay_remark = $('.endDelayRemark_'+id).val();

    if(actual_end_date == '' && actual_start_date == '' || start_delay_remark == ''){

        if (actual_start_date == '') {

            $('<span class="error">Please select date</span>').
            insertAfter('#actualStartDt'+id);

        } else if (schedule_start_date < actual_start_date ) {
            
                $("span.error").hide();
                $(".error").removeClass("error");

                if (start_delay_remark == '' ) {
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
                            actual_start_date:actual_start_date,
                            schedule_start_date:schedule_start_date,
                            start_delay_remark:start_delay_remark,
                        },
                        success:function(data){
                            $('.actualStartDate_'+id).html(data.actual_start_date);
                            $('.activityStatus_'+id).html(data.activity_status);
                            $('.actualStartDate_'+id).prop('disabled', true);
                            $('.startDelayRemark_'+id).prop('disabled', true);
                        },
                    });
                }
            
        } else {

             $.ajax({
                url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
                method:'post',          
                data:{
                    id:id,
                    actual_start_date:actual_start_date,
                    schedule_start_date:schedule_start_date,
                    start_delay_remark:start_delay_remark,
                },
                success:function(data){
                    $('.actualStartDate_'+id).html(data.actual_start_date);
                    $('.activityStatus_'+id).html(data.activity_status);
                    $('.actualStartDate_'+id).prop('disabled', true);
                    $('.startDelayRemark_'+id).prop('disabled', true);
                },
            });

        }

    } else {

        if (actual_end_date == '') {

            $('<span class="error">Please select date</span>').
            insertAfter('#actualEndDt'+id);

        } else if (schedule_end_date < actual_end_date) {
            
            $("span.error").hide();
            $(".error").removeClass("error");

            if (end_delay_remark == '') {
                
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
                        schedule_start_date:schedule_start_date,
                        actual_start_date:actual_start_date,
                        actual_end_date:actual_end_date,
                        schedule_end_date:schedule_end_date,
                        end_delay_remark:end_delay_remark,
                    },
                    success:function(data){
                        $('.actualEndDate_'+id).html(data.actual_end_date);
                        $('.activityStatus_'+id).html(data.activity_status);
                        $('.actualEndDate_'+id).prop('disabled', true);
                        $('.endDelayRemark_'+id).prop('disabled', true);
                    },
                });
            }
            
        } else {
            
            $.ajax({
                url:'/sms-admin/study-schedule-monitoring/view/save-study-schedule-activity-status',
                method:'post',          
                data:{
                    id:id,
                    schedule_start_date:schedule_start_date,
                    actual_start_date:actual_start_date,
                    actual_end_date:actual_end_date,
                    schedule_end_date:schedule_end_date,
                    end_delay_remark:end_delay_remark,
                },
                success:function(data){
                    $('.actualEndDate_'+id).html(data.actual_end_date);
                    $('.activityStatus_'+id).html(data.activity_status);
                    $('.actualEndDate_'+id).prop('disabled', true);
                    $('.endDelayRemark_'+id).prop('disabled', true);
                },
            });

        }
    }

});