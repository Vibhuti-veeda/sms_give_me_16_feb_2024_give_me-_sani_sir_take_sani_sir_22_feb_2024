@extends('layouts.admin')
@section('title','All Studies')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Studies</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                @if($access->add == '1')
                                    <a href="{{ route('admin.addStudy') }}" class="headerButtonStyle" role="button" title="Add Study">
                                        Add Study
                                    </a>
                                @endif
                            </li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionExample">
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button fw-medium @if(isset($filter) && ($filter == 1)) @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#studyCollapseFilter" aria-expanded="false" aria-controls="studyCollapseFilter">
                        Filters
                    </button>
                </h2>
                <div id="studyCollapseFilter" class="accordion-collapse @if(isset($filter) && ($filter == 1)) @else collapse @endif" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body collapse show">
                        <form method="post" action="{{ route('admin.studyList') }}">
                            @csrf
                            <input type="hidden" name="page" id="page" value="{{$page}}">
                            <div class="row">

                                <div class="col-md-2">
                                    <label class="control-label">Study No</label>
                                    <select class="form-control select2" name="study_no" id="study_no" style="width: 100%;">
                                        <option value="">Select Study No</option>
                                        @if(!is_null($studies))
                                            @foreach($studies as $sk => $sv)
                                                <option @if($studyName == $sv->study_no) selected @endif value="{{ $sv->study_no }}">{{ $sv->study_no }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="control-label">Project Managers</label>
                                    <select class="form-control select2" name="project_manager" id="project_manager" style="width: 100%;">
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
                                    <label class="control-label">Drug Name</label>
                                    <select class="form-control select2" name="drug_name" id="drug_name" style="width: 100%;">
                                        <option value="">Select Drug Name</option>
                                        @if(!is_null($drugs))
                                            @foreach($drugs as $dk => $dv)
                                                <option @if($drugName == $dv->id) selected @endif value="{{ $dv->id }}">
                                                    {{ $dv->drug_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="control-label">Dosage Form</label>
                                    <select class="form-control select2" name="dosage_form_id" id="dosage_form_id" style="width: 100%;">
                                        <option value="">Select Dosage Form</option>
                                        @if(!is_null($dosageForms->paraCode))
                                            @foreach($dosageForms->paraCode as $dk => $dv)
                                                <option @if($dosageFormName == $dv->id) selected @endif value="{{ $dv->id }}">
                                                    {{ $dv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="control-label">UOM</label>
                                    <select class="form-control select2" name="uom_id" id="uom_id" style="width: 100%;">
                                        <option value="">Select UOM</option>
                                        @if(!is_null($uoms->paraCode))
                                            @foreach($uoms->paraCode as $uk => $uv)
                                                <option @if($uomName == $uv->id) selected @endif value="{{ $uv->id }}">
                                                    {{ $uv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="control-label">Drug Type</label>
                                    <select class="form-control select2" name="drug_type" id="drug_type" style="width: 100%;">
                                        <option value="">Select Drug Type</option>
                                        <option @if($drugType == 'TEST') selected @endif value="TEST">Test</option>
                                        <option @if($drugType == 'REFERENCE') selected @endif value="REFERENCE">Reference</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Complexity</label>
                                    <select class="form-control select2" name="complexity" id="complexity" style="width: 100%;">
                                        <option value="">Select Complexity</option>
                                        @if(!is_null($complexity->paraCode))
                                            @foreach($complexity->paraCode as $ck => $cv)
                                                <option @if($complexityName == $cv->id) selected @endif value="{{ $cv->id }}">
                                                    {{ $cv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Study Design</label>
                                    <select class="form-control select2" name="study_design" id="study_design" style="width: 100%;">
                                        <option value="">Select Study Design</option>
                                        @if(!is_null($studyDesign->paraCode))
                                            @foreach($studyDesign->paraCode as $sk => $sv)
                                                <option @if($studyDesignName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Study Sub Type</label>
                                    <select class="form-control select2" name="study_sub_type" id="study_sub_type" style="width: 100%;">
                                        <option value="">Select Study Sub Type</option>
                                        @if(!is_null($studySubType->paraCode))
                                            @foreach($studySubType->paraCode as $ssk => $ssv)
                                                <option @if($studySubTypeName == $ssv->id) selected @endif value="{{ $ssv->id }}">
                                                    {{ $ssv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Study Type</label>
                                    <select class="form-control select2" name="study_type" id="study_type" style="width: 100%;">
                                        <option value="">Select Study Type</option>
                                        @if(!is_null($studyType->paraCode))
                                            @foreach($studyType->paraCode as $sk => $sv)
                                                <option @if($studyTypeName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Study Condition</label>
                                    <select class="form-control select2" name="study_condition" id="study_condition" style="width: 100%;">
                                        <option value="">Select Study Condition</option>
                                        @if(!is_null($studyCondition->paraCode))
                                            @foreach($studyCondition->paraCode as $sk => $sv)
                                                <option @if($studyConditionName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                        
                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Subject Type</label>
                                    <select class="form-control select2" name="subject_type" id="subject_type" style="width: 100%;">
                                        <option value="">Select Subject Type</option>
                                        @if(!is_null($subjectType->paraCode))
                                            @foreach($subjectType->paraCode as $sk => $sv)
                                                <option @if($subjectTypeName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Priority</label>
                                    <select class="form-control select2" name="priority" id="priority" style="width: 100%;">
                                        <option value="">Select Priority</option>
                                        @if(!is_null($priority->paraCode))
                                            @foreach($priority->paraCode as $sk => $sv)
                                                <option @if($priorityName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Blinding Status</label>
                                    <select class="form-control select2" name="blinding_status" id="blinding_status" style="width: 100%;">
                                        <option value="">Select Blinding Status</option>
                                        @if(!is_null($blindingStatus->paraCode))
                                            @foreach($blindingStatus->paraCode as $sk => $sv)
                                                <option @if($blindingStatusName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> 

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Regulatory Submission</label>
                                    <select class="form-control select2" name="regulatory_submission" id="regulatory_submission" style="width: 100%;">
                                        <option value="">Select Regulatory Submission</option>
                                        @if(!is_null($regulatorySubmission->paraCode))
                                            @foreach($regulatorySubmission->paraCode as $sk => $sv)
                                                <option @if($regulatorySubmissionName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Sponsor Name</label>
                                    <select class="form-control select2" name="sponsor_id" id="sponsor_id" style="width: 100%;">
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

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">CR Location</label>
                                    <select class="form-control select2" name="cr_location" id="cr_location" style="width: 100%;">
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
                                    <select class="form-control select2" name="br_location" id="br_location" style="width: 100%;">
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

                                 <div class="col-md-2 mt-4">
                                    <label class="control-label">Scope</label>
                                    <select class="form-control select2" name="scope" id="scope" style="width: 100%;">
                                        <option value="">Select Scope</option>
                                        @if(!is_null($scope->paraCode))
                                            @foreach($scope->paraCode as $sk => $sv)
                                                <option @if($scopeName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Principle Investigator</label>
                                    <select class="form-control select2" name="principle_investigator" id="principle_investigator" style="width: 100%;">
                                        <option value="">Select Principle Investigator</option>
                                        @if(!is_null($principle->principleInvestigator))
                                        @foreach($principle->principleInvestigator as $pk => $pv)
                                            <option @if($principleName == $pv->id) selected @endif value="{{ $pv->id }}">
                                                    {{ $pv->name }}
                                                </option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Bioanalytical Investigator</label>
                                    <select class="form-control select2" name="bioanalytical_investigator" id="bioanalytical_investigator" style="width: 100%;">
                                        <option value="">Select Bioanalytical Investigator</option>
                                        @if(!is_null($bioanalytical->bioanalyticalInvestigator))
                                        @foreach($bioanalytical->bioanalyticalInvestigator as $pk => $pv)
                                            <option @if($bioanalyticalName == $pv->id) selected @endif value="{{ $pv->id }}">
                                                    {{ $pv->name }}
                                                </option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4"><label class="control-label">Special Notes</label>
                                    <select class="form-control select2" name="special_notes" id="special_notes" style="width: 100%;">
                                        <option value="">Select Special Notes</option>
                                        @if(!is_null($specialNotes->paraCode))
                                            @foreach($specialNotes->paraCode as $sk => $sv)
                                                <option @if($specialNotesName == $sv->id) selected @endif value="{{ $sv->id }}">
                                                    {{ $sv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4"><label class="control-label">No of Subject</label>
                                    <select class="form-control select2" name="no_of_subject" id="no_of_subject" style="width: 100%;">
                                        <option value="">Select No of Subject</option>
                                        @if(!is_null($subject))
                                            @foreach($subject as $sk => $sv)
                                                <option @if($noOfSubject == $sv->no_of_subject) selected @endif value="{{ $sv->no_of_subject }}">
                                                    {{ $sv->no_of_subject }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Study Result</label>
                                    <select class="form-control select2" name="study_result" id="study_result" style="width: 100%;">
                                        <option value="">Select Study Result</option>
                                        <option @if($studyResult == 'NA') selected @endif value="NA">
                                            NA
                                        </option>
                                        <option @if($studyResult == 'PASS') selected @endif value="PASS">
                                            Pass
                                        </option>
                                        <option @if($studyResult == 'FAIL') selected @endif value="FAIL">
                                            Fail
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Study Status</label>
                                    <select class="form-control select2" name="study_status" id="study_status" style="width: 100%;">
                                    <option value="">Select Study Status</option>
                                        @if(!is_null($studyStatus))
                                            @foreach($studyStatus as $sk => $sv)
                                                <option @if($studyStatusName == $sv->study_status) selected @endif value="{{ $sv->study_status }}">
                                                    {{ $sv->study_status }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Sponsor Study No</label>
                                    <select class="form-control select2" name="sponsor_study_no" id="sponsor_study_no" style="width: 100%;">
                                        <option value="">Select Sponsor Study No</option>
                                        @if(!is_null($sponsorStudy))
                                            @foreach($sponsorStudy as $sk => $sv)
                                                <option @if($sponsorStudyName == $sv->sponsor_study_no) selected @endif value="{{ $sv->sponsor_study_no }}">
                                                    {{ $sv->sponsor_study_no }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Clinical Ward Location</label>
                                    <select class="form-control select2" name="clinical_ward_location" id="clinical_ward_location" style="width: 100%;">
                                        <option value="">Select Clinical Ward Location</option>
                                        @if(!is_null($clinicalWardMaster))
                                            @foreach($clinicalWardMaster as $cwk => $cwv)
                                                <option @if($clinicalWardLocation == $cwv->id) selected @endif value="{{ $cwv->id }}">
                                                    {{ $cwv->ward_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Total Groups</label>
                                    <select class="form-control select2" name="total_group" id="total_group" style="width: 100%;">
                                        <option value="">Select Total Groups</option>
                                        @if(!is_null($totalGroups))
                                            @foreach($totalGroups as $gk => $gv)
                                                <option @if($noOfGroup == $gv->no_of_groups) selected @endif value="{{ $gv->no_of_groups }}">
                                                    {{ $gv->no_of_groups }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Total Periods</label>
                                    <select class="form-control select2" name="no_of_periods" id="no_of_periods" style="width: 100%;">
                                        <option value="">Select Total Periods</option>
                                        @if(!is_null($totalPeriods))
                                            @foreach($totalPeriods as $pk => $pv)
                                                <option @if($noOfPeriod == $pv->no_of_periods) selected @endif value="{{ $pv->no_of_periods }}">
                                                    {{ $pv->no_of_periods }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Total Pre Housing</label>
                                    <select class="form-control select2" name="pre_housing" id="pre_housing" style="width: 100%;">
                                        <option value="">Select Pre Housing</option>
                                        @if(!is_null($preHousing))
                                            @foreach($preHousing as $pk => $pv)
                                                <option @if($noOfPreHousing == $pv->pre_housing) selected @endif value="{{ $pv->pre_housing }}">
                                                    {{ $pv->pre_housing }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-2 mt-4">
                                    <label class="control-label">Total Post Housing</label>
                                    <select class="form-control select2" name="post_housing" id="post_housing" style="width: 100%;">
                                        <option value="">Select Post Housing</option>
                                        @if(!is_null($postHousing))
                                            @foreach($postHousing as $pk => $pv)
                                                <option @if($noOfPreHousing == $pv->post_housing) selected @endif value="{{ $pv->post_housing }}">
                                                    {{ $pv->post_housing }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-1 mt-4">
                                    <button type="submit" class="btn btn-primary vendors save_button mt-4">Submit</button>
                                </div>
                                @if(isset($filter) && ($filter == 1))
                                    <div class="col-md-1 mt-4">
                                        <a href="{{ route('admin.studyList') }}" class="btn btn-danger mt-4 cancel_button" id="filter" name="save_and_list" value="save_and_list">
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
                        <div style="margin-top: -40px; transform: translate(137px, 51px); width: fit-content;">
                            <a href="{{ route('admin.exportStudy')}}" class="btn btn-secondary">Export</a>    
                        </div> 
                        <table id="tableList" class="table table-striped table-bordered nowrap tableList-search" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    @if($admin == 'yes')
                                        <th class='notexport'>Actions</th>
                                    @else
                                        @if($access->edit != '' || $access->delete != '')
                                            <th class='notexport'>Actions</th>
                                        @endif
                                    @endif
                                    @if($access->edit != '' || $access->delete != '')
                                        <th>Status</th>
                                    @endif
                                    <th>Study Result</th>
                                    <th>Study Status</th>
                                    <th>Study Slotted</th>
                                    <th>Tentative Clinical Date</th>
                                    @if(Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2)
                                        <th>Projection Status</th>
                                    @endif
                                    <th>Study No</th>
                                    <th>Drug</th>
                                    <th>Sponsor</th>
                                    <th>Project Manager</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!is_null($studies))
                                    @php
                                        $count = (($offset == 0) ? 1 : $offset+1); 
                                    @endphp
                                    @foreach($studies as $sk => $sv)
                                        <tr>
                                            <td>{{ $count++ }}</td>

                                            @if($admin == 'yes' || $access->edit != '')
                                                @if($sv->no_of_groups > 1)
                                                    <td>
                                                        @if(!str_contains($sv->study_no, 'G'))
                                                            <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('admin.copyStudy',base64_encode($sv->id)) }}" role="button" title="Copy" onclick="return confirm('Are you sure you want to copy this study no - {{$sv->study_no}}?');">
                                                                <i class="bx bx-copy-alt"></i>
                                                            </a>
                                                        @endif

                                                        <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('admin.editStudy',base64_encode($sv->id)) }}" role="button" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                        
                                                        <!-- <a class="btn btn-danger waves-effect waves-light" href="{{ route('admin.deleteStudy',base64_encode($sv->id)) }}" role="button" onclick="return confirm('Do you want to delete this study?');" title="Delete">
                                                            Delete
                                                        </a> -->
                                                        @if($access->delete != '')
                                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light deleteBtn" value="{{ $sv->id }}" title="Delete">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                            <div class="modal fade" id="openDeleteStudyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="copyDeleteLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('admin.deleteStudy')}}" method="POST">
                                                                            @csrf
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="copyDeleteLabel">
                                                                                    Delete
                                                                                </h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="study_delete_id" id="study_id">
                                                                                <center>
                                                                                    <p>Are you sure you want to delete?</p>
                                                                                </center>
                                                                            </div>
                                                                            <div class="modal-footer" style="margin-right:180px">
                                                                                <button type="submit" class="btn btn-danger">Yes</button>
                                                                                <a class="btn btn-primary" data-bs-dismiss="modal">
                                                                                    No
                                                                                </a>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                        @if(!str_contains($sv->study_no, 'G'))
                                                            <a class="btn btn-primary waves-effect btn-sm waves-light" href="{{ route('admin.addCopyStudy',base64_encode($sv->id)) }}" role="button" title="Copy" onclick="return confirm('Are you sure you want to copy this study no - {{$sv->study_no}}?');">
                                                                <i class="bx bx-copy-alt"></i>
                                                            </a>
                                                        @endif

                                                        <a class="btn btn-primary waves-effect btn-sm waves-light" href="{{ route('admin.editStudy',base64_encode($sv->id)) }}" role="button" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                        
                                                        @if($access->delete != '')
                                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light deleteBtn" value="{{ $sv->id }}" title="Delete">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                            <div class="modal fade" id="openDeleteStudyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="copyDeleteLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('admin.deleteStudy')}}" method="POST">
                                                                            @csrf
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="copyDeleteLabel">
                                                                                    Delete
                                                                                </h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="study_delete_id" id="study_id">
                                                                                <center>
                                                                                    <p>Are you sure you want to delete?</p>
                                                                                </center>
                                                                            </div>
                                                                            <div class="modal-footer" style="margin-right:180px">
                                                                                <button type="submit" class="btn btn-danger">Yes</button>
                                                                                <a class="btn btn-primary" data-bs-dismiss="modal">
                                                                                    No
                                                                                </a>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                @if((($access->add != '' && !str_contains($sv->study_no, 'G') && $sv->no_of_groups > 1)) || ($access->edit != '') || ($access->delete != ''))
                                                    <td>
                                                        @if($access->add != '' && !str_contains($sv->study_no, 'G') && $sv->no_of_groups > 1)
                                                            <a class="btn btn-primary waves-effect btn-sm waves-light" href="{{ route('admin.copyStudy',base64_encode($sv->id)) }}" role="button" title="Copy" onclick="return confirm('Are you sure you want to copy this study no - {{$sv->study_no}}?');">
                                                                <i class="bx bx-copy-alt"></i>
                                                            </a>
                                                        @endif
                                                        @if($access->edit != '')
                                                            <a class="btn btn-primary waves-effect btn-sm waves-light" href="{{ route('admin.editStudy',base64_encode($sv->id)) }}" role="button" title="Edit">
                                                                <i class="bx bx-edit-alt"></i>
                                                            </a>
                                                        @endif
                                                        @if($access->delete != '')
                                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light deleteBtn" value="{{ $sv->id }}" title="Delete">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                            <div class="modal fade" id="openDeleteStudyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="copyDeleteLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('admin.deleteStudy')}}" method="POST">
                                                                            @csrf
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="copyDeleteLabel">
                                                                                    Delete
                                                                                </h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="study_delete_id" id="study_id">
                                                                                <center>
                                                                                    <p>Are you sure you want to delete?</p>
                                                                                </center>
                                                                            </div>
                                                                            <div class="modal-footer" style="margin-right:180px">
                                                                                <button type="submit" class="btn btn-danger">Yes</button>
                                                                                <a class="btn btn-primary" data-bs-dismiss="modal">
                                                                                    No
                                                                                </a>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endif

                                            @if($admin == 'yes' || $access->edit != '')
                                                @php $checked = ''; @endphp
                                                @if($sv->is_active == 1) 
                                                    @php $checked = 'checked' @endphp 
                                                @endif
                                                <td>
                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                        <input class="form-check-input studyStatus" type="checkbox" id="customSwitch{{ $sk }}" value="1" data-id="{{ $sv->id }}" {{ $checked }}>
                                                        <label class="form-check-label" for="customSwitch{{ $sk }}"></label>
                                                    </div>
                                                </td>
                                            @endif

                                            @if($admin == 'yes' || $access->edit != '')
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-select studyResult" name="study_result" id="study_result" data-id="{{ $sv->id }}">
                                                            <option value="">Study Result</option>
                                                            <option @if($sv->study_result == 'PASS') selected @endif value="PASS">Pass</option>
                                                            <option @if($sv->study_result == 'FAIL') selected @endif value="FAIL">Fail</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            @else 
                                                <td>
                                                    {{ $sv->study_result != '' ? $sv->study_result : '-' }}
                                                </td>
                                            @endif

                                            @if($admin == 'yes' || $access->edit != '')
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-select studyHoldStatus" name="study_status" id="study_status" data-id="{{ $sv->id }}">
                                                            <option value="">Study Status</option>
                                                            <option @if($sv->study_status == 'UPCOMING') selected @endif value="UPCOMING" disabled>
                                                                Upcoming
                                                            </option>
                                                            <option @if($sv->study_status == 'ONGOING') selected @endif value="ONGOING">
                                                                Ongoing
                                                            </option>
                                                            <option @if($sv->study_status == 'COMPLETED') selected @endif value="COMPLETED" disabled>
                                                                Completed
                                                            </option>
                                                            <option @if($sv->study_status == 'HOLD') selected @endif value="HOLD">
                                                                Hold
                                                            </option>
                                                        </select>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    {{ $sv->study_status != '' ? $sv->study_status : '-' }}
                                                </td>
                                            @endif

                                            @if($admin == 'yes' || $access->edit != '')
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-select studyProjected" name="study_slotted" id="study_slotted" data-id="{{ $sv->id }}">
                                                            <option value="">Study Slotted</option>
                                                            <option @if($sv->study_slotted == 'YES') selected @endif value="YES">Yes</option>
                                                            <option @if($sv->study_slotted == 'NO') selected @endif value="NO">No</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                   {{ $sv->study_slotted != '' ? $sv->study_slotted : '-' }} 
                                                </td>
                                            @endif


                                            @if($admin == 'yes' || $access->edit != '')
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control datepickerStyle tentativeClinicalDate" name="tentative_clinical_date" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" data-id="{{ $sv->id }}" value="{{ !is_null($sv->tentative_clinical_date) ? date('d M Y', strtotime($sv->tentative_clinical_date)) : '' }}" required>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                   {{ $sv->tentative_clinical_date != '' ? date('d M Y', strtotime($sv->tentative_clinical_date)) : '-' }} 
                                                </td>
                                            @endif

                                            @if(Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2)
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-select projectionStatus" name="projection_status" id="projection_status" data-id="{{ $sv->id }}">
                                                            <option value="">Study Slotted</option>
                                                            <option @if($sv->projection_status == 'GREEN') selected @endif value="GREEN">
                                                                Green
                                                            </option>
                                                            <option @if($sv->projection_status == 'RED') selected @endif value="RED">
                                                                Red
                                                            </option>
                                                            <option @if($sv->projection_status == 'YELLOW') selected @endif value="YELLOW">
                                                                Yellow
                                                            </option>
                                                        </select>
                                                    </div>
                                                </td>
                                            @endif
                                            
                                            <td>{{ $sv->study_no }}</td>

                                            <td>
                                                @if(!is_null($sv->drugDetails)) 
                                                    @php $drug = ''; @endphp
                                                    @foreach($sv->drugDetails as $dk => $dv)
                                                        @if(!is_null($dv->drugName) && !is_null($dv->drugDosageName) && !is_null($dv->dosage) && !is_null($dv->drugUom) && !is_null($dv->drugType) && $dv->drugType->type == 'TEST')
                                                            @php 
                                                                $drug = $dv->drugName->drug_name.' - '.$dv->drugDosageName->para_value .' - '.$dv->dosage .''.$dv->drugUom->para_value;
                                                            @endphp
                                                        @endif    
                                                        
                                                    @endforeach
                                                    <p>{{ $drug != '' ? $drug : '---' }}</p>
                                                @endif
                                            </td>

                                            <td>{{ ((!is_null($sv->sponsorName)) && ($sv->sponsorName->sponsor_name != '')) ? $sv->sponsorName->sponsor_name : '---' }}</td>
                                            <td>
                                                {{ ((!is_null($sv->projectManager)) && ($sv->projectManager->name != '')) ? $sv->projectManager->name ." - ". $sv->projectManager->employee_code : '---' }}
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-2">
                            Showing {{ $offset + 1 }} to {{ min($page * $perPage, $recordCount) }} of {{ $recordCount }} entries
                        </div>
                        <div style="float:right;">
                            @if ($pageCount >= 1)
                                <nav aria-label="...">
                                    <ul class="pagination">
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link" data-page= "{{1}}" href="javascript:void(0)">First</a>
                                        </li>
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link h5" data-page= "{{ ($page - 1) }}" href="javascript:void(0)">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        @for ($i = max(1, $page); $i <= min($page + 4, $pageCount); $i++)
                                            <li class="page-item {{($page == $i) ? 'active' : '' }}" aria-current="page">
                                                <a class="page-link" data-page= "{{ $i }}" href="javascript:void(0)">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link h5" data-page= "{{ ($page + 1) }}" href="javascript:void(0)">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link" data-page="{{ $pageCount }}" href="javascript:void(0)">Last</a>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection