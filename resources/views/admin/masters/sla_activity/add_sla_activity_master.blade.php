@extends('layouts.admin')
@section('title','Add SLA Activity')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add SLA Activity</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.slaActivityMasterList') }}">
                                    SLA Activity Master List
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Add SLA Activity</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveSlaActivityMaster') }}" method="post" id="addActivitySlotting" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity Name<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectActivityName" name="activity_name" id="activity_name" data-placeholder="Select Activity Name">
                                    <option value="">Select Activity Name</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option value="{{ $av->id }}">{{ $av->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectActivityName"></span>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Study Design<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectStudyDesign" name="study_design" id="study_design" data-placeholder="Select Study Design" required>
                                    <option value="">Select Study Design</option>
                                    @if(!is_null($studyDesign->paraCode))
                                        @foreach($studyDesign->paraCode as $sk => $sv)
                                            <option value="{{ $sv->id }}">{{ $sv->para_value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectStudyDesign"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>No of From Subjects<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric daysRequired" name="no_from_subject" placeholder="No of From Subjects" autocomplete="off" maxlength="3" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>No of To Subjects<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric minimumDays" name="no_to_subject" placeholder="No of To Subjects" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>No of Days<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric minimumDays" name="no_of_days" placeholder="No of Days" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>CDISC?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="is_cdisc" id="customSwitch" value="1" data-id="">&nbsp;Yes
                                
                            </div>
                            
                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Save
                                    </button>
                                    <button type="submit" class="btn btn-secondary waves-effect waves-light mr-1" name="btn_submit" value="save_and_update">
                                        Save & Add New
                                    </button>
                                    <a href="{{ route('admin.slaActivityMasterList') }}" class="btn btn-danger waves-effect">
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