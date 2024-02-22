<form class="custom-validation addActualStartDate" method="post" id="addActualStartDate" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $studySheduleStartDate->id }}">
    <input type="hidden" name="activity_id" id="activity_id" value="{{ $studySheduleStartDate->activity_id }}">
    <input type="hidden" name="study_id" id="study_id" value="{{ $studySheduleStartDate->study_id }}">
    <input type="hidden" name="schedule_start_date" id="schedule_start_date" value="{{ $studySheduleStartDate->scheduled_start_date }}">
    <input type="hidden" name="schedule_end_date" id="schedule_end_date" value="{{ $studySheduleStartDate->scheduled_end_date }}">
    <input type="hidden" name="actual_end_date" id="actual_end_date" value="{{ $studySheduleStartDate->actual_end_date }}">
    <input type="hidden" name="role_id" id="role_id" value="{{ Auth::guard('admin')->user()->role_id }}">

    @php
        $isReasonFilled = '';
        $isRemarkFilled = '';
        $actualStartDate = '';

        if(!is_null($studySheduleStartDate->actual_start_date)) {
            $actualStartDate = date('Y-m-d', strtotime($studySheduleStartDate->actual_start_date));

            if($actualStartDate > $studySheduleStartDate->scheduled_start_date) {
                $isReasonFilled = true;
                $isRemarkFilled = false;
            } else if($actualStartDate <= $studySheduleStartDate->scheduled_start_date) {
                $isReasonFilled = false;
                $isRemarkFilled = false;
            } 
        }

        if(($studySheduleStartDate->actual_start_date == '') && ($studySheduleStartDate->start_delay_reason_id == '') && ($studySheduleStartDate->start_delay_remark == '')) {
            $isReasonFilled = false;
            $isRemarkFilled = false;
        }

        if($studySheduleStartDate->start_delay_reason_id == '0') {
            $isReasonFilled = true;
            $isRemarkFilled = true;
        } else if(($studySheduleStartDate->start_delay_reason_id != '0') && ($studySheduleStartDate->start_delay_reason_id != null)) {
            $isReasonFilled = true;
            $isRemarkFilled = false;
        }

        if(($studySheduleStartDate->start_delay_remark != null) && ($studySheduleStartDate->start_delay_reason_id == '0')) {
            $isRemarkFilled = true;
        }
    @endphp

    <div class="row offset-1">

        <div class="form-group mb-3 row" >
            <label class="col-md-3 col-form-label">Schedule Start Date<span class="mandatory">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="scheduled_start_date" id="scheduled_start_date" placeholder="Enter schedule start date" autocomplete="off" value="{{ (!is_null($studySheduleStartDate) && $studySheduleStartDate->scheduled_start_date != '') ?  date('d M Y', strtotime($studySheduleStartDate->scheduled_start_date)) : ''}}" disabled style="width:80%;">
            </div>
        </div>
        
        <div class=" form-group mb-3 row" >
            <label class="col-md-3 col-form-label">Actual Start Date<span class="mandatory">*</span></label>
            <div class="col-md-9">
                @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1) 
                
                <!-- {{ $studySheduleStartDate->actual_start_date != '' ? date('d M Y', strtotime($studySheduleStartDate->actual_start_date)) : '' }} -->
                    
                    <input type="date" class="form-control actualStartDate scheduleDatepicker"  max="{{date('Y-m-d')}}" name="actual_start_date" id="actual_start_date" autocomplete="off" value="{{ ($studySheduleStartDate->actual_start_date != '') ?  date('Y-m-d', strtotime($studySheduleStartDate->actual_start_date)) : ''}}" required style="width:80%;">     
                @else
            
                <!-- {{ $studySheduleStartDate->actual_start_date != '' ? date('d M Y', strtotime($studySheduleStartDate->actual_start_date)) : '' }} -->
                    <input type="date" class="form-control actualStartDate scheduleDatepicker"  max="{{date('Y-m-d')}}" name="actual_start_date" id="actual_start_date" autocomplete="off" value="{{ (!is_null($studySheduleStartDate) && $studySheduleStartDate->actual_start_date != '') ?  date('Y-m-d', strtotime($studySheduleStartDate->actual_start_date)) : ''}}" @if($studySheduleStartDate->actual_start_date != '') ? disabled : '' @endif required style="width:80%;">
                @endif
            </div>
        </div>

        <div class=" form-group mb-3 row" style="{{ $isReasonFilled == true ? '' : 'display: none' }}" id="startActivityReason">
            <label class="col-md-3 col-form-label">Start Activity Reason<span class="mandatory">*</span></label>
            <div class="col-md-9">
                @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                    <select class="form-select start_delay_reason_id" name="start_delay_reason_id" id="start_delay_reason_id" data-placeholder="Select Activity Reason" required style="width:80%;">
                        <option value="">Select Activity Reason</option>
                        @if(!is_null($studySheduleStartDate->startDelayReasons))
                            @foreach($studySheduleStartDate->startDelayReasons as $sdrk => $sdrv)
                                @if($studySheduleStartDate->activity_id == $sdrv->activity_id)
                                    @if(!is_null($sdrv->start_delay_remark))
                                        <option @if($studySheduleStartDate->start_delay_reason_id == $sdrv->id) selected @endif value="{{ $sdrv->id }}">
                                            {{ $sdrv->start_delay_remark }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        <option @if($studySheduleStartDate->start_delay_reason_id == '0') selected @endif value="0">
                            Other
                        </option>
                    </select>       
                @else
                    <select class="form-select start_delay_reason_id" name="start_delay_reason_id" id="start_delay_reason_id" data-placeholder="Select Activity Reason" required @if($studySheduleStartDate->actual_start_date != '') ? disabled : '' @endif style="width:80%;">
                        <option value="">Select Activity Reason</option>
                        @if(!is_null($studySheduleStartDate->startDelayReasons))
                            @foreach($studySheduleStartDate->startDelayReasons as $sdrk => $sdrv)
                                @if($studySheduleStartDate->activity_id == $sdrv->activity_id)
                                    @if(!is_null($sdrv->start_delay_remark))
                                        <option @if($studySheduleStartDate->start_delay_reason_id == $sdrv->id) selected @endif value="{{ $sdrv->id }}">
                                            {{ $sdrv->start_delay_remark }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                        <option @if($studySheduleStartDate->start_delay_reason_id == '0') selected @endif value="0">
                            Other
                        </option>
                    </select>                
                @endif
            </div>
        </div>

        <div class=" form-group mb-3 row" style="{{ $isRemarkFilled == true ? '' : 'display: none' }}" id="startActivityRemark">
            <label class="col-md-3 col-form-label" >Start Activity Remark<span class="mandatory">*</span></label>
            <div class="col-md-9">
                @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                                    
                    <input type="text" class="form-control startDelayRemark" name="start_delay_remark" id="start_delay_remark" value="{{ (!is_null($studySheduleStartDate) && $studySheduleStartDate->start_delay_remark != '') ?  ($studySheduleStartDate->start_delay_remark) : ''}}" autocomplete="off" title="{{ $studySheduleStartDate->start_delay_remark }}" style="width:80%;">
                    <!-- {{ $studySheduleStartDate->start_delay_remark != '' ? $studySheduleStartDate->start_delay_remark : '---' }} -->
                @else    
                    <input type="text" class="form-control startDelayRemark" name="start_delay_remark" id="start_delay_remark" value="{{ (!is_null($studySheduleStartDate) && $studySheduleStartDate->start_delay_remark != '') ?  ($studySheduleStartDate->start_delay_remark) : ''}}" @if($studySheduleStartDate->actual_start_date != '') ? disabled : '' @endif required autocomplete="off" title="{{ $studySheduleStartDate->start_delay_remark }}" style="width:80%;">
                    <!-- {{ $studySheduleStartDate->start_delay_remark != '' ? $studySheduleStartDate->start_delay_remark : '---' }} -->       
                @endif 
            </div>
        </div>

        @php
            $count = 0;
        @endphp

        @if(!is_null($activityMetaDataActualStartDate))
            @foreach($activityMetaDataActualStartDate as $amask => $amasv)
                @if((!is_null($amasv->controlName)) && ($amasv->controlName->control_type != ''))
                    @php
                        $count++;
                        $data = '';
 
                        if((!is_null($amasv->studyActivityMetadata)) && ($amasv->studyActivityMetadata->actual_value != '')) {
                            $data = $amasv->studyActivityMetadata->actual_value;
                        }
                    @endphp

                    @if($amasv->controlName->control_type == 'text')
                        <div class=" form-group mb-3 row">
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control text" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" id="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" value="{{ $data }}" autocomplete="off" data-is-mandatory = "{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}"maxlength="250" style="width:80%;">  
                                @if($amasv->is_mandatory  == '1')
                                    <span class="text-error-message" style="color: red; margin-top: 5px;">
                                        Please enter text
                                    </span>
                                @endif 
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'textarea')
                        <div class=" form-group mb-3 row">
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <textarea class="form-control textArea" autocomplete="off" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" id="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" data-is-mandatory="{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}" maxlength="1000"
                                style="width:80%;">{{ $data }}</textarea>   
                                @if($amasv->is_mandatory  == '1')
                                    <span class="textarea-error-message" style="color: red; margin-top: 5px;">
                                        Please enter text
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'date')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control metaDataDatepicker date" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="dd mm yyyy" autocomplete="off" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" id="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" value="{{ (($data != '') ? date('d M Y', strtotime($data)) : '') }}" data-is-mandatory="{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}"
                                style="width:80%;">
                                @if($amasv->is_mandatory  == '1')
                                    <span class="date-error-message" style="color: red; margin-top: 5px;">
                                        Please select date
                                    </span>
                                @endif        
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'datetime')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="datetime-local" class="form-control dateTime" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="yyyy-mm-dd" autocomplete="off"
                                name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" id="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" value="{{ (($data != '') ? date('Y-m-d\TH:i', strtotime($data)) : '') }}" data-is-mandatory="{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}" style="width:80%;">
                                @if($amasv->is_mandatory  == '1')
                                    <span class="datetime-error-message" style="color: red; margin-top: 5px;">
                                        Please select date-time
                                    </span>
                                @endif         
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'file')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <input type="file" class="form-control file" autocomplete="off" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" id="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" value="{{ $data }}" data-is-mandatory="{{ (($amasv->is_mandatory == '1') && ($data == ''))  ? 'true' : 'false' }}" accept=".pdf"  style="width:80%;">
                                @if($amasv->is_mandatory  == '1')
                                    <span class="file-error-message" style="color: red; margin-top: 5px; display: block;">
                                        Please upload file
                                    </span>
                                @endif 
                                <span class="file-error" style="display:none; margin-top: 5px; color:red;">
                                    File size exceeds 5MB limit
                                </span>   
                                @if($data != '')
                                    <div>
                                        <a class="btn btn-primary btn-sm waves-effect waves-light mt-2" href="{{ asset('uploads/activity_metadata/actual_start') }}/{{ $data }}" title="View File" role="button" target="_blank">
                                            <i class="mdi mdi-file-pdf"></i>
                                        </a>
                                    </div>
                                @endif    
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'radio')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9" style="padding-top: 9px;width: 61%">
                                @php
                                    $radioSourceValue = [];

                                    if($amasv->source_value != '') {
                                        $radioSourceValue = explode(',', $amasv->source_value);
                                    }
                                @endphp

                                @for($i = 0; $i< sizeof($radioSourceValue); $i++)
                                    <div class="form-check form-check-inline pt-1">
                                        <input class="form-check-input radio-option inlineRadioOptions_{{ $amasv->id }}" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" type="radio" data-group="{{ $amasv->id }}" data-is-mandatory = "{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}" id="inlineRadio_{{ $amasv->id }}_{{ $i }}" value="{{ $radioSourceValue[$i] }}" {{ (($data == $radioSourceValue[$i]) ? 'checked' : '') }}>
                                        <label class="form-check-label inlineRadio_{{ $amasv->id }}_{{ $i }}" for="{{ $amasv->controlName->control_type . $count }}">
                                            {{ $radioSourceValue[$i] }}
                                        </label>
                                    </div>
                                @endfor
                                @if($amasv->is_mandatory == '1')
                                    <br>
                                    <span class="r1 radio-error-message" data-group="{{ $amasv->id }}" style="color: red; margin-top: 5px; display: none;">
                                        Please select option
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'checkbox')
                        <div class=" form-group mb-3 row" >
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{$amasv->source_question}}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9" style="padding-top: 9px;width: 61%">
                                @php
                                    $checkSourceValue = [];
                                    $actualValue = [];

                                    if($amasv->source_value != '') {
                                        $checkSourceValue = explode(',', $amasv->source_value);
                                    }

                                    if($data != '') {
                                        $actualValue = explode('|', $data);
                                    }
                                @endphp

                                @for($i = 0; $i< sizeof($checkSourceValue); $i++)
                                    <div class="form-check form-check-inline pt-1">
                                        <input class="form-check-input checkbox-option inlineCheckboxOptions_{{ $amasv->id }}" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}][]" type="checkbox" data-group="{{ $amasv->id }}" id="inlineCheckbox_{{ $amasv->id }}_{{ $i }}" data-is-mandatory = "{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}" value="{{ $checkSourceValue[$i] }}" {{ ((!empty($actualValue)) && (in_array($checkSourceValue[$i], $actualValue))) ? 'checked' : '' }}>
                                        <label class="form-check-label inlineCheckbox_{{ $amasv->id }}_{{ $i }}" for="{{ $amasv->controlName->control_type . $count }}">
                                            {{ $checkSourceValue[$i] }}
                                        </label>
                                    </div>
                                @endfor
                                @if($amasv->is_mandatory == '1')
                                    <br>
                                    <span class="checkbox-error-message" data-group="{{ $amasv->id }}" style="color: red; margin-top: 5px; display: none;">
                                        Please select option
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($amasv->controlName->control_type == 'select')
                        <div class=" form-group mb-3 row">
                            <label class="col-md-3" for="{{ $amasv->controlName->control_type . $count }}">
                                {{ $amasv->source_question }}@if($amasv->is_mandatory  == '1')<span class="mandatory">*</span>@endif
                            </label>
                            <div class="col-md-9">
                                <select class="form-select select" name="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" id="{{ $amasv->controlName->control_type }}[{{ $amasv->id }}]" data-is-mandatory="{{ ($amasv->is_mandatory == '1') ? 'true' : 'false' }}" style="width:80%;">
                                    <option value="">Select Option</option>
                                    @php
                                        $selectSourceValue = [];

                                        if($amasv->source_value != '') {
                                            $selectSourceValue = explode(',', $amasv->source_value);
                                        }
                                    @endphp

                                    @for($i = 0; $i< sizeof($selectSourceValue); $i++)
                                        <option value="{{ $selectSourceValue[$i] }}" {{ (($data == $selectSourceValue[$i]) ? 'selected' : '') }}>
                                            {{ $selectSourceValue[$i] }}
                                        </option>
                                    @endfor
                                </select>   
                                @if($amasv->is_mandatory == '1')
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

        $("#addActualStartDate").validate({
            errorElement : 'span',
            rules: {
                actual_start_date: {
                    required: true,
                },
                start_delay_reason_id: {
                    required: true,
                },
                start_delay_remark: {
                    required: true,
                },
            },
            messages: {
                actual_start_date: {
                    required: 'Please select actual start date',
                },
                start_delay_reason_id: {
                    required: 'Please select start activity reason',
                },
                start_delay_remark: {
                    required: 'Please enter start activity remark',
                },     
            }
        });
    });    

    $(document).ready(function(){

        $(document).on('change', '#actual_start_date', function () {
            var actualStartDate = $('#actual_start_date').val();

            if (actualStartDate != '') {
                $('#actual_start_date-error').text('');
            }
        });

        $(document).on('change', '#start_delay_reason_id', function () {
            var actualStartDelayReason = $('#start_delay_reason_id').val();

            if (actualStartDelayReason != '') {
                $('#start_delay_reason_id-error').text('');
            }
        });
    });

    var startCount = 0;
    $(document).ready(function(){
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

        $(document).on('click', '.saveActualStartDate', function(){
            startCount++;
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

            if(!$('#addActualStartDate').valid()){
                $('#addActualStartDate').validate();
                isFormValid = false;
            }

            if(!isFormValid) {
                startCount = 0;
            }

             if((isFormValid == true) && (startCount == 1)){
                var formData = $('#addActualStartDate').serialize();
                var scheduledId = $('#id').val();
                var roleId = $('#role_id').val();
                var startTitle = '';
                var convertSchedulesStatus = '';
                var startDelayReasonId = $('#start_delay_reason_id option:selected').val();
                var startDelayReason = $('#start_delay_reason_id option:selected').text().trim();
                var startDelayRemark = $('#start_delay_remark').val();

                if($('#startActivityReason').css('display') != 'none'){
                    if((startDelayReasonId != '') && (startDelayReasonId != 0)){
                        startTitle = startDelayReason;
                    } else if((startDelayReasonId != '') && (startDelayReasonId == 0)){
                        startTitle = startDelayReason + " - " + startDelayRemark;
                    }
                }

                $.ajax({

                    method: 'POST',
                    url: "/sms-admin/study-schedule-monitoring/add/save-study-schedule-actual-start-date-modal",
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        toastr.success('Actual start date successfully updated!'); 
                        $('#openStartDateModal').modal('hide');
                        convertSchedulesStatus = response.scheduleStatus.charAt(0).toUpperCase() + response.scheduleStatus.slice(1).toLowerCase();
                        $('table tbody tr[data-id="' + scheduledId + '"] .actualStartdate').html(response.actualStartDate);
                        $('table tbody tr[data-id="' + scheduledId + '"] .actualStartdate').prop('title', startTitle);
                        $('table tbody tr[data-id="' + scheduledId + '"] .scheduleStatus').html(convertSchedulesStatus);

                        if((roleId == 2) || (roleId == 1)) {
                            $('table tbody tr td a.saveEndDate[data-id="' + scheduledId +'"]').removeClass('disabled');
                        } else {
                            $('table tbody tr td a.saveStartDate[data-id="' + scheduledId +'"]').addClass('disabled');
                            $('table tbody tr td a.saveEndDate[data-id="' + scheduledId +'"]').removeClass('disabled');
                        }
                    },
                });
            }
        });   
    })

    // validation
    $(document).ready(function() {
       
        // Attach a submit event to the form
        $('form').submit(function(event) {

            
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