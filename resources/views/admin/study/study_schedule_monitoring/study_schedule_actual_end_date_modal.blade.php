
<form class="custom-validation addActualEndDate" method="post" id="addActualEndDate" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="id" id="id" value="{{ $studyScheduleEndDate->id }}">
    <input type="hidden" name="activity_id" id="activity_id" value="{{ $studyScheduleEndDate->activity_id }}">
    <input type="hidden" name="study_id" id="study_id" value="{{ $studyScheduleEndDate->study_id }}">
    <input type="hidden" name="schedule_end_date" id="schedule_end_date" value="{{ $studyScheduleEndDate->scheduled_end_date }}">
    <input type="hidden" name="schedule_start_date" id="schedule_start_date" value="{{ $studyScheduleEndDate->scheduled_start_date }}">
    <input type="hidden" name="actual_start_date" id="actual_start_date" value="{{ $studyScheduleEndDate->actual_start_date }}">
    <input type="hidden" name="role_id" id="role_id" value="{{ Auth::guard('admin')->user()->role_id }}">

    @php
        $isReasonFilled = '';
        $isRemarkFilled = '';
        $actualEndDate = '';

        if(!is_null($studyScheduleEndDate->actual_end_date)) {
            $actualEndDate = date('Y-m-d', strtotime($studyScheduleEndDate->actual_end_date));
            if($actualEndDate > $studyScheduleEndDate->scheduled_end_date) {
                $isReasonFilled = true;
                $isRemarkFilled = false;
            } else if($actualEndDate <= $studyScheduleEndDate->scheduled_end_date) {
                $isReasonFilled = false;
                $isRemarkFilled = false;
            }
        }

        if(($studyScheduleEndDate->actual_end_date == '') && ($studyScheduleEndDate->end_delay_reason_id == '') && ($studyScheduleEndDate->end_delay_remark == '')) {
            $isReasonFilled = false;
            $isRemarkFilled = false;
        }

        if($studyScheduleEndDate->end_delay_reason_id == '0') {
            $isReasonFilled = true;
            $isRemarkFilled = true;
        } else if(($studyScheduleEndDate->end_delay_reason_id != '0') && ($studyScheduleEndDate->end_delay_reason_id != null)) {
            $isReasonFilled = true;
            $isRemarkFilled = false;
        }

        if(($studyScheduleEndDate->end_delay_remark != null) && ($studyScheduleEndDate->end_delay_reason_id == '0')) {
            $isRemarkFilled = true;
        }
    @endphp

	<div class="row offset-1">

        <div class="form-group mb-3 row" >
            <label class="col-md-3">Schedule End Date<span class="mandatory">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="scheduled_end_date" id="scheduled_end_date" placeholder="Enter schedule end date" autocomplete="off" value="{{ (!is_null($studyScheduleEndDate) && $studyScheduleEndDate->scheduled_end_date != '') ?  date('d M Y', strtotime($studyScheduleEndDate->scheduled_end_date)) : ''}}" disabled style="width:80%;">
            </div>
        </div>

	    <div class="form-group mb-3 row" >
	        <label class="col-md-3">Actual End Date<span class="mandatory">*</span></label>
	        <div class="col-md-9">
	        	@if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
	            	<input type="date" class="form-control actualEndDate scheduleDatepicker" min="{{$studyScheduleEndDate->actual_start_date}}" max="{{date('Y-m-d')}}" name="actual_end_date" id="actual_end_date" autocomplete="off" value="{{ $studyScheduleEndDate->actual_end_date != '' ? date('Y-m-d', strtotime($studyScheduleEndDate->actual_end_date)) : '' }}" required style="width:80%;"> 	           	
	            @else
	            	<input type="date" class="form-control actualEndDate scheduleDatepicker" min="{{$studyScheduleEndDate->actual_start_date}}" max="{{date('Y-m-d')}}"  name="actual_end_date" id="actual_end_date" autocomplete="off" value="{{ $studyScheduleEndDate->actual_end_date != '' ? date('Y-m-d', strtotime($studyScheduleEndDate->actual_end_date)) : '' }}" @if($studyScheduleEndDate->actual_end_date != '') ? disabled : '' @elseif($studyScheduleEndDate->actual_start_date == '') ? disabled : '' @endif required style="width: 80%;">	            	
	            @endif
	        </div>
	    </div>

        <div class="form-group mb-3 row" style="{{ $isReasonFilled == true ? '' : 'display: none' }}" id="endActivityReason">
            <label class="col-md-3">End Activity Reason<span class="mandatory">*</span></label>
            <div class="col-md-9">
                @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)                   
                    <select class="form-select end_delay_reason_id" name="end_delay_reason_id" id="end_delay_reason_id" data-placeholder="Select Delay Reason" required style="width:80%;">
                        <option value="">Select Delay Reason</option>
                        @if(!is_null($studyScheduleEndDate->endDelayReasons))
                            @foreach($studyScheduleEndDate->endDelayReasons as $edrk => $edrv)
                                @if($studyScheduleEndDate->activity_id == $edrv->activity_id)
                                    @if(!is_null($edrv->end_delay_remark))
                                        <option @if($studyScheduleEndDate->end_delay_reason_id == $edrv->id) selected @endif value="{{ $edrv->id }}">
                                            {{ $edrv->end_delay_remark }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        <option @if($studyScheduleEndDate->end_delay_reason_id == '0') selected @endif value="0">
                            Other
                        </option>
                    </select>
                @else
                    <select class="form-select end_delay_reason_id" name="end_delay_reason_id" id="end_delay_reason_id" data-placeholder="Select Delay Reason" required @if($studyScheduleEndDate->actual_end_date != '') ? disabled : '' @elseif($studyScheduleEndDate->actual_start_date == '') ? disabled : '' @endif style="width:80%;">
                        <option value="">Select Delay Reason</option>
                        @if(!is_null($studyScheduleEndDate->endDelayReasons))
                            @foreach($studyScheduleEndDate->endDelayReasons as $edrk => $edrv)
                                @if($studyScheduleEndDate->activity_id == $edrv->activity_id)
                                    @if(!is_null($edrv->end_delay_remark))
                                        <option @if($studyScheduleEndDate->end_delay_reason_id == $edrv->id) selected @endif value="{{ $edrv->id }}">
                                            {{ $edrv->end_delay_remark }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        <option @if($studyScheduleEndDate->end_delay_reason_id == '0') selected @endif value="0">
                            Other
                        </option>
                    </select>   
                @endif
            </div>
        </div>

        <div class="form-group mb-3 row" style="{{ $isRemarkFilled == true ? '' : 'display: none' }}" id="endActivityRemark">
            <label class="col-md-3">End Activity Remark<span class="mandatory">*</span></label>
            <div class="col-md-9">
                @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                    <input type="text" class="form-control endDelayRemark" name="end_delay_remark" id="end_delay_remark" value="{{ $studyScheduleEndDate->end_delay_remark }}" required autocomplete="off" title="{{ $studyScheduleEndDate->end_delay_remark }}" style="width: 80%;">
                    <!-- {{ $studyScheduleEndDate->end_delay_remark != '' ? $studyScheduleEndDate->end_delay_remark : '---' }} -->    
                @else
                    <input type="text" class="form-control endDelayRemark" name="end_delay_remark" id="end_delay_remark" value="{{ $studyScheduleEndDate->end_delay_remark }}" @if($studyScheduleEndDate->actual_end_date != '') ? disabled : '' @elseif($studyScheduleEndDate->actual_start_date == '') ? disabled : '' @endif required autocomplete="off" title="{{ $studyScheduleEndDate->end_delay_remark }}" style="width: 80%">
                    <!-- {{ $studyScheduleEndDate->end_delay_remark != '' ? $studyScheduleEndDate->end_delay_remark : '---' }} -->
                @endif
            </div>
        </div>

        @php
            $count = 0;
        @endphp

         @if(!is_null($activityMetaDataActualEndDate))
            @foreach($activityMetaDataActualEndDate as $amaek => $amaev)
                @if((!is_null($amaev->controlName)) && ($amaev->controlName->control_type != ''))
                    @php
                        $count++;
                        $data = '';
 
                        if((!is_null($amaev->studyActivityMetadata)) && ($amaev->studyActivityMetadata->actual_value != '')) {
                            $data = $amaev->studyActivityMetadata->actual_value;
                        }
                    @endphp

                    @if($amaev->controlName->control_type == 'text')
                        <div class=" form-group mb-3 row">
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control text" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" id="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" value="{{ $data }}" autocomplete="off" data-is-mandatory = "{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}"maxlength="250" style="width:80%;">  
                                @if($amaev->is_mandatory  == '1')
                                    <span class="text-error-message" style="color: red; margin-top: 5px;">
                                        Please enter text
                                    </span>
                                @endif 
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'textarea')
                        <div class=" form-group mb-3 row">
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <textarea class="form-control textArea" autocomplete="off" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" id="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" data-is-mandatory="{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}" maxlength="1000"
                                style="width:80%;">{{ $data }}</textarea>   
                                @if($amaev->is_mandatory  == '1')
                                    <span class="textarea-error-message" style="color: red; margin-top: 5px;">
                                        Please enter text
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'date')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control metaDataDatepicker date" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="dd mm yyyy" autocomplete="off" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" id="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" value="{{ (($data != '') ? date('d M Y', strtotime($data)) : '') }}" data-is-mandatory="{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}"
                                style="width:80%;">
                                @if($amaev->is_mandatory  == '1')
                                    <span class="date-error-message" style="color: red; margin-top: 5px;">
                                        Please select date
                                    </span>
                                @endif        
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'datetime')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="datetime-local" class="form-control dateTime" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="yyyy-mm-dd" autocomplete="off"
                                name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" id="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" value="{{ (($data != '') ? date('Y-m-d\TH:i', strtotime($data)) : '') }}" data-is-mandatory="{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}" style="width:80%;">
                                @if($amaev->is_mandatory  == '1')
                                    <span class="datetime-error-message" style="color: red; margin-top: 5px;">
                                        Please select date-time
                                    </span>
                                @endif         
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'file')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="file" class="form-control file" autocomplete="off" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" id="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" value="{{ $data }}" data-is-mandatory="{{ (($amaev->is_mandatory == '1') && ($data == ''))  ? 'true' : 'false' }}" accept=".pdf"  style="width:80%;">
                                @if($amaev->is_mandatory  == '1')
                                    <span class="file-error-message" style="color: red; margin-top: 5px; display: block;">
                                        Please upload file
                                    </span>
                                @endif 
                                <span class="file-error" style="display:none; margin-top: 5px; color:red;">
                                    File size exceeds 5MB limit
                                </span>   
                                @if($data != '')
                                    <div>
                                        <a class="btn btn-primary btn-sm waves-effect waves-light mt-2" href="{{ asset('uploads/activity_metadata/actual_end') }}/{{ $data }}" title="View File" role="button" target="_blank">
                                            <i class="mdi mdi-file-pdf"></i>
                                        </a>
                                    </div>
                                @endif    
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'radio')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9" style="padding-top: 9px;width: 61%">
                                @php
                                    $radioSourceValue = [];

                                    if($amaev->source_value != '') {
                                        $radioSourceValue = explode(',', $amaev->source_value);
                                    }
                                @endphp

                                @for($i = 0; $i< sizeof($radioSourceValue); $i++)
                                    <div class="form-check form-check-inline pt-1">
                                        <input class="form-check-input radio-option inlineRadioOptions_{{ $amaev->id }}" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" type="radio" data-group="{{ $amaev->id }}" data-is-mandatory = "{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}" id="inlineRadio_{{ $amaev->id }}_{{ $i }}" value="{{ $radioSourceValue[$i] }}" {{ (($data == $radioSourceValue[$i]) ? 'checked' : '') }}>
                                        <label class="form-check-label inlineRadio_{{ $amaev->id }}_{{ $i }}" for="{{ $amaev->controlName->control_type . $count }}">
                                            {{ $radioSourceValue[$i] }}
                                        </label>
                                    </div>
                                @endfor
                                @if($amaev->is_mandatory == '1')
                                    <br>
                                    <span class="r1 radio-error-message" data-group="{{ $amaev->id }}" style="color: red; margin-top: 5px; display: none;">
                                        Please select option
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'checkbox')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9" style="padding-top: 9px;width: 61%">
                                @php
                                    $checkSourceValue = [];
                                    $actualValue = [];

                                    if($amaev->source_value != '') {
                                        $checkSourceValue = explode(',', $amaev->source_value);
                                    }

                                    if($data != '') {
                                        $actualValue = explode('|', $data);
                                    }
                                @endphp

                                @for($i = 0; $i< sizeof($checkSourceValue); $i++)
                                    <div class="form-check form-check-inline pt-1">
                                        <input class="form-check-input checkbox-option inlineCheckboxOptions_{{ $amaev->id }}" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}][]" type="checkbox" data-group="{{ $amaev->id }}" id="inlineCheckbox_{{ $amaev->id }}_{{ $i }}" data-is-mandatory = "{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}" value="{{ $checkSourceValue[$i] }}" {{ ((!empty($actualValue)) && (in_array($checkSourceValue[$i], $actualValue))) ? 'checked' : '' }}>
                                        <label class="form-check-label inlineCheckbox_{{ $amaev->id }}_{{ $i }}" for="{{ $amaev->controlName->control_type . $count }}">
                                            {{ $checkSourceValue[$i] }}
                                        </label>
                                    </div>
                                @endfor
                                @if($amaev->is_mandatory == '1')
                                    <br>
                                    <span class="checkbox-error-message" data-group="{{ $amaev->id }}" style="color: red; margin-top: 5px; display: none;">
                                        Please select option
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($amaev->controlName->control_type == 'select')
                        <div class=" form-group mb-3 row">
                            <label class="col-md-3" for="{{ $amaev->controlName->control_type . $count }}">
                                {{ $amaev->source_question }}@if($amaev->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <select class="form-select select" name="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" id="{{ $amaev->controlName->control_type }}[{{ $amaev->id }}]" data-is-mandatory="{{ ($amaev->is_mandatory == '1') ? 'true' : 'false' }}" style="width:80%;">
                                    <option value="">Select Option</option>
                                    @php
                                        $selectSourceValue = [];

                                        if($amaev->source_value != '') {
                                            $selectSourceValue = explode(',', $amaev->source_value);
                                        }
                                    @endphp

                                    @for($i = 0; $i< sizeof($selectSourceValue); $i++)
                                        <option value="{{ $selectSourceValue[$i] }}" {{ (($data == $selectSourceValue[$i]) ? 'selected' : '') }}>
                                            {{ $selectSourceValue[$i] }}
                                        </option>
                                    @endfor
                                </select>   
                                @if($amaev->is_mandatory == '1')
                                    <span class="select-error-message" style="color: red; margin-top: 5px;">
                                        Please select option
                                    </span>
                                @endif        
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        @endif   
	</div>
</form>

<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    

    $(document).ready(function(){
        $.each($('.metaDataDatepicker'), function() {
            $(this).datepicker({
                dateFormat: "dd M yy",
            });
        });
    });

    $(document).ready(function(){

        $("#addActualEndDate").validate({
            errorElement : 'span',
            rules: {
                actual_end_date: {
                    required: true,
                    min: false,
                },
                end_delay_reason_id: {
                    required: true,
                },
                end_delay_remark: {
                    required: true,
                },
            },
            messages: {
                actual_end_date: {
                    required: 'Please select actual end date',
                },
                end_delay_reason_id: {
                    required: 'Please select end activity reason',
                },
                end_delay_remark: {
                    required: 'Please enter end activity remark',
                },  
            }
        });
    });

     $(document).ready(function(){

        $(document).on('change', '#actual_end_date', function () {
            var actualEndDate = $('#actual_end_date').val();

            if (actualEndDate != '') {
                $('#actual_end_date-error').text('');
            }
        });

        $(document).on('change', '#end_delay_reason_id', function () {
            var actualEndDelayReason = $('#end_delay_reason_id').val();

            if (actualEndDelayReason != '') {
                $('#end_delay_reason_id-error').text('');
            }
        });
    });

   // metadata error message hide and show and actual end date modal data ajax threw save
    var endCount = 0;
    $(document).ready(function() {
        // Hide error messages initially
        $('.text-error-message').hide();
        $('.textarea-error-message').hide();
        $('.date-error-message').hide();
        $('.datetime-error-message').hide();
        $('.select-error-message').hide();
        $('.file-error-message').hide();

         // Attach change event to the radio buttons
        $('input[type^="radio"]').on('change', function() {
            // Find the specific group name of the changed input field
            const groupName = $(this).data('group');

            // Hide the radio error message for the changed input field
            $(`.radio-error-message[data-group="${groupName}"]`).hide();
        });

        // Attach change event to the radio buttons
        $('input[type^="checkbox"]').on('change', function() {
            // Find the specific group name of the changed input field
            const groupName = $(this).data('group');

            // Check if none of the checkboxes in the group are checked
            if ($(`input[type^="checkbox"][data-group="${groupName}"]:checked`).length === 0) {
                // Show the checkbox error message for the group
                $(`.checkbox-error-message[data-group="${groupName}"]`).show();
            } else {
                // Hide the checkbox error message when at least one checkbox is checked
                $(`.checkbox-error-message[data-group="${groupName}"]`).hide();
            }
        });

        // Attach input event to the text
        $('.text').on('input', function() {
            // Find the specific index of the changed input field
            const index = $('.text').index(this);

            // Hide the text box error message for the changed input field
            if ($(this).val() === "") {
                $(this).next('span.text-error-message').show();
            } else {
                $(this).next('span.text-error-message').hide();
            }
        });

        // Attach input event to the textarea
        $('.textArea').on('input', function() {
            // Find the specific index of the changed input field
            const index = $('.textArea').index(this);
            
            // Hide the text box error message for the changed input field
            if ($(this).val() === "") {
                $(this).next('span.textarea-error-message').show();
            } else {
                $(this).next('span.textarea-error-message').hide();
            }
        });

        // Attach input event to the date
        $('.date').on('change', function() {
            // Find the specific index of the changed input field
            const index = $('.date').index(this);
            
            // Hide the text box error message for the changed input field
            if ($(this).val() === "") {
                $(this).next('span.date-error-message').show();
            } else {
                $(this).next('span.date-error-message').hide();
            }
        });

        // Attach input event to the datetime
        $('.dateTime').on('change', function() {
            // Find the specific index of the changed input field
            const index = $('.dateTime').index(this);
            
            // Hide the text box error message for the changed input field
            if (($(this).val()) === "") {
                $(this).next('span.datetime-error-message').show();
            } else {
                $(this).next('span.datetime-error-message').hide();
            }
        });

        // Attach input event to the select
        $('.select').on('change', function() {
            // Find the specific index of the changed input field
            const index = $('.select').index(this);
            
            // Hide the text box error message for the changed input field
            if (($(this).val()) === "") {
                $(this).next('span.select-error-message').show();
            } else {
                $(this).next('span.select-error-message').hide();
            }
        });

        $(document).on('click', '.saveActualEndDate', function(){
            endCount++;
            var isFormValid = true;
            // Check if any radio button is checked and it's a mandatory field
            $('.radio-option').each(function () {
                const groupName = $(this).data('group');
                const isMandatory = $(this).data('is-mandatory') === true;

                if (($(`.inlineRadioOptions_${groupName}:checked`).length === 0) && (isMandatory)) {
                    $(`.radio-error-message[data-group="${groupName}"]`).show();
                    isFormValid = false;
                } else {
                    $(`.radio-error-message[data-group="${groupName}"]`).hide();
                }
            });

            // Check if any checkbox button is checked and it's a mandatory field
            $('.checkbox-option').each(function () {
                const groupName = $(this).data('group');
                const isMandatory = $(this).data('is-mandatory') === true;
               
                if (($(`.inlineCheckboxOptions_${groupName}:checked`).length === 0) && (isMandatory)) {
                    $(`.checkbox-error-message[data-group="${groupName}"]`).show();
                    isFormValid = false;
                } else {
                    $(`.checkbox-error-message[data-group="${groupName}"]`).hide();
                }
            });

            // Loop through each input with name 'text'
            $('.text').each(function(index) {
                const isMandatory = $(this).data('is-mandatory') === true;

                if (($(this).val() === "") && (isMandatory)) {
                    // Show the text box error message for each specific input field
                    $(this).next('span.text-error-message').show();
                    isFormValid = false;
                } else {
                    // Hide the text box error message for each specific input field
                    $(this).next('span.text-error-message').hide();
                }
            });

            // Loop through each input with name 'textarea'
            $('.textArea').each(function(index) {
                const isMandatory = $(this).data('is-mandatory') === true;
         
                if (($(this).val() === "") && (isMandatory)) {
                    // Show the textarea error message for each specific input field
                    $(this).next('span.textarea-error-message').show();
                    isFormValid = false;
                } else {
                    // Hide the textarea error message for each specific input field
                    $(this).next('span.textarea-error-message').hide();
                }
            });

            // Loop through each input with name 'date'
            $('.date').each(function(index) {
                const isMandatory = $(this).data('is-mandatory') === true;
         
                if (($(this).val() === "") && (isMandatory)) {
                    // Show the date box error message for each specific input field
                    $(this).next('span.date-error-message').show();
                    isFormValid = false;
                } else {
                    // Hide the date box error message for each specific input field
                    $(this).next('span.date-error-message').hide();
                }
            });

            // Loop through each input with name 'datetime'
            $('.dateTime').each(function(index) {
                const isMandatory = $(this).data('is-mandatory') === true;
         
                if (($(this).val() === "") && (isMandatory)) {        
                    // Show the datetime box error message for each specific input field
                    $(this).next('span.datetime-error-message').show();
                    isFormValid = false;
                } else {
                    // Hide the datetime box error message for each specific input field
                    $(this).next('span.datetime-error-message').hide();
                }
            });

            // Loop through each input with name 'select'
            $('.select').each(function(index) {
                const isMandatory = $(this).data('is-mandatory') === true;
         
                if (($(this).val() === "") && (isMandatory)) {
                    // Show the select box error message for each specific input field
                    $(this).next('span.select-error-message').show();
                    isFormValid = false;
                } else {
                    // Hide the daselecttetime box error message for each specific input field
                    $(this).next('span.select-error-message').hide();
                }
            });

            if(!$('#addActualEndDate').valid()){
               $('#addActualEndDate').validate();
               isFormValid = false;     
            }

            if(!isFormValid) {
                endCount = 0;
            }

            if((isFormValid == true) && (endCount == 1)){
                var formData = $('#addActualEndDate').serialize();
                var scheduledId = $('#id').val();
                var roleId = $('#role_id').val();
                var endTitle = '';
                var convertSchedulesStatus = '';
                var endDelayReasonId = $('#end_delay_reason_id option:selected').val();
                var endDelayReason = $('#end_delay_reason_id option:selected').text().trim();
                var endDelayRemark = $('#end_delay_remark').val();

                if($('#endActivityReason').css('display') != 'none'){
                    if((endDelayReasonId != '') && (endDelayReasonId != 0)){
                        endTitle = endDelayReason;
                    } else if((endDelayReasonId != '') && (endDelayReasonId == 0)){
                        endTitle = endDelayReason + " - " + endDelayRemark;
                    }
                }

                $.ajax({

                    method: 'POST',
                    url: "/sms-admin/study-schedule-monitoring/add/save-study-schedule-actual-end-date-modal",
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        toastr.success('Actual end date successfully updated!');
                        $('#openEndDateModal').modal('hide');
                        convertSchedulesStatus = response.scheduleStatus.charAt(0).toUpperCase() + response.scheduleStatus.slice(1).toLowerCase();
                        $('table tbody tr[data-id = "'+ scheduledId +'"] .actualEnddate').html(response.actualEndDate);
                        $('table tbody tr[data-id="' + scheduledId + '"] .actualEnddate').prop('title', endTitle);
                        $('table tbody tr[data-id = "'+ scheduledId +'"] .scheduleStatus').html(convertSchedulesStatus);

                        if((roleId != 2) && (roleId != 1)){
                            $('table tbody tr td a.saveEndDate[data-id="' + scheduledId +'"]').addClass('disabled');
                        }
                    },
                });
            }
        });
    }); 

    // validation For file
    $(document).ready(function() {
        $('.file-error-message').hide();
        $('form').on('submit', function(e) {
            $('input[type="file"]').each(function(index) {
                const fileInput = this;
                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                const fileError = $(fileInput).closest('.form-group').find('.file-error');
                const fileErrorMessage = $(fileInput).closest('.form-group').find('.file-error-message');
                if (file && file.type === 'application/pdf' && file.size > maxSize) {
                    fileError.show();
                    fileErrorMessage.hide();
                    e.preventDefault();
                } else if (!file && $(fileInput).data('is-mandatory') === true) {
                    fileErrorMessage.show();
                    e.preventDefault();
                } else {
                    fileError.hide(); // Display error message
                    fileErrorMessage.hide();
                }
            });
        });

        $('input[type="file"]').on('change', function() {
            const fileInput = this;
            const fileError = $(fileInput).closest('.form-group').find('.file-error');
            const fileErrorMessage = $(fileInput).closest('.form-group').find('.file-error-message');
            const isRequired = $(fileInput).data('is-mandatory') === true;
            const file = fileInput.files[0];

            if (isRequired && !file) {
                fileErrorMessage.show();
                fileError.hide();
            } else {
                fileErrorMessage.hide();
                fileError.hide(); // Clear custom validity
            }
        });
    });



   
</script>