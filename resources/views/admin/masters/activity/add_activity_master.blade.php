@extends('layouts.admin')
@section('title','Add Activity')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Activity</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.activityMasterList') }}">
                                    Activity Master List
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Add Activity</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveActivityMaster') }}" method="post" id="addActivity" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity Name<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="activity_name" placeholder="Activity Name" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Days Required<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric daysRequired" name="days_required" placeholder="Days Required" autocomplete="off" maxlength="3" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Minimum Days Allowed<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric minimumDays" name="minimum_days_allowed" placeholder="Minimum Days Allowed" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Maximum Days Allowed<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric maximumDays" name="maximum_days_allowed" placeholder="Maximum Days Allowed" autocomplete="off" maxlength="3" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Next Activity</label>
                                <select class="form-control select2" name="next_activity" id="next_activity" data-placeholder="Select Next Activity">
                                    <option value="">Select Next Activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option value="{{ $av->id }}">{{ $av->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="nextActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Buffer Days<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric" name="buffer_days" id="buffer_days" placeholder="Buffer Days" autocomplete="off" maxlength="3" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 activity_type" name="activity_type" id="activity_type" data-placeholder="Select Activity Type" required>
                                    <option value="">Select Activity Type</option>
                                    @if(!is_null($activityTypes))
                                        @foreach($activityTypes as $atk => $atv)
                                            <option value="{{ $atv->id }}">
                                                {{ $atv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif        
                                </select>
                                <span id="activityType"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sequence No</label>
                                <input type="text" class="form-control numeric" name="sequence_no" placeholder="Sequence No" autocomplete="off" maxlength="3"/>
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
                                            <option value="{{ $rv->id }}">{{ $rv->name }}</option>
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
                                            <option value="{{ $av->id }}">
                                                {{ $av->activity_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="previousActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Is Milestone?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input isMilestone" type="checkbox" name="is_milestone" id="customSwitch" value="1" data-id="">&nbsp;Yes
                                <input type="text" class="form-control width milestonePercentage" name="milestone_percentage" placeholder="Milestone Percentage" autocomplete="off" style="display: none;" /><br>
                                <input type="text" class="form-control numeric milestonePercentage" name="milestone_amount" placeholder="Milestone Amount" autocomplete="off" style="display: none;" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Parent Activity</label>
                                <select class="form-control select2" name="parent_activity" id="parent_activity" data-placeholder="Select Parent Activity">
                                    <option value="">Select Parent Activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option value="{{ $av->id }}">{{ $av->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="parentActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity Days Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 activity_days" name="activity_days" id="activity_days" data-placeholder="Select Activity Days Type" required>
                                    <option value="">Select Activity Days Type</option>
                                    <option value="CALENDAR">Calendar Days</option>
                                    <option value="WORKING">Working Days</option>
                                </select>
                                <span id="activityDays"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Is Parellel?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_parellel" id="customSwitch" value="1" data-id="">&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>Is group specific?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_group_specific" id="customSwitch" value="1" data-id="">&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>Is period specific?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_period_specific" id="customSwitch" value="1" data-id="">&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>Is Dependent On Child?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_dependent" id="customSwitch" value="1" data-id="">&nbsp;Yes
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
                                        Save
                                    </button>
                                    <button type="submit" class="btn btn-secondary waves-effect waves-light mr-1" name="btn_submit" value="save_and_update">
                                        Save & Add New
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