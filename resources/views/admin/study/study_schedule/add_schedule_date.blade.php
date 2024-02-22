@extends('layouts.admin')
@section('title','Add Study Schedule Date')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        Add Study Schedule Date: 
                        <span style="color: blue;">
                            {{ $studyNo->study_no }} 

                            @if(!is_null($studyNo->drugDetails)) 
                                @php $drug = ''; @endphp
                                @foreach($studyNo->drugDetails as $dk => $dv)
                                    @if(!is_null($dv->drugName) && !is_null($dv->drugDosageName) && !is_null($dv->dosage) && !is_null($dv->drugUom) && !is_null($dv->drugType) && $dv->drugType->type == 'TEST')
                                        @php 
                                            $drug = $dv->drugName->drug_name;
                                        @endphp
                                    @endif    
                                    
                                @endforeach

                                ({{ $drug != '' ? $drug : '---' }} - {{ (!is_null($studyNo->sponsorName) && ($studyNo->sponsorName->sponsor_name != '')) ? $studyNo->sponsorName->sponsor_name : '' }} - {{ (!is_null($studyNo->projectManager) && ($studyNo->projectManager->name != '')) ? $studyNo->projectManager->name : '' }})
                                
                            @endif
                        </span>
                    </h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.studyScheduleList') }}">
                                    ALL SCHEDULED STUDY
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Add Study Schedule Date</li>
                        </ol>
                    </div>                    
                </div>
            </div>
        </div>

        <input type="hidden" name="id" id="id" value="{{ $id }}">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <!-- <div class="form-group">
                            <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                        </div> -->

                        <form class="custom-validation saveRequiredDay" action="Javascript: void(0);" method="post" id="addStudySchedule" enctype="multipart/form-data">
                        @csrf
                            @if(count($psActivitySchedule)>0)
                                <div class="form-group mb-3">
                                
                                    <center>
                                        <h5>
                                            <b>
                                                Pre Study Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Activity Name</th>
                                                <!-- <th>Activity Sequence No</th>-->
                                                <th>Milestone Activity</th>
                                                @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                @endif
                                                <th>Required Days</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                @if(Auth::guard('admin')->user()->role_id != 14)
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($psActivitySchedule as $psk => $psv)
                                                <tr>
                                                    <input type="hidden" name="activity_type" id="activity_type" value="{{ $psv->activity_type }}">
                                                    <input type="hidden" name="study_id" id="study_id" value="{{ $psv->study_id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $psv->activity_name }} @if($psv->activity_version_type != '') ({{ $psv->activity_version_type }}-{{ $psv->activity_version }}) @endif</td>
                                                    <!-- <td>
                                                        <input type="text" class="form-control" name="activity_sequence[{{ $psv->id }}][sequence]" value="{{ $psv->activity_sequence_no }}">
                                                    </td>-->
                                                    <td>
                                                        <div class="form-check form-check-primary mb-3">
                                                            <input class="form-check-input milestoneActivity" type="checkbox" id="formCheckcolor{{ $psk }}" @if($psv->is_milestone == 1) checked @else @endif data-id="{{ $psv->id }}">
                                                            <label class="form-check-label" for="formCheckcolor{{ $psk }}">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input startMilestoneActivity" type="checkbox" id="formCheckcolor{{ $psk }}" data-id="{{ $psv->id }}" @if($psv->is_start_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $psk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input endmilestoneActivity" type="checkbox" id="formCheckcolor{{ $psk }}" data-id="{{ $psv->id }}" @if($psv->is_end_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $psk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <input type="text" class="form-control psRequiredDays psRequiredDays_{{ $psv->id }}" name="require_days[{{ $psv->id }}][days]" value="{{ $psv->require_days }}" data-val="{{ $psv->id }}" data-valdate="{{ $psv->require_days }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control @if($psv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $psv->id }} scheduleDatePickerStyle" name="schedule_date[{{ $psv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $psv->id }}" data-acitivity="{{ $psv->id }}" data-study="{{ $psv->study_id }}" data-sequence="{{ $psv->activity_sequence_no }}" data-type="{{ $psv->activity_type }}" data-version="{{ $psv->activity_version }}" data-days="{{ $psv->require_days }}" value="{{ $psv->scheduled_start_date != '' ? date('d M Y', strtotime($psv->scheduled_start_date)) : '' }}" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="schedule_date" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $psv->scheduled_end_date != '' ? date('d M Y', strtotime($psv->scheduled_end_date)) : '' }}" disabled />
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id != 14)
                                                        @if($psv->scheduled_start_date != '')
                                                            <td>
                                                                <a class="btn btn-primary btn-sm waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $psv->id }}" data-study="{{ $psv->study_id }}" data-sequence="{{ $psv->activity_sequence_no }}" data-id="{{ $psv->id }}" data-type="{{ $psv->activity_type }}" data-version="{{ $psv->activity_version }}"  href="Javascript:void(0)">
                                                                    <i class="bx bx-calendar-event"></i>
                                                                </a>
                                                                <a id="myCopyActivityModal" data-toggle="modal" data-target="#openCopyActivityModal" href="Javascript:void(0)" type="submit" class="btn btn-primary btn-sm waves-effect waves-light copyActivity" role="button" title="Copy Activity" data-id="{{ $psv->id }}" href="Javascript:void(0)">
                                                                    <i class="bx bx-copy-alt"></i>
                                                                </a>
                                                            </td>
                                                        @else
                                                            <td>---</td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                                @if(Auth::guard('admin')->user()->role_id != 14)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="button-items">
                                                        <center>
                                                            <a id="myRemarkModal" data-toggle="modal" data-target="#openScheduleRemarkModal" href="Javascript:void(0)" type="submit" class="btn btn-primary waves-effect waves-light mr-1 saveRemark" data-study="{{ $psv->study_id }}" data-activity="{{ $psv->activity_type }}" data-schedule="{{ $psv->id }}" name="btn_submit" value="save">
                                                                Save
                                                            </a>
                                                            <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
                                                                Cancel
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        </form>

                        <form class="custom-validation saveRequiredDay" action="Javascript: void(0);" method="post" id="addStudySchedule" enctype="multipart/form-data">
                        @csrf
                            @if(count($crActivitySchedule)>0)
                                <div class="form-group mb-3">
                                
                                    <center>
                                        <h5>
                                            <b>
                                                CR Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Activity Name</th>
                                                <!-- <th>Activity Sequence No</th>
                                                <th>GN</th>
                                                <th>PN</th> -->
                                                <th>Milestone Activity</th>
                                                @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                @endif
                                                <th>Required Days</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                @if(Auth::guard('admin')->user()->role_id != 14)
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($crActivitySchedule as $sk => $sv)
                                                <tr>
                                                     <input type="hidden" name="activity_type" id="activity_type" value="{{ $sv->activity_type }}">
                                                     <input type="hidden" name="study_id" id="study_id" value="{{ $sv->study_id }}">
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $sv->activity_name }} @if($sv->group_no != 1)(G{{ $sv->group_no }}) @endif @if(($sv->group_no != 1) && ($sv->period_no != 1)) - @endif @if($sv->period_no != 1) (P{{ $sv->period_no }}) @endif
                                                    </td>
                                                    <!-- <td>
                                                        <input type="text" class="form-control" name="activity_sequence[{{ $sv->id }}][sequence]" value="{{ $sv->activity_sequence_no }}">
                                                    </td>
                                                    <td>
                                                        {{ $sv->group_no }}
                                                    </td>
                                                    <td>
                                                        {{ $sv->period_no }}
                                                    </td> -->
                                                    <td>
                                                        <div class="form-check form-check-primary mb-3">
                                                            <input class="form-check-input milestoneActivity" type="checkbox" id="formCheckcolor{{ $sk }}" name="milestone[{{ $sv->id }}][activity]" @if($sv->is_milestone == 1) checked @else @endif data-id="{{ $sv->id }}">
                                                            <label class="form-check-label" for="formCheckcolor{{ $sk }}">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input startMilestoneActivity" type="checkbox" id="formCheckcolor{{ $sk }}" data-id="{{ $sv->id }}" @if($sv->is_start_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $sk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input endmilestoneActivity" type="checkbox" id="formCheckcolor{{ $sk }}" data-id="{{ $sv->id }}" @if($sv->is_end_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $sk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <input type="text" class="form-control crRequiredDays crRequiredDays_{{ $sv->id }}" name="require_days[{{ $sv->id }}][days]" value="{{ $sv->require_days }}" data-val="{{ $sv->id }}" data-valdate="{{ $sv->require_days }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif>
                                                    </td>
                                                    <!-- <td>
                                                        <input type="text" class="form-control" name="schedule_date[{{ $sv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $sv->id }}" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" value="{{ $sv->scheduled_start_date != '' ? date('d-m-Y', strtotime($sv->scheduled_start_date)) : '' }}" />
                                                    </td> -->
                                                    <td>
                                                        <input type="text" class="form-control @if($sv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $sv->id }} scheduleDatePickerStyle" name="schedule_date[{{ $sv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $sv->id }}" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-type="{{ $sv->activity_type }}" data-version="{{ $sv->activity_version }}" value="{{ $sv->scheduled_start_date != '' ? date('d M Y', strtotime($sv->scheduled_start_date)) : '' }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif/>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="schedule_date" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $sv->scheduled_end_date != '' ? date('d M Y', strtotime($sv->scheduled_end_date)) : '' }}" disabled />
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id != 14)
                                                        @if($sv->scheduled_start_date != '')
                                                            <td>
                                                                <a class="btn btn-primary btn-sm waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-id="{{ $sv->id }}" data-type="{{ $sv->activity_type }}" data-version="{{ $sv->activity_version }}" href="Javascript:void(0)">
                                                                    <i class="bx bx-calendar-event"></i>
                                                                </a>
                                                                <!-- <a class="btn btn-primary waves-effect waves-light copyActivity" role="button" title="Copy Activity" data-id="{{ $sv->id }}" href="Javascript:void(0)">
                                                                    Copy Activity
                                                                </a> -->
                                                            </td>
                                                        @else
                                                            <td>---</td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                                @if(Auth::guard('admin')->user()->role_id != 14)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="button-items">
                                                        <center>
                                                            <a id="myRemarkModal" data-toggle="modal" data-target="#openScheduleRemarkModal" href="Javascript:void(0)" type="submit" class="btn btn-primary waves-effect waves-light mr-1 saveRemark" data-study="{{ $sv->study_id }}" data-activity="{{ $sv->activity_type }}" data-schedule="{{ $sv->id }}" name="btn_submit" value="save">
                                                                Save
                                                            </a>
                                                            <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
                                                                Cancel
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        </form>

                        <form class="custom-validation saveRequiredDay" action="Javascript: void(0);" method="post" id="addStudySchedule" enctype="multipart/form-data">
                        @csrf
                            @if(count($brActivitySchedule)>0)
                                <div class="form-group mb-3">
                                
                                    <center>
                                        <h5>
                                            <b>
                                                BR Activities List
                                            </b>
                                        </h5>
                                    </center><br>
                                
                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Activity Name</th>
                                                <!-- <th>Activity Sequence No</th>
                                                <th>GN</th>
                                                <th>PN</th> -->
                                                <th>Milestone Activity</th>
                                                @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                @endif
                                                <th>Required Days</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                @if(Auth::guard('admin')->user()->role_id != 14)
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($brActivitySchedule as $bk => $bv)
                                                <tr>
                                                    <input type="hidden" name="activity_type" id="activity_type" value="{{ $bv->activity_type }}">
                                                     <input type="hidden" name="study_id" id="study_id" value="{{ $bv->study_id }}">
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $bv->activity_name }} @if($bv->group_no != 1)(G{{ $bv->group_no }}) @endif @if(($bv->group_no != 1) && ($bv->period_no != 1)) - @endif @if($bv->period_no != 1) (P{{ $bv->period_no }}) @endif
                                                    </td>
                                                    <!-- <td>
                                                        <input type="text" class="form-control" name="activity_sequence[{{ $bv->id }}][sequence]" value="{{ $bv->activity_sequence_no }}">
                                                    </td>
                                                    <td>
                                                        {{ $bv->group_no }}
                                                    </td>
                                                    <td>
                                                        {{ $bv->period_no }}
                                                    </td> -->
                                                    <td>
                                                        <div class="form-check form-check-primary mb-3">
                                                            <input class="form-check-input milestoneActivity" type="checkbox" id="formCheckcolor{{ $bk }}" name="milestone[{{ $bv->id }}][activity]" @if($bv->is_milestone == 1) checked @else @endif data-id="{{ $bv->id }}">
                                                            <label class="form-check-label" for="formCheckcolor{{ $bk }}">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input startMilestoneActivity" type="checkbox" id="formCheckcolor{{ $bk }}" data-id="{{ $bv->id }}" @if($bv->is_start_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $bk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input endmilestoneActivity" type="checkbox" id="formCheckcolor{{ $bk }}" data-id="{{ $bv->id }}" @if($bv->is_end_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $bk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <input type="text" class="form-control brRequiredDays brRequiredDays_{{ $bv->id }}" name="require_days[{{ $bv->id }}][days]" data-val="{{ $bv->id }}" value="{{ $bv->require_days }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control @if($bv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $bv->id }} scheduleDatePickerStyle" name="schedule_date[{{ $bv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $bv->id }}" data-acitivity="{{ $bv->id }}" data-study="{{ $bv->study_id }}" data-sequence="{{ $bv->activity_sequence_no }}" data-type="{{ $bv->activity_type }}" data-version="{{ $bv->activity_version }}" value="{{ $bv->scheduled_start_date != '' ? date('d M Y', strtotime($bv->scheduled_start_date)) : '' }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="schedule_date" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $bv->scheduled_end_date != '' ? date('d M Y', strtotime($bv->scheduled_end_date)) : '' }}" disabled/>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id != 14)
                                                        @if($bv->scheduled_start_date != '')
                                                            <td>
                                                                <a class="btn btn-primary btn-sm waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $bv->id }}" data-study="{{ $bv->study_id }}" data-sequence="{{ $bv->activity_sequence_no }}" data-id="{{ $bv->id }}" data-type="{{ $bv->activity_type }}" data-version="{{ $bv->activity_version }}" href="Javascript:void(0)">
                                                                    <i class="bx bx-calendar-event"></i>
                                                                </a>
                                                                <!-- <a class="btn btn-primary waves-effect waves-light copyActivity" role="button" title="Copy Activity" data-id="{{ $bv->id }}" href="Javascript:void(0)">
                                                                    Copy Activity
                                                                </a> -->
                                                            </td>
                                                        @else
                                                            <td>---</td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                                @if(Auth::guard('admin')->user()->role_id != 14)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="button-items">
                                                        <center>
                                                            <a id="myRemarkModal" data-toggle="modal" data-target="#openScheduleRemarkModal" href="Javascript:void(0)" type="submit" class="btn btn-primary waves-effect waves-light mr-1 saveRemark" data-study="{{ $bv->study_id }}" data-activity="{{ $bv->activity_type }}" data-schedule="{{ $bv->id }}" name="btn_submit" value="save">
                                                                Save
                                                            </a>
                                                            <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
                                                                Cancel
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        </form>

                        <form class="custom-validation saveRequiredDay" action="Javascript: void(0);" method="post" id="addStudySchedule" enctype="multipart/form-data">
                        @csrf
                            @if(count($pbActivitySchedule)>0)
                                <div class="form-group mb-3">

                                    <center>
                                        <h5>
                                            <b>
                                                PB Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Activity Name</th>
                                                <!-- <th>Activity Sequence No</th>
                                                <th>GN</th>
                                                <th>PN</th> -->
                                                <th>Milestone Activity</th>
                                                @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                @endif
                                                <th>Required Days</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                @if(Auth::guard('admin')->user()->role_id != 14)
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pbActivitySchedule as $pk => $pv)
                                                <tr>
                                                    <input type="hidden" name="activity_type" id="activity_type" value="{{ $pv->activity_type }}">
                                                     <input type="hidden" name="study_id" id="study_id" value="{{ $pv->study_id }}">
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $pv->activity_name }} @if($pv->group_no != 1)(G{{ $pv->group_no }}) @endif @if(($pv->group_no != 1) && ($pv->period_no != 1)) - @endif @if($pv->period_no != 1) (P{{ $pv->period_no }}) @endif
                                                    </td>
                                                    <!-- <td>
                                                        <input type="text" class="form-control" name="activity_sequence[{{ $pv->id }}][sequence]" value="{{ $pv->activity_sequence_no }}">
                                                    </td>
                                                    <td>
                                                        {{ $pv->group_no }}
                                                    </td>
                                                    <td>
                                                        {{ $pv->period_no }}
                                                    </td> -->
                                                    <td>
                                                        <div class="form-check form-check-primary mb-3">
                                                            <input class="form-check-input milestoneActivity" type="checkbox" id="formCheckcolor{{ $pk }}" name="milestone[{{ $pv->id }}][activity]" @if($pv->is_milestone == 1) checked @else @endif data-id="{{ $pv->id }}">
                                                            <label class="form-check-label" for="formCheckcolor{{ $pk }}">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input startMilestoneActivity" type="checkbox" id="formCheckcolor{{ $pk }}" data-id="{{ $pv->id }}" @if($pv->is_start_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $pk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input endmilestoneActivity" type="checkbox" id="formCheckcolor{{ $pk }}" data-id="{{ $pv->id }}" @if($pv->is_end_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $pk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <input type="text" class="form-control pbRequiredDays pbRequiredDays_{{ $pv->id }}" name="require_days[{{ $pv->id }}][days]" data-val="{{ $pv->id }}" value="{{ $pv->require_days }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control @if($pv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $pv->id }} scheduleDatePickerStyle" name="schedule_date[{{ $pv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $pv->id }}" data-acitivity="{{ $pv->id }}" data-study="{{ $pv->study_id }}" data-sequence="{{ $pv->activity_sequence_no }}" data-type="{{ $pv->activity_type }}" data-version="{{ $pv->activity_version }}" value="{{ $pv->scheduled_start_date != '' ? date('d M Y', strtotime($pv->scheduled_start_date)) : '' }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="schedule_date" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $pv->scheduled_end_date != '' ? date('d M Y', strtotime($pv->scheduled_end_date)) : '' }}" disabled/>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id != 14)
                                                        @if($pv->scheduled_start_date != '')
                                                            <td>
                                                                <a class="btn btn-primary btn-sm waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $pv->id }}" data-study="{{ $pv->study_id }}" data-sequence="{{ $pv->activity_sequence_no }}" data-id="{{ $pv->id }}" data-type="{{ $pv->activity_type }}" data-version="{{ $pv->activity_version }}" href="Javascript:void(0)">
                                                                    <i class="bx bx-calendar-event"></i>
                                                                </a>
                                                                <!-- <a class="btn btn-primary waves-effect waves-light copyActivity" role="button" title="Copy Activity" data-id="{{ $pv->id }}" href="Javascript:void(0)">
                                                                    Copy Activity
                                                                </a> -->
                                                            </td>
                                                        @else
                                                            <td>---</td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if(Auth::guard('admin')->user()->role_id != 14)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="button-items">
                                                        <center>
                                                            <a id="myRemarkModal" data-toggle="modal" data-target="#openScheduleRemarkModal" href="Javascript:void(0)" type="submit" class="btn btn-primary waves-effect waves-light mr-1 saveRemark" data-study="{{ $pv->study_id }}" data-activity="{{ $pv->activity_type }}" data-schedule="{{ $pv->id }}" name="btn_submit" value="save">
                                                                Save
                                                            </a>
                                                            <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
                                                                Cancel
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        </form>
                        
                        <form class="custom-validation saveRequiredDay" action="Javascript: void(0);" method="post" id="addStudySchedule" enctype="multipart/form-data">
                        @csrf
                            @if(count($rwActivitySchedule)>0)
                                <div class="form-group mb-3">

                                    <center>
                                        <h5>
                                            <b>
                                                RW Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Activity Name</th>
                                                <!-- <th>Activity Sequence No</th>
                                                <th>GN</th>
                                                <th>PN</th> -->
                                                <th>Milestone Activity</th>
                                                @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                @endif
                                                <th>Required Days</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                @if(Auth::guard('admin')->user()->role_id != 14)
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rwActivitySchedule as $sk => $sv)
                                                <tr>
                                                    <input type="hidden" name="activity_type" id="activity_type" value="{{ $sv->activity_type }}">
                                                     <input type="hidden" name="study_id" id="study_id" value="{{ $sv->study_id }}">
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $sv->activity_name }} @if($sv->group_no != 1)(G{{ $sv->group_no }}) @endif @if(($sv->group_no != 1) && ($sv->period_no != 1)) - @endif @if($sv->period_no != 1) (P{{ $sv->period_no }}) @endif
                                                    </td>
                                                    <!-- <td>
                                                        <input type="text" class="form-control" name="activity_sequence[{{ $sv->id }}][sequence]" value="{{ $sv->activity_sequence_no }}">
                                                    </td>
                                                    <td>
                                                        {{ $sv->group_no }}
                                                    </td>
                                                    <td>
                                                        {{ $sv->period_no }}
                                                    </td> -->
                                                    <td>
                                                        <div class="form-check form-check-primary mb-3">
                                                            <input class="form-check-input milestoneActivity" type="checkbox" id="formCheckcolor{{ $sk }}" name="milestone[{{ $sv->id }}][activity]" @if($sv->is_milestone == 1) checked @else @endif data-id="{{ $sv->id }}">
                                                            <label class="form-check-label" for="formCheckcolor{{ $sk }}">
                                                            </label>
                                                        </div>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id == 14 || Auth::guard('admin')->user()->role_id == 1)
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input startMilestoneActivity" type="checkbox" id="formCheckcolor{{ $sk }}" data-id="{{ $sv->id }}" @if($sv->is_start_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $sk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-primary mb-3">
                                                                <input class="form-check-input endmilestoneActivity" type="checkbox" id="formCheckcolor{{ $sk }}" data-id="{{ $sv->id }}" @if($sv->is_end_milestone_activity == 1) checked @else @endif>
                                                                <label class="form-check-label" for="formCheckcolor{{ $sk }}">
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <input type="text" class="form-control rwRequiredDays rwRequiredDays_{{ $sv->id }}" name="require_days[{{ $sv->id }}][days]" data-val="{{ $sv->id }}" value="{{ $sv->require_days }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control @if($sv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $sv->id }} scheduleDatePickerStyle" name="schedule_date[{{ $sv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $sv->id }}" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-type="{{ $sv->activity_type }}" data-version="{{ $sv->activity_version }}" value="{{ $sv->scheduled_start_date != '' ? date('d M Y', strtotime($sv->scheduled_start_date)) : '' }}" @if(Auth::guard('admin')->user()->role_id != 14) enable @else disabled @endif />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="schedule_date" placeholder="dd/mm/yyyy" data-provide="scheduleDatePickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" value="{{ $sv->scheduled_end_date != '' ? date('d M Y', strtotime($sv->scheduled_end_date)) : '' }}" disabled/>
                                                    </td>
                                                    @if(Auth::guard('admin')->user()->role_id != 14)
                                                        @if($sv->scheduled_start_date != '')
                                                            <td>
                                                                <a class="btn btn-primary btn-sm waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-id="{{ $sv->id }}" data-type="{{ $sv->activity_type }}" data-version="{{ $sv->activity_version }}" href="Javascript:void(0)">
                                                                    <i class="bx bx-calendar-event"></i>
                                                                </a>
                                                                <!-- <a class="btn btn-primary waves-effect waves-light copyActivity" role="button" title="Copy Activity" data-id="{{ $sv->id }}" href="Javascript:void(0)">
                                                                    Copy Activity
                                                                </a> -->
                                                            </td>
                                                        @else
                                                            <td>---</td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if(Auth::guard('admin')->user()->role_id != 14)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="button-items">
                                                        <center>
                                                            <a id="myRemarkModal" data-toggle="modal" data-target="#openScheduleRemarkModal" href="Javascript:void(0)" type="submit" class="btn btn-primary waves-effect waves-light mr-1 saveRemark" data-study="{{ $sv->study_id }}" data-activity="{{ $sv->activity_type }}" data-schedule="{{ $sv->id }}" name="btn_submit" value="save">
                                                                Save
                                                            </a>
                                                            <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
                                                                Cancel
                                                            </a>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection