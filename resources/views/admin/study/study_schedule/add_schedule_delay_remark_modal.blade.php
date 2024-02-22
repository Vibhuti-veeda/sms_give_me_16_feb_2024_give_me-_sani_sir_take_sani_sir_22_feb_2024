<div class="card">
    <div class="card-body">

        <form class="custom-validation" action="{{ route('admin.saveScheduleDelayRemark') }}" method="post" id="addScheduleDelayRemark">
            @csrf
            
            <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $scheduleId }}">
            <input type="hidden" name="study_id" id="study_id" value="{{ $studyId }}">
            <input type="hidden" name="sequence_no" id="sequence_no" value="{{ $sequenceNo }}">
            <input type="hidden" name="activity_id" id="activity_id" value="{{ $activityId }}">
            <input type="hidden" name="date" id="date" value="{{ $date }}">
            <input type="hidden" name="old_date" id="oldDate" value="{{ date('Y-m-d', strtotime($oldDate->scheduled_start_date)) }}">
            <input type="hidden" name="activity_type" id="activityType" value="{{ $activityType }}">

            <div class="form-group mb-3">
                <label>Remark<span class="mandatory">*</span></label>
                <input type="text" class="form-control" name="schedule_delay_remark" id="schedule_delay_remark" placeholder="Remark" autocomplete="off" required/>
            </div>
            
            <center>
                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1 col-lg-3" name="btn_submit" id="delayRemark" value="save">
                    Save
                </button>
            </center>

        </form>

    </div>
</div>

<script type="text/javascript">
    $("#delayRemark").click(function() {

        // Schedule update remark validation
        $("#addScheduleDelayRemark").validate({
            errorElement : 'div',
            rules: {
                schedule_delay_remark: {
                    required: true,
                },
            },
            messages: {
                schedule_delay_remark: {
                    required: 'Please enter schedule delay remark',
                },
            }
        });

        if($('#schedule_delay_remark').val() != ''){
            var id = $('#schedule_id').val();
            var date = $('#date').val();
            var studyId = $('#study_id').val();
            var SequenceNo = $('#sequence_no').val();
            var old_date = $('#oldDate').val();
            var activityType = $('#activityType').val();

            $.ajax({
                url: "/sms-admin/study-schedule/view/change-schedule-date",
                method:'POST',
                data:{ id:id, date:date, studyId:studyId, SequenceNo:SequenceNo, old_date:old_date, activityType:activityType },
                success: function(data){
                    if (data == 'true') {
                        location.reload();
                    }
                }
            });
            $("#addScheduleDelayRemark").submit();
        }
    });
</script>