@extends('layouts.admin')
@section('title','Add Study Schedule')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Study Schedule</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.studyScheduleList') }}">
                                    Study Schedule List
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Add Study Schedule</li>
                        </ol>
                    </div>                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveStudySchedule') }}" method="post" id="addStudySchedule" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study<span class="mandatory">*</span></label>
                                <select class="form-control select2 select_study" name="study" id="study" data-placeholder="Select Study" required>
                                    <option value="">Select Study</option>
                                    @if(!is_null($studyList))
                                        @foreach($studyList as $sk => $sv)
                                            <option value="{{ $sv->id }}">{{ $sv->study_no }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectStudy"></span>
                            </div>

                            <div class="form-group mb-3">
                            </div>
                            
                            <div class="row">
                                <div class="form-check form-switch form-switch-md mb-3 col-lg-2" dir="ltr">
                                    <input class="form-check-input checkAllSchedule" type="checkbox" id="customSwitch" value="">
                                    <label class="form-check-label" for="customSwitch">
                                        Select All
                                    </label>
                                </div>

                                <div class="form-check form-switch form-switch-md mb-3 col-lg-2" dir="ltr">
                                    <input class="form-check-input checkPSActivity" type="checkbox" id="customSwitch" value="">
                                    <label class="form-check-label" for="customSwitch">
                                        Pre Study
                                    </label>
                                </div>
                                                                 
                                <div class="form-check form-switch form-switch-md mb-3 col-lg-2" dir="ltr">
                                    <input class="form-check-input checkCRActivity" type="checkbox" id="customSwitch" value="">
                                    <label class="form-check-label" for="customSwitch">
                                        CR
                                    </label>
                                </div>
                                                                 
                                <div class="form-check form-switch form-switch-md mb-3 col-lg-2" dir="ltr">
                                    <input class="form-check-input checkBRActivity" type="checkbox" id="customSwitch" value="">
                                    <label class="form-check-label" for="customSwitch">
                                        BR
                                    </label>
                                </div>
                                                                 
                                <div class="form-check form-switch form-switch-md mb-3 col-lg-2" dir="ltr">
                                    <input class="form-check-input checkPBActivity" type="checkbox" id="customSwitch" value="">
                                    <label class="form-check-label" for="customSwitch">
                                        PB
                                    </label>
                                </div>

                                <div class="form-check form-switch form-switch-md mb-3 col-lg-2" dir="ltr">
                                    <input class="form-check-input checkRWActivity" type="checkbox" id="customSwitch" value="">
                                    <label class="form-check-label" for="customSwitch">
                                        RW
                                    </label>
                                </div>

                            </div>

                            <div class="form-group mb-3">
                                <table id="study-schedule" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <!-- <th>Sr. No</th> -->
                                            <th>Activity Name</th>
                                            <!-- <th>Days Required</th> -->
                                            <!-- <th>Next Activity</th>
                                            <th>Responsibility</th>
                                            <th>Previous Activity</th>
                                            <th>Parent Activity</th> -->             
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($activities))
                                            @foreach($activities as $ak => $av)
                                                <tr>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                            <input class="form-check-input scheduleStatus scheduleStatus_{{ $av->id }} selectActivity crActivity_{{ (!is_null($av->crCode) && $av->crCode->para_value != '') ? $av->crCode->para_value : '' }} brActivity_{{ (!is_null($av->brCode) && $av->brCode->para_value != '') ? $av->brCode->para_value : '' }} rwActivity_{{ (!is_null($av->rwCode) && $av->rwCode->para_value != '') ? $av->rwCode->para_value : '' }} pbActivity_{{ (!is_null($av->pbCode) && $av->pbCode->para_value != '') ? $av->pbCode->para_value : '' }} psActivity_{{ (!is_null($av->psCode) && $av->psCode->para_value != '') ? $av->psCode->para_value : '' }}" type="checkbox" id="customSwitch{{ $ak }}" value="{{ $av->id }}" data-id="{{ $av->id }}" name="activity[{{$ak}}][id]">
                                                            <label class="form-check-label" for="customSwitch{{ $ak }}"></label>
                                                        </div>
                                                    </td>

                                                    <!-- <td>{{ $loop->iteration }}</td> -->
                                                    <td>{{ $av->activity_name }}</td>
                                                    <!-- <td>{{ $av->days_required }}</td> -->
                                                    <!-- <td>
                                                        {{ (!is_null($av->nextActivity) && $av->nextActivity->activity_name != '' ) ? $av->nextActivity->activity_name : '---' }}
                                                    </td>
                                                    <td>
                                                        {{ (!is_null($av->responsible) && $av->responsible->name != '') ? $av->responsible->name : '' }}
                                                    </td>
                                                    <td>
                                                        {{ (!is_null($av->previousActivity) && $av->previousActivity->activity_name != '') ? $av->previousActivity->activity_name : '---' }}
                                                    </td>
                                                    <td>
                                                        {{ (!is_null($av->parentActivity) && $av->parentActivity->activity_name != '') ? $av->parentActivity->activity_name : '---' }}
                                                    </td> -->
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group mb-3">
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
                                    <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
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