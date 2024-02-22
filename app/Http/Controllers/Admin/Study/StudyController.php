<?php

namespace App\Http\Controllers\Admin\Study;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SponsorMaster;
use App\Models\ParaMaster;
use App\Models\LocationMaster;
use App\Models\Study;
use App\Http\Controllers\GlobalController;
use App\Models\StudySubmission;
use App\Models\StudyScope;
use App\Models\Role;
use App\Models\DrugMaster;
use App\Models\StudyDrugDetails;
use App\Models\ClinicalWardMaster;
use Auth;
use App\Models\StudyTrail;
use App\Models\StudyDrugDetailsTrail;
use App\Models\StudyScopeTrail;
use App\Models\StudySubmissionTrail;
use App\Models\StudySchedule;
use App\Models\StudyScheduleTrail;
use App\Models\RoleModuleAccess;
use App\Models\Admin;
use DB;
use App\View\VwPreStudyProjection;
use App\Exports\StudyExport;
use Maatwebsite\Excel\Facades\Excel;

class StudyController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    /**
        * Study list
        *
        * @param mixed $studies
        *
        * @return to study listing page
    **/
    public function studyList(Request $request){

        $filter = 0;
        $projectManagerName = '';
        $complexityName = '';
        $studyDesignName='';
        $studySubTypeName = '';
        $studyTypeName = '';
        $studyConditionName ='';
        $subjectTypeName='';
        $priorityName = '';
        $blindingStatusName = '';
        $regulatorySubmissionName = '';
        $studyName = '';
        $sponsorName = '';
        $crLocationName = '';
        $brLocationName = '';
        $scopeName = '';
        $principleName = '';
        $bioanalyticalName = '';
        $specialNotesName = '';
        $noOfSubject = '';
        $noOfMaleSubject = '';
        $noOfFemaleSubject = '';
        $studyResult = '';
        $sponsorStudyName = '';
        $washoutPeriod = '';
        $clinicalWardLocation = '';
        $noOfGroup = '';
        $noOfPeriod = '';
        $noOfHousing = '';
        $noOfPreHousing = '';
        $noOfPostHousing = '';
        $startAllocationDate = '';
        $endAllocationDate = '';
        $startTentativeDate = '';
        $endTentativeDate = '';
        $startEndTentativeDate = '';
        $endEndTentativeDate = '';
        $startImpDate = '';
        $endImpDate = '';
        $drugName = '';
        $dosageFormName = '';
        $uomName = '';
        $drugType = '';
        $studyStatusName = '';

        $perPage = 25;
        if($request->page != ''){
            $page = $request->page;
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $projectManagers = Admin::whereIn('role_id', ['2', '3'])
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->get();

        $complexity = ParaMaster::where('para_code', 'Complexity')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with(['paraCode'])
                                ->first();

        $studyDesign = ParaMaster::where('para_code', 'StudyDesign')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with(['paraCode'])
                                 ->first();

        $studySubType = ParaMaster::where('para_code', 'StudySubType')
                                  ->where('is_active', 1)
                                  ->where('is_delete', 0)
                                  ->with(['paraCode'])
                                  ->first();

        $studyType = ParaMaster::where('para_code', 'StudyType')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with(['paraCode'])
                                ->first();

        $studyCondition = ParaMaster::where('para_code', 'StudyCondition')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with(['paraCode'])
                                    ->first();

        $subjectType = ParaMaster::where('para_code', 'SubjectType')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with(['paraCode'])
                                 ->first();

        $priority = ParaMaster::where('para_code', 'Priority')
                              ->where('is_active', 1)
                              ->where('is_delete', 0)
                              ->with(['paraCode'])
                              ->first();

        $blindingStatus = ParaMaster::where('para_code', 'BlindingStatus')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with(['paraCode'])
                                    ->first();

        $regulatorySubmission = ParaMaster::where('para_code', 'Submission')
                                          ->where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->with(['paraCode'])
                                          ->first();

        $maleStudies = Study::where('is_active', 1)
                            ->where('is_delete', 0)
                            ->groupBy('no_of_male_subjects')
                            ->get();

        $femaleSubject = Study::where('is_active', 1)
                              ->where('is_delete', 0)
                              ->groupBy('no_of_female_subjects')
                              ->get();

        $subject = Study::where('is_active', 1)
                        ->where('is_delete', 0)
                        ->groupBy('no_of_subject')
                        ->get();

        $specialNotes = ParaMaster::where('para_code', 'SpecialNotes')
                                  ->where('is_active', 1)
                                  ->where('is_delete', 0)
                                  ->with(['paraCode'])
                                  ->first();

        $studies = Study::where('is_active', 1)->where('is_delete', 0)->get();
        
        $sponsorStudy = Study::where('is_active', 1)
                             ->where('is_delete', 0)
                             ->groupBy('sponsor_study_no')
                             ->get();

        $sponsors = SponsorMaster::where('is_active', 1)->where('is_delete', 0)->get();

        $crLocation = LocationMaster::where('location_type', 'CRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $brLocation = LocationMaster::where('location_type', 'BRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $scope = ParaMaster::where('para_code', 'Scope')
                            ->where('is_active', 1)
                            ->where('is_delete', 0)
                            ->with(['paraCode'])
                            ->first();

        $principle = Role::where('name', 'Principle Investigator')
                         ->with([
                                'principleInvestigator'
                            ])
                         ->first();

        $bioanalytical = Role::where('name', 'Bioanalytical Investigator')
                             ->with([
                                    'bioanalyticalInvestigator'
                                ])
                             ->first();

        $totalWashoutPeriod = Study::where('is_active', 1)
                                   ->where('is_delete', 0)
                                   ->groupBy('washout_period')
                                   ->get();

        $clinicalWardMaster = ClinicalWardMaster::where('is_active', 1)
                                                ->where('is_delete', 0)
                                                ->get();

        $totalGroups = Study::where('is_active', 1)
                            ->where('is_delete', 0)
                            ->groupBy('no_of_groups')
                            ->get();

        $totalPeriods = Study::where('is_active', 1)
                             ->where('is_delete', 0)
                             ->groupBy('no_of_periods')
                             ->get();

        $totalHousing = Study::where('is_active', 1)
                             ->where('is_delete', 0)
                             ->groupBy('total_housing')
                             ->get();

        $preHousing = Study::where('is_active', 1)
                            ->where('is_delete', 0)
                            ->groupBy('pre_housing')
                            ->get();

        $postHousing = Study::where('is_active', 1)
                            ->where('is_delete', 0)
                            ->groupBy('post_housing')
                            ->get();

        $dosageForms = ParaMaster::where('para_code', 'DosageForm')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with(['paraCode'])
                                 ->first();

        $uoms = ParaMaster::where('para_code', 'UOM')
                          ->where('is_active', 1)
                          ->where('is_delete', 0)
                          ->with(['paraCode'])
                          ->first();

        $studyStatus= Study::where('is_delete',0)
                           ->where('is_active',1)
                           ->groupBy('study_status')
                           ->get();
        
        $drugs = DrugMaster::where('is_active', 1)->where('is_delete', 0)->get();

        $query = Study::select('id', 'study_result', 'study_status', 'study_slotted', 'tentative_clinical_date', 'projection_status', 'study_no', 'sponsor', 'project_manager', 'is_active')
                        ->where('is_delete', 0)
                        ->whereHas('projectManager', function($q){
                            $q->where('is_active',1);
                        })
                        ->orderBy('id', 'DESC');
        $queryCount = Study::where('is_delete', 0);                
        if(isset($request->project_manager) && $request->project_manager != ''){
            $filter = 1;
            $projectManagerName = $request->project_manager;
            $query->where('project_manager',$projectManagerName);
            $queryCount->where('project_manager',$projectManagerName);
        }

        if(isset($request->complexity) && $request->complexity != ''){
            $filter = 1;
            $complexityName = $request->complexity;
            $query->where('complexity',$complexityName);
            $queryCount->where('complexity',$complexityName);
        }

        if(isset($request->study_design) && $request->study_design != ''){
            $filter = 1;
            $studyDesignName = $request->study_design;
            $query->where('study_design',$studyDesignName);
            $queryCount->where('study_design',$studyDesignName);
        }

        if(isset($request->study_sub_type) && $request->study_sub_type != ''){
            $filter = 1;
            $studySubTypeName = $request->study_sub_type;
            $query->where('study_sub_type',$studySubTypeName);
            $queryCount->where('study_sub_type',$studySubTypeName);
        }

        if(isset($request->study_type) && $request->study_type != ''){ 
            $filter = 1;
            $studyTypeName = $request->study_type;
            $query->where('study_type',$studyTypeName);
            $queryCount->where('study_type',$studyTypeName);
        }

        if(isset($request->study_condition) && $request->study_condition != ''){ 
            $filter = 1;
            $studyConditionName = $request->study_condition;
            $query->where('study_condition',$studyConditionName);
            $queryCount->where('study_condition',$studyConditionName);
        }

        if(isset($request->subject_type) && $request->subject_type != ''){ 
            $filter = 1;
            $subjectTypeName = $request->subject_type;
            $query->where('subject_type',$subjectTypeName);
            $queryCount->where('subject_type',$subjectTypeName);
        }

        if(isset($request->priority) && $request->priority != ''){ 
            $filter = 1;
            $priorityName = $request->priority;
            $query->where('priority',$priorityName);
            $queryCount->where('priority',$priorityName);
        }

        if(isset($request->blinding_status) && $request->blinding_status != ''){ 
            $filter = 1;
            $blindingStatusName = $request->blinding_status;
            $query->where('blinding_status',$blindingStatusName);
            $queryCount->where('blinding_status',$blindingStatusName);
        }

        if(isset($request->regulatory_submission) && $request->regulatory_submission != ''){ 
            $filter = 1;
            $regulatorySubmissionName = $request->regulatory_submission;
            $query->whereHas('studyRegulatory', function($q) use($regulatorySubmissionName){ $q->where('regulatory_submission',$regulatorySubmissionName);});
            $queryCount->whereHas('studyRegulatory', function($q) use($regulatorySubmissionName){ $q->where('regulatory_submission',$regulatorySubmissionName);});
        }

        if(isset($request->study_no) && $request->study_no != ''){
            $filter = 1;
            $studyName = $request->study_no;
            $query->where('study_no',$studyName);
            $queryCount->where('study_no',$studyName);
        }

        if(isset($request->sponsor_id) && $request->sponsor_id != ''){
            $filter = 1;
            $sponsorName = $request->sponsor_id;
            $query->where('sponsor',$sponsorName);
            $queryCount->where('sponsor',$sponsorName);
        }

        if(isset($request->cr_location) && $request->cr_location != ''){
            $filter = 1;
            $crLocationName = $request->cr_location;
            $query->where('cr_location',$crLocationName);
            $queryCount->where('cr_location',$crLocationName);
        }

        if(isset($request->br_location) && $request->br_location != ''){
            $filter = 1;
            $brLocationName = $request->br_location;
            $query->where('br_location',$brLocationName);
            $queryCount->where('br_location',$brLocationName);
        }

        if(isset($request->scope) && $request->scope != ''){
            $filter = 1;
            $scopeName = $request->scope;
            $query->whereHas('studyScope', function($q) use($scopeName){ $q->where('scope',$scopeName);});
            $queryCount->whereHas('studyScope', function($q) use($scopeName){ $q->where('scope',$scopeName);});
        }

        if(isset($request->principle_investigator) && $request->principle_investigator != ''){
            $filter = 1;
            $principleName = $request->principle_investigator;
            $query->where('principle_investigator',$principleName);
            $queryCount->where('principle_investigator',$principleName);
        }

        if(isset($request->bioanalytical_investigator) && $request->bioanalytical_investigator != ''){
            $filter = 1;
            $bioanalyticalName = $request->bioanalytical_investigator;
            $query->where('bioanalytical_investigator',$bioanalyticalName);
            $queryCount->where('bioanalytical_investigator',$bioanalyticalName);
        }

        if(isset($request->special_notes) && $request->special_notes != ''){
            $filter = 1;
            $specialNotesName = $request->special_notes;
            $query->where('special_notes',$specialNotesName); 
            $queryCount->where('special_notes',$specialNotesName); 
        } 

        if(isset($request->no_of_subject) && $request->no_of_subject != ''){
            $filter = 1;
            $noOfSubject = $request->no_of_subject;
            $query->where('no_of_subject',$noOfSubject); 
            $queryCount->where('no_of_subject',$noOfSubject); 
        } 

        /*if(isset($request->no_of_male_subjects) && $request->no_of_male_subjects != ''){
            $filter = 1;
            $noOfMaleSubject = $request->no_of_male_subjects;
            $query->where('no_of_male_subjects',$noOfMaleSubject); 
        }

        if(isset($request->no_of_female_subjects) && $request->no_of_female_subjects != ''){
            $filter = 1;
            $noOfFemaleSubject = $request->no_of_female_subjects;
            $query->where('no_of_female_subjects',$noOfFemaleSubject); 
        }*/

        if(isset($request->study_result) && $request->study_result != ''){
            $filter = 1;
            $studyResult = $request->study_result;
            $query->where('study_result',$studyResult); 
            $queryCount->where('study_result',$studyResult); 
        }

        if(isset($request->sponsor_study_no) && $request->sponsor_study_no != ''){
            $filter = 1;
            $sponsorStudyName = $request->sponsor_study_no;
            $query->where('sponsor_study_no',$sponsorStudyName);
            $queryCount->where('sponsor_study_no',$sponsorStudyName);
        }

        /*if(isset($request->washout_period) && $request->washout_period != ''){
            $filter = 1;
            $washoutPeriod = $request->washout_period;
            $query->where('washout_period',$washoutPeriod); 
            $queryCount->where('washout_period',$washoutPeriod); 
        }*/

        if(isset($request->clinical_ward_location) && $request->clinical_ward_location != ''){
            $filter = 1;
            $clinicalWardLocation = $request->clinical_ward_location;
            $query->where('clinical_word_location',$clinicalWardLocation); 
            $queryCount->where('clinical_word_location',$clinicalWardLocation); 
        }

        if(isset($request->total_group) && $request->total_group != ''){
            $filter = 1;
            $noOfGroup = $request->total_group;
            $query->where('no_of_groups',$noOfGroup); 
            $queryCount->where('no_of_groups',$noOfGroup); 
        }

        if(isset($request->no_of_periods) && $request->no_of_periods != ''){
            $filter = 1;
            $noOdPeriod = $request->no_of_periods;
            $query->where('no_of_periods',$noOdPeriod); 
            $queryCount->where('no_of_periods',$noOdPeriod); 
        }

        /*if(isset($request->total_housing) && $request->total_housing != ''){
            $filter = 1;
            $noOfHousing = $request->total_housing;
            $query->where('total_housing',$noOfHousing); 
            $queryCount->where('total_housing',$noOfHousing); 
        }*/

        if(isset($request->pre_housing) && $request->pre_housing != ''){
            $filter = 1;
            $noOfPreHousing = $request->pre_housing;
            $query->where('pre_housing',$noOfPreHousing); 
            $queryCount->where('pre_housing',$noOfPreHousing); 
        }
        
        if(isset($request->post_housing) && $request->post_housing != ''){
            $filter = 1;
            $noOfPostHousing = $request->post_housing;
            $query->where('post_housing',$noOfPostHousing);
            $queryCount->where('post_housing',$noOfPostHousing);
        }

        /*if($request->start_all_date != '' && $request->end_all_date != ''){
            $filter = 1;
            $startAllocationDate = $request->start_all_date;
            $endAllocationDate = $request->end_all_date;
            $query->whereBetween('study_no_allocation_date',array($this->convertDateTime($startAllocationDate),$this->convertDateTime($endAllocationDate)));
            $queryCount->whereBetween('study_no_allocation_date',array($this->convertDateTime($startAllocationDate),$this->convertDateTime($endAllocationDate)));
        }

        if($request->start_tentative_date != '' && $request->end_tentative_date != ''){
            $filter = 1;
            $startTentativeDate = $request->start_tentative_date;
            $endTentativeDate = $request->end_tentative_date;
            $query->whereBetween('tentative_study_start_date',array($this->convertDateTime($startTentativeDate),$this->convertDateTime($endTentativeDate)));
            $queryCount->whereBetween('tentative_study_start_date',array($this->convertDateTime($startTentativeDate),$this->convertDateTime($endTentativeDate)));
        }
        
        if($request->start_end_tentative_date != '' && $request->end_end_tentative_date != ''){
            $filter = 1;
            $startEndTentativeDate = $request->start_end_tentative_date;
            $endEndTentativeDate = $request->end_end_tentative_date;
            $query->whereBetween('tentative_study_end_date',array($this->convertDateTime($startEndTentativeDate),$this->convertDateTime($endEndTentativeDate)));
            $queryCount->whereBetween('tentative_study_end_date',array($this->convertDateTime($startEndTentativeDate),$this->convertDateTime($endEndTentativeDate)));
        }

        if($request->start_imp_date != '' && $request->end_imp_date != ''){
            $filter = 1;
            $startImpDate = $request->start_imp_date;
            $endImpDate = $request->end_imp_date;
            $query->whereBetween('tentative_study_end_date',array($this->convertDateTime($startImpDate),$this->convertDateTime($endImpDate)));
            $queryCount->whereBetween('tentative_study_end_date',array($this->convertDateTime($startImpDate),$this->convertDateTime($endImpDate)));
        }*/

        if(isset($request->drug_name) && $request->drug_name != ''){
            $filter = 1;
            $drugName = $request->drug_name;
            $query->whereHas('drugDetails', function($q) use($drugName){ $q->where('drug_id',$drugName);});
            $queryCount->whereHas('drugDetails', function($q) use($drugName){ $q->where('drug_id',$drugName);});
        }

        if(isset($request->dosage_form_id) && $request->dosage_form_id != ''){
            $filter = 1;
            $dosageFormName = $request->dosage_form_id;
            $query->whereHas('drugDetails', function($q) use($dosageFormName){ $q->where('dosage_form_id',$dosageFormName);});
            $queryCount->whereHas('drugDetails', function($q) use($dosageFormName){ $q->where('dosage_form_id',$dosageFormName);});
        }

        if(isset($request->uom_id) && $request->uom_id != ''){
            $filter = 1;
            $dosageFormName = $request->uom_id;
            $query->whereHas('drugDetails', function($q) use($dosageFormName){ $q->where('uom_id',$dosageFormName);});
            $queryCount->whereHas('drugDetails', function($q) use($dosageFormName){ $q->where('uom_id',$dosageFormName);});
        }
        
        if(isset($request->uom_id) && $request->uom_id != ''){
            $filter = 1;
            $uomName = $request->uom_id;
            $query->whereHas('drugDetails', function($q) use($uomName){ $q->where('uom_id',$uomName);});
            $queryCount->whereHas('drugDetails', function($q) use($uomName){ $q->where('uom_id',$uomName);});
        }
        
        if(isset($request->drug_type) && $request->drug_type != ''){
            $filter = 1;
            $drugType = $request->drug_type;
            $query->whereHas('drugDetails', function($q) use($drugType){ $q->where('type',$drugType);});
            $queryCount->whereHas('drugDetails', function($q) use($drugType){ $q->where('type',$drugType);});
        }

        if(isset($request->study_status) && $request->study_status != ''){
            $filter = 1;
            $studyStatusName = $request->study_status;
            $query->where('study_status',$studyStatusName);
            $queryCount->where('study_status',$studyStatusName);
        }

        if (Auth::guard('admin')->user()->role_id == 3) {
            $studies = $query->where('project_manager', Auth::guard('admin')->user()->id)
                            ->with([
                                    'sponsorName',
                                    'studyType',
                                    'priorityName',
                                    'studyDesignName',
                                    'studySubTypeName',
                                    'subjectTypeName',
                                    'blindingStatusName',
                                    'crLocationName',
                                    'wardName',
                                    'complexityName',
                                    'studyConditionName',
                                    'brLocationName',
                                    'projectManager',
                                    'specialNotesName',
                                    'principleInvestigator',
                                    'bioanalyticalInvestigator',
                                    'studyScope' => function($q){
                                        $q->with([
                                            'scopeName'
                                        ]);
                                    },
                                    'drugDetails' => function($q) {
                                        $q->with([
                                            'drugName',
                                            'drugDosageName',
                                            'drugUom',
                                            'drugType'
                                        ]);
                                    }
                                ])
                            ->skip($offset)
                            ->limit($perPage)
                            ->get();
            $recordCount = $queryCount->where('project_manager', Auth::guard('admin')->user()->id)->count();
        } else {
            $studies = $query->with([
                                    'sponsorName',
                                    'studyType',
                                    'priorityName',
                                    'studyDesignName',
                                    'studySubTypeName',
                                    'subjectTypeName',
                                    'blindingStatusName',
                                    'crLocationName',
                                    'wardName',
                                    'complexityName',
                                    'studyConditionName',
                                    'brLocationName',
                                    'projectManager',
                                    'specialNotesName',
                                    'principleInvestigator',
                                    'bioanalyticalInvestigator',
                                    'studyScope' => function($q){
                                        $q->with([
                                            'scopeName'
                                        ]);
                                    },
                                    'drugDetails' => function($q) {
                                        $q->with([
                                            'drugName',
                                            'drugDosageName',
                                            'drugUom',
                                            'drugType'
                                        ]);
                                    }
                                ])
                            ->skip($offset)
                            ->limit($perPage)
                            ->get();
            $recordCount = $queryCount->count();
        }

        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','study-master')
                                      ->first();
        }

        return view('admin.study.study.study_list', compact('studies', 'admin', 'access', 'projectManagers', 'filter', 'projectManagerName','complexityName','complexity','studyDesign','studyDesignName', 'studySubType', 'studySubTypeName', 'studyType', 'studyTypeName', 'studyConditionName', 'studyCondition','subjectTypeName','subjectType', 'priority', 'priorityName', 'blindingStatusName', 'blindingStatus', 'regulatorySubmissionName', 'regulatorySubmission','sponsors','sponsorName', 'crLocation', 'crLocationName', 'brLocation', 'brLocationName','scopeName','scope','principleName','principle','bioanalytical','bioanalyticalName','studyName','studies', 'specialNotesName','specialNotes','noOfSubject','noOfMaleSubject', 'maleStudies', 'subject', 'femaleSubject', 'noOfFemaleSubject', 'studyResult', 'sponsorStudyName', 'totalWashoutPeriod', 'washoutPeriod', 'clinicalWardMaster', 'clinicalWardLocation', 'totalGroups', 'noOfGroup', 'totalPeriods', 'noOfPeriod', 'totalHousing', 'noOfHousing', 'preHousing', 'noOfPreHousing', 'noOfPostHousing', 'postHousing', 'startAllocationDate', 'endAllocationDate', 'startTentativeDate', 'endTentativeDate', 'startEndTentativeDate', 'endEndTentativeDate', 'startImpDate', 'endImpDate', 'drugs', 'drugName', 'dosageForms', 'dosageFormName', 'uoms', 'uomName', 'drugType', 'studyStatus','studyStatusName','sponsorStudy', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    /**
        * Add study
        *
        * @param mixed $sponsors, $dosageform, $scope, $studyDesign, $studySubType, $subjectType, $blindingStatus, 
        *        $crLocation, $dosage, $uom, $regulatorySubmission, $studyType, $complexity, $studyCondition, $priority, 
        *        $brLocation, $principle, $bioanalytical, $projectManager
        *
        * @return to add study page
    **/
    public function addStudy(){

        $sponsors = SponsorMaster::select('id', 'sponsor_name')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->orderBy('sponsor_name')
                                 ->get();

        $scope = ParaMaster::select('id', 'para_description')
                            ->where('para_code', 'Scope')
                            ->where('is_active', 1)
                            ->where('is_delete', 0)
                            ->with([
                                'paraCode' => function($q){
                                    $q->select('id', 'para_value', 'para_master_id');
                                }
                            ])
                            ->first();

        $studyType = ParaMaster::select('id', 'para_description')
                               ->where('para_code', 'StudyType')
                               ->where('is_active', 1)
                               ->where('is_delete', 0)
                               ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                ])
                               ->first();

        $studyDesign = ParaMaster::select('id', 'para_description')
                                 ->where('para_code', 'StudyDesign')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                 ])
                                 ->first();

        $studySubType = ParaMaster::select('id', 'para_description')
                                  ->where('para_code', 'StudySubType')
                                  ->where('is_active', 1)
                                  ->where('is_delete', 0)
                                  ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                  ->first();

        $subjectType = ParaMaster::select('id', 'para_description')
                                 ->where('para_code', 'SubjectType')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                 ->first();

        $blindingStatus = ParaMaster::select('id', 'para_description')
                                    ->where('para_code', 'BlindingStatus')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                    ->first();

        $crLocation = LocationMaster::select('id', 'location_name')
                                    ->where('location_type', 'CRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->orderBy('location_name')
                                    ->get();

        $projectManager = Role::select('id')
                              ->whereIn('id', ['2', '3'])
                              ->with([
                                    'projectHead' => function($q){
                                        $q->select('id', 'role_id', 'employee_code', 'name')
                                          ->where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->orderBy('name');
                                    }
                                ])
                              ->get();

        $regulatorySubmission = ParaMaster::select('id', 'para_description')
                                          ->where('para_code', 'Submission')
                                          ->where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->with([
                                                'paraCode' => function($q){
                                                    $q->select('id', 'para_value', 'para_master_id');
                                                }
                                            ])
                                          ->first();

        $complexity = ParaMaster::select('id', 'para_description')
                                ->where('para_code', 'Complexity')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                ])
                                ->first();

        $studyCondition = ParaMaster::select('id', 'para_description')
                                    ->where('para_code', 'StudyCondition')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                    ->first();

        $priority = ParaMaster::select('id', 'para_description')
                              ->where('para_code', 'Priority')
                              ->where('is_active', 1)
                              ->where('is_delete', 0)
                              ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                              ])
                              ->first();

        $brLocation = LocationMaster::select('id', 'location_name')
                                    ->where('location_type', 'BRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->orderBy('location_name')
                                    ->get();

        $principle = Role::select('id')
                         ->where('id', '4')
                         ->with([
                                'principleInvestigator' => function($q){
                                    $q->select('id', 'role_id', 'employee_code', 'name')
                                      ->where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->orderBy('name');
                                }
                            ])
                         ->first();

        $bioanalytical = Role::select('id')
                             ->where('id', '5')
                             ->with([
                                    'bioanalyticalInvestigator' => function($q){
                                        $q->select('id', 'role_id', 'employee_code', 'name')
                                          ->where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->orderBy('name');
                                    }
                                ])
                             ->first();

        $specialNotes = ParaMaster::select('id', 'para_description')
                                  ->where('para_code', 'SpecialNotes')
                                  ->where('is_active', 1)
                                  ->where('is_delete', 0)
                                  ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                  ])
                                  ->first();

        $dosageForm = ParaMaster::where('para_code', 'DosageForm')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with(['paraCode'])
                                ->first();

        $drug = DrugMaster::where('is_active', 1)->where('is_delete', 0)->orderBy('drug_name')->get();

        $uom = ParaMaster::where('para_code', 'UOM')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();

        return view('admin.study.study.add_study', compact('sponsors', 'scope', 'studyDesign', 'studySubType', 'subjectType', 'blindingStatus', 'crLocation', 'regulatorySubmission', 'studyType', 'complexity', 'studyCondition', 'priority', 'brLocation', 'principle', 'bioanalytical', 'projectManager', 'specialNotes', 'dosageForm', 'drug', 'uom'));
    }

    /**
        * Select clinical ward location
        *
        * @param $id
        *
        * @return to add & edit study page on cr location click append related clinical ward location through Ajax call
    **/
    public function selectClinicalWardLocation(Request $request){

        $clinicalWordLocation = ClinicalWardMaster::where('location_id', $request->id)->get();

        return $clinicalWordLocation;
    }

    /**
        * Save study
        *
        * @param $study_no, $sponsor, $study_text, $study_design, $study_sub_type, $blinding_status, $no_of_subject,
        *        $no_of_male_subjects, $no_of_female_subjects, $washout_period, $cr_location, $clinical_word_location, 
        *        $additional_requirement, $quotation_amount, $cdisc_require, $study_type, $complexity, 
        *        $study_condition, $priority, $no_of_groups, $no_of_periods, $total_housing, $br_location, 
        *        $study_no_allocation_date, $tentative_study_start_date, $tentative_study_end_date, $tentative_imp_date, 
        *        $project_manager, $principle_investigator, $bioanalytical_investigator, $total_sponsor_queries,
        *        $open_sponsor_queries, $study_result fields save in Study database
        * 
        *        $project_id, $regulatory_submission fields save in StudySubmission database
        *        $project_id, $scope fields save in StudyScope database
        *        $study_id, $dosage_form_id, $drug_id, $dosage, $uom_id, $type, $manufacturedby fields save in 
        *        StudyDrugDetails database
        *
        * @return to activity master listing page with data store in Study, StudySubmission, StudyScope, StudyDrugDetails database
    **/
    public function saveStudy(Request $request){
        
        $project = new Study;
        $project->study_no = strtoupper($request->study_no);
        $project->sponsor_study_no = $request->sponsor_study_no;
        $project->sponsor = $request->sponsor;
        $project->study_text = $request->study_text;
        $project->study_design = $request->study_design;
        $project->study_sub_type = $request->study_sub_type;
        $project->subject_type = $request->subject_type;
        $project->blinding_status = $request->blinding_status;
        $project->no_of_subject = $request->no_of_subject;
        $project->no_of_male_subjects = $request->no_of_male_subjects;
        $project->no_of_female_subjects = $request->no_of_female_subjects;
        $project->washout_period = $request->washout_period;
        $project->cr_location = $request->cr_location;
        $project->clinical_word_location = $request->clinical_word_location;
        $project->additional_requirement = $request->additional_requirement;
        $project->study_type = $request->study_type;
        $project->complexity = $request->complexity;
        $project->study_condition = $request->study_condition;
        $project->priority = $request->priority;
        $project->no_of_groups = $request->no_of_groups;
        $project->no_of_periods = $request->no_of_periods;
        /*$project->total_housing = $request->total_housing;*/
        $project->pre_housing = $request->pre_housing;
        $project->post_housing = $request->post_housing;
        $project->br_location = $request->br_location;
        /*$project->study_no_allocation_date = $this->convertDateTime($request->study_no_allocation_date);
        $project->tentative_study_start_date = $this->convertDateTime($request->tentative_study_start_date);
        $project->tentative_study_end_date = $this->convertDateTime($request->tentative_study_end_date);
        $project->tentative_imp_date = $this->convertDateTime($request->tentative_imp_date);*/
        $project->project_manager = $request->project_manager;
        $project->principle_investigator = $request->principle_investigator;
        $project->bioanalytical_investigator = $request->bioanalytical_investigator;
        /*$project->total_sponsor_queries = $request->total_sponsor_queries;
        $project->open_sponsor_queries = $request->open_sponsor_queries;*/

        if(isset($request->quotation_amount) && $request->quotation_amount != ''){
            $project->quotation_amount = $request->quotation_amount;
        }

        if(isset($request->cdisc_require) && $request->cdisc_require != '') {
            $project->cdisc_require = $request->cdisc_require;
        }

        if(isset($request->tlf_require) && $request->tlf_require != '') {
            $project->tlf_require = $request->tlf_require;
        }

        if(isset($request->sap_require) && $request->sap_require != '') {
            $project->sap_require = $request->sap_require;
        }

        if(isset($request->ecrf_require) && $request->ecrf_require != '') {
            $project->ecrf_require = $request->ecrf_require;
        }

        if(isset($request->btif_require) && $request->btif_require != '') {
            $project->btif_require = $request->btif_require;
        }

        if(isset($request->csbe_require) && $request->csbe_require != '') {
            $project->csbe_require = $request->csbe_require;
        }

        if ($request->study_result != '') {
            $project->study_result = $request->study_result;
        }

        if(isset($request->special_notes) && $request->special_notes != ''){
            $project->special_notes = $request->special_notes;
        }

        if(isset($request->remark) && $request->remark != ''){
            $project->remark = $request->remark;
        }

        /*if($request->token_number != ''){
            $project->token_number = $request->token_number;
        }

        if($request->regulatory_queries != ''){
            $project->regulatory_queries = $request->regulatory_queries;
        } else {
            $project->regulatory_queries = 0;
        }*/

        if (Auth::guard('admin')->user()->id != '') {
            $project->created_by_user_id = Auth::guard('admin')->user()->id;
        }

        $project->save();

        $projectTrail = new StudyTrail;
        $projectTrail->study_id = $project->id;
        $projectTrail->study_no = $request->study_no;
        $projectTrail->sponsor_study_no = $request->sponsor_study_no;
        $projectTrail->sponsor = $request->sponsor;
        $projectTrail->study_text = $request->study_text;
        $projectTrail->study_design = $request->study_design;
        $projectTrail->study_sub_type = $request->study_sub_type;
        $projectTrail->subject_type = $request->subject_type;
        $projectTrail->blinding_status = $request->blinding_status;
        $projectTrail->no_of_subject = $request->no_of_subject;
        $projectTrail->no_of_male_subjects = $request->no_of_male_subjects;
        $projectTrail->no_of_female_subjects = $request->no_of_female_subjects;
        $projectTrail->washout_period = $request->washout_period;
        $projectTrail->cr_location = $request->cr_location;
        $projectTrail->clinical_word_location = $request->clinical_word_location;
        $projectTrail->additional_requirement = $request->additional_requirement;
        $projectTrail->study_type = $request->study_type;
        $projectTrail->complexity = $request->complexity;
        $projectTrail->study_condition = $request->study_condition;
        $projectTrail->priority = $request->priority;
        $projectTrail->no_of_groups = $request->no_of_groups;
        $projectTrail->no_of_periods = $request->no_of_periods;
        /*$projectTrail->total_housing = $request->total_housing;*/
        $projectTrail->pre_housing = $request->pre_housing;
        $projectTrail->post_housing = $request->post_housing;
        $projectTrail->br_location = $request->br_location;
        /*$projectTrail->study_no_allocation_date = $this->convertDateTime($request->study_no_allocation_date);
        $projectTrail->tentative_study_start_date = $this->convertDateTime($request->tentative_study_start_date);
        $projectTrail->tentative_study_end_date = $this->convertDateTime($request->tentative_study_end_date);
        $projectTrail->tentative_imp_date = $this->convertDateTime($request->tentative_imp_date);*/
        $projectTrail->project_manager = $request->project_manager;
        $projectTrail->principle_investigator = $request->principle_investigator;
        $projectTrail->bioanalytical_investigator = $request->bioanalytical_investigator;
        /*$projectTrail->total_sponsor_queries = $request->total_sponsor_queries;
        $projectTrail->open_sponsor_queries = $request->open_sponsor_queries;*/

        if(isset($request->quotation_amount) && $request->quotation_amount != ''){
            $projectTrail->quotation_amount = $request->quotation_amount;
        } else {
            $projectTrail->quotation_amount = NULL;
        }

        if(isset($request->total_housing) && $request->total_housing != ''){
            $projectTrail->total_housing = $request->total_housing;
        } else {
            $projectTrail->total_housing = NULL;
        } 

        if ($request->study_result != '') {
            $projectTrail->study_result = $request->study_result;
        }

        if(isset($request->special_notes) && $request->special_notes != ''){
            $projectTrail->special_notes = $request->special_notes;
        }

        if(isset($request->remark) && $request->remark != ''){
            $projectTrail->remark = $request->remark;
        }

        if(isset($request->cdisc_require) && $request->cdisc_require != '') {
            $projectTrail->cdisc_require = $request->cdisc_require;
        }

        if(isset($request->tlf_require) && $request->tlf_require != '') {
            $projectTrail->tlf_require = $request->tlf_require;
        }

        if(isset($request->sap_require) && $request->sap_require != '') {
            $projectTrail->sap_require = $request->sap_require;
        }

        if(isset($request->ecrf_require) && $request->ecrf_require != '') {
            $projectTrail->ecrf_require = $request->ecrf_require;
        }

        if(isset($request->btif_require) && $request->btif_require != '') {
            $projectTrail->btif_require = $request->btif_require;
        }

        if(isset($request->csbe_require) && $request->csbe_require != '') {
            $projectTrail->csbe_require = $request->csbe_require;
        }

        /*if($request->token_number != ''){
            $projectTrail->token_number = $request->token_number;
        }

        if($request->regulatory_queries != ''){
            $projectTrail->regulatory_queries = $request->regulatory_queries;
        } else {
            $projectTrail->regulatory_queries = 0;
        }*/

        if (Auth::guard('admin')->user()->id != '') {
            $projectTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }

        $projectTrail->save();

        if(!is_null($request->regulatory_submission)){
            foreach ($request->regulatory_submission as $rk => $rv) {
                $submission = new StudySubmission;
                $submission->project_id = $project->id;
                $submission->regulatory_submission = $rv;
                $submission->created_by_user_id = Auth::guard('admin')->user()->id;
                $submission->save();

                $submissionTrail = new StudySubmissionTrail;
                $submissionTrail->study_submission_id = $submission->id;
                $submissionTrail->project_id = $projectTrail->id;
                $submissionTrail->regulatory_submission = $rv;
                $submissionTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                $submissionTrail->save();
            }
        }

        if(!is_null($request->scope)){
            foreach ($request->scope as $sk => $sv) {
                $scope = new StudyScope;
                $scope->project_id = $project->id;
                $scope->scope = $sv;
                $scope->created_by_user_id = Auth::guard('admin')->user()->id;
                $scope->save();

                $scopeTrail = new StudyScopeTrail;
                $scopeTrail->study_scope_id = $scope->id;
                $scopeTrail->project_id = $projectTrail->id;
                $scopeTrail->scope = $sv;
                $scopeTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                $scopeTrail->save();
            }
        }

        if(!is_null($request->drug)){
            foreach ($request->drug as $dk => $dv) {
                $drug = new StudyDrugDetails;
                $drug->study_id = $project->id;
                $drug->dosage_form_id = $dv['dosage_form'];
                $drug->drug_id = $dv['drug'];
                $drug->dosage = $dv['dosage'];
                $drug->drug_strength = $dv['drug_strength'];
                $drug->uom_id = $dv['uom'];
                $drug->type = $dv['type'];
                $drug->manufacturedby = $dv['manufacture'];
                $drug->created_by_user_id = Auth::guard('admin')->user()->id;
                $drug->save();

                $drugTrail = new StudyDrugDetailsTrail;
                $drugTrail->study_drug_details_id = $drug->id;
                $drugTrail->study_id = $projectTrail->id;
                $drugTrail->dosage_form_id = $dv['dosage_form'];
                $drugTrail->drug_id = $dv['drug'];
                $drugTrail->dosage = $dv['dosage'];
                $drugTrail->drug_strength = $dv['drug_strength'];
                $drugTrail->uom_id = $dv['uom'];
                $drugTrail->type = $dv['type'];
                $drugTrail->manufacturedby = $dv['manufacture'];
                $drugTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                $drugTrail->save();
            }
        }

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addStudy' : 'admin.studyList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study',
                'message' => 'Study successfully added!',
            ],
        ]);

    }

    /**
        * Edit study details
        *
        * @param mixed $sponsors, $dosageform, $scope, $studyDesign, $studySubType, $subjectType, $blindingStatus,
        *        $crLocation, $dosage, $uom, $regulatorySubmission, $studyType, $complexity, $studyCondition, $priority, 
        *        $brLocation, $principle, $bioanalytical, $projectManager, $drug, $study, $clinicalWordLocation, 
        *        $scopeId, $regulatoryId
        *
        * @return to edit study page
    **/
    public function editStudy($id){
        
        $study = Study::where('id', base64_decode($id))
                      ->with([
                            'studyScope',
                            'studyRegulatory',
                            'drugDetails' => function($q){
                                $q->select('id', 'study_id','drug_id', 'dosage_form_id', 'dosage', 'drug_strength', 'uom_id', 'type', 'manufacturedby');
                            },
                        ])
                      ->withCount(['drugDetails'])
                      ->first();

        $drug = DrugMaster::select('id', 'drug_name')
                          ->where('is_active', 1)
                          ->where('is_delete', 0)
                          ->get();

        $dosageform = ParaMaster::select('id', 'para_description')
                                ->where('para_code', 'DosageForm')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with([
                                    'paraCode' =>function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                ])
                                ->first();

        $uom = ParaMaster::select('id', 'para_description')
                         ->where('para_code', 'UOM')
                         ->where('is_active', 1)
                         ->where('is_delete', 0)
                         ->with([
                            'paraCode' => function($q){
                                $q->select('id', 'para_value', 'para_master_id');
                            }
                         ])
                         ->first();

        $sponsors = SponsorMaster::select('id', 'sponsor_name')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->get();

        $scope = ParaMaster::select('id', 'para_description')
                           ->where('para_code', 'Scope')
                           ->where('is_active', 1)
                           ->where('is_delete', 0)
                           ->with([
                                'paraCode' => function($q){
                                    $q->select('id', 'para_value', 'para_master_id');
                                }
                            ])
                           ->first();

        $studyType = ParaMaster::select('id', 'para_description')
                               ->where('para_code', 'StudyType')
                               ->where('is_active', 1)
                               ->where('is_delete', 0)
                               ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                ])
                               ->first();

        $studyDesign = ParaMaster::select('id', 'para_description')
                                 ->where('para_code', 'StudyDesign')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                 ])
                                 ->first();

        $studySubType = ParaMaster::select('id', 'para_description')
                                  ->where('para_code', 'StudySubType')
                                  ->where('is_active', 1)
                                  ->where('is_delete', 0)
                                  ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                  ->first();

        $subjectType = ParaMaster::select('id', 'para_description')
                                 ->where('para_code', 'SubjectType')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                 ->first();

        $blindingStatus = ParaMaster::select('id', 'para_description')
                                    ->where('para_code', 'BlindingStatus')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                    ->first();

        $crLocation = LocationMaster::select('id', 'location_name')
                                    ->where('location_type', 'CRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $clinicalWordLocation = ClinicalWardMaster::select('id', 'ward_name')
                                                  ->where('location_id', $study->cr_location)
                                                  ->get();

        $projectManager = Role::select('id')
                              ->whereIn('id', ['2', '3'])
                              ->with([
                                    'projectHead' => function($q){
                                        $q->select('id', 'role_id', 'employee_code', 'name');
                                    }
                                ])
                              ->get();

        $regulatorySubmission = ParaMaster::select('id', 'para_description')
                                          ->where('para_code', 'Submission')
                                          ->where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->with([
                                                'paraCode' => function($q){
                                                    $q->select('id', 'para_value', 'para_master_id');
                                                }
                                            ])
                                          ->first();

        $complexity = ParaMaster::select('id', 'para_description')
                                ->where('para_code', 'Complexity')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                                ])
                                ->first();

        $studyCondition = ParaMaster::select('id', 'para_description')
                                    ->where('para_code', 'StudyCondition')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                    ->first();

        $priority = ParaMaster::select('id', 'para_description')
                              ->where('para_code', 'Priority')
                              ->where('is_active', 1)
                              ->where('is_delete', 0)
                              ->with([
                                    'paraCode' => function($q){
                                        $q->select('id', 'para_value', 'para_master_id');
                                    }
                              ])
                              ->first();

        $brLocation = LocationMaster::select('id', 'location_name')
                                    ->where('location_type', 'BRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $principle = Role::select('id')
                         ->where('id', '4')
                         ->with([
                                'principleInvestigator' => function($q){
                                    $q->select('id', 'role_id', 'employee_code', 'name');
                                }
                            ])
                         ->first();

        $bioanalytical = Role::select('id')
                             ->where('id', '5')
                             ->with([
                                    'bioanalyticalInvestigator' => function($q){
                                        $q->select('id', 'role_id', 'employee_code', 'name');
                                    }
                                ])
                             ->first();

        $specialNotes = ParaMaster::select('id', 'para_description')
                                  ->where('para_code', 'SpecialNotes')
                                  ->where('is_active', 1)
                                  ->where('is_delete', 0)
                                  ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                  ])
                                  ->first();

        $scopeId = array();
        if (!is_null($study->studyScope)) {
            foreach ($study->studyScope as $sk => $sv) {
                $scopeId[] = $sv->scope;
            }
        }

        $regulatoryId = array();
        if (!is_null($study->studyRegulatory)) {
            foreach ($study->studyRegulatory as $rk => $rv) {
                $regulatoryId[] = $rv->regulatory_submission;
            }
        }

        return view('admin.study.study.edit_study', compact('sponsors', 'dosageform', 'scope', 'studyDesign', 'studySubType', 'subjectType', 'blindingStatus', 'crLocation', 'uom', 'regulatorySubmission', 'studyType', 'complexity', 'studyCondition', 'priority', 'brLocation', 'study', 'scopeId', 'regulatoryId', 'principle', 'bioanalytical', 'drug', 'clinicalWordLocation', 'projectManager', 'specialNotes'));
    }

    /**
        * Update study
        *
        * @param $study_no, $sponsor, $study_text, $study_design, $study_sub_type, $blinding_status, $no_of_subject,
        *        $no_of_male_subjects, $no_of_female_subjects, $washout_period, $cr_location, $clinical_word_location, 
        *        $additional_requirement, $quotation_amount, $cdisc_require, $study_type, $complexity, 
        *        $study_condition, $priority, $no_of_groups, $no_of_periods, $total_housing, $br_location, 
        *        $study_no_allocation_date, $tentative_study_start_date, $tentative_study_end_date, $tentative_imp_date, 
        *        $project_manager, $principle_investigator, $bioanalytical_investigator, $total_sponsor_queries,
        *        $open_sponsor_queries, $study_result fields save in Study database
        * 
        *        $project_id, $regulatory_submission fields save in StudySubmission database
        *        $project_id, $scope fields save in StudyScope database
        *        $study_id, $dosage_form_id, $drug_id, $dosage, $uom_id, $type, $manufacturedby fields save in 
        *        StudyDrugDetails database
        *
        * @return to activity master listing page with data update in Study, StudySubmission, StudyScope, StudyDrugDetails database
    **/
    public function updateStudy(Request $request){

        $checkStudy = Study::where('id', $request->id)->first();
        $groupNo = substr($request->study_no,-1);

        if($checkStudy->group_study != ''){

            $getStudy = Study::whereIn('id',[$checkStudy->group_study])->first();
            //$checkGroupStudy = Study::where('group_study', $request->group_study_id)->where('id', '!=', $request->id)->get();
            
            $totalSubject = Study::where('is_delete',0)->where('group_study', $request->group_study_id)->where('id', '!=', $request->id)->sum('no_of_subject');
            $totalMaleSubject = Study::where('is_delete',0)->where('group_study', $request->group_study_id)->where('id', '!=', $request->id)->sum('no_of_male_subjects');
            $totalFemaleSubject = Study::where('is_delete',0)->where('group_study', $request->group_study_id)->where('id', '!=', $request->id)->sum('no_of_female_subjects');

            $subjectDifference = $getStudy->no_of_subject - $totalSubject;
            $maleSubjectDifference = $getStudy->no_of_male_subjects - $totalMaleSubject;
            $femaleSubjectDifference = $getStudy->no_of_female_subjects - $totalFemaleSubject;
            
        } else {

            $getStudy = Study::where('id',$request->id)->first();
            
            $totalSubject = Study::where('id', $request->id)->sum('no_of_subject');
            $totalMaleSubject = Study::where('id', $request->id)->sum('no_of_male_subjects');
            $totalFemaleSubject = Study::where('id', $request->id)->sum('no_of_female_subjects');

            $subjectDifference = $getStudy->no_of_subject - $totalSubject;
            $maleSubjectDifference = $getStudy->no_of_male_subjects - $totalMaleSubject;
            $femaleSubjectDifference = $getStudy->no_of_female_subjects - $totalFemaleSubject;

        }

        if((($request->no_of_subject > $subjectDifference) || ($request->no_of_male_subjects > $maleSubjectDifference) || ($request->no_of_female_subjects > $femaleSubjectDifference)) && ($request->study_no != $getStudy->study_no.'-G1') && ($request->study_no != $getStudy->study_no) && ($checkStudy->group_study != '')) {
            
            if($getStudy->no_of_groups > 2){
                if(($request->no_of_subject < $subjectDifference) || ($request->no_of_male_subjects < $maleSubjectDifference) || ($request->no_of_female_subjects < $femaleSubjectDifference)){

                    $updateStudy = Study::where('id', $request->id)->update([
                                                                'no_of_subject' => Null,
                                                                'no_of_male_subjects' => Null,
                                                                'no_of_female_subjects' => Null
                                                            ]);

                } else {
                    $updateStudy = Study::where('id', $request->id)->update([
                                                                    'no_of_subject' => Null,
                                                                    'no_of_male_subjects' => Null,
                                                                    'no_of_female_subjects' => Null
                                                                ]);
                }
            } else {
                $updateStudy = Study::where('id', $request->id)->update([
                                                                'no_of_subject' => Null,
                                                                'no_of_male_subjects' => Null,
                                                                'no_of_female_subjects' => Null
                                                            ]);
            }

            return redirect()->back()->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Study',
                    'message' => 'Enter correct sum of male & female subject!',
                ],
            ]);

        } else if((($request->no_of_subject > $getStudy->no_of_subject) || ($request->no_of_male_subjects > $getStudy->no_of_male_subjects) || ($request->no_of_female_subjects > $getStudy->no_of_female_subjects)) && ($request->study_no == $getStudy->study_no.'-G1')) {
            
            $updateStudy = Study::where('id', $request->id)->update([
                                                                'no_of_subject' => Null,
                                                                'no_of_male_subjects' => Null,
                                                                'no_of_female_subjects' => Null
                                                            ]);

            return redirect()->back()->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Study',
                    'message' => 'Enter correct male and female subjects!',
                ],
            ]);
        
        } elseif ((($request->no_of_subject < $subjectDifference) || ($request->no_of_male_subjects < $maleSubjectDifference) || ($request->no_of_female_subjects < $femaleSubjectDifference)) && ($request->study_no != $getStudy->study_no.'-G1') && ($getStudy->no_of_groups == $groupNo)) {
            
            $updateStudy = Study::where('id', $request->id)->update([
                                                                'no_of_subject' => Null,
                                                                'no_of_male_subjects' => Null,
                                                                'no_of_female_subjects' => Null
                                                            ]);
            return redirect()->back()->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Study',
                    'message' => 'Enter correct male and female subjects!',
                ],
            ]);

        } else {
            
            $project = Study::findOrFail($request->id);
            $project->study_no = strtoupper($request->study_no);
            $project->sponsor_study_no = $request->sponsor_study_no;
            $project->sponsor = $request->sponsor;
            $project->study_text = $request->study_text;
            $project->study_design = $request->study_design;
            $project->study_sub_type = $request->study_sub_type;
            $project->subject_type = $request->subject_type;
            $project->blinding_status = $request->blinding_status;
            $project->no_of_subject = $request->no_of_subject;
            $project->no_of_male_subjects = $request->no_of_male_subjects;
            $project->no_of_female_subjects = $request->no_of_female_subjects;
            $project->washout_period = $request->washout_period;
            $project->cr_location = $request->cr_location;
            $project->clinical_word_location = $request->clinical_word_location;
            $project->additional_requirement = $request->additional_requirement;
            $project->study_type = $request->study_type;
            $project->complexity = $request->complexity;
            $project->study_condition = $request->study_condition;
            $project->priority = $request->priority;
            $project->no_of_groups = $request->no_of_groups;
            $project->no_of_periods = $request->no_of_periods;
            /*$project->total_housing = $request->total_housing;*/
            $project->pre_housing = $request->pre_housing;
            $project->post_housing = $request->post_housing;
            $project->br_location = $request->br_location;
            /*$project->study_no_allocation_date = $this->convertDateTime($request->study_no_allocation_date);
            $project->tentative_study_start_date = $this->convertDateTime($request->tentative_study_start_date);
            $project->tentative_study_end_date = $this->convertDateTime($request->tentative_study_end_date);
            $project->tentative_imp_date = $this->convertDateTime($request->tentative_imp_date);*/
            $project->project_manager = $request->project_manager;
            $project->principle_investigator = $request->principle_investigator;
            $project->bioanalytical_investigator = $request->bioanalytical_investigator;
            /*$project->total_sponsor_queries = $request->total_sponsor_queries;
            $project->open_sponsor_queries = $request->open_sponsor_queries;*/

            if (isset($request->quotation_amount) && $request->quotation_amount != '') {
                $project->quotation_amount = $request->quotation_amount;
            }

            if (isset($request->cdisc_require) && $request->cdisc_require != '') {
                $project->cdisc_require = $request->cdisc_require;
            } else {
                $project->cdisc_require = 0;
            }

            if(isset($request->tlf_require) && $request->tlf_require != '') {
                $project->tlf_require = $request->tlf_require;
            } else {
                $project->tlf_require = 0;
            }

            if ($request->study_result != '') {
                $project->study_result = $request->study_result;
            }

            if(isset($request->special_notes) && $request->special_notes != ''){
                $project->special_notes = $request->special_notes;
            }

            if(isset($request->remark) && $request->remark != ''){
                $project->remark = $request->remark;
            }

            /*if($request->token_number != ''){
                $project->token_number = $request->token_number;
            }*/

            if(isset($request->sap_require) && $request->sap_require != '') {
                $project->sap_require = $request->sap_require;
            } else {
                $project->sap_require = 0;
            }

            if(isset($request->ecrf_require) && $request->ecrf_require != '') {
                $project->ecrf_require = $request->ecrf_require;
            } else {
                $project->ecrf_require = 0;
            }

            if(isset($request->btif_require) && $request->btif_require != '') {
                $project->btif_require = $request->btif_require;
            } else {
                $project->btif_require = 0;
            }

            if(isset($request->csbe_require) && $request->csbe_require != '') {
                $project->csbe_require = $request->csbe_require;
            } else {
                $project->csbe_require = 0;
            }

            /*if($request->regulatory_queries != ''){
                $project->regulatory_queries = $request->regulatory_queries;
            } else {
                $project->regulatory_queries = 0;
            }*/

            if (Auth::guard('admin')->user()->id != '') {
                $project->updated_by_user_id = Auth::guard('admin')->user()->id;
            }

            $project->save();
        }

        $projectTrail = new StudyTrail;
        $projectTrail->study_id = $request->id;
        $projectTrail->study_no = $request->study_no;
        $projectTrail->sponsor_study_no = $request->sponsor_study_no;
        $projectTrail->sponsor = $request->sponsor;
        $projectTrail->study_text = $request->study_text;
        $projectTrail->study_design = $request->study_design;
        $projectTrail->study_sub_type = $request->study_sub_type;
        $projectTrail->subject_type = $request->subject_type;
        $projectTrail->blinding_status = $request->blinding_status;
        $projectTrail->no_of_subject = $request->no_of_subject;
        $projectTrail->no_of_male_subjects = $request->no_of_male_subjects;
        $projectTrail->no_of_female_subjects = $request->no_of_female_subjects;
        $projectTrail->washout_period = $request->washout_period;
        $projectTrail->cr_location = $request->cr_location;
        $projectTrail->clinical_word_location = $request->clinical_word_location;
        $projectTrail->additional_requirement = $request->additional_requirement;
        $projectTrail->study_type = $request->study_type;
        $projectTrail->complexity = $request->complexity;
        $projectTrail->study_condition = $request->study_condition;
        $projectTrail->priority = $request->priority;
        $projectTrail->no_of_groups = $request->no_of_groups;
        $projectTrail->no_of_periods = $request->no_of_periods;
        /*$projectTrail->total_housing = $request->total_housing;*/
        $projectTrail->pre_housing = $request->pre_housing;
        $projectTrail->post_housing = $request->post_housing;
        $projectTrail->br_location = $request->br_location;
        /*$projectTrail->study_no_allocation_date = $this->convertDateTime($request->study_no_allocation_date);
        $projectTrail->tentative_study_start_date = $this->convertDateTime($request->tentative_study_start_date);
        $projectTrail->tentative_study_end_date = $this->convertDateTime($request->tentative_study_end_date);
        $projectTrail->tentative_imp_date = $this->convertDateTime($request->tentative_imp_date);*/
        $projectTrail->project_manager = $request->project_manager;
        $projectTrail->principle_investigator = $request->principle_investigator;
        $projectTrail->bioanalytical_investigator = $request->bioanalytical_investigator;
        /*$projectTrail->total_sponsor_queries = $request->total_sponsor_queries;
        $projectTrail->open_sponsor_queries = $request->open_sponsor_queries;*/

        if (isset($request->quotation_amount) && $request->quotation_amount != '') {
            $projectTrail->quotation_amount = $request->quotation_amount;
        } else {
            $projectTrail->quotation_amount = NULL;
        }

        if (isset($request->total_housing) && $request->total_housing != '') {
            $projectTrail->total_housing = $request->total_housing;
        } else {
            $projectTrail->total_housing = NULL;
        }

        if (isset($request->cdisc_require) && $request->cdisc_require != '') {
            $projectTrail->cdisc_require = $request->cdisc_require;
        } else {
            $projectTrail->cdisc_require = 0;
        }

        if(isset($request->tlf_require) && $request->tlf_require != '') {
            $projectTrail->tlf_require = $request->tlf_require;
        } else {
            $projectTrail->tlf_require = 0;
        }
        
        if ($request->study_result != '') {
            $projectTrail->study_result = $request->study_result;
        }

        if(isset($request->special_notes) && $request->special_notes != ''){
            $projectTrail->special_notes = $request->special_notes;
        }

        if(isset($request->remark) && $request->remark != ''){
            $projectTrail->remark = $request->remark;
        }
        /*if($request->token_number != ''){
            $projectTrail->token_number = $request->token_number;
        }*/

        if(isset($request->sap_require) && $request->sap_require != '') {
            $projectTrail->sap_require = $request->sap_require;
        } else {
            $projectTrail->sap_require = 0;
        }

        if(isset($request->ecrf_require) && $request->ecrf_require != '') {
            $projectTrail->ecrf_require = $request->ecrf_require;
        } else {
            $projectTrail->ecrf_require = 0;
        }

        if(isset($request->btif_require) && $request->btif_require != '') {
            $projectTrail->btif_require = $request->btif_require;
        } else {
            $projectTrail->btif_require = 0;
        }

        if(isset($request->csbe_require) && $request->csbe_require != '') {
            $projectTrail->csbe_require = $request->csbe_require;
        } else {
            $projectTrail->csbe_require = 0;
        }

        /*if($request->regulatory_queries != ''){
            $projectTrail->regulatory_queries = $request->regulatory_queries;
        } else {
            $projectTrail->regulatory_queries = 0;
        }*/

        if (Auth::guard('admin')->user()->id != '') {
            $projectTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }

        $projectTrail->save();

        if(!is_null($request->regulatory_submission)){
            StudySubmission::where('project_id',$project->id)->delete();
            foreach ($request->regulatory_submission as $rk => $rv) {
                $submission = new StudySubmission;
                $submission->project_id = $project->id;
                $submission->regulatory_submission = $rv;
                $submission->updated_by_user_id = Auth::guard('admin')->user()->id;
                $submission->save();

                $submissionTrail = new StudySubmissionTrail;
                $submissionTrail->study_submission_id = $submission->id;
                $submissionTrail->project_id = $projectTrail->id;
                $submissionTrail->regulatory_submission = $rv;
                $submissionTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                $submissionTrail->save();
            }
        }

        if(!is_null($request->scope)){
            StudyScope::where('project_id',$project->id)->delete();
            foreach ($request->scope as $sk => $sv) {
                $scope = new StudyScope;
                $scope->project_id = $project->id;
                $scope->scope = $sv;
                $scope->updated_by_user_id = Auth::guard('admin')->user()->id;
                $scope->save();

                $scopeTrail = new StudyScopeTrail;
                $scopeTrail->study_scope_id = $scope->id;
               $scopeTrail->project_id = $projectTrail->id;
                $scopeTrail->scope = $sv;
                $scopeTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                $scopeTrail->save();
            }
        }

        if(!is_null($request->drug)){
            StudyDrugDetails::where('study_id',$project->id)->delete();
            foreach ($request->drug as $dk => $dv) {
                $drug = new StudyDrugDetails;
                $drug->study_id = $project->id;
                $drug->dosage_form_id = $dv['dosage_form'];
                $drug->drug_id = $dv['drug'];
                $drug->dosage = $dv['dosage'];
                $drug->drug_strength = $dv['drug_strength'];
                $drug->uom_id = $dv['uom'];
                $drug->type = $dv['type'];
                $drug->manufacturedby = $dv['manufacture'];
                $drug->updated_by_user_id = Auth::guard('admin')->user()->id;
                $drug->save();

                $drugTrail = new StudyDrugDetailsTrail;
                $drugTrail->study_drug_details_id = $drug->id;
                $drugTrail->study_id = $projectTrail->id;
                $drugTrail->dosage_form_id = $dv['dosage_form'];
                $drugTrail->drug_id = $dv['drug'];
                $drugTrail->dosage = $dv['dosage'];
                $drugTrail->drug_strength = $dv['drug_strength'];
                $drugTrail->uom_id = $dv['uom'];
                $drugTrail->type = $dv['type'];
                $drugTrail->manufacturedby = $dv['manufacture'];
                $drugTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                $drugTrail->save();
            }
        }

        return redirect(route('admin.studyList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study',
                'message' => 'Study successfully updated!',
            ],
        ]);
    }

    /**
        * Delete study
        *
        * @param $id
        *
        * @return to study listing page with data delete from Study database
    **/
    public function deleteStudy(Request $request){

        $delete = Study::where('id',$request->study_delete_id)
                       ->update([
                            'is_delete' => 1, 
                            'updated_by_user_id' => Auth::guard('admin')->user()->id
                        ]);
                       
        $deleteStudy = Study::where('id',$request->study_delete_id)->first();
        
        $deleteTrail = new StudyTrail;
        $deleteTrail->study_id = $request->study_delete_id;
        $deleteTrail->study_no = $deleteStudy->study_no;
        $deleteTrail->sponsor_study_no = $deleteStudy->sponsor_study_no;
        $deleteTrail->sponsor = $deleteStudy->sponsor;
        $deleteTrail->study_text = $deleteStudy->study_text;
        $deleteTrail->study_design = $deleteStudy->study_design;
        $deleteTrail->study_sub_type = $deleteStudy->study_sub_type;
        $deleteTrail->subject_type = $deleteStudy->subject_type;
        $deleteTrail->blinding_status = $deleteStudy->blinding_status;
        $deleteTrail->no_of_subject = $deleteStudy->no_of_subject;
        $deleteTrail->no_of_male_subjects = $deleteStudy->no_of_male_subjects;
        $deleteTrail->no_of_female_subjects = $deleteStudy->no_of_female_subjects;
        $deleteTrail->washout_period = $deleteStudy->washout_period;
        $deleteTrail->cr_location = $deleteStudy->cr_location;
        $deleteTrail->clinical_word_location = $deleteStudy->clinical_word_location;
        $deleteTrail->additional_requirement = $deleteStudy->additional_requirement;
        $deleteTrail->study_type = $deleteStudy->study_type;
        $deleteTrail->complexity = $deleteStudy->complexity;
        $deleteTrail->study_condition = $deleteStudy->study_condition;
        $deleteTrail->priority = $deleteStudy->priority;
        $deleteTrail->no_of_groups = $deleteStudy->no_of_groups;
        $deleteTrail->no_of_periods = $deleteStudy->no_of_periods;
        /*$deleteTrail->total_housing = $deleteStudy->total_housing;*/
        $deleteTrail->pre_housing = $deleteStudy->pre_housing;
        $deleteTrail->post_housing = $deleteStudy->post_housing;
        $deleteTrail->br_location = $deleteStudy->br_location;
        /*$deleteTrail->study_no_allocation_date = $deleteStudy->study_no_allocation_date;
        $deleteTrail->tentative_study_start_date = $deleteStudy->tentative_study_start_date;
        $deleteTrail->tentative_study_end_date = $deleteStudy->tentative_study_end_date;
        $deleteTrail->tentative_imp_date = $deleteStudy->tentative_imp_date;*/
        $deleteTrail->project_manager = $deleteStudy->project_manager;
        $deleteTrail->principle_investigator = $deleteStudy->principle_investigator;
        $deleteTrail->bioanalytical_investigator = $deleteStudy->bioanalytical_investigator;
        /*$deleteTrail->total_sponsor_queries = $deleteStudy->total_sponsor_queries;
        $deleteTrail->open_sponsor_queries = $deleteStudy->open_sponsor_queries;*/

        if (isset($deleteStudy->quotation_amount) && $deleteStudy->quotation_amount != '') {
            $deleteTrail->quotation_amount = $deleteStudy->quotation_amount;
        }

        if (isset($deleteStudy->cdisc_require) && $deleteStudy->cdisc_require != '') {
            $deleteTrail->cdisc_require = $deleteStudy->cdisc_require;
        } else {
            $deleteTrail->cdisc_require = 0;
        }

        if(isset($deleteStudy->tlf_require) && $deleteStudy->tlf_require != '') {
            $deleteTrail->tlf_require = $deleteStudy->tlf_require;
        } else {
            $deleteTrail->tlf_require = 0;
        }
        
        if ($deleteStudy->study_result != '') {
            $deleteTrail->study_result = $deleteStudy->study_result;
        }

        if(isset($deleteStudy->special_notes) && $deleteStudy->special_notes != ''){
            $deleteTrail->special_notes = $deleteStudy->special_notes;
        }

        if(isset($request->remark) && $request->remark != ''){
            $deleteTrail->remark = $request->remark;
        }
        /*if($deleteStudy->token_number != ''){
            $deleteTrail->token_number = $deleteStudy->token_number;
        }*/

        if(isset($deleteStudy->sap_require) && $deleteStudy->sap_require != '') {
            $deleteTrail->sap_require = $deleteStudy->sap_require;
        } else {
            $deleteTrail->sap_require = 0;
        }

        if(isset($deleteStudy->ecrf_require) && $deleteStudy->ecrf_require != '') {
            $deleteTrail->ecrf_require = $deleteStudy->ecrf_require;
        } else {
            $deleteTrail->ecrf_require = 0;
        }

        if(isset($deleteStudy->btif_require) && $deleteStudy->btif_require != '') {
            $deleteTrail->btif_require = $deleteStudy->btif_require;
        } else {
            $deleteTrail->btif_require = 0;
        }

        if(isset($deleteStudy->csbe_require) && $deleteStudy->csbe_require != '') {
            $deleteTrail->csbe_require = $deleteStudy->csbe_require;
        } else {
            $deleteTrail->csbe_require = 0;
        }

        // if($deleteStudy->regulatory_queries != ''){
        //     $deleteTrail->regulatory_queries = $deleteStudy->regulatory_queries;
        // } else {
        //     $deleteTrail->regulatory_queries = 0;
        // }

        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }

        $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        $deleteTrail->is_delete = 1;
        $deleteTrail->save();

        $getStudySchedule = StudySchedule::where('study_id',$request->study_delete_id)->first();

        if (!is_null($getStudySchedule)) {
            $deleteStudyScheduleTrail = new StudyScheduleTrail;
            $deleteStudyScheduleTrail->study_schedule_id = $getStudySchedule->id;
            $deleteStudyScheduleTrail->study_id = $request->study_delete_id;
            $deleteStudyScheduleTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
            $deleteStudyScheduleTrail->is_delete = 1;
            $deleteStudyScheduleTrail->save();

            $deleteStudySchedule = StudySchedule::where('study_id',$request->study_delete_id)->delete();
        }

        if($delete){
            return redirect(route('admin.studyList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Study',
                    'message' => 'Study successfully deleted',
                ],
            ]);     
        }
    }

    /**
        * Study status change
        *
        * @param $id, $option
        *
        * @return to study listing page change on toggle Study active & deactive
    **/
    public function changeStudyStatus(Request $request){

        $status = Study::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    /**
        * Select drug details
        *
        * @param $dosageForm, $drug, $uom
        *
        * @return to add & edit study page append select drug details fields
    **/
    public function selectDrugDetails(Request $request){
        
        $dosageForm = ParaMaster::where('para_code', 'DosageForm')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with(['paraCode'])
                                ->first();

        $drug = DrugMaster::where('is_active', 1)->where('is_delete', 0)->orderBy('drug_name')->get();
        /*$dosage = ParaMaster::where('para_code', 'Dosage')->with(['paraCode'])->first();*/
        $uom = ParaMaster::where('para_code', 'UOM')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();

        $html = '<tr class="removeRow"><td>';

        $html .= '<select class="form-select select_drug noValidate" name="drug['.$request->id.'][drug]" id="drug_details" required><option value="">Drug</option>';

        if (!is_null($drug)) {
            foreach ($drug as $dk => $dv) {
                $html .= '<option value="'.$dv->id.'">'.$dv->drug_name.'</option>';
            }
        }

        $html .= '</select><span class="select_drug_error" style="color: red; display: none;">Please select drug</span></td><td><select class="form-select select_dosage_form noValidate" id="drug_dosage_form" name="drug['.$request->id.'][dosage_form]" data-placeholder="Select Dosage Form" required><option value="">Dosage Form</option>';

        if (!is_null($dosageForm->paraCode)) {
            foreach ($dosageForm->paraCode as $dk => $dv) {
                $html .= '<option value="'.$dv->id.'">'.$dv->para_value.'</option>';
            }
        }

        $html .= '</select><span class="select_dosage_form_error" style="color: red; display: none;">Please select dosage form</span></td><td><input type="text" id="drug_strength" class="form-control dosage noValidate" name="drug['.$request->id.'][dosage]" placeholder="Dosage" autocomplete="off" required/><span class="dosage_error" style="color: red; display: none;">Please enter dosage</span></td><td><input type="text" id="drug_strength" class="form-control drug_strength noValidate" name="drug['.$request->id.'][drug_strength]" placeholder="Drug Strength"  autocomplete="off"/><span class="drug_strength_error" style="color: red; display: none;">Please enter drug strength</span></td><td><select class="form-select selectUOM noValidate" name="drug['.$request->id.'][uom]" data-placeholder="Select UOM" id="drug_strength" required><option value="">UOM</option>';

        if (!is_null($uom->paraCode)) {
            foreach ($uom->paraCode as $uk => $uv) {
                $html .= '<option value="'.$uv->id.'">'.$uv->para_value.'</option>';
            }
        }

        $html .= '</select><span class="select_uom_error" style="color: red; display: none;">Please select uom</span></td><td><select class="form-select selectType noValidate" name="drug['.$request->id.'][type]" id="drug_reference" data-placeholder="Select Type" required><option value="">Type</option><option value="TEST">Test</option><option value="REFERENCE">Reference</option>';

        $html .= '</select><span class="select_type_error" style="color: red; display: none;">Please select drug reference</span></td><td><input type="text" class="form-control manufacture noValidate" name="drug['.$request->id.'][manufacture]" id="manufacture" placeholder="Manufacture / Distribution By" autocomplete="off" required/><span class="manufacture_error" style="color: red; display: none;">Please enter manufacture</span></td><td><a href="javascript:void(0);" class="text-danger remove" data-toggle="tooltip" data-id="'.$request->id.'" data-placement="top" title="" data-original-title="Remove"><i class="mdi mdi-close font-size-20"></i></a></td></tr>';

        return response()->json(['html'=> $html]);
    }

    public function copyStudy($id){

        $originalStudy = Study::with([
                                    'studyScope', 
                                    'studyRegulatory',
                                    'drugDetails'
                                ])
                                ->find(base64_decode($id));

        
        $checkStudy = Study::where('study_no', $originalStudy->study_no)
                            ->where('is_delete', 0)
                            ->count();

        $checkGroup = Study::where('group_study', base64_decode($id))
                           ->where('is_delete', 0)
                           ->count();

        if($checkGroup < $originalStudy->no_of_groups){
            $allStudy = $checkStudy + $checkGroup;
            $newStudy = $originalStudy->replicate();
            $newStudy->save();

            $update = Study::where('id', $newStudy->id)->update(['global_priority_no' => NULL]);
            if ($allStudy > 0) {
                $studyNo = $originalStudy->study_no."-G".$allStudy;
                
                $updateStudy = Study::where('id', $newStudy->id)
                                    ->update([
                                        'no_of_groups' => 1,
                                        'study_no' => $studyNo,
                                        'group_study' => base64_decode($id),
                                        'no_of_subject' => NULL,
                                        'no_of_male_subjects' => NULL,
                                        'no_of_female_subjects' => NULL
                                    ]);
            }
            $studyTrail = new StudyTrail;
            $studyTrail->study_id = base64_decode($id);
            $studyTrail->study_no = $studyNo;
            $studyTrail->sponsor_study_no = $originalStudy->sponsor_study_no;
            $studyTrail->sponsor = $originalStudy->sponsor;
            $studyTrail->study_text = $originalStudy->study_text;
            $studyTrail->study_design = $originalStudy->study_design;
            $studyTrail->study_sub_type = $originalStudy->study_sub_type;
            $studyTrail->subject_type = $originalStudy->subject_type;
            $studyTrail->blinding_status = $originalStudy->blinding_status;
            $studyTrail->no_of_subject = NULL;
            $studyTrail->no_of_male_subjects = NULL;
            $studyTrail->no_of_female_subjects = NULL;
            $studyTrail->washout_period = $originalStudy->washout_period;
            $studyTrail->cr_location = $originalStudy->cr_location;
            $studyTrail->clinical_word_location = $originalStudy->clinical_word_location;
            $studyTrail->additional_requirement = $originalStudy->additional_requirement;
            $studyTrail->study_type = $originalStudy->study_type;
            $studyTrail->complexity = $originalStudy->complexity;
            $studyTrail->study_condition = $originalStudy->study_condition;
            $studyTrail->priority = $originalStudy->priority;
            $studyTrail->no_of_groups = 1;
            $studyTrail->no_of_periods = $originalStudy->no_of_periods;
            /*$studyTrail->total_housing = $originalStudy->total_housing;*/
            $studyTrail->pre_housing = $originalStudy->pre_housing;
            $studyTrail->post_housing = $originalStudy->post_housing;
            $studyTrail->br_location = $originalStudy->br_location;
            $studyTrail->study_no_allocation_date = $originalStudy->study_no_allocation_date;
            $studyTrail->tentative_study_start_date =$originalStudy->tentative_study_start_date;
            $studyTrail->tentative_study_end_date = $originalStudy->tentative_study_end_date;
            $studyTrail->tentative_imp_date = $originalStudy->tentative_imp_date;
            $studyTrail->project_manager = $originalStudy->project_manager;
            $studyTrail->principle_investigator = $originalStudy->principle_investigator;
            $studyTrail->bioanalytical_investigator = $originalStudy->bioanalytical_investigator;
            $studyTrail->total_sponsor_queries = $originalStudy->total_sponsor_queries;
            $studyTrail->open_sponsor_queries = $originalStudy->open_sponsor_queries;
            $studyTrail->group_study = base64_decode($id);

            if(isset($originalStudy->quotation_amount) && $originalStudy->quotation_amount != ''){
                $studyTrail->quotation_amount = $originalStudy->quotation_amount;
            } else {
                $studyTrail->quotation_amount = NULL;
            }

            if(isset($originalStudy->total_housing) && $originalStudy->total_housing != ''){
                $studyTrail->total_housing = $originalStudy->total_housing;
            } else {
                $studyTrail->total_housing = NULL;
            } 

            if ($originalStudy->study_result != '') {
                $studyTrail->study_result = $originalStudy->study_result;
            }

            if(isset($originalStudy->special_notes) && $originalStudy->special_notes != ''){
                $studyTrail->special_notes = $originalStudy->special_notes;
            }

            if(isset($originalStudy->remark) && $originalStudy->remark != ''){
                $studyTrail->remark = $originalStudy->remark;
            }

            if(isset($originalStudy->cdisc_require) && $originalStudy->cdisc_require != '') {
                $studyTrail->cdisc_require = $originalStudy->cdisc_require;
            }

            if(isset($originalStudy->tlf_require) && $originalStudy->tlf_require != '') {
                $studyTrail->tlf_require = $originalStudy->tlf_require;
            }

            if(isset($originalStudy->sap_require) && $originalStudy->sap_require != '') {
                $studyTrail->sap_require = $originalStudy->sap_require;
            }

            if(isset($originalStudy->ecrf_require) && $originalStudy->ecrf_require != '') {
                $studyTrail->ecrf_require = $originalStudy->ecrf_require;
            }

            if(isset($originalStudy->btif_require) && $originalStudy->btif_require != '') {
                $studyTrail->btif_require = $originalStudy->btif_require;
            }

            if(isset($originalStudy->csbe_require) && $originalStudy->csbe_require != '') {
                $studyTrail->csbe_require = $originalStudy->csbe_require;
            }

            if($originalStudy->token_number != ''){
                $studyTrail->token_number = $originalStudy->token_number;
            }

            if($originalStudy->regulatory_queries != ''){
                $studyTrail->regulatory_queries = $originalStudy->regulatory_queries;
            } else {
                $studyTrail->regulatory_queries = 0;
            }

            if (Auth::guard('admin')->user()->id != '') {
                $studyTrail->created_by_user_id = Auth::guard('admin')->user()->id;
            }

            $studyTrail->save();

            $drugDetails = StudyDrugDetails::where('study_id', base64_decode($id))->get();
            if(!is_null($drugDetails)){
                foreach ($drugDetails as $dk => $dv) {
                    $drug = new StudyDrugDetails;
                    $drug->study_id = $newStudy->id;
                    $drug->dosage_form_id = $dv->dosage_form_id;
                    $drug->drug_id = $dv->drug_id;
                    $drug->dosage = $dv->dosage;
                    $drug->drug_strength = $dv->drug_strength;
                    $drug->uom_id = $dv->uom_id;
                    $drug->type = $dv->type;
                    $drug->manufacturedby = $dv->manufacturedby;
                    $drug->remarks = $dv->remarks;
                    $drug->created_by_user_id = Auth::guard('admin')->user()->id;
                    $drug->save();

                    $drugTrail = new StudyDrugDetailsTrail;
                    $drugTrail->study_drug_details_id = $drug->id;
                    $drugTrail->study_id = $newStudy->id;
                    $drugTrail->dosage_form_id = $dv->dosage_form_id;
                    $drugTrail->drug_id = $dv->drug_id;
                    $drugTrail->dosage = $dv->dosage;
                    $drugTrail->drug_strength = $dv->drug_strength;
                    $drugTrail->uom_id = $dv->uom_id;
                    $drugTrail->type = $dv->type;
                    $drugTrail->manufacturedby = $dv->manufacturedby;
                    $drugTrail->remarks = $dv->remarks;
                    $drugTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                    $drugTrail->save();
                }
            }

            $getStudySubmission = StudySubmission::where('project_id', base64_decode($id))->get();
            if(!is_null($getStudySubmission)){
                foreach ($getStudySubmission as $sk => $sv) {
                    $submission = new StudySubmission;
                    $submission->project_id = $newStudy->id;
                    $submission->regulatory_submission = $sv->regulatory_submission;
                    $submission->created_by_user_id = Auth::guard('admin')->user()->id;
                    $submission->save();

                    $submissionTrail = new StudySubmissionTrail;
                    $submissionTrail->study_submission_id = $submission->id;
                    $submissionTrail->project_id = $newStudy->id;
                    $submissionTrail->regulatory_submission = $sv->regulatory_submission;
                    $submissionTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                    $submissionTrail->save();
                }
            }

            $getStudyScope = StudyScope::where('project_id', base64_decode($id))->get();
            if(!is_null($getStudyScope)){
                foreach ($getStudyScope as $sk => $sv) {
                    $scope = new StudyScope;
                    $scope->project_id = $newStudy->id;
                    $scope->scope = $sv->scope;
                    $scope->created_by_user_id = Auth::guard('admin')->user()->id;
                    $scope->save();

                    $scopeTrail = new StudyScopeTrail;
                    $scopeTrail->study_scope_id = $scope->id;
                    $scopeTrail->project_id = $newStudy->id;
                    $scopeTrail->scope = $sv->scope;
                    $scopeTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                    $scopeTrail->save();
                }
            }

        } else {
            return redirect(route('admin.studyList'))->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Copy Study',
                    'message' => 'Create group limit exceeds!',
                ],
            ]);
        }

        return redirect(route('admin.studyList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study',
                'message' => 'Study successfully copied!',
            ],
        ]);
    }



    public function addCopyStudy($id){
        
        $sponsors = SponsorMaster::where('is_active', 1)->where('is_delete', 0)->get();
        $dosageform = ParaMaster::where('para_code', 'DosageForm')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $scope = ParaMaster::where('para_code', 'Scope')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $studyDesign = ParaMaster::where('para_code', 'StudyDesign')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $studySubType = ParaMaster::where('para_code', 'StudySubType')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $subjectType = ParaMaster::where('para_code', 'SubjectType')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $blindingStatus = ParaMaster::where('para_code', 'BlindingStatus')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $crLocation = LocationMaster::where('location_type', 'CRSITE')->where('is_active', 1)->where('is_delete', 0)->get();
        $dosage = ParaMaster::where('para_code', 'Dosage')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $uom = ParaMaster::where('para_code', 'UOM')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $regulatorySubmission = ParaMaster::where('para_code', 'Submission')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $studyType = ParaMaster::where('para_code', 'StudyType')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $complexity = ParaMaster::where('para_code', 'Complexity')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $studyCondition = ParaMaster::where('para_code', 'StudyCondition')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $priority = ParaMaster::where('para_code', 'Priority')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();
        $brLocation = LocationMaster::where('location_type', 'BRSITE')->where('is_active', 1)->where('is_delete', 0)->get();
        $drug = DrugMaster::where('is_active', 1)->where('is_delete', 0)->where('is_active', 1)->where('is_delete', 0)->get();

        $principle = Role::where('name', 'Principle Investigator')
                         ->with([
                                'principleInvestigator'
                            ])
                         ->first();

        $bioanalytical = Role::where('name', 'Bioanalytical Investigator')
                             ->with([
                                    'bioanalyticalInvestigator'
                                ])
                             ->first();

        $projectManager = Role::whereIn('name', ['Project Manager - Head', 'Project Manager'])
                              ->with(['projectHead'])
                              ->get();

        $study = Study::where('id', base64_decode($id))
                      ->with([
                            'studyScope', 
                            'studyRegulatory',
                            'drugDetails'
                        ])
                      ->withCount(['drugDetails'])
                      ->first();

        $specialNotes = ParaMaster::where('para_code', 'SpecialNotes')->where('is_active', 1)->where('is_delete', 0)->with(['paraCode'])->first();

        $clinicalWordLocation = ClinicalWardMaster::where('location_id', $study->cr_location)->get();

        $scopeId = array();
        if (!is_null($study->studyScope)) {
            foreach ($study->studyScope as $sk => $sv) {
                $scopeId[] = $sv->scope;
            }
        }

        $regulatoryId = array();
        if (!is_null($study->studyRegulatory)) {
            foreach ($study->studyRegulatory as $rk => $rv) {
                $regulatoryId[] = $rv->regulatory_submission;
            }
        }

        return view('admin.study.study.add_copy_study', compact('sponsors', 'dosageform', 'scope', 'studyDesign', 'studySubType', 'subjectType', 'blindingStatus', 'crLocation', 'dosage', 'uom', 'regulatorySubmission', 'studyType', 'complexity', 'studyCondition', 'priority', 'brLocation', 'study', 'scopeId', 'regulatoryId', 'principle', 'bioanalytical', 'drug', 'clinicalWordLocation', 'projectManager', 'specialNotes'));
    }

    public function checkStudyNoExist(Request $request){
        
        $query = Study::where('is_delete',0)->where('study_no', $request->study_no);
        if(isset($request->id)) {
            $query->where('id','!=',$request->id);
        }
        $studyNo = $query->first();

        return $studyNo ? 'false' : 'true';
    }

    public function studyResult(Request $request){
        
        $status = Study::where('id',$request->id)->update(['study_result' => $request->result]);

        return $status ? 'true' : 'false';
    }

    public function studyStatus(Request $request){

        $status = Study::where('id',$request->id)->update(['study_status' => $request->status]);

        if ($request->status == 'HOLD') {
            $scheduleActivity = StudySchedule::where('study_id', $request->id)
                                             ->where('actual_start_date', Null)
                                             ->where('actual_end_date', Null)
                                             ->update(['is_active' => 0]);

            return $status ? 'true' : 'false';
        } else {
            $scheduleActivity = StudySchedule::where('study_id', $request->id)
                                             ->where('actual_start_date', Null)
                                             ->where('actual_end_date', Null)
                                             ->update(['is_active' => 1]);

            return $status ? 'true' : 'false';
        }

    }

    public function studyProjected(Request $request){
        
        $status = Study::where('id',$request->id)->update(['study_slotted' => $request->projected]);

        $updateStudyTrail = StudyTrail::where('study_id', $request->id)->orderBy('study_id', 'DESC')->update(['study_slotted' => $request->projected]);

        return $status ? 'true' : 'false';
    }

    public function studyTentativeClinicalDate(Request $request){

        $status = Study::where('id',$request->id)->update(['tentative_clinical_date' => $this->convertDateTime($request->date)]);

        $updateStudyTrail = StudyTrail::where('study_id', $request->id)->orderBy('study_id', 'DESC')->update(['tentative_clinical_date' => $this->convertDateTime($request->date)]);

        return $status ? 'true' : 'false';
    }

    // Manual update pre study projection status with update in study trail
    public function preStudyProjectionStatus(Request $request){
        
        $status = Study::where('id',$request->study_id)->update(['projection_status' => $request->projection_status]);

        $updateTrailStatus = StudyTrail::where('study_id',$request->study_id)->orderBy('study_id', 'DESC')->update(['projection_status' => $request->projection_status]);

        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportStudy(){
        return Excel::download(new StudyExport, 'All Studies  Study Management System.xlsx');
    }
}
