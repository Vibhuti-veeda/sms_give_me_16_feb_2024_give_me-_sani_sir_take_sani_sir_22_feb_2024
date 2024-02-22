@extends('layouts.admin')
@section('title','All Study Activity Monitoring')
@section('content')

<div class="page-content">
    <div class="container-fluid">

       <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Study Activity Monitoring</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">All Study Activity Monitoring</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionExample">
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#studyCollapseFilter" aria-expanded="true" aria-controls="studyCollapseFilter">
                        Filters
                    </button>
                </h2>
                <div id="studyCollapseFilter" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="post" action="{{ route('admin.studyActivityMonitoringList') }}">
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <label class="control-label">Project Managers</label>
                                    <select class="form-control select2" name="project_manager" style="width: 100%;">
                                        <option value="">Select Project Managers</option>
                                        @if(!is_null($projectManagers))
                                            @foreach($projectManagers as $pk => $pv)
                                                <option @if($projectManagerName == $pv->id) selected @endif value="{{ $pv->id }}">
                                                    {{ $pv->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="control-label">Study No</label>
                                    <select class="form-control select2" name="study_name" style="width: 100%;">
                                        <option value="">Select Study No</option>
                                        @if(!is_null($studies))
                                            @foreach($studies as $sk => $sv)
                                                <option @if($studyName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->study_no }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="control-label">Activity Name</label>
                                    <select class="form-control select2" multiple name="activity_id[]" style="width: 100%;">
                                        <option value="">Select Activity Name</option>
                                        @if(!is_null($activities))
                                            @foreach($activities as $ak => $av)
                                                <option @if(in_array($av->id, $activityName)) selected @endif value="{{ $av->id }}">
                                                    {{ $av->activity_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="control-label">Sponsor Name</label>
                                    <select class="form-control select2" name="sponsor_id" style="width: 100%;">
                                        <option value="">Select Sponsor Name</option>
                                        @if(!is_null($sponsors))
                                            @foreach($sponsors as $sk => $sv)
                                                <option @if($sponsorName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->sponsor_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="control-label">Activity Status</label>
                                    <select class="form-control select2" name="activity_status" style="width: 100%;">
                                        <option value="">Select Activity Status</option>
                                        @if(!is_null($activityStatusMaster))
                                            @foreach($activityStatusMaster as $ak => $av)
                                                <option @if($activityStatusName == $av->activity_status_code || $status == $av->activity_status_code) selected @endif value="{{ $av->activity_status_code }}">
                                                    {{ $av->activity_status }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">CR Location</label>
                                    <select class="form-control select2" name="cr_location" style="width: 100%;">
                                        <option value="">Select CR Location</option>
                                        @if(!is_null($crLocation))
                                            @foreach($crLocation as $ck => $cv)
                                                <option @if($crLocationName == $cv->id) selected @endif value="{{ $cv->id }}">
                                                    {{ $cv->location_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">BR Location</label>
                                    <select class="form-control select2" name="br_location" style="width: 100%;">
                                        <option value="">Select BR Location</option>
                                        @if(!is_null($brLocation))
                                            @foreach($brLocation as $bk => $bv)
                                                <option @if($brLocationName == $bv->id) selected @endif value="{{ $bv->id }}">
                                                    {{ $bv->location_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <label class="control-label">Study Sub Type</label>
                                    <select class="form-control select2" name="study_sub_type" style="width: 100%;">
                                        <option value="">Select Study Sub Type</option>
                                        @if(!is_null($studySubTypes))
                                            @foreach($studySubTypes as $sstk => $sstv)
                                                <option @if($studySubType == $sstv->id) selected @endif value="{{ $sstv->id }}">
                                                    {{ $sstv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">CDisc</label>
                                    <select class="form-control select2" name="cdisc" style="width: 100%;">
                                        <option value="">All</option>
                                        <option value="1" @if($CDisc == 1) selected @endif>Yes</option>
                                        <option value="0" @if($CDisc == 0 && $CDisc != '') selected @endif>No</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label>Schedule Start Date Range</label>
                                        <div>
                                            <div class="input-daterange input-group" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-provide="datepickerStyle" autocomplete="off">
                                                <input type="text" class="form-control datepickerStyle" name="start_date" value="{{ $startDate }}" autocomplete="off" placeholder="From Date">
                                                <input type="text" class="form-control datepickerStyle" name="end_date" value="{{ $endDate }}" autocomplete="off" placeholder="To Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label>Schedule End Date Range</label>
                                        <div>
                                            <div class="input-daterange input-group" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-provide="datepickerStyle" autocomplete="off">
                                                <input type="text" class="form-control datepickerStyle" name="schedule_start_date" value="{{ $scheduleStartDate }}" autocomplete="off" placeholder="From Date">
                                                <input type="text" class="form-control datepickerStyle" name="schedule_end_date" value="{{ $scheduleEndDate }}" autocomplete="off" placeholder="To Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label>Actual Start Date</label>
                                        <div>
                                            <div class="input-daterange input-group" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-provide="datepickerStyle" autocomplete="off">
                                                <input type="text" class="form-control datepickerStyle" name="actual_start_date" value="{{ $actualStartDate }}" autocomplete="off" placeholder="From Date">
                                                <input type="text" class="form-control datepickerStyle" name="actual_end_date" value="{{ $actualEndDate }}" autocomplete="off" placeholder="To Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group ">
                                        <label>Actual End Date</label>
                                        <div>
                                            <div class="input-daterange input-group" data-date-format="dd/mm/yyyy"data-date-autoclose="true" data-provide="datepickerStyle" autocomplete="off">
                                                <input type="text" class="form-control datepickerStyle" name="actual_end_start_date" value="{{ $actualEndStartDate }}" autocomplete="off" placeholder="From Date">
                                                <input type="text" class="form-control datepickerStyle" name="actual_end_end_date" value="{{ $actualEndEndDate }}" autocomplete="off" placeholder="To Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1 mt-4 pt-1">
                                    <button type="submit" class="btn btn-primary vendors save_button mt-4">Submit</button>
                                </div>
                                @if(isset($filter) && ($filter == 1))
                                    <div class="col-md-1 mt-4 pt-1">
                                        <a href="{{ route('admin.studyActivityMonitoringList') }}" class="btn btn-danger mt-4 cancel_button" id="filter" name="save_and_list" value="save_and_list" style="margin-left:-10px !important;">
                                            Reset
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-activitylist" class="table table-striped table-bordered nowrap datatable-searches" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Study No</th>
                                    <th>Activity Name</th>
                                    <th>Schedule Start Date</th>
                                    <th>Actual Start Date</th>
                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                        <th>Actual Start Date(Filled)</th>
                                    @endif
                                    <th>Schedule End Date</th>
                                    <th>Actual End Date</th>
                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                        <th>Actual End Date(Filled)</th>
                                    @endif
                                    <th>Activity Status</th>
                                    <!-- <th>Group No</th>
                                    <th>Period No</th> -->
                                    <th>Start Remark</th>
                                    <th>End Remark</th>
                                    <th>Project Manager</th>
                                    <th>Sponsor Name</th>
                                    <th>CR Location</th>
                                    <th>BR Location</th>
                                    <th>Study Type</th>
                                    <th>Regulatory</th>
                                    <th>Sub Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!is_null($activityStatus))
                                    @foreach($activityStatus as $ak => $av)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            
                                            <td class="bolfFont">
                                                <a id="myModal" data-id="{{ $av->study_id }}" data-toggle="modal" data-target="#openScheduleDelayModal" href="Javascript:void(0)">
                                                    {{ ((!is_null($av->studyNo)) && ($av->studyNo->study_no != '')) ? $av->studyNo->study_no : '---' }}
                                                </a> 
                                            </td>
                                            
                                            @if(Auth::guard('admin')->user()->role_id == 6)
                                                <td class="bolfFont">
                                                    {{ $av->activity_name }} @if($av->group_no != 1)(G{{ $av->group_no }}) @endif @if(($av->group_no != 1) && ($av->period_no != 1)) - @endif @if($av->period_no != 1) (P{{ $av->period_no }}) @endif @if($av->activity_version_type != '') ({{ $av->activity_version_type }}-{{ $av->activity_version }}) @endif
                                                </td>
                                            @elseif(in_array($av->id, $projectManagerActivities) || Auth::guard('admin')->user()->role_id == 1)
                                                <td class="bolfFont">
                                                    <a href="{{ route('admin.studyScheduleStatus', base64_encode($av->study_id)) }}">
                                                        {{ $av->activity_name }} @if($av->group_no != 1)(G{{ $av->group_no }}) @endif @if(($av->group_no != 1) && ($av->period_no != 1)) - @endif @if($av->period_no != 1) (P{{ $av->period_no }}) @endif @if($av->activity_version_type != '') ({{ $av->activity_version_type }}-{{ $av->activity_version }}) @endif
                                                    </a>
                                                </td>
                                            @else
                                                <td>
                                                    {{ $av->activity_name }} @if($av->group_no != 1)(G{{ $av->group_no }}) @endif @if(($av->group_no != 1) && ($av->period_no != 1)) - @endif @if($av->period_no != 1) (P{{ $av->period_no }}) @endif @if($av->activity_version_type != '') ({{ $av->activity_version_type }}-{{ $av->activity_version }}) @endif
                                                </td>
                                            @endif

                                            <td>
                                                {{ $av->scheduled_start_date != '' ? date('d M Y', strtotime($av->scheduled_start_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ $av->actual_start_date != '' ? date('d M Y', strtotime($av->actual_start_date)) : '---' }}
                                            </td>
                                            @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                <td>
                                                    {{ $av->actual_start_date_time != '' ? date('d M Y H:i:s', strtotime($av->actual_start_date_time)) : '---' }}
                                                </td>
                                            @endif
                                            <td>
                                                {{ $av->scheduled_end_date != '' ? date('d M Y', strtotime($av->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ $av->actual_end_date != '' ? date('d M Y', strtotime($av->actual_end_date)) : '---' }}
                                            </td>
                                            @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                <td>
                                                    {{ $av->actual_end_date_time != '' ? date('d M Y H:i:s', strtotime($av->actual_end_date_time)) : '---' }}
                                                </td>
                                            @endif
                                            <td>
                                                {{ ((!is_null($av->activityName)) && ($av->activityName->activity_status != '')) ? $av->activityName->activity_status : '---' }}
                                            </td>
                                            <!-- <td>{{ $av->group_no }}</td>
                                            <td>{{ $av->period_no }}</td> -->
                                            <td>
                                                @if(($av->start_delay_reason_id == '') && ($av->start_delay_remark != ''))
                                                    Other - {{ $av->start_delay_remark }}
                                                @elseif(((!is_null($av->startDelayReason)) && ($av->startDelayReason->start_delay_remark != '')))
                                                    {{ $av->startDelayReason->start_delay_remark }}
                                                @elseif($av->start_delay_remark != '')
                                                    {{ $av->start_delay_remark }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(($av->end_delay_reason_id == '') && ($av->end_delay_remark != ''))
                                                    Other - {{ $av->end_delay_remark }}
                                                @elseif(((!is_null($av->endDelayReason)) && ($av->endDelayReason->end_delay_remark != '')))
                                                    {{ $av->endDelayReason->end_delay_remark }}
                                                @elseif($av->end_delay_remark != '')
                                                    {{ $av->end_delay_remark }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ ((!is_null($av->studyNo)) && (!is_null($av->studyNo->projectManager)) && ($av->studyNo->projectManager->name != '')) ? $av->studyNo->projectManager->name : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($av->studyNo)) && (!is_null($av->studyNo->sponsorName)) && ($av->studyNo->sponsorName->sponsor_name != '')) ? $av->studyNo->sponsorName->sponsor_name : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($av->studyNo)) && (!is_null($av->studyNo->crLocationName)) && ($av->studyNo->crLocationName->location_name != '')) ? $av->studyNo->crLocationName->location_name : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($av->studyNo)) && (!is_null($av->studyNo->brLocationName)) && ($av->studyNo->brLocationName->location_name != '')) ? $av->studyNo->brLocationName->location_name : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($av->studyNo)) && (!is_null($av->studyNo->studyType)) && ($av->studyNo->studyType->para_value != '')) ? $av->studyNo->studyType->para_value : '-' }}
                                            </td>
                                            <td>
                                                @if((!is_null($av->studyNo)) && (!is_null($av->studyNo->studyRegulatories)) && (!is_null($av->studyNo->studyRegulatories->regulatorySubmission)))
                                                    @php $regulatory = []; @endphp
                                                    @foreach($av->studyNo->studyRegulatories->regulatorySubmission as $rk => $rv)
                                                        @php 
                                                            $regulatory[] = $rv->para_value;
                                                        @endphp
                                                    @endforeach
                                                    <p>{{ implode(' | ', $regulatory) }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                {{ ((!is_null($av->studyNo)) && (!is_null($av->studyNo->studySubTypeName)) && ($av->studyNo->studySubTypeName->para_value != '')) ? $av->studyNo->studySubTypeName->para_value : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection