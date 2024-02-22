@extends('layouts.admin')
@section('title','Add Study Schedule Activity Status')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        Add Study Schedule Activity Status :
                        <span style="color:blue;">
                            {{ $study->study_no }}
                        </span>
                    </h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.studyScheduleStatus', $id) }}">
                                    All Study Schedule Status
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                Add Study Schedule Activity Status
                            </li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveStudyScheduleActivityStatus') }}" method="post" id="addStudyScheduleActivityStatus" enctype="multipart/form-data">
            @csrf
            <!-- <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <input type="hidden" name="id" value="{{ $schedule->id }}">
                            <input type="hidden" name="study_id" value="{{ $schedule->study_id }}">

                            <div class="mb-3">
                                <label>Activity Name<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" id="activity_name" name="activity_name" placeholder="Activity Name" autocomplete="off" value="{{ $schedule->activity_name }}" readonly />
                            </div>

                            <div class="mb-3">
                                <label>Schedule Start Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="schedule_start_dt" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ date('d/m/Y', strtotime($schedule->scheduled_start_date)) }}" disabled>
                                <input type="hidden" name="schedule_start_date" value="{{ date('d/m/Y', strtotime($schedule->scheduled_start_date)) }}">
                            </div>

                            <div class="mb-3">
                                <label>Actual Start Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="actual_start_date" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ $schedule->actual_start_date != '' ? date('d/m/Y', strtotime($schedule->actual_start_date)) : '' }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Start Delay Remark</label>
                                <input type="text" class="form-control" id="start_delay_remark" name="start_delay_remark" placeholder="Start Delay Remark" autocomplete="off" value="{{ $schedule->start_delay_remark }}"/>
                            </div>

                            <div class="mb-3">
                                <label>Schedule End Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="schedule_end_dt" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ date('d/m/Y', strtotime($schedule->scheduled_end_date)) }}" disabled>
                                <input type="hidden" name="schedule_end_date" value="{{ date('d/m/Y', strtotime($schedule->scheduled_end_date)) }}">
                            </div>

                            <div class="mb-3">
                                <label>Actual End Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="actual_end_date" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ $schedule->actual_end_date != '' ? date('d/m/Y', strtotime($schedule->actual_end_date)) : '' }}">
                            </div>

                            <div class="mb-3">
                                <label>End Delay Remark</label>
                                <input type="text" class="form-control" id="end_delay_remark" name="end_delay_remark" placeholder="End Delay Remark" autocomplete="off" value="{{ $schedule->end_delay_remark }}"/>
                            </div>

                            <center>
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                    Save
                                </button>
                                <a href="{{ route('admin.studyScheduleMonitoringList') }}" class="btn btn-danger waves-effect">
                                    Cancel
                                </a>
                            </center>
                        </div>
                    </div>
                </div>

            </div> -->

            <div class="row">
                <div class="col-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>
                            <br><br>

                            <input type="hidden" name="id" value="{{ $schedule->id }}">
                            <input type="hidden" name="study_id" value="{{ $schedule->study_id }}">

                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-3 col-form-label">
                                    Activity Name<span class="mandatory">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="activity_name" name="activity_name" placeholder="Activity Name" autocomplete="off" value="{{ $schedule->activity_name }}" readonly />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="example-search-input" class="col-md-3 col-form-label">
                                    Schedule Start Date<span class="mandatory">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control scheduleStartDate datepickerStyle" name="schedule_start_dt" placeholder="dd-mm-yyyy" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ date('d-m-Y', strtotime($schedule->scheduled_start_date)) }}" disabled>
                                    <input type="hidden" name="schedule_start_date" value="{{ date('d-m-Y', strtotime($schedule->scheduled_start_date)) }}" >
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="example-email-input" class="col-md-3 col-form-label">
                                    Actual Start Date<span class="mandatory">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control actualStartDate datepickerStyle" name="actual_start_date" placeholder="dd-mm-yyyy" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $schedule->actual_start_date != '' ? date('d-m-Y', strtotime($schedule->actual_start_date)) : '' }}" required @if($schedule->actual_start_date != '') readonly @endif>
                                </div>
                            </div>
                            @if($schedule->start_delay_remark != '')
                                <div class="mb-3 row delayedStartRemark" >
                                    <label for="example-url-input" class="col-md-3 col-form-label">
                                        Delayed Start Remark
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="start_delay_remark" name="start_delay_remark" placeholder="Delayed Start Remark" autocomplete="off" value="{{ $schedule->start_delay_remark }}" disabled />
                                    </div>
                                </div>
                            @else
                                <div class="mb-3 row delayedStartRemark" style="display: none;">
                                    <label for="example-url-input" class="col-md-3 col-form-label">
                                        Delayed Start Remark
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="start_delay_remark" name="start_delay_remark" placeholder="Delayed Start Remark" autocomplete="off" value="{{ $schedule->start_delay_remark }}"/>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3 row">
                                <label for="example-tel-input" class="col-md-3 col-form-label">
                                    Schedule End Date<span class="mandatory">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control scheduleEndDate datepickerStyle" name="schedule_end_dt" placeholder="dd/mm/yyyy" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ date('d-m-Y', strtotime($schedule->scheduled_end_date)) }}" disabled>
                                    <input type="hidden" name="schedule_end_date" value="{{ date('d-m-Y', strtotime($schedule->scheduled_end_date)) }}">
                                </div>
                            </div>
                            @if($schedule->actual_start_date != '')
                                <div class="mb-3 row">
                                    <label for="example-password-input" class="col-md-3 col-form-label">
                                        Actual End Date
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control actualEndDate datepickerStyle" name="actual_end_date" placeholder="dd-mm-yyyy" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $schedule->actual_end_date != '' ? date('d-m-Y', strtotime($schedule->actual_end_date)) : '' }}">
                                    </div>
                                </div>
                            @endif
                            @if($schedule->end_delay_remark != '')
                                <div class="mb-3 row delayedEndRemark">
                                    <label for="example-number-input" class="col-md-3 col-form-label">
                                        Delayed End Remark
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="end_delay_remark" name="end_delay_remark" placeholder="Delayed End Remark" autocomplete="off" value="{{ $schedule->end_delay_remark }}" disabled />
                                    </div>
                                </div>
                            @else
                                <div class="mb-3 row delayedEndRemark" style="display: none;">
                                    <label for="example-number-input" class="col-md-3 col-form-label">
                                        Delayed End Remark
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="end_delay_remark" name="end_delay_remark" placeholder="Delayed End Remark" autocomplete="off" value="{{ $schedule->end_delay_remark }}"/>
                                    </div>
                                </div>
                            @endif
                            
                            <center>
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                    Save
                                </button>
                                <a href="{{ route('admin.studyScheduleStatus', base64_encode($schedule->study_id)) }}" class="btn btn-danger waves-effect">
                                    Cancel
                                </a>
                            </center>

                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection