@extends('layouts.admin')
@section('title','Study Schedule Status')
@section('content')

<div class="page-content">
    <div class="container-fluid">

       <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Study Schedule Status : 
                        <span style="color:blue;">
                            {{ $study->study_no }}

                            @if(!is_null($study->drugDetails)) 
                                @php $drug = ''; @endphp
                                @foreach($study->drugDetails as $dk => $dv)
                                    @if(!is_null($dv->drugName) && !is_null($dv->drugDosageName) && !is_null($dv->dosage) && !is_null($dv->drugUom) && !is_null($dv->drugType) && $dv->drugType->type == 'TEST')
                                        @php 
                                            $drug = $dv->drugName->drug_name;
                                        @endphp
                                    @endif
                                    
                                @endforeach

                                ({{ $drug != '' ? $drug : '---' }} - {{ (!is_null($study->sponsorName) && ($study->sponsorName->sponsor_name != '')) ? $study->sponsorName->sponsor_name : '' }} - {{ (!is_null($study->projectManager) && ($study->projectManager->name != '')) ? $study->projectManager->name : '' }})
                                
                            @endif
                        </span>
                    </h4>

                   <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.studyScheduleMonitoringList') }}">
                                    All Study Schedule Tracking
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Study Schedule Status</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>

        @if(!is_null($activitySchedule))
            @foreach($activitySchedule as $sk => $av)
                @if(count($av->activities)>0)
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{$av->para_value}}" aria-expanded="false" aria-controls="{{$av->para_value}}">
                                    {{ $av->para_value }} Activities
                                </button>
                            </h2>
                            <div id="{{$av->para_value}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form id="studyScheduleMonitoringForm" action="#" method="post">
                                        <div class="row" id="hiii">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="form-group mb-3">
                                                            <table class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;display: block;overflow: auto;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sr. No</th>
                                                                        <th>Activity Name</th>
                                                                        <!-- <th>Group No</th>
                                                                        <th>Period No</th> -->
                                                                        <th>Schedule Start Date</th>
                                                                        <th>Actual Start Date</th>
                                                                        <th>Start Activity Remark</th>
                                                                        <th>Action</th>
                                                                        <th>Schedule End Date</th>
                                                                        <th>Actual End Date</th>
                                                                        <th>End Activity Remark</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>                                   
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(!is_null($av->activities))
                                                                        @php $srNo = 1; @endphp
                                                                        @foreach($av->activities as $aak => $aav)
                                                                            @if($aav->studySchedule)
                                                                                @foreach($aav->studySchedule as $ask=>$asv)
                                                                                    @if($asv != '')
                                                                                        <tr>
                                                                                            <td style="width:2%">
                                                                                                {{ $srNo++ }}
                                                                                            </td>
                                                                                            <td style="width:12%">
                                                                                                {{ $asv->activity_name }} @if($asv->group_no != 1)(G{{ $asv->group_no }}) @endif @if(($asv->group_no != 1) && ($asv->period_no != 1)) - @endif @if($asv->period_no != 1) (P{{ $asv->period_no }}) @endif
                                                                                            </td>
                                                                                            <!-- <td>{{ $av->group_no }}</td>
                                                                                            <td>{{ $av->period_no }}</td> -->
                                                                                            <td style="width:9%">
                                                                                                {{ (!is_null($asv) && $asv->scheduled_start_date != '') ?  date('d M Y', strtotime($asv->scheduled_start_date)) : '' }}
                                                                                            </td>

                                                                                            @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                                                                                                <td style="width:15%">
                                                                                                    <!-- {{ $aav->actual_start_date != '' ? date('d M Y', strtotime($aav->actual_start_date)) : '' }} -->
                                                                                                    <input type="text" class="form-control actualStartDate actualStartDate_{{ $asv->id }} scheduleDatepicker" name="actual_start_date" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ (!is_null($asv) && $asv->actual_start_date != '') ?  date('d M Y', strtotime($asv->actual_start_date)) : ''}}" data-id="{{ $aav->id }}" data-ssd="{{ (!is_null($asv) && $asv->scheduled_start_date != '') ?  ($asv->scheduled_start_date) : '' }}" required >
                                                                                                        
                                                                                                    <span id="actualStartDt{{ $asv->id }}"></span>
                                                                                                </td>
                                                                                            @else
                                                                                                <td style="width:15%">
                                                                                                    <!-- {{ $aav->actual_start_date != '' ? date('d M Y', strtotime($aav->actual_start_date)) : '' }} -->
                                                                                                    <input type="text" class="form-control actualStartDate actualStartDate_{{ $asv->id }} scheduleDatepicker" name="actual_start_date" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ (!is_null($asv) && $asv->actual_start_date != '') ?  date('d M Y', strtotime($asv->actual_start_date)) : ''}}" data-id="{{ $aav->id }}" data-ssd="{{ (!is_null($asv) && $asv->scheduled_start_date != '') ?  ($asv->scheduled_start_date) : '' }}" @if($asv->actual_start_date != '') ? disabled : '' @endif data-msg="Please select date" required>
                                                                                
                                                                                                    <span id="actualStartDt{{ $asv->id }}"></span>
                                                                                                </td>
                                                                                            @endif

                                                                                            @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                                                                                                <td style="width:10%">
                                                                                                    <input type="text" class="form-control startDelayRemark_{{ $asv->id }}" name="start_delay_remark"  data-msg="Please enter start activity remark" value="{{ (!is_null($asv) && $asv->start_delay_remark != '') ?  ($asv->start_delay_remark) : ''}}" autocomplete="off" title="{{ $asv->start_delay_remark }}">
                                                                                                    <!-- {{ $aav->start_delay_remark != '' ? $aav->start_delay_remark : '---' }} -->
                                                                                                    <span id="startDelayRemark{{ $asv->id }}"></span>
                                                                                                </td>
                                                                                            @else
                                                                                                <td style="width:10%">
                                                                                                    <input type="text" class="form-control startDelayRemark_{{ $asv->id }}" name="start_delay_remark"  data-msg="Please enter start activity remark" value="{{ (!is_null($asv) && $asv->start_delay_remark != '') ?  ($asv->start_delay_remark) : ''}}" @if($asv->actual_start_date != '') ? disabled : '' @endif required autocomplete="off" title="{{ $asv->start_delay_remark }}">
                                                                                                    <!-- {{ $aav->start_delay_remark != '' ? $aav->start_delay_remark : '---' }} -->
                                                                                                    <span id="startDelayRemark{{ $asv->id }}"></span>
                                                                                                </td>
                                                                                            @endif

                                                                                            @if(Auth::guard('admin')->user()->role_id == 3)
                                                                                                @if($asv->actual_start_date=='')
                                                                                                    <td style="width:5%;">
                                                                                                        <a class="btn btn-primary waves-effect waves-light saveStartDate saveStart_{{$aav->id}} " data-id="{{ $asv->id }}" name="start_save" value="{{ $asv->id }}" href="Javascript:void(0);" role="button" title="Save">
                                                                                                                Save
                                                                                                        </a>
                                                                                                    </td>
                                                                                                @else
                                                                                                    <td style="width:5%; pointer-events:none;">
                                                                                                        <a class="btn btn-primary waves-effect waves-light saveStartDate disabled" data-id="{{ $asv->id }} " href="Javascript:void(0);" role="button" name="end_save" title="Save">
                                                                                                            Save
                                                                                                        </a>
                                                                                                    </td>
                                                                                                @endif
                                                                                            @else
                                                                                                <td style="width:5%;">
                                                                                                    <a class="btn btn-primary waves-effect waves-light saveStartDate" data-id="{{ $asv->id }}" href="Javascript:void(0);" role="button" title="Save">
                                                                                                        Save
                                                                                                    </a>
                                                                                                </td>
                                                                                            @endif

                                                                                            <td style="width:9%">
                                                                                                {{ (!is_null($asv) && $asv->scheduled_end_date != '') ?  date('d M Y', strtotime($asv->scheduled_end_date)) : '' }}
                                                                                                
                                                                                            </td>

                                                                                            @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                                                                                                <td style="width:15%">
                                                                                                    <input type="text" class="form-control actualEndDate actualEndDate_{{ $asv->id }} scheduleDatepicker" data-startdate="{{$asv->actual_start_date}}" name="actual_end_date" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ $asv->actual_end_date != '' ? date('d M Y', strtotime($asv->actual_end_date)) : '' }}" data-id="{{ $asv->id }}" data-ssd="{{ $asv->scheduled_end_date }}" data-msg="Please select date" required>
                                                                                                    <span id="actualEndDt{{ $asv->id }}"></span>
                                                                                                    <!-- {{ $aav->actual_end_date != '' ? date('d M Y', strtotime($aav->actual_end_date)) : '' }} -->
                                                                                                </td>
                                                                                            @else
                                                                                                <td style="width:15%">
                                                                                                    <input type="text" class="form-control actualEndDate actualEndDate_{{ $asv->id }} scheduleDatepicker" data-startdate="{{$asv->actual_start_date}}" name="actual_end_date" placeholder="yyyy-mm-dd" data-date-autoclose="true" data-date-format="yyyy-mm-dd" autocomplete="off" value="{{ $asv->actual_end_date != '' ? date('d M Y', strtotime($asv->actual_end_date)) : '' }}" data-id="{{ $asv->id }}" data-ssd="{{ $asv->scheduled_end_date }}" @if($asv->actual_end_date != '') ? disabled : '' @elseif($asv->actual_start_date == '') ? disabled : '' @endif data-msg="Please select date" required>
                                                                                                    <span id="actualEndDt{{ $asv->id }}"></span>
                                                                                                    <!-- {{ $aav->actual_end_date != '' ? date('d M Y', strtotime($aav->actual_end_date)) : '' }} -->
                                                                                                </td>
                                                                                            @endif

                                                                                            @if(Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 1)
                                                                                                <td style="width:10%">
                                                                                                    <input type="text" class="form-control endDelayRemark_{{ $asv->id }}" name="end_delay_remark"  data-msg="Please enter end activity remark" value="{{ $asv->end_delay_remark }}" required autocomplete="off" title="{{ $asv->end_delay_remark }}">
                                                                                                    <span id="endDelayRemark{{ $asv->id }}"></span>
                                                                                                    <!-- {{ $aav->end_delay_remark != '' ? $aav->end_delay_remark : '---' }} -->
                                                                                                </td>
                                                                                            @else
                                                                                                <td style="width:10%">
                                                                                                    <input type="text" class="form-control endDelayRemark_{{ $asv->id }}" name="end_delay_remark"  data-msg="Please enter end activity remark" value="{{ $asv->end_delay_remark }}" @if($asv->actual_end_date != '') ? disabled : '' @elseif($asv->actual_start_date == '') ? disabled : '' @endif required autocomplete="off" title="{{ $asv->end_delay_remark }}">
                                                                                                    <span id="endDelayRemark{{ $asv->id }}"></span>
                                                                                                    <!-- {{ $aav->end_delay_remark != '' ? $aav->end_delay_remark : '---' }} -->
                                                                                                </td>
                                                                                            @endif
                                                                                            <td style="width:8% !important">
                                                                                                <span class="activityStatus_{{ $asv->activityStatusName->id }}" title="{{ $asv->activityStatusName->activity_status_description }}">
                                                                                                    {{ ($asv->activityStatusName->activity_status) }} 
                                                                                                </span>
                                                                                                
                                                                                            </td>
                                                                                            @if(Auth::guard('admin')->user()->role_id == 3)
                                                                                                @if($asv->actual_end_date == '' && $asv->actual_start_date != '')
                                                                                                    <td style="width:5%">
                                                                                                        <a class="btn btn-primary waves-effect waves-light saveEndDate saveEndStart_{{$asv->id}}" data-id="{{ $asv->id }}" href="Javascript:void(0);" name="save_end" role="button" title="Save" id="save_end">
                                                                                                            Save
                                                                                                        </a>
                                                                                                    </td>
                                                                                                @elseif($asv->actual_end_date == '')
                                                                                                    <td style="width:5%;">
                                                                                                        <a class="btn btn-primary waves-effect waves-light saveEndDate saveEnd_{{$asv->id}} disabled" data-id="{{ $asv->id }}" href="Javascript:void(0);" role="button" title="Save">
                                                                                                            Save
                                                                                                        </a>
                                                                                                    </td>
                                                                                                @else
                                                                                                    <td style="width:5%;">
                                                                                                        <a class="btn btn-primary waves-effect waves-light saveEndDate disabled" data-id="{{ $asv->id }}" href="Javascript:void(0);" role="button" title="Save">
                                                                                                            Save
                                                                                                        </a>
                                                                                                    </td>
                                                                                                @endif
                                                                                            @else
                                                                                                <td style="width:5%;">
                                                                                                    <a class="btn btn-primary waves-effect waves-light saveEndDate" data-id="{{ $asv->id }}" href="Javascript:void(0);" role="button" title="Save">
                                                                                                        Save
                                                                                                    </a>
                                                                                                </td>
                                                                                            @endif
                                                                                        </tr>
                                                                                    
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                @endif
            @endforeach
        
        @endif
    </div>
</div>

@endsection