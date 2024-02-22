<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-nowrap table-bordered new_drug_details">
                <thead>
                      <tr>
                        <th>Drug</th>
                        <th>Dosage From</th>
                        <th>Dosage</th>
                        <th>Drug Strength</th>
                        <th>UOM</th>
                        <th>Reference Type</th>
                        <th>Manufacture / Distribution By</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!is_null($study->drugDetails))
                        @foreach($study->drugDetails as $sk => $sv)
                            <tr class="removeRow">
                                <td>
                                    <select class="form-control select_drug noValidate" name="drug[{{ $sk }}][drug]" id="drug_details" required disabled>
                                        <option value="">Drug</option>
                                        @if (!is_null($drug))
                                            @foreach ($drug as $dk => $dv)
                                                <option @if($sv->drug_id == $dv->id) selected @endif value="{{ $dv->id }}">
                                                    {{ $dv->drug_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select_dosage_form noValidate" id="drug_dosage_form" name="drug[{{ $sk }}][dosage_form]" data-placeholder="Select Dosage Form" required disabled>
                                        <option value="">Dosage Form</option>
                                        @if(!is_null($dosageform->paraCode))
                                            @foreach($dosageform->paraCode as $dk => $dv)
                                                <option @if($sv->dosage_form_id == $dv->id) selected @endif value="{{ $dv->id }}">
                                                    {{ $dv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="drug_strength" class="form-control dosage noValidate" name="drug[{{ $sk }}][dosage]" placeholder="Dose" autocomplete="off" value="{{ $sv->dosage }}" required disabled/>
                                </td>
                                <td>
                                    <input type="text" id="drug_strength" class="form-control drug_strength noValidate" name="drug[{{ $sk }}][drug_strength]" placeholder="Drug Strength"  autocomplete="off" value="{{ $sv->drug_strength }}" disabled/>
                                </td>
                                <td>
                                    <select class="form-control selectUOM noValidate" name="drug[{{ $sk }}][uom]" data-placeholder="Select UOM" id="drug_strength" required disabled>
                                        <option value="">UOM</option>
                                        @if(!is_null($uom->paraCode))
                                            @foreach($uom->paraCode as $uk => $uv)
                                                <option @if($sv->uom_id == $uv->id) selected @endif value="{{ $uv->id }}">
                                                    {{ $uv->para_value }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control selectType noValidate" name="drug[{{ $sk }}][type]" id="drug_reference" data-placeholder="Select Type" required disabled>
                                        <option value="">Type</option>
                                        <option @if($sv->type == 'TEST') selected @endif value="TEST">Test</option>
                                        <option @if($sv->type == 'REFERENCE') selected @endif value="REFERENCE">Reference</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control manufacture noValidate" name="drug[{{ $sk }}][manufacture]" id="manufacture" placeholder="Manufacture / Distribution By" autocomplete="off" value="{{ $sv->manufacturedby }}" required disabled/>
                                </td>
                            </tr>
                        @endforeach
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

                <input type="hidden" name="id" value="{{ $study->id }}">

                <div class="form-group mb-3">
                    <label>Sponsor<span class="mandatory">*</span></label>
                    <select class="form-control select2 select_sponsor" name="sponsor" id="sponsor" data-placeholder="Select Sponsor" required disabled>
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
                    <label>Study Type<span class="mandatory">*</span></label>
                    <select class="form-control select2 selectStudyType" name="study_type" id="study_type" data-placeholder="Select Study Type" required disabled>
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
                    <select class="form-control select2 selectStudyDesign" name="study_design" id="study_design" data-placeholder="Select Study Design" required disabled>
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
                    <select class="form-control select2 selectStudySubType" name="study_sub_type" id="study_sub_type" data-placeholder="Select Study Sub Type" required disabled>
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
                    <select class="form-control select2 selectSubjectType" name="subject_type" id="subject_type" data-placeholder="Select Subject Type" required disabled>
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
                    <select class="form-control select2 selectBlindingStatus" name="blinding_status" id="blinding_status" data-placeholder="Select Blinding Status" required disabled>
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
                    <input type="text" class="form-control numeric" name="no_of_subject" id="no_of_subject" placeholder="No Of Subjects" autocomplete="off" value="{{ $study->no_of_subject }}" required disabled />
                </div>

                <div class="form-group mb-3">
                    <label>No of Male Subjects<span class="mandatory">*</span></label>
                    <input type="text" class="form-control numeric" name="no_of_male_subjects" id="no_of_male_subjects" placeholder="No of Male Subjects" autocomplete="off" value="{{ $study->no_of_male_subjects }}" required disabled />
                </div>

                <div class="form-group mb-3">
                    <label>No of Female Subjects<span class="mandatory">*</span></label>
                    <input type="text" class="form-control numeric" name="no_of_female_subjects" id="no_of_female_subjects" placeholder="No of Female Subjects" autocomplete="off" value="{{ $study->no_of_female_subjects }}" required disabled />
                </div>

                <div class="form-group mb-3">
                    <label>Washout Period(In Days)<span class="mandatory">*</span></label>
                    <input type="text" class="form-control width" name="washout_period" id="washout_period" placeholder="Washout Period" autocomplete="off" value="{{ $study->washout_period }}" required disabled />
                </div>

                <div class="form-group mb-3">
                    <label>CR Location<span class="mandatory">*</span></label>
                    <select class="form-control select2 selectCrLocation" name="cr_location" data-placeholder="Select CR Location" required disabled>
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
                    <label>Clinical Word Location<span class="mandatory">*</span></label>
                    <select class="form-control select2 selectClinicalWordLocation" name="clinical_word_location" data-placeholder="Select Clinical Word Location" required disabled>
                        <option @if($study->clinical_word_location == '7') selected @endif value={{ $study->clinical_word_location }}>NA</option>
                        @if(!is_null($clinicalWordLocation))
                            @foreach($clinicalWordLocation as $cwk => $cwv)
                                <option @if($study->clinical_word_location == $cwv->id) selected @endif value="{{ $cwv->id }}">
                                    {{ $cwv->ward_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <span id="selectClinicalWordLocation"></span>
                </div>

                <div class="form-group mb-3">
                    <label>Additional Requirement<span class="mandatory">*</span></label>
                    <input type="text" class="form-control" name="additional_requirement" id="additional_requirement" placeholder="Additional Requirement" autocomplete="off" value="{{ $study->additional_requirement }}" required disabled />
                </div>

                <div class="form-group mb-3">
                    <label>Study Title / Protocol Title<span class="mandatory">*</span></label>
                    <textarea class="form-control" name="study_text" id="study_text" placeholder="Study Title / Protocol Title" required disabled>{{ $study->study_text }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Study Result</label>
                    <select class="form-control select2 studyResult" name="study_result" data-placeholder="Select Study Result" disabled>
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
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">

                <div class="form-group mb-3">
                    <label>Study No<span class="mandatory">*</span></label>
                    <input type="text" class="form-control" name="study_no" id="study_no" placeholder="Study No" autocomplete="off" value="{{ $study->study_no }}" maxlength="11" required disabled/>
                </div>

                <div class="form-group mb-3">
                    <label>Sponsor Study No<span class="mandatory">*</span></label>
                    <input type="text" class="form-control" name="sponsor_study_no" id="sponsor_study_no" placeholder="Sponsor Study No" autocomplete="off" value="{{ $study->sponsor_study_no }}" required  disabled/>
                </div>

                <div class="form-group mb-3">
                    <label>Complexity<span class="mandatory">*</span></label>
                    <select class="form-control select2 selectComplexity" name="complexity" id="complexity" data-placeholder="Select Complexity" required disabled>
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
                    <select class="form-control select2 selectStudyCondition" name="study_condition" data-placeholder="Select Study Condition" required disabled>
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
                    <span id="selectPriority"></span>
                </div>

                <div class="form-group mb-3">
                    <label>No Of Groups<span class="mandatory">*</span></label>
                    <input type="text" class="form-control numeric" name="no_of_groups" id="no_of_groups" placeholder="No Of Groups" autocomplete="off" value="{{ $study->no_of_groups }}" min="1" required disabled/>
                </div>

                <div class="form-group mb-3">
                    <label>No Of Periods<span class="mandatory">*</span></label>
                    <input type="text" class="form-control numeric" name="no_of_periods" id="no_of_periods" placeholder="No Of Periods" autocomplete="off" value="{{ $study->no_of_periods }}" min="1" required disabled/>
                </div>

                <div class="form-group mb-3">
                    <label>Pre Housing (In Hours)<span class="mandatory">*</span></label>
                    <input type="text" class="form-control width" name="pre_housing" id="total_housing" placeholder="Pre Housing" autocomplete="off" value="{{ $study->pre_housing }}" required disabled/>
                </div>

                <div class="form-group mb-3">
                    <label>Post Housing (In Hours)<span class="mandatory">*</span></label>
                    <input type="text" class="form-control width" name="post_housing" id="total_housing" placeholder="Post Housing" autocomplete="off" value="{{ $study->post_housing }}" required disabled/>
                </div>

                <div class="form-group mb-3">
                    <label>BR Location<span class="mandatory">*</span></label>
                    <select class="form-control select2 selectBrLocation" name="br_location" id="br_location" data-placeholder="Select BR Location" required disabled>
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

                <div class="form-group mb-3">
                    <label>Principle Investigator<span class="mandatory">*</span></label>
                    <select class="form-control select2" name="principle_investigator" data-placeholder="Select Principle Investigator" required disabled>
                        <option value="">Select Principle Investigator</option>
                        @if(!is_null($principle->principleInvestigator))
                            @foreach($principle->principleInvestigator as $pk => $pv)
                                <option @if($study->principle_investigator == $pv->id) selected @endif value="{{ $pv->id }}">
                                    {{ $pv->employee_code }} - {{ $pv->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Bioanalytical Investigator<span class="mandatory">*</span></label>
                    <select class="form-control select2" name="bioanalytical_investigator" data-placeholder="Select Bioanalytical Investigator" required disabled>
                        <option value="">Select Bioanalytical Investigator</option>
                        <option @if ($study->bioanalytical_investigator == '0') selected @endif value="0">NA</option>
                        @if(!is_null($bioanalytical->bioanalyticalInvestigator))
                            @foreach($bioanalytical->bioanalyticalInvestigator as $bk => $bv)
                                <option @if($study->bioanalytical_investigator == $bv->id) selected @endif value="{{ $bv->id }}">
                                    {{ $bv->employee_code }} - {{ $bv->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Project Manager<span class="mandatory">*</span></label>
                    <select class="form-control select2 projectManager" name="project_manager" data-placeholder="Select Project Manager" required disabled>
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
                    <label>Remark<span class="mandatory">*</span></label>
                    <textarea class="form-control" name="study_text" id="study_text" placeholder="Remark" required disabled>{{ $study->remark }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>CDisc Require?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" name="cdisc_require" id="customSwitch" value="1" @if($study->cdisc_require == 1) checked @endif disabled>&nbsp;Yes
                </div>

                <div class="form-group mb-3">
                    <label>TLF Require?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" name="tlf_require" id="customSwitch" value="1" @if($study->tlf_require == 1) checked @endif disabled>&nbsp;Yes
                </div>

                <div class="form-group mb-3">
                    <label>SAP Require?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" name="sap_require" id="customSwitch" value="1" @if($study->sap_require == 1) checked @endif disabled>&nbsp;Yes
                </div>

                <div class="form-group mb-3">
                    <label>eCRF Require?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" name="ecrf_require" id="customSwitch" value="1" @if($study->ecrf_require == 1) checked @endif disabled>&nbsp;Yes
                </div>

                <div class="form-group mb-3">
                    <label>BTIF Require?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" name="btif_require" id="customSwitch" value="1" @if($study->btif_require == 1) checked @endif disabled>&nbsp;Yes
                </div>

                <div class="form-group mb-3">
                    <label>CSBE Required?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="form-check-input" type="checkbox" name="csbe_require" id="customSwitch" value="1" @if($study->csbe_require == 1) checked @endif disabled>&nbsp;Yes
                </div>
            </div>
        </div>
    </div>
</div>
