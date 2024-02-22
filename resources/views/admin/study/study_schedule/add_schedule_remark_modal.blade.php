<div class="card">
    <div class="card-body">

        <form class="custom-validation" action="{{ route('admin.saveScheduleDelayRemark') }}" method="post" id="showScheduleRemarkModal" name="showScheduleRemarkModal">
            @csrf

            <input type="hidden" name="study_id" value="{{ $studyId }}">
            <input type="hidden" name="activity_type_id" value="{{ $activityId }}">
            <input type="hidden" name="schedule_id" value="{{ $scheduleId }}">

            <div class="form-group mb-3">
                <label>Remark<span class="mandatory">*</span></label>
                <input type="text" class="form-control" name="schedule_delay_remark" id="schedule_delay_remark" placeholder="Remark" autocomplete="off" data-msg="Please enter remark" required/>
                <span id="remark_error" style="color: red;"></span>
            </div>
            
            <center>
                <input onclick="submitAction()" class="btn btn-primary waves-effect" id="submit_value" type="button" name="submit_value" value="Save">
            </center>

        </form>

    </div>
</div>