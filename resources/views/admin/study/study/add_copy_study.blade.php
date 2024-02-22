@extends('layouts.admin')
@section('title','Add Study')
@section('content')
<style type="text/css">
    input[type='text']{
        color:blue;
    }
    #select2-sponsor-container, #select2-study_type-container, #select2-study_design-container, #select2-complexity-container, #select2-study_sub_type-container, #select2-study_condition-container, #select2-subject_type-container, #select2-priority-container, #select2-blinding_status-container, #select2-br_location-container, #select2-cr_location-container, #select2-clinical_word_location-container, #select2-principle_investigator-container, #select2-bioanalytical_investigator-container, #select2-study_result-container,#select2-project_manager-container, #select2-special_notes-container, #study_text, #drug_details, #select2-drug_details-container,#select2-drug_dosage_form-container,#select2-drug_strength-container,#select2-drug_reference-container,#drug_strength,#drug_reference,#drug_dosage_form,#remark {
        color: blue;
    }
    #row {
        --bs-gutter-x: 0px;
    }
</style>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Study</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.studyList') }}">
                                    Study List
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Add Study</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveStudy') }}" method="post" id="addProject" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <span style="color:red;float:right;">* is mandatory</span>
                    </div>

                    <div class="py-2 mt-3 row" id="row">
                        <h3 class="font-size-15 font-weight-bold">
                            Drug Details
                            {{-- <a href="javascript:void(0);" class="text-primary addNewDrug form-group float-right" data-toggle="tooltip" data-id="{{ $study->drug_details_count }}" data-value="0" data-placement="top" title="" >
                                <i class="mdi mdi-plus font-size-20"></i>
                            </a> --}}
                        </h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-nowrap table-bordered new_drug_details">
                            <thead>
                                <!-- <tr>
                                    <th>Dosage Form</th>
                                    <th>Drug</th>
                                    <th>Dosage</th>
                                    <th>UOM</th>
                                    <th>Type</th>
                                    <th>Manufacture / Distribution By</th>
                                    <th></th>
                                </tr> -->
                                <tr>
                                    <th>Drug<span class="mandatory">*</span></th>
                                    <th>Dosage Form<span class="mandatory">*</span></th>
                                    <th>Dosage<span class="mandatory">*</span></th>
                                    <th>Drug Strength</th>
                                    <th>UOM<span class="mandatory">*</span></th>
                                    <th>Reference Type<span class="mandatory">*</span></th>
                                    <th>Manufacture / Distribution By<span class="mandatory">*</span></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($study->drugDetails)>0)
                                    @foreach($study->drugDetails as $sk => $sv)
                                        <tr @if(!$loop->first) class='removeRow' @endif>
                                            <td>
                                                <select class="form-select select_drug noValidate" name="drug[{{ $sk }}][drug]" id="drug_details" required>
                                                    <option value="">Drug</option>
                                                    @if (!is_null($drug))
                                                        @foreach ($drug as $dk => $dv)
                                                            <option @if($sv->drug_id == $dv->id) selected @endif value="{{ $dv->id }}">
                                                                {{ $dv->drug_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="select_drug_error" style="color: red; display: none;">Please select drug</span>
                                            </td>
                                            <td>
                                                <select class="form-select select_dosage_form noValidate" id="drug_dosage_form" name="drug[{{ $sk }}][dosage_form]" data-placeholder="Select Dosage Form" required>
                                                    <option value="">Dosage Form</option>
                                                    @if(!is_null($dosageform->paraCode))
                                                        @foreach($dosageform->paraCode as $dk => $dv)
                                                            <option @if($sv->dosage_form_id == $dv->id) selected @endif value="{{ $dv->id }}">
                                                                {{ $dv->para_value }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="select_dosage_form_error" style="color: red; display: none;">Please select dosage form</span>
                                            </td>
                                            <td>
                                                <input type="text" id="drug_strength" class="form-control dosage noValidate" name="drug[{{ $sk }}][dosage]" placeholder="Dosage" autocomplete="off" value="{{ $sv->dosage }}" required/>
                                                <span class="dosage_error" style="color: red; display: none;">Please enter dosage</span>
                                            </td>
                                            <td>
                                                <input type="text" id="drug_strength" class="form-control drug_strength noValidate" name="drug[{{ $sk }}][drug_strength]" placeholder="Drug Strength"  autocomplete="off" value="{{ $sv->drug_strength }}"/>
                                            </td>
                                            <td>
                                                <select class="form-select selectUOM noValidate" name="drug[{{ $sk }}][uom]" data-placeholder="Select UOM" id="drug_strength" required>
                                                    <option value="">UOM</option>
                                                    @if(!is_null($uom->paraCode))
                                                        @foreach($uom->paraCode as $uk => $uv)
                                                            <option @if($sv->uom_id == $uv->id) selected @endif value="{{ $uv->id }}">
                                                                {{ $uv->para_value }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="select_uom_error" style="color: red; display: none;">Please select uom</span>
                                            </td>
                                            <td>
                                                <select class="form-select selectType noValidate" name="drug[{{ $sk }}][type]" id="drug_reference" data-placeholder="Select Type" required>
                                                    <option value="">Type</option>
                                                    <option @if($sv->type == 'TEST') selected @endif value="TEST">Test</option>
                                                    <option @if($sv->type == 'REFERENCE') selected @endif value="REFERENCE">Reference</option>
                                                </select>
                                                <span class="select_type_error" style="color: red; display: none;">Please select drug reference</span>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control manufacture noValidate" name="drug[{{ $sk }}][manufacture]" id="manufacture" placeholder="Manufacture / Distribution By" autocomplete="off" value="{{ $sv->manufacturedby }}" required/>
                                                <span class="manufacture_error" style="color: red; display: none;">Please enter manufacture</span>
                                            </td>
                                            <td>
                                                @if ($loop->first)
                                                    <a href="javascript:void(0);" class="text-primary addNewDrug form-group" data-toggle="tooltip" data-id="{{ $study->drug_details_count }}" data-value="0" data-placement="top" title="" >
                                                        <i class="mdi mdi-plus font-size-20"></i>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0);" class="text-danger remove" data-toggle="tooltip" data-id="{{ $sk }}"  data-placement="top" title="" data-original-title="Remove">
                                                        <i class="mdi mdi-close font-size-20"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <select class="form-select select_drug noValidate" name="drug[0][drug]" id="drug_details" required>
                                                <option value="">Drug</option>
                                                @if (!is_null($drug))
                                                    @foreach ($drug as $dgk => $dgv)
                                                        <option value="{{ $dgv->id }}">{{ $dgv->drug_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="select_drug_error" style="color: red; display: none;">Please select drug</span>
                                        </td>
    
                                        <td>
                                            <select class="form-select select_dosage_form noValidate" id="drug_dosage_form" name="drug[0][dosage_form]" data-placeholder="Select Dosage Form" required>
                                                <option value="">Dosage Form</option>
                                                @if (!is_null($dosageForm))
                                                    @if (!is_null($dosageForm->paraCode))
                                                        @foreach ($dosageForm->paraCode as $dfpk => $dfpv)
                                                            <option value="{{ $dfpv->id }}">{{ $dfpv->para_value }}</option>
                                                        @endforeach                                          
                                                    @endif
                                                @endif
                                            </select>
                                            <span class="select_dosage_form_error" style="color: red; display: none;">Please select dosage form</span>
                                        </td>
    
                                        <td>
                                            <input type="text" id="drug_strength" class="form-control dosage noValidate" name="drug[0][dosage]" placeholder="Dose" autocomplete="off" required/>
                                            <span class="dosage_error" style="color: red; display: none;">Please enter dose</span>
                                        </td>
   
                                        <td>
                                            <input type="text" id="drug_strength" class="form-control drug_strength noValidate" name="drug[0][drug_strength]" placeholder="Drug Strength"  autocomplete="off"/>
                                        </td>
    
                                        <td>
                                            <select class="form-select selectUOM noValidate" name="drug[0][uom]" data-placeholder="Select UOM" id="drug_strength" required>
                                                <option value="">UOM</option>
                                                @if (!is_null($uom))
                                                    @if (!is_null($uom->paraCode))
                                                        @foreach ($uom->paraCode as $umpk => $umpv)
                                                            <option value="{{ $umpv->id }}">{{ $umpv->para_value }}</option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                            <span class="select_uom_error" style="color: red; display: none;">Please select uom</span>
                                        </td>
    
                                        <td>
                                            <select class="form-select selectType noValidate" name="drug[0][type]" id="drug_reference" data-placeholder="Select Type" required>
                                                <option value="">Type</option>
                                                <option value="TEST">Test</option>
                                                <option value="REFERENCE">Reference</option>
                                            </select>
                                            <span class="select_type_error" style="color: red; display: none;">Please select drug reference</span>
                                        </td>
    
                                        <td>
                                            <input type="text" class="form-control manufacture noValidate" name="drug[0][manufacture]" id="manufacture" placeholder="Manufacture / Distribution By" autocomplete="off" required/>
                                            <span class="manufacture_error" style="color: red; display: none;">Please enter manufacture</span>
                                        </td>
    
                                        <td>
                                            <a href="javascript:void(0);" class="text-primary addNewDrug form-group" data-toggle="tooltip" data-id="1" data-value="1" data-placement="top" title="">
                                                <i class="mdi mdi-plus font-size-20"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sponsor<span class="mandatory">*</span></label>
                                <select class="form-control select2 select_sponsor" name="sponsor" id="sponsor" data-placeholder="Select Sponsor" required>
                                    <option value="">Select Sponsor</option>
                                    @if(!is_null($sponsors))
                                        @foreach($sponsors as $sk => $sv)
                                            <option @if($study->sponsor == $sv->id) selected @endif value="{{ $sv->id }}">
                                                {{ $sv->sponsor_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectSponsor"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Scope<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectScope" name="scope[]" multiple id="scope" data-placeholder="Select Scope" required>
                                    <option value="">Select Scope</option>
                                    @if(!is_null($scope->paraCode))
                                        @foreach($scope->paraCode as $sk => $sv)
                                            <option @if(in_array($sv->id,$scopeId)) selected @endif value="{{ $sv->id }}">
                                                {{ $sv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectScope"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectStudyType" name="study_type" id="study_type" data-placeholder="Select Study Type" required>
                                    <option value="">Select Study Type</option>
                                    @if(!is_null($studyType->paraCode))
                                        @foreach($studyType->paraCode as $stk => $stv)
                                            <option @if($study->study_type == $stv->id) selected @endif value="{{ $stv->id }}">
                                                {{ $stv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectStudyType"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study Design<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectStudyDesign" name="study_design" id="study_design" data-placeholder="Select Study Design" required>
                                    <option value="">Select Study Design</option>
                                    @if(!is_null($studyDesign->paraCode))
                                        @foreach($studyDesign->paraCode as $sk => $sv)
                                            <option @if($study->study_design == $sv->id) selected @endif value="{{ $sv->id }}">
                                                {{ $sv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectStudyDesign"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study Sub Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectStudySubType" name="study_sub_type" id="study_sub_type" data-placeholder="Select Study Sub Type" required>
                                    <option value="">Select Study Sub Type</option>
                                    @if(!is_null($studySubType->paraCode))
                                        @foreach($studySubType->paraCode as $ssk => $ssv)
                                            <option @if($study->study_sub_type == $ssv->id) selected @endif value="{{ $ssv->id }}">
                                                {{ $ssv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectStudySubType"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Subject Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectSubjectType" name="subject_type" id="subject_type" data-placeholder="Select Subject Type" required>
                                    <option value="">Select Subject Type</option>
                                    @if(!is_null($subjectType->paraCode))
                                        @foreach($subjectType->paraCode as $stk => $stv)
                                            <option @if($study->subject_type == $stv->id) selected @endif value="{{ $stv->id }}">
                                                {{ $stv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectSubjectType"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Blinding Status<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectBlindingStatus" name="blinding_status" id="blinding_status" data-placeholder="Select Blinding Status" required>
                                    <option value="">Select Blinding Status</option>
                                    @if(!is_null($blindingStatus->paraCode))
                                        @foreach($blindingStatus->paraCode as $bk => $bv)
                                            <option @if($study->blinding_status == $bv->id) selected @endif value="{{ $bv->id }}">
                                                {{ $bv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectBlindingStatus"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>No Of Subjects<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric totalSubject" name="no_of_subject" id="no_of_subject" placeholder="No Of Subjects" autocomplete="off" value="{{ $study->no_of_subject }}" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>No of Male Subjects<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric maleSubject" name="no_of_male_subjects" id="no_of_male_subjects" placeholder="No of Male Subjects" autocomplete="off" value="{{ $study->no_of_male_subjects }}" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>No of Female Subjects<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric femaleSubject" name="no_of_female_subjects" id="no_of_female_subjects" placeholder="No of Female Subjects" autocomplete="off" value="{{ $study->no_of_female_subjects }}" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Washout Period(In Days)<span class="mandatory">*</span></label>
                                <input type="text" class="form-control width" name="washout_period" id="washout_period" placeholder="Washout Period" autocomplete="off" value="{{ $study->washout_period }}" required readonly/>
                            </div>

                            <div class="form-group mb-3">
                                <label>CR Location<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectCrLocation" id="cr_location" name="cr_location" data-placeholder="Select CR Location" required>
                                    <option value="">Select CR Location</option>
                                    @if(!is_null($crLocation))
                                        @foreach($crLocation as $lk => $lv)
                                            <option @if($study->cr_location == $lv->id) selected @endif value="{{ $lv->id }}">{{ $lv->location_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectCrLocation"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Clinical Ward Location<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectClinicalWordLocation" id="clinical_word_location" name="clinical_word_location" data-placeholder="Select Clinical Ward Location" required disabled>
                                    <option @if($study->clinical_word_location == '7') selected @endif value={{ $study->clinical_word_location }}>NA</option>
                                    @if(!is_null($clinicalWordLocation))
                                        @foreach($clinicalWordLocation as $cwk => $cwv)
                                            <option @if($study->clinical_word_location == $cwv->id) selected @endif value="{{ $cwv->id }}">
                                                {{ $cwv->ward_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="clinical_word_location" id="clinical_word_location" value="{{ $study->clinical_word_location }}">
                                <span id="selectClinicalWordLocation"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Additional Requirement<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="additional_requirement" id="additional_requirement" placeholder="Additional Requirement" autocomplete="off" value="{{ $study->additional_requirement }}" required readonly/>
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label>Quotation Amount<span class="mandatory">*</span></label>
                                <input type="text" class="form-control width" name="quotation_amount" id="quotation_amount" placeholder="Quotation Amount" autocomplete="off" value="{{ $study->quotation_amount }}" required/>
                            </div> -->

                            <div class="form-group mb-3">
                                <label>Study Title / Protocol Title<span class="mandatory">*</span></label>
                                <textarea class="form-control" name="study_text" id="study_text" placeholder="Study Title / Protocol Title" required readonly>{{ $study->study_text }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Project Manager<span class="mandatory">*</span></label>
                                <select class="form-control select2 projectManager" id="project_manager" name="project_manager" data-placeholder="Select Project Manager" required>
                                    <option value="">Select Project Manager</option>
                                    @if(!is_null($projectManager))
                                        @foreach($projectManager as $pk => $pv)
                                            @if(!is_null($pv->projectHead))
                                                @foreach($pv->projectHead as $pk => $pv)
                                                    <option @if($study->project_manager == $pv->id) selected @endif value="{{ $pv->id }}">
                                                        {{ $pv->employee_code }} - {{ $pv->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <span id="projectManager"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study Result</label>
                                <select class="form-control select2 studyResult" id="study_result" name="study_result" data-placeholder="Select Study Result">
                                    <option value="">Select Study Result</option>
                                    <option @if($study->study_result == 'NA') selected @endif value="NA">
                                        NA
                                    </option>
                                    <option @if($study->study_result == 'PASS') selected @endif value="PASS">
                                        Pass
                                    </option>
                                    <option @if($study->study_result == 'FAIL') selected @endif value="FAIL">
                                        Fail
                                    </option>
                                </select>
                                <!-- <span id="studyResult"></span> -->
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label>Total Sponsor Queries<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric" name="total_sponsor_queries" id="total_sponsor_queries" placeholder="Total Sponsor Queries" value="{{ $study->total_sponsor_queries }}" required >
                            </div>

                            <div class="form-group mb-3">
                                <label>Open Sponsor Queries<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric" name="open_sponsor_queries" id="open_sponsor_queries" placeholder="Open Sponsor Queries" value="{{ $study->open_sponsor_queries }}" required >
                            </div>

                            <div class="form-group mb-3">
                                <label>Regulatory Queries</label>
                                <input type="text" class="form-control numeric" name="regulatory_queries" id="regulatory_queries" placeholder="Regulatory Queries" value="{{ $study->regulatory_queries }}" >
                            </div>

                            <div class="form-group mb-3">
                                <label>Token Number</label>
                                <input type="text" class="form-control numeric" name="token_number" id="token_number" placeholder="Token Number" value="{{ $study->token_number }}" >
                            </div> -->

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
                                <label>Study No<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="study_no" id="study_no" placeholder="Study No" autocomplete="off"  maxlength="11" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sponsor Study No<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="sponsor_study_no" id="sponsor_study_no" placeholder="Sponsor Study No" autocomplete="off" value="{{ $study->sponsor_study_no }}" required />
                            </div>

                            <div class="form-group mb-3">
                                <label>Regulatory Submission<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectRegulatorySubmission" name="regulatory_submission[]" multiple id="regulatory_submission" data-placeholder="Select Regulatory Submission" required>
                                    <option value="">Select Regulatory Submission</option>
                                    @if(!is_null($regulatorySubmission->paraCode))
                                        @foreach($regulatorySubmission->paraCode as $rsk => $rsv)
                                            <option @if(in_array($rsv->id,$regulatoryId)) selected @endif value="{{ $rsv->id }}">
                                                {{ $rsv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectRegulatorySubmission"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Complexity<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectComplexity" name="complexity" id="complexity" data-placeholder="Select Complexity" required>
                                    <option value="">Select Complexity</option>
                                    @if(!is_null($complexity->paraCode))
                                        @foreach($complexity->paraCode as $ck => $cv)
                                            <option @if($study->complexity == $cv->id) selected @endif value="{{ $cv->id }}">
                                                {{ $cv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectComplexity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study Condition<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectStudyCondition" name="study_condition" id="study_condition" data-placeholder="Select Study Condition" required>
                                    <option value="">Select Study Condition</option>
                                    @if(!is_null($studyCondition->paraCode))
                                        @foreach($studyCondition->paraCode as $skk => $skv)
                                            <option @if($study->study_condition == $skv->id) selected @endif value="{{ $skv->id }}">
                                                {{ $skv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectStudyCondition"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Priority<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectPriority" name="priority" id="priority" data-placeholder="Select Priority" required disabled>
                                    <option value="">Select Priority</option>
                                    @if(!is_null($priority->paraCode))
                                        @foreach($priority->paraCode as $pk => $pv)
                                            <option @if($study->priority == $pv->id) selected @endif value="{{ $pv->id }}">
                                                {{ $pv->para_value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="priority" id="priority" value="{{ $study->priority }}">
                                <span id="selectPriority"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>No Of Groups<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric" name="no_of_groups" id="no_of_groups" placeholder="No Of Groups" autocomplete="off" value="{{ $study->no_of_groups }}" min="1" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>No Of Periods<span class="mandatory">*</span></label>
                                <input type="text" class="form-control numeric" name="no_of_periods" id="no_of_periods" placeholder="No Of Periods" autocomplete="off" value="{{ $study->no_of_periods }}" min="1" required/>
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label>Total Housing  (In Hours)<span class="mandatory">*</span></label>
                                <input type="text" class="form-control width" name="total_housing" id="total_housing" placeholder="Total Housing" autocomplete="off" value="{{ $study->total_housing }}" required/>
                            </div> -->

                            <div class="form-group mb-3">
                                <label>Pre Housing (In Hours)<span class="mandatory">*</span></label>
                                <input type="text" class="form-control width" name="pre_housing" id="total_housing" placeholder="Pre Housing" autocomplete="off" value="{{ $study->pre_housing }}" required readonly/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Post Housing (In Hours)<span class="mandatory">*</span></label>
                                <input type="text" class="form-control width" name="post_housing" id="total_housing" placeholder="Post Housing" autocomplete="off" value="{{ $study->post_housing }}" required readonly/>
                            </div>

                            <div class="form-group mb-3">
                                <label>BR Location<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectBrLocation" name="br_location" id="br_location" data-placeholder="Select BR Location" required>
                                    <option value="">Select BR Location</option>
                                    @if(!is_null($brLocation))
                                        @foreach($brLocation as $lk => $lv)
                                            <option @if($study->br_location == $lv->id) selected @endif value="{{ $lv->id }}">
                                                {{ $lv->location_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectBrLocation"></span>
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label>Study No Allocation Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control study_no_allocation_date datepickerStyle" name="study_no_allocation_date" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ date('d-m-Y', strtotime($study->study_no_allocation_date)) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study Start Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control tentative_study_start_date datepickerStyle" name="tentative_study_start_date" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ date('d-m-Y', strtotime($study->tentative_study_start_date)) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Study End Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control tentative_study_end_date datepickerStyle" name="tentative_study_end_date" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ date('d-m-Y', strtotime($study->tentative_study_end_date)) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>IMP Date<span class="mandatory">*</span></label>
                                <input type="text" class="form-control tentative_imp_date datepickerStyle" name="tentative_imp_date" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd/mm/yyyy" autocomplete="off" value="{{ date('d-m-Y', strtotime($study->tentative_imp_date)) }}" required>
                            </div> -->

                            <div class="form-group mb-3">
                                <label>Principle Investigator<span class="mandatory">*</span></label>
                                <select class="form-control select2 principleInvestigator" id="principle_investigator" name="principle_investigator" data-placeholder="Select Principle Investigator" required>
                                    <option value="">Select Principle Investigator</option>
                                    @if(!is_null($principle->principleInvestigator))
                                        @if($study->principle_investigator == 0)
                                            <option value="0" selected>NA</option>
                                            @foreach($principle->principleInvestigator as $pk => $pv)
                                                <option @if($study->principle_investigator == $pv->id) selected @endif value="{{ $pv->id }}">
                                                    {{ $pv->employee_code }} - {{ $pv->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="0">NA</option>
                                            @foreach($principle->principleInvestigator as $pk => $pv)
                                                <option @if($study->principle_investigator == $pv->id) selected @endif value="{{ $pv->id }}">
                                                    {{ $pv->employee_code }} - {{ $pv->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                                <span id="selectPrinciple"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Bioanalytical Investigator<span class="mandatory">*</span></label>
                                <select class="form-control select2" id="bioanalytical_investigator" name="bioanalytical_investigator" data-placeholder="Select Bioanalytical Investigator" required disabled>
                                    <option value="">Select Bioanalytical Investigator</option>
                                    <option @if ($study->bioanalytical_investigator == '0') selected @endif value="0">NA</option>
                                    @if(!is_null($bioanalytical->bioanalyticalInvestigator))
                                        @if($study->bioanalytical_investigator == 0)
                                            <option value="0" selected>NA</option>
                                            @foreach($bioanalytical->bioanalyticalInvestigator as $bk => $bv)
                                                <option @if($study->bioanalytical_investigator == $bv->id) selected @endif value="{{ $bv->id }}">
                                                    {{ $bv->employee_code }} - {{ $bv->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="0">NA</option>
                                            @foreach($bioanalytical->bioanalyticalInvestigator as $bk => $bv)
                                                <option @if($study->bioanalytical_investigator == $bv->id) selected @endif value="{{ $bv->id }}">
                                                    {{ $bv->employee_code }} - {{ $bv->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                                <input type="hidden" name="bioanalytical_investigator" id="bioanalytical_investigator" value="{{ $study->bioanalytical_investigator }}">
                            </div>

                            <div class="form-group mb-3"><label>Special Notes</label>
                                <select class="form-control select2 selectSpecialNotes" name="special_notes" id="special_notes" data-placeholder="Select Special Notes">
                                    <option value="">Select Special Notes</option>
                                    <option value="0">NA</option>
                                    @if(!is_null($specialNotes->paraCode))
                                        @foreach($specialNotes->paraCode as $pk => $pv)
                                        <option @if($study->special_notes == $pv->id) selected @endif value="{{ $pv->id }}">
                                            {{ $pv->para_value }}
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Remark</label>
                                <textarea class="form-control" name="remark" id="remark" placeholder="Remark">{{ $study->remark }}</textarea>
                            </div>


                            <div class="form-group mb-3">
                                <label>CDisc Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="cdisc_require" id="customSwitch" value="1" @if($study->cdisc_require == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>TLF Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="tlf_require" id="customSwitch" value="1" @if($study->tlf_require == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>SAP Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="sap_require" id="customSwitch" value="1" @if($study->sap_require == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>eCRF Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="ecrf_require" id="customSwitch" value="1" @if($study->ecrf_require == 1) checked @endif>&nbsp;Yes
                            </div>

                            <div class="form-group mb-3">
                                <label>BTIF Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="btif_require" id="customSwitch" value="1" @if($study->btif_require == 1) checked @endif>&nbsp;Yes
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>CSBE Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" name="csbe_require" id="customSwitch" value="1" @if($study->csbe_require == 1) checked @endif>&nbsp;Yes
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
                                    <a href="{{ route('admin.studyList') }}" class="btn btn-danger waves-effect">
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