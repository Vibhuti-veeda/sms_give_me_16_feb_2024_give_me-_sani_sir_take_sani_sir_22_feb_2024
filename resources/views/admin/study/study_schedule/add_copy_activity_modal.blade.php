<div class="card">
    <div class="card-body">

        <form class="custom-validation" action="{{ route('admin.copyStudyActivity') }}" method="post" id="showCopyActivityModal" name="showCopyActivityModal">
            @csrf

            <input type="hidden" name="schedule_id" value="{{ $scheduleId }}">

            <div class="form-group mb-3">
                <label>Copy Type<span class="mandatory">*</span></label>
                <select class="form-select" name="activity_version_type" id="activity_version_type" data-placeholder="Select Type" required>
                    <option value="">Select Type</option>
                    <option value="V">Version</option>
                    <option value="A">Amendment</option>
                    <option value="E">Extension</option>
                </select>
                <span id="copy_type_error" style="color: red;"></span>
            </div>
            
            <center>
                <input onclick="submitCopyActivityType()" class="btn btn-primary waves-effect" id="submit_value" type="button" name="submit_value" value="Save">
            </center>

        </form>

    </div>
</div>