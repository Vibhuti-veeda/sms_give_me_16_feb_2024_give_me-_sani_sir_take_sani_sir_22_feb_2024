@extends('layouts.admin')
@section('title','Edit Activity')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Edit Activity</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.activityMasterList') }}">
                                    Activity Master List
                                </a>
                            `</li>
                            <li class="breadcrumb-item active">Edit Activity</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.updateActivityMaster') }}" method="post" id="addActivity" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <input type="hidden" name="id" id="id" value="{{ $activity->id }}">

                            <div class="form-group mb-3">
                                <label>Activity Name<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="activity_name" placeholder="Activity Name" autocomplete="off" value="{{ $activity->activity_name }}" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Days Required<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric daysRequired" name="days_required" placeholder="Days Required" autocomplete="off" value="{{ $activity->days_required }}" maxlength="3" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Minimum Days Allowed<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric minimumDays" name="minimum_days_allowed" placeholder="Minimum Days Allowed" autocomplete="off" value="{{ $activity->minimum_days_allowed }}" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Maximum Days Allowed<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric maximumDays" name="maximum_days_allowed" placeholder="Maximum Days Allowed" autocomplete="off" maxlength="3" value="{{ $activity->maximum_days_allowed }}" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Next Activity</label>
                                <select class="form-control select2" name="next_activity" id="next_activity" data-placeholder="Select Next Activity">
                                    <option value="">Select Next Activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option @if($activity->next_activity == $av->id) selected @endif value="{{ $av->id }}">{{ $av->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="nextActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Buffer Days<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric" name="buffer_days" id="buffer_days" placeholder="Buffer Days" autocomplete="off" value="{{ $activity->buffer_days }}" maxlength="3" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 activity_type" name="activity_type" id="activity_type" data-placeholder="Select Activity Type" required>
                                    <option value="">Select Activity Type</option>
                                    @if(!is_null($activityTypes))
                                        @foreach($activityTypes as $atk => $atv)
                                            <option @if($activity->activity_type == $atv->id) selected @endif value="{{ $atv->id }}">
                                                {{ $atv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="activityType"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sequence No</label>
                                <input type="text" class="form-control numeric" name="sequence_no" placeholder="Sequence No" autocomplete="off" maxlength="3" value="{{ $activity->sequence_no }}" />
                            </div>
                
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 ">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Responsibility<span class="mandatory">*</span></label>
                                <select class="form-control select2 select_responsibility" name="responsibility" id="responsibility" data-placeholder="Select Responsibility" required >
                                    <option value="">Select Responsibility</option>
                                    @if(!is_null($responsibels))
                                        @foreach($responsibels as $rk => $rv)
                                            <option @if($activity->responsibility == $rv->id) selected @endif value="{{ $rv->id }}">{{ $rv->name }}</option>
                                        @endforeach
                                    @endif`
                                </select>
                                <span id="selectResponsibility"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Previous Activity</label>
                                <select class="form-control select2" name="previous_activity" id="previous_activity" data-placeholder="Select Previous Activity">
                                    <option value="">Select Previous Activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option @if($activity->previous_activity == $av->id) selected @endif value="{{ $av->id }}">{{ $av->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="previousActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Is Milestone?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input isMilestone" type="checkbox" name="is_milestone" id="customSwitch" value="1" @if($activity->is_milestone == 1) checked @endif>&nbsp;Yes

                                @if($activity->is_milestone == 1)
                                    <input type="text" class="form-control width milestonePercentage" name="milestone_percentage" placeholder="Milestone Percentage" autocomplete="off" value="{{ $activity->milestone_percentage }}" /><br>

                                    <input type="text" class="form-control numeric milestonePercentage" name="milestone_amount" placeholder="Milestone Amount" autocomplete="off" value="{{ $activity->milestone_amount }}" />
                                @else
                                    <input type="text" class="form-control width milestonePercentage" name="milestone_percentage" placeholder="Milestone Percentage" autocomplete="off" style="display: none;" /><br>
                                    <input type="text" class="form-control numeric milestonePercentage" name="milestone_amount" placeholder="Milestone Amount" autocomplete="off" style="display: none;" />
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Parent Activity</label>
                                <select class="form-control select2" name="parent_activity" id="parent_activity" data-placeholder="Select Parent Activity">
                                    <option value="">Select Parent Activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option @if($activity->parent_activity == $av->id) selected @endif value="{{ $av->id }}">{{ $av->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="parentActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity Days Type<span class="mandatory">*</span></label>
                                <select class="form-control select2" name="activity_days" id="activity_days" data-placeholder="Select Activity Days Type" required>
                                    <option value="">Select Activity Days Type</option>
                                    <option @if($activity->activity_days == 'CALENDAR') selected @endif value="CALENDAR">
                                        Calendar Days
                                    </option>
                                    <option @if($activity->activity_days == 'WORKING') selected @endif value="WORKING">
                                        Working Days
                                    </option>
                                </select>
                                <span id="activityDays"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Is Parellel?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_parellel" id="customSwitch" value="1" @if($activity->is_parellel == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>Is group specific?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_group_specific" id="customSwitch" value="1" @if($activity->is_group_specific == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>Is period specific?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_period_specific" id="customSwitch" value="1" @if($activity->is_period_specific == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>Is Dependent On Child?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_dependent" id="customSwitch" @if($activity->is_dependent == 1) checked @endif value="1">&nbsp;Yes
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.activityMasterList') }}" class="btn btn-danger waves-effect">
                                        Cancel
                                    </a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection