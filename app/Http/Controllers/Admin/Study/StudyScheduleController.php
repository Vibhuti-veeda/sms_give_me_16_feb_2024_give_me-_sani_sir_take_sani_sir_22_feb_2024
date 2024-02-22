<?php

namespace App\Http\Controllers\Admin\Study;

use App\Http\Controllers\Controller;
use App\Models\ParaMaster;
use App\Models\Admin;
use App\Models\StudyScheduleDelayRemarkTrail;
use App\Models\SlaActivityMaster;
use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use App\Models\StudySchedule;
use App\Models\Study;
use App\Models\ActivityMaster;
use Carbon\Carbon;
use App\Models\StudyScheduleTrail;
use Auth;
use App\Models\HolidayMaster;
use App\Models\RoleModuleAccess;
use App\Models\StudyScheduleDelayRemark;
use App\Models\ParaCode;
use DB;
use App\Jobs\SendEmailOnStudyScheduleCreated;
use DateTime;
use App\Exports\StudyScheduleExport;
use Maatwebsite\Excel\Facades\Excel;

class StudyScheduleController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    /**
        * Study schedule list
        *
        * @param mixed $studies
        *
        * @return to study schedule listing page
    **/
    public function studyScheduleList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $studyNo = Study::where('is_delete', 0)
                        ->whereHas('projectManager', function($q){
                            $q->where('is_active',1);
                        })
                        ->pluck('id');

        if (Auth::guard('admin')->user()->role_id == 3) {

            $studies = StudySchedule::where('is_delete', 0)
                                    ->select('id', 'study_id')
                                    ->whereHas(
                                        'studyNo', function($q) { 
                                            $q->select('id','study_no', 'sponsor')
                                              ->where('project_manager',Auth::guard('admin')->user()->id);
                                        }
                                    )
                                    ->with([
                                        'studyNo' => function ($q) {
                                            $q->select('id','study_no', 'sponsor')
                                              ->with([
                                                'sponsorName' => function($q){
                                                    $q->select('id','sponsor_name');
                                                }
                                            ]);
                                        },
                                        'drugDetails' => function($q) {
                                            $q->with([
                                                'drugName' => function($q){
                                                    $q->select('id','drug_name');
                                                },
                                                'drugDosageName' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugUom' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugType'
                                            ]);
                                        }
                                    ])
                                    ->whereIn('study_id',$studyNo)
                                    ->groupBy('study_id')
                                    ->orderBy('id', 'DESC')
                                    ->skip($offset)
                                    ->limit($perPage)
                                    ->get();

            $studiesCount = StudySchedule::where('is_delete', 0)
                                    ->whereIn('study_id',$studyNo)
                                    ->whereHas(
                                        'studyNo', function($q) { 
                                            $q->select('id','study_no', 'sponsor')
                                              ->where('project_manager',Auth::guard('admin')->user()->id);
                                        }
                                    )
                                    ->groupBy('study_id')
                                    ->get();

        } else {
            $studies = StudySchedule::where('is_delete', 0)
                                    ->select('id', 'study_id')
                                    ->with([
                                        'studyNo' => function ($q) {
                                            $q->select('id','study_no', 'sponsor')
                                              ->with([
                                                'sponsorName' => function($q){
                                                    $q->select('id','sponsor_name');
                                                }
                                            ]);
                                        },
                                        'drugDetails' => function($q) {
                                            $q->with([
                                                'drugName' => function($q){
                                                    $q->select('id','drug_name');
                                                },
                                                'drugDosageName' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugUom' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugType'
                                            ]);
                                        }
                                    ])
                                    ->whereIn('study_id',$studyNo)
                                    ->groupBy('study_id')
                                    ->orderBy('id', 'DESC')
                                    ->skip($offset)
                                    ->limit($perPage)
                                    ->get();

            $studiesCount = StudySchedule::where('is_delete', 0)
                                        ->whereIn('study_id',$studyNo)
                                        ->groupBy('study_id')
                                        ->get();
        }

        $recordCount = $studiesCount->count();
        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','study-schedule')
                                      ->first();
        }

        return view('admin.study.study_schedule.study_schedule_list', compact('studies', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    /**
        * Add study schedule
        *
        * @param mixed $schedule, $studyList, $activities
        *
        * @return to add study schedule page
    **/
    public function addStudySchedule(){

        $schedule = StudySchedule::where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->groupBy('study_id')
                                 ->pluck('study_id');

        if (Auth::guard('admin')->user()->role_id == 3) {
            $studyList = Study::where('is_active', 1)
                                ->where('is_delete', 0)
                                ->where('project_manager',Auth::guard('admin')->user()->id)
                                ->whereHas('projectManager', function($q){
                                    $q->where('is_active',1);
                                })
                                ->whereNotIn('id', $schedule)
                                ->select('id', 'study_no')
                                ->get();
        } else {
            $studyList = Study::where('is_active', 1)
                                ->where('is_delete', 0)
                                ->whereNotIn('id', $schedule)
                                ->whereHas('projectManager', function($q){
                                    $q->where('is_active',1);
                                })
                                ->select('id', 'study_no')
                                ->get();
        }
        
        $activities = ActivityMaster::select('id', 'activity_type', 'activity_name', 'is_milestone')
                                    ->where('is_delete', 0)
                                    ->where('is_active', 1)
                                    ->with([
                                        'responsible' =>function($q){
                                            $q->select('id', 'name');
                                        },
                                        'nextActivity' =>function($q){
                                            $q->select('id', 'activity_name');
                                        },
                                        'previousActivity' =>function($q){
                                            $q->select('id', 'activity_name');
                                        },
                                        'parentActivity' =>function($q){
                                            $q->select('id', 'activity_name');
                                        },
                                        'crCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'brCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'rwCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'pbCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'psCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                    ])
                                    ->get();

        return view('admin.study.study_schedule.add_study_schedule', compact('studyList', 'activities'));
    }

    /**
        * Save study schedule
        *
        * @param $study_id, $activity_id, $activity_name, $require_days, $minimum_days_allowed, 
        *        $maximum_days_allowed, $next_activity_id, $responsibility_id, $previous_activity_id, 
        *        $is_milestone, $milestone_percentage, $milestone_amount, $reference_parent_activity_id, 
        *        $is_parellel, $is_dependent, $is_group_specific, $is_period_specific, $group_no, 
        *        $period_no fields save in StudySchedule database
        *
        * @return to study schedule listing page with data store in StudySchedule database
    **/
    public function saveStudySchedule(Request $request){

        $activity = '';
        $activitiySlot = '';
        $date = Carbon::now();
        $currentDate = date('Y-m-d', strtotime($date));
     
        if(!is_null($request->activity)){
            foreach ($request->activity as $aak => $aav) {
                if (!is_null($aav)) {
                    foreach ($aav as $ak => $av) {

                        $study = Study::where('id', $request->study)->first();

                        $activitiySlot = SlaActivityMaster::where('is_cdisc', $study->cdisc_require)
                                                           ->where('no_from_subject', '<', $study->no_of_subject)
                                                           ->where('no_to_subject', '>', $study->no_of_subject)
                                                           ->where('study_design', $study->study_design)
                                                           ->where('activity_id', $av)
                                                           ->where('is_active', 1)
                                                           ->where('is_delete', 0)
                                                           ->with(['activityName'])
                                                           ->first();

                        if(is_null($activitiySlot)){
                            $activity = ActivityMaster::where('id', $av)->first();
                        }

                        if (($activity != '' && $activity->is_group_specific == 1) || ($activitiySlot != '' && $activitiySlot->activityName->is_group_specific == 1)) {
                            $multipleGroup = $study->no_of_groups * $study->no_of_periods;
                        }
                        
                        $x = 1;

                        /*if($currentDate >= $study->tentative_study_start_date){
                            $studyStatusUpdate = Study::where('id', $request->study)->update(['study_status' => 'ONGOING']);
                        } else {
                            $studyStatusUpdate = Study::where('id', $request->study)->update(['study_status' => 'UPCOMING']);
                        }*/

                        if (is_null($activitiySlot)) {
                            
                            if($activity->is_group_specific == 1 && $activity->is_period_specific == 1){
                                for ($i=1; $i <= $study->no_of_groups; $i++) { 
                                    for ($j=1; $j <= $study->no_of_periods; $j++) { 

                                        $studyschedule = new StudySchedule;
                                        $studyschedule->study_id = $request->study;
                                        $studyschedule->activity_id = $av;
                                        $studyschedule->activity_name = $activity->activity_name;
                                        $studyschedule->require_days = $activity->days_required;
                                        $studyschedule->minimum_days_allowed = $activity->minimum_days_allowed;
                                        $studyschedule->maximum_days_allowed = $activity->maximum_days_allowed;
                                        $studyschedule->next_activity_id = $activity->next_activity_id;
                                        $studyschedule->responsibility_id = $activity->responsibility;
                                        $studyschedule->previous_activity_id = $activity->previous_activity;
                                        $studyschedule->is_milestone = 0;
                                        $studyschedule->milestone_percentage = $activity->milestone_percentage;
                                        $studyschedule->milestone_amount = $activity->milestone_amount;
                                        $studyschedule->reference_parent_activity_id = $activity->parent_activity;
                                        $studyschedule->is_parellel = $activity->is_parellel;
                                        $studyschedule->is_dependent = $activity->is_dependent;
                                        $studyschedule->is_group_specific = $activity->is_group_specific;
                                        $studyschedule->is_period_specific = $activity->is_period_specific;
                                        $studyschedule->activity_type = $activity->activity_type;
                                        $studyschedule->activity_sequence_no = $activity->sequence_no;
                                        $studyschedule->group_no = $i;
                                        $studyschedule->period_no = $j;
                                        $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                                        $studyschedule->activity_version = 0;
                                        $studyschedule->save();

                                        $studyScheduleTrail = new StudyScheduleTrail;
                                        $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                                        $studyScheduleTrail->study_id = $request->study;
                                        $studyScheduleTrail->activity_id = $av;
                                        $studyScheduleTrail->activity_name = $activity->activity_name;
                                        $studyScheduleTrail->require_days = $activity->days_required;
                                        $studyScheduleTrail->minimum_days_allowed = $activity->minimum_days_allowed;
                                        $studyScheduleTrail->maximum_days_allowed = $activity->maximum_days_allowed;
                                        $studyScheduleTrail->next_activity_id = $activity->next_activity_id;
                                        $studyScheduleTrail->responsibility_id = $activity->responsibility;
                                        $studyScheduleTrail->previous_activity_id = $activity->previous_activity;
                                        $studyScheduleTrail->is_milestone = 0;
                                        $studyScheduleTrail->milestone_percentage = $activity->milestone_percentage;
                                        $studyScheduleTrail->milestone_amount = $activity->milestone_amount;
                                        $studyScheduleTrail->reference_parent_activity_id = $activity->parent_activity;
                                        $studyScheduleTrail->is_parellel = $activity->is_parellel;
                                        $studyScheduleTrail->is_dependent = $activity->is_dependent;
                                        $studyScheduleTrail->is_group_specific = $activity->is_group_specific;
                                        $studyScheduleTrail->is_period_specific = $activity->is_period_specific;
                                        $studyScheduleTrail->activity_type = $activity->activity_type;
                                        $studyScheduleTrail->activity_sequence_no = $activity->sequence_no;
                                        $studyScheduleTrail->group_no = $i;
                                        $studyScheduleTrail->period_no = $j;
                                        $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                        $studyScheduleTrail->activity_version = 0;
                                        $studyScheduleTrail->save();

                                    }
                                }
                            } else {
                                $studyschedule = new StudySchedule;
                                $studyschedule->study_id = $request->study;
                                $studyschedule->activity_id = $av;
                                $studyschedule->activity_name = $activity->activity_name;
                                $studyschedule->require_days = $activity->days_required;
                                $studyschedule->minimum_days_allowed = $activity->minimum_days_allowed;
                                $studyschedule->maximum_days_allowed = $activity->maximum_days_allowed;
                                $studyschedule->next_activity_id = $activity->next_activity_id;
                                $studyschedule->responsibility_id = $activity->responsibility;
                                $studyschedule->previous_activity_id = $activity->previous_activity;
                                $studyschedule->is_milestone = 0;
                                $studyschedule->milestone_percentage = $activity->milestone_percentage;
                                $studyschedule->milestone_amount = $activity->milestone_amount;
                                $studyschedule->reference_parent_activity_id = $activity->parent_activity;
                                $studyschedule->is_parellel = $activity->is_parellel;
                                $studyschedule->is_dependent = $activity->is_dependent;
                                $studyschedule->is_group_specific = $activity->is_group_specific;
                                $studyschedule->is_period_specific = $activity->is_period_specific;
                                $studyschedule->activity_type = $activity->activity_type;
                                $studyschedule->activity_sequence_no = $activity->sequence_no;
                                $studyschedule->group_no = 1;
                                $studyschedule->period_no = 1;
                                $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                                $studyschedule->activity_version = 0;
                                $studyschedule->save();

                                $studyScheduleTrail = new StudyScheduleTrail;
                                $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                                $studyScheduleTrail->study_id = $request->study;
                                $studyScheduleTrail->activity_id = $av;
                                $studyScheduleTrail->activity_name = $activity->activity_name;
                                $studyScheduleTrail->require_days = $activity->days_required;
                                $studyScheduleTrail->minimum_days_allowed = $activity->minimum_days_allowed;
                                $studyScheduleTrail->maximum_days_allowed = $activity->maximum_days_allowed;
                                if ($activity->next_activity_id != '') {
                                    $studyScheduleTrail->next_activity_id = $activity->next_activity_id;
                                } else {
                                    $studyScheduleTrail->next_activity_id = Null;
                                }
                                $studyScheduleTrail->responsibility_id = $activity->responsibility;
                                $studyScheduleTrail->previous_activity_id = $activity->previous_activity;
                                $studyScheduleTrail->is_milestone = 0;
                                $studyScheduleTrail->milestone_percentage = $activity->milestone_percentage;
                                $studyScheduleTrail->milestone_amount = $activity->milestone_amount;
                                $studyScheduleTrail->reference_parent_activity_id = $activity->parent_activity;
                                $studyScheduleTrail->is_parellel = $activity->is_parellel;
                                $studyScheduleTrail->is_dependent = $activity->is_dependent;
                                $studyScheduleTrail->is_group_specific = $activity->is_group_specific;
                                $studyScheduleTrail->is_period_specific = $activity->is_period_specific;
                                $studyScheduleTrail->activity_type = $activity->activity_type;
                                $studyScheduleTrail->activity_sequence_no = $activity->sequence_no;
                                $studyScheduleTrail->group_no = 1;
                                $studyScheduleTrail->period_no = 1;
                                $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                $studyScheduleTrail->activity_version = 0;
                                $studyScheduleTrail->save();
                            }
                        } else {
                            if($activitiySlot->activityName->is_group_specific == 1 && $activitiySlot->activityName->is_period_specific == 1){
                                for ($i=1; $i <= $study->no_of_groups; $i++) { 
                                    for ($j=1; $j <= $study->no_of_periods; $j++) { 

                                        $studyschedule = new StudySchedule;
                                        $studyschedule->study_id = $request->study;
                                        $studyschedule->activity_id = $av;
                                        $studyschedule->activity_name = $activitiySlot->activityName->activity_name;
                                        $studyschedule->require_days = $activitiySlot->no_of_days;
                                        $studyschedule->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                                        $studyschedule->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                                        $studyschedule->next_activity_id = $activitiySlot->activityName->next_activity_id;
                                        $studyschedule->responsibility_id = $activitiySlot->activityName->responsibility;
                                        $studyschedule->previous_activity_id = $activitiySlot->activityName->previous_activity;
                                        $studyschedule->is_milestone = 0;
                                        $studyschedule->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                                        $studyschedule->milestone_amount = $activitiySlot->activityName->milestone_amount;
                                        $studyschedule->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                                        $studyschedule->is_parellel = $activitiySlot->activityName->is_parellel;
                                        $studyschedule->is_dependent = $activitiySlot->activityName->is_dependent;
                                        $studyschedule->is_group_specific = $activitiySlot->activityName->is_group_specific;
                                        $studyschedule->is_period_specific = $activitiySlot->activityName->is_period_specific;
                                        $studyschedule->activity_type = $activitiySlot->activityName->activity_type;
                                        $studyschedule->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                                        $studyschedule->group_no = $i;
                                        $studyschedule->period_no = $j;
                                        $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                                        $studyschedule->activity_version = 0;
                                        $studyschedule->save();

                                        $studyScheduleTrail = new StudyScheduleTrail;
                                        $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                                        $studyScheduleTrail->study_id = $request->study;
                                        $studyScheduleTrail->activity_id = $av;
                                        $studyScheduleTrail->activity_name = $activitiySlot->activityName->activity_name;
                                        $studyScheduleTrail->require_days = $activitiySlot->no_of_days;
                                        $studyScheduleTrail->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                                        $studyScheduleTrail->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                                        $studyScheduleTrail->next_activity_id = $activitiySlot->activityName->next_activity_id;
                                        $studyScheduleTrail->responsibility_id = $activitiySlot->activityName->responsibility;
                                        $studyScheduleTrail->previous_activity_id = $activitiySlot->activityName->previous_activity;
                                        $studyScheduleTrail->is_milestone = 0;
                                        $studyScheduleTrail->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                                        $studyScheduleTrail->milestone_amount = $activitiySlot->activityName->milestone_amount;
                                        $studyScheduleTrail->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                                        $studyScheduleTrail->is_parellel = $activitiySlot->activityName->is_parellel;
                                        $studyScheduleTrail->is_dependent = $activitiySlot->activityName->is_dependent;
                                        $studyScheduleTrail->is_group_specific = $activitiySlot->activityName->is_group_specific;
                                        $studyScheduleTrail->is_period_specific = $activitiySlot->activityName->is_period_specific;
                                        $studyScheduleTrail->activity_type = $activitiySlot->activityName->activity_type;
                                        $studyScheduleTrail->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                                        $studyScheduleTrail->group_no = $i;
                                        $studyScheduleTrail->period_no = $j;
                                        $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                        $studyScheduleTrail->activity_version = 0;
                                        $studyScheduleTrail->save();

                                    }
                                }
                            } else {
                                $studyschedule = new StudySchedule;
                                $studyschedule->study_id = $request->study;
                                $studyschedule->activity_id = $av;
                                $studyschedule->activity_name = $activitiySlot->activityName->activity_name;
                                $studyschedule->require_days = $activitiySlot->no_of_days;
                                $studyschedule->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                                $studyschedule->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                                $studyschedule->next_activity_id = $activitiySlot->activityName->next_activity_id;
                                $studyschedule->responsibility_id = $activitiySlot->activityName->responsibility;
                                $studyschedule->previous_activity_id = $activitiySlot->activityName->previous_activity;
                                $studyschedule->is_milestone = 0;
                                $studyschedule->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                                $studyschedule->milestone_amount = $activitiySlot->activityName->milestone_amount;
                                $studyschedule->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                                $studyschedule->is_parellel = $activitiySlot->activityName->is_parellel;
                                $studyschedule->is_dependent = $activitiySlot->activityName->is_dependent;
                                $studyschedule->is_group_specific = $activitiySlot->activityName->is_group_specific;
                                $studyschedule->is_period_specific = $activitiySlot->activityName->is_period_specific;
                                $studyschedule->activity_type = $activitiySlot->activityName->activity_type;
                                $studyschedule->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                                $studyschedule->group_no = 1;
                                $studyschedule->period_no = 1;
                                $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                                $studyschedule->activity_version = 0;
                                $studyschedule->save();

                                $studyScheduleTrail = new StudyScheduleTrail;
                                $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                                $studyScheduleTrail->study_id = $request->study;
                                $studyScheduleTrail->activity_id = $av;
                                $studyScheduleTrail->activity_name = $activitiySlot->activityName->activity_name;
                                $studyScheduleTrail->require_days = $activitiySlot->no_of_days;
                                $studyScheduleTrail->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                                $studyScheduleTrail->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                                $studyScheduleTrail->next_activity_id = $activitiySlot->activityName->next_activity_id;
                                $studyScheduleTrail->responsibility_id = $activitiySlot->activityName->responsibility;
                                $studyScheduleTrail->previous_activity_id = $activitiySlot->activityName->previous_activity;
                                $studyScheduleTrail->is_milestone = 0;
                                $studyScheduleTrail->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                                $studyScheduleTrail->milestone_amount = $activitiySlot->activityName->milestone_amount;
                                $studyScheduleTrail->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                                $studyScheduleTrail->is_parellel = $activitiySlot->activityName->is_parellel;
                                $studyScheduleTrail->is_dependent = $activitiySlot->activityName->is_dependent;
                                $studyScheduleTrail->is_group_specific = $activitiySlot->activityName->is_group_specific;
                                $studyScheduleTrail->is_period_specific = $activitiySlot->activityName->is_period_specific;
                                $studyScheduleTrail->activity_type = $activitiySlot->activityName->activity_type;
                                $studyScheduleTrail->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                                $studyScheduleTrail->group_no = 1;
                                $studyScheduleTrail->period_no = 1;
                                $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                $studyScheduleTrail->activity_version = 0;
                                $studyScheduleTrail->save();
                            }
                        }

                    }
                }

            }
        }

        if (!is_null($studyschedule)) {
            $bdUser = Admin::where('role_id', 14)->get();
            if (!is_null($bdUser)) {
                foreach ($bdUser as $buk => $buv) {
                    $newStudyScheduledCreated = StudySchedule::where('id', $studyschedule->id)
                                                             ->with([
                                                                    'studyNo' => function($q){
                                                                        $q->with([
                                                                            'projectManager'
                                                                        ]);
                                                                    }
                                                                ])
                                                             ->first();
                    
                    $this->dispatch((new SendEmailOnStudyScheduleCreated($buv->email_id,$buv->name,$newStudyScheduledCreated->studyNo->study_no,$newStudyScheduledCreated->studyNo->projectManager->name))->delay(10));
                }
            }

        }

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addStudySchedule' : 'admin.studyScheduleList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule',
                'message' => 'Study schedule successfully added!',
            ],
        ]);

    }

    public function editStudySchedule($id){

        $study = Study::where('id', base64_decode($id))->first();

        $getScheduleActivities = StudySchedule::where('study_id', base64_decode($id))->where('is_active', 1)->where('is_delete', 0)->pluck('activity_id')->toArray();
        $getActivityType = StudySchedule::where('study_id', base64_decode($id))->where('is_active', 1)->where('is_delete', 0)->groupBy('activity_type')->pluck('activity_type')->toArray();
        $activityTypes = ParaMaster::select('id', 'para_description')
                                 ->where('para_code', 'ActivityType')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with([
                                        'paraCode' => function($q){
                                            $q->select('id', 'para_value', 'para_master_id');
                                        }
                                    ])
                                 ->first();

        $activities = ActivityMaster::select('id', 'activity_type', 'activity_name', 'is_milestone')
                                    ->where('is_delete', 0)
                                    ->where('is_active', 1)
                                    ->with([
                                        'responsible' =>function($q){
                                            $q->select('id', 'name');
                                        },
                                        'nextActivity' =>function($q){
                                            $q->select('id', 'activity_name');
                                        },
                                        'previousActivity' =>function($q){
                                            $q->select('id', 'activity_name');
                                        },
                                        'parentActivity' =>function($q){
                                            $q->select('id', 'activity_name');
                                        },
                                        'crCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'brCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'rwCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'pbCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                        'psCode' => function($q) {
                                            $q->select('id', 'para_value');
                                        },
                                    ])
                                    ->get();
        
        return view('admin.study.study_schedule.edit_study_schedule', compact('study', 'activities', 'getScheduleActivities', 'getActivityType', 'activityTypes'));
    }

    public function updateStudySchedule(Request $request){

        $acid = array();
        if (!is_null($request->activity)) {
            foreach ($request->activity as $ak => $av) {
                $acid[] = $av;

            }
        }

        $deleteScheduleActivities = StudySchedule::where('study_id', $request->study)->whereNotIn('activity_id', $acid)->delete();

        $activity = '';
        $activitiySlot = '';

        $activityId = array();
        if (!is_null($request->activity)) {
            foreach ($request->activity as $ak => $av) {
                $studyschedule = StudySchedule::where('study_id', $request->study)->where('is_active', 1)->where('is_delete', 0)->pluck('activity_id')->toArray();
                if(!in_array($av, $studyschedule)) {

                    $study = Study::where('id', $request->study)->first();

                    $activitiySlot = SlaActivityMaster::where('is_cdisc', $study->cdisc_require)
                                                       ->where('no_from_subject', '<', $study->no_of_subject)
                                                       ->where('no_to_subject', '>', $study->no_of_subject)
                                                       ->where('study_design', $study->study_design)
                                                       ->where('activity_id', $av)
                                                       ->where('is_active', 1)
                                                       ->where('is_delete', 0)
                                                       ->with(['activityName'])
                                                       ->first();

                    if(is_null($activitiySlot)){
                        $activity = ActivityMaster::where('id', $av)->first();
                    }

                    if (($activity != '' && $activity->is_group_specific == 1) || ($activitiySlot != '' && $activitiySlot->activityName->is_group_specific == 1)) {
                        $multipleGroup = $study->no_of_groups * $study->no_of_periods;
                    }
                    
                    $x = 1;

                    if (is_null($activitiySlot)) {
                        
                        if($activity->is_group_specific == 1 && $activity->is_period_specific == 1){
                            for ($i=1; $i <= $study->no_of_groups; $i++) { 
                                for ($j=1; $j <= $study->no_of_periods; $j++) { 

                                    $studyschedule = new StudySchedule;
                                    $studyschedule->study_id = $request->study;
                                    $studyschedule->activity_id = $av;
                                    $studyschedule->activity_name = $activity->activity_name;
                                    $studyschedule->require_days = $activity->days_required;
                                    $studyschedule->minimum_days_allowed = $activity->minimum_days_allowed;
                                    $studyschedule->maximum_days_allowed = $activity->maximum_days_allowed;
                                    $studyschedule->next_activity_id = $activity->next_activity_id;
                                    $studyschedule->responsibility_id = $activity->responsibility;
                                    $studyschedule->previous_activity_id = $activity->previous_activity;
                                    $studyschedule->is_milestone = 0;
                                    $studyschedule->milestone_percentage = $activity->milestone_percentage;
                                    $studyschedule->milestone_amount = $activity->milestone_amount;
                                    $studyschedule->reference_parent_activity_id = $activity->parent_activity;
                                    $studyschedule->is_parellel = $activity->is_parellel;
                                    $studyschedule->is_dependent = $activity->is_dependent;
                                    $studyschedule->is_group_specific = $activity->is_group_specific;
                                    $studyschedule->is_period_specific = $activity->is_period_specific;
                                    $studyschedule->activity_type = $activity->activity_type;
                                    $studyschedule->activity_sequence_no = $activity->sequence_no;
                                    $studyschedule->group_no = $i;
                                    $studyschedule->period_no = $j;
                                    $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                                    $studyschedule->activity_version = 0;
                                    $studyschedule->save();

                                    $studyScheduleTrail = new StudyScheduleTrail;
                                    $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                                    $studyScheduleTrail->study_id = $request->study;
                                    $studyScheduleTrail->activity_id = $av;
                                    $studyScheduleTrail->activity_name = $activity->activity_name;
                                    $studyScheduleTrail->require_days = $activity->days_required;
                                    $studyScheduleTrail->minimum_days_allowed = $activity->minimum_days_allowed;
                                    $studyScheduleTrail->maximum_days_allowed = $activity->maximum_days_allowed;
                                    $studyScheduleTrail->next_activity_id = $activity->next_activity_id;
                                    $studyScheduleTrail->responsibility_id = $activity->responsibility;
                                    $studyScheduleTrail->previous_activity_id = $activity->previous_activity;
                                    $studyScheduleTrail->is_milestone = 0;
                                    $studyScheduleTrail->milestone_percentage = $activity->milestone_percentage;
                                    $studyScheduleTrail->milestone_amount = $activity->milestone_amount;
                                    $studyScheduleTrail->reference_parent_activity_id = $activity->parent_activity;
                                    $studyScheduleTrail->is_parellel = $activity->is_parellel;
                                    $studyScheduleTrail->is_dependent = $activity->is_dependent;
                                    $studyScheduleTrail->is_group_specific = $activity->is_group_specific;
                                    $studyScheduleTrail->is_period_specific = $activity->is_period_specific;
                                    $studyScheduleTrail->activity_type = $activity->activity_type;
                                    $studyScheduleTrail->activity_sequence_no = $activity->sequence_no;
                                    $studyScheduleTrail->group_no = $i;
                                    $studyScheduleTrail->period_no = $j;
                                    $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                    $studyScheduleTrail->activity_version = 0;
                                    $studyScheduleTrail->save();

                                }
                            }
                        } else {
                            $studyschedule = new StudySchedule;
                            $studyschedule->study_id = $request->study;
                            $studyschedule->activity_id = $av;
                            $studyschedule->activity_name = $activity->activity_name;
                            $studyschedule->require_days = $activity->days_required;
                            $studyschedule->minimum_days_allowed = $activity->minimum_days_allowed;
                            $studyschedule->maximum_days_allowed = $activity->maximum_days_allowed;
                            $studyschedule->next_activity_id = $activity->next_activity_id;
                            $studyschedule->responsibility_id = $activity->responsibility;
                            $studyschedule->previous_activity_id = $activity->previous_activity;
                            $studyschedule->is_milestone = 0;
                            $studyschedule->milestone_percentage = $activity->milestone_percentage;
                            $studyschedule->milestone_amount = $activity->milestone_amount;
                            $studyschedule->reference_parent_activity_id = $activity->parent_activity;
                            $studyschedule->is_parellel = $activity->is_parellel;
                            $studyschedule->is_dependent = $activity->is_dependent;
                            $studyschedule->is_group_specific = $activity->is_group_specific;
                            $studyschedule->is_period_specific = $activity->is_period_specific;
                            $studyschedule->activity_type = $activity->activity_type;
                            $studyschedule->activity_sequence_no = $activity->sequence_no;
                            $studyschedule->group_no = 1;
                            $studyschedule->period_no = 1;
                            $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                            $studyschedule->activity_version = 0;
                            $studyschedule->save();

                            $studyScheduleTrail = new StudyScheduleTrail;
                            $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                            $studyScheduleTrail->study_id = $request->study;
                            $studyScheduleTrail->activity_id = $av;
                            $studyScheduleTrail->activity_name = $activity->activity_name;
                            $studyScheduleTrail->require_days = $activity->days_required;
                            $studyScheduleTrail->minimum_days_allowed = $activity->minimum_days_allowed;
                            $studyScheduleTrail->maximum_days_allowed = $activity->maximum_days_allowed;
                            if ($activity->next_activity_id != '') {
                                $studyScheduleTrail->next_activity_id = $activity->next_activity_id;
                            } else {
                                $studyScheduleTrail->next_activity_id = Null;
                            }
                            $studyScheduleTrail->responsibility_id = $activity->responsibility;
                            $studyScheduleTrail->previous_activity_id = $activity->previous_activity;
                            $studyScheduleTrail->is_milestone = 0;
                            $studyScheduleTrail->milestone_percentage = $activity->milestone_percentage;
                            $studyScheduleTrail->milestone_amount = $activity->milestone_amount;
                            $studyScheduleTrail->reference_parent_activity_id = $activity->parent_activity;
                            $studyScheduleTrail->is_parellel = $activity->is_parellel;
                            $studyScheduleTrail->is_dependent = $activity->is_dependent;
                            $studyScheduleTrail->is_group_specific = $activity->is_group_specific;
                            $studyScheduleTrail->is_period_specific = $activity->is_period_specific;
                            $studyScheduleTrail->activity_type = $activity->activity_type;
                            $studyScheduleTrail->activity_sequence_no = $activity->sequence_no;
                            $studyScheduleTrail->group_no = 1;
                            $studyScheduleTrail->period_no = 1;
                            $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                            $studyScheduleTrail->activity_version = 0;
                            $studyScheduleTrail->save();
                        }
                    } else {
                        if($activitiySlot->activityName->is_group_specific == 1 && $activitiySlot->activityName->is_period_specific == 1){
                            for ($i=1; $i <= $study->no_of_groups; $i++) { 
                                for ($j=1; $j <= $study->no_of_periods; $j++) { 

                                    $studyschedule = new StudySchedule;
                                    $studyschedule->study_id = $request->study;
                                    $studyschedule->activity_id = $av;
                                    $studyschedule->activity_name = $activitiySlot->activityName->activity_name;
                                    $studyschedule->require_days = $activitiySlot->no_of_days;
                                    $studyschedule->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                                    $studyschedule->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                                    $studyschedule->next_activity_id = $activitiySlot->activityName->next_activity_id;
                                    $studyschedule->responsibility_id = $activitiySlot->activityName->responsibility;
                                    $studyschedule->previous_activity_id = $activitiySlot->activityName->previous_activity;
                                    $studyschedule->is_milestone = 0;
                                    $studyschedule->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                                    $studyschedule->milestone_amount = $activitiySlot->activityName->milestone_amount;
                                    $studyschedule->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                                    $studyschedule->is_parellel = $activitiySlot->activityName->is_parellel;
                                    $studyschedule->is_dependent = $activitiySlot->activityName->is_dependent;
                                    $studyschedule->is_group_specific = $activitiySlot->activityName->is_group_specific;
                                    $studyschedule->is_period_specific = $activitiySlot->activityName->is_period_specific;
                                    $studyschedule->activity_type = $activitiySlot->activityName->activity_type;
                                    $studyschedule->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                                    $studyschedule->group_no = $i;
                                    $studyschedule->period_no = $j;
                                    $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                                    $studyschedule->activity_version = 0;
                                    $studyschedule->save();

                                    $studyScheduleTrail = new StudyScheduleTrail;
                                    $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                                    $studyScheduleTrail->study_id = $request->study;
                                    $studyScheduleTrail->activity_id = $av;
                                    $studyScheduleTrail->activity_name = $activitiySlot->activityName->activity_name;
                                    $studyScheduleTrail->require_days = $activitiySlot->no_of_days;
                                    $studyScheduleTrail->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                                    $studyScheduleTrail->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                                    $studyScheduleTrail->next_activity_id = $activitiySlot->activityName->next_activity_id;
                                    $studyScheduleTrail->responsibility_id = $activitiySlot->activityName->responsibility;
                                    $studyScheduleTrail->previous_activity_id = $activitiySlot->activityName->previous_activity;
                                    $studyScheduleTrail->is_milestone = 0;
                                    $studyScheduleTrail->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                                    $studyScheduleTrail->milestone_amount = $activitiySlot->activityName->milestone_amount;
                                    $studyScheduleTrail->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                                    $studyScheduleTrail->is_parellel = $activitiySlot->activityName->is_parellel;
                                    $studyScheduleTrail->is_dependent = $activitiySlot->activityName->is_dependent;
                                    $studyScheduleTrail->is_group_specific = $activitiySlot->activityName->is_group_specific;
                                    $studyScheduleTrail->is_period_specific = $activitiySlot->activityName->is_period_specific;
                                    $studyScheduleTrail->activity_type = $activitiySlot->activityName->activity_type;
                                    $studyScheduleTrail->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                                    $studyScheduleTrail->group_no = $i;
                                    $studyScheduleTrail->period_no = $j;
                                    $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                    $studyScheduleTrail->activity_version = 0;
                                    $studyScheduleTrail->save();

                                }
                            }
                        } else {
                            $studyschedule = new StudySchedule;
                            $studyschedule->study_id = $request->study;
                            $studyschedule->activity_id = $av;
                            $studyschedule->activity_name = $activitiySlot->activityName->activity_name;
                            $studyschedule->require_days = $activitiySlot->no_of_days;
                            $studyschedule->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                            $studyschedule->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                            $studyschedule->next_activity_id = $activitiySlot->activityName->next_activity_id;
                            $studyschedule->responsibility_id = $activitiySlot->activityName->responsibility;
                            $studyschedule->previous_activity_id = $activitiySlot->activityName->previous_activity;
                            $studyschedule->is_milestone = 0;
                            $studyschedule->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                            $studyschedule->milestone_amount = $activitiySlot->activityName->milestone_amount;
                            $studyschedule->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                            $studyschedule->is_parellel = $activitiySlot->activityName->is_parellel;
                            $studyschedule->is_dependent = $activitiySlot->activityName->is_dependent;
                            $studyschedule->is_group_specific = $activitiySlot->activityName->is_group_specific;
                            $studyschedule->is_period_specific = $activitiySlot->activityName->is_period_specific;
                            $studyschedule->activity_type = $activitiySlot->activityName->activity_type;
                            $studyschedule->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                            $studyschedule->group_no = 1;
                            $studyschedule->period_no = 1;
                            $studyschedule->created_by_user_id = Auth::guard('admin')->user()->id;
                            $studyschedule->activity_version = 0;
                            $studyschedule->save();

                            $studyScheduleTrail = new StudyScheduleTrail;
                            $studyScheduleTrail->study_schedule_id = $studyschedule->id;
                            $studyScheduleTrail->study_id = $request->study;
                            $studyScheduleTrail->activity_id = $av;
                            $studyScheduleTrail->activity_name = $activitiySlot->activityName->activity_name;
                            $studyScheduleTrail->require_days = $activitiySlot->no_of_days;
                            $studyScheduleTrail->minimum_days_allowed = $activitiySlot->activityName->minimum_days_allowed;
                            $studyScheduleTrail->maximum_days_allowed = $activitiySlot->activityName->maximum_days_allowed;
                            $studyScheduleTrail->next_activity_id = $activitiySlot->activityName->next_activity_id;
                            $studyScheduleTrail->responsibility_id = $activitiySlot->activityName->responsibility;
                            $studyScheduleTrail->previous_activity_id = $activitiySlot->activityName->previous_activity;
                            $studyScheduleTrail->is_milestone = 0;
                            $studyScheduleTrail->milestone_percentage = $activitiySlot->activityName->milestone_percentage;
                            $studyScheduleTrail->milestone_amount = $activitiySlot->activityName->milestone_amount;
                            $studyScheduleTrail->reference_parent_activity_id = $activitiySlot->activityName->parent_activity;
                            $studyScheduleTrail->is_parellel = $activitiySlot->activityName->is_parellel;
                            $studyScheduleTrail->is_dependent = $activitiySlot->activityName->is_dependent;
                            $studyScheduleTrail->is_group_specific = $activitiySlot->activityName->is_group_specific;
                            $studyScheduleTrail->is_period_specific = $activitiySlot->activityName->is_period_specific;
                            $studyScheduleTrail->activity_type = $activitiySlot->activityName->activity_type;
                            $studyScheduleTrail->activity_sequence_no = $activitiySlot->activityName->sequence_no;
                            $studyScheduleTrail->group_no = 1;
                            $studyScheduleTrail->period_no = 1;
                            $studyScheduleTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                            $studyScheduleTrail->activity_version = 0;
                            $studyScheduleTrail->save();
                        }
                    }

                }
            }
        }

        return redirect(route('admin.studyScheduleList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule',
                'message' => 'Study schedule successfully updated!',
            ],
        ]);

    }

    /**
        * Delete study schedule
        *
        * @param $id
        *
        * @return to study schedule listing page with data delete from StudySchedule database
    **/
    public function deleteStudySchedule($id){
        
        $delete = StudySchedule::where('study_id',base64_decode($id))->delete();

        $updateStudyScheduleTrail = StudyScheduleTrail::where('study_id', base64_decode($id))->update(['is_delete' => 1]);

        if($delete){

            return redirect(route('admin.studyScheduleList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Study Schedule',
                    'message' => 'Study schedule successfully deleted',
                ],
            ]);     
        }
    }

    /**
        * Add study schedule date
        *
        * @param mixed $schedule, $activity
        *
        * @return to add study schedule date page
    **/
    public function addStudyScheduleDate($id){

        $scheduleSequence = StudySchedule::where('study_id', base64_decode($id))
                                         ->with(['nextActivity'])
                                         ->orderBy('group_no', 'ASC')
                                         ->orderby('period_no', 'ASC')
                                         ->get();

        $studyNo = Study::where('id', base64_decode($id))
                        ->with([
                                'sponsorName',
                                'projectManager',
                                'drugDetails' => function($q) {
                                    $q->with([
                                        'drugName',
                                        'drugDosageName',
                                        'drugUom',
                                        'drugType'
                                    ]);
                                }
                            ])
                        ->first();

        $activity = array();
        if (!is_null($scheduleSequence)) {
            foreach ($scheduleSequence as $key => $value) {
                $activity[$key]['id'] = $value->id;
                $activity[$key]['activity_id'] = $value->activity_id;
                $activity[$key]['name'] = $value->activity_name;
                $activity[$key]['group_no'] = $value->group_no;
                $activity[$key]['period_no'] = $value->period_no;
            }
        }

        /*$crActivitySchedule = StudySchedule::where('study_id', base64_decode($id))
                                           ->with('crActivity')
                                           ->orderBy('activity_sequence_no', 'ASC')
                                           ->get();

        $brActivitySchedule = StudySchedule::where('study_id', base64_decode($id))
                                           ->with('brActivity')
                                           ->orderBy('activity_sequence_no', 'ASC')
                                           ->get();

        $pbActivitySchedule = StudySchedule::where('study_id', base64_decode($id))
                                           ->with('pbActivity')
                                           ->orderBy('activity_sequence_no', 'ASC')
                                           ->get();
                                           
        $rwActivitySchedule = StudySchedule::where('study_id', base64_decode($id))
                                           ->with('rwActivity')
                                           ->orderBy('activity_sequence_no', 'ASC')
                                           ->get();*/

        $psActivitySchedule = DB::table("study_schedules")
                                ->join("activity_masters", function($join){
                                    $join->on("study_schedules.activity_id", "=", "activity_masters.id");
                                })
                                ->join("para_codes", function($join){
                                    $join->on("para_codes.id", "=", "activity_masters.activity_type");
                                })
                                ->select('study_schedules.id','study_schedules.activity_name','study_schedules.group_no','study_schedules.period_no','study_schedules.require_days','study_schedules.scheduled_start_date','study_schedules.scheduled_end_date','study_schedules.study_id','activity_masters.activity_type','study_schedules.activity_sequence_no', 'study_schedules.is_milestone', 'is_start_milestone_activity', 'is_end_milestone_activity', 'activity_version', 'activity_version_type')
                                ->where('study_id', base64_decode($id))
                                ->where("para_codes.para_value", "=", 'PS')
                                ->where("study_schedules.is_active", "=", 1)
                                ->where("study_schedules.is_delete", "=", 0)
                                ->orderBy('activity_sequence_no', 'ASC')
                                ->orderBy('activity_version', 'ASC')
                                ->get();

        $crActivityScheduleCount = DB::table("study_schedules")
                                     ->join("activity_masters", function($join){
                                            $join->on("study_schedules.activity_id", "=", "activity_masters.id");
                                     })
                                     ->join("para_codes", function($join){
                                            $join->on("para_codes.id", "=", "activity_masters.activity_type");
                                     })
                                     ->select('study_schedules.id','study_schedules.activity_name','study_schedules.group_no','study_schedules.period_no','study_schedules.require_days','study_schedules.scheduled_start_date','study_schedules.study_id','activity_masters.activity_type','study_schedules.activity_sequence_no', 'is_start_milestone_activity', 'is_end_milestone_activity')
                                     ->where('study_id', base64_decode($id))
                                     ->where("para_codes.para_value", "=", 'CR')
                                     ->where("study_schedules.is_active", "=", 1)
                                     ->where("study_schedules.is_delete", "=", 0)
                                     ->orderBy('activity_sequence_no', 'ASC')
                                     ->get();

        $checkin = $crActivityScheduleCount->where('activity_name', 'Checkin');
        $dosing = $crActivityScheduleCount->where('activity_name', 'Dosing');
        $maxPeriod = $checkin->count();
        $srNo = 1;

        if(!is_null($crActivityScheduleCount)){
            foreach($crActivityScheduleCount as $sk => $sv){
                if($sv->activity_name == 'Screening'){
                    $updateSchedule = StudySchedule::where('id', $sv->id)->update(['activity_sequence_no' => $srNo]);
                }
            }

            for($i = 1; $i <= $maxPeriod; $i++){
                foreach($checkin->where('period_no', $i) as $ck => $cv){
                    $srNo += 1;
                    $updateSchedule = StudySchedule::where('id', $cv->id)->update(['activity_sequence_no' => $srNo]);
                }
                foreach($dosing->where('period_no', $i) as $dk => $dv){
                    $srNo += 1;
                    $updateSchedule = StudySchedule::where('id', $dv->id)->update(['activity_sequence_no' => $srNo]);
                }
            }

            foreach($crActivityScheduleCount as $sk => $sv){
                if($sv->activity_name != 'Screening' && $sv->activity_name != 'Checkin' && $sv->activity_name != 'Dosing'){
                    $srNo += 1;
                    $updateSchedule = StudySchedule::where('id', $sv->id)->update(['activity_sequence_no' => $srNo]);
                }
            }
        }

        $crActivitySchedule = DB::table("study_schedules")
                                ->join("activity_masters", function($join){
                                    $join->on("study_schedules.activity_id", "=", "activity_masters.id");
                                })
                                ->join("para_codes", function($join){
                                    $join->on("para_codes.id", "=", "activity_masters.activity_type");
                                })
                                ->select('study_schedules.id','study_schedules.activity_name','study_schedules.group_no','study_schedules.period_no','study_schedules.require_days','study_schedules.scheduled_start_date','study_schedules.scheduled_end_date','study_schedules.study_id','activity_masters.activity_type','study_schedules.activity_sequence_no', 'study_schedules.is_milestone', 'is_start_milestone_activity', 'is_end_milestone_activity', 'activity_version')
                                ->where('study_id', base64_decode($id))
                                ->where("para_codes.para_value", "=", 'CR')
                                ->where("study_schedules.is_active", "=", 1)
                                ->where("study_schedules.is_delete", "=", 0)
                                ->orderBy('activity_sequence_no', 'ASC')
                                ->get();

        $brActivitySchedule = DB::table("study_schedules")
                                ->join("activity_masters", function($join){
                                    $join->on("study_schedules.activity_id", "=", "activity_masters.id");
                                })
                                ->join("para_codes", function($join){
                                    $join->on("para_codes.id", "=", "activity_masters.activity_type");
                                })
                                ->select('study_schedules.id','study_schedules.activity_name','study_schedules.group_no','study_schedules.period_no','study_schedules.require_days','study_schedules.scheduled_start_date','study_schedules.scheduled_end_date','study_schedules.study_id','activity_masters.activity_type','study_schedules.activity_sequence_no', 'study_schedules.is_milestone', 'is_start_milestone_activity', 'is_end_milestone_activity', 'activity_version')
                                ->where('study_id', base64_decode($id))
                                ->where("para_codes.para_value", "=", 'BR')
                                ->where("study_schedules.is_active", "=", 1)
                                ->where("study_schedules.is_delete", "=", 0)
                                ->orderBy('activity_sequence_no', 'ASC')
                                ->get();

        $pbActivitySchedule = DB::table("study_schedules")
                                ->join("activity_masters", function($join){
                                    $join->on("study_schedules.activity_id", "=", "activity_masters.id");
                                })
                                ->join("para_codes", function($join){
                                    $join->on("para_codes.id", "=", "activity_masters.activity_type");
                                })
                                ->select('study_schedules.id','study_schedules.activity_name','study_schedules.group_no','study_schedules.period_no','study_schedules.require_days','study_schedules.scheduled_start_date','study_schedules.scheduled_end_date','study_schedules.study_id','activity_masters.activity_type','study_schedules.activity_sequence_no', 'study_schedules.is_milestone', 'is_start_milestone_activity', 'is_end_milestone_activity', 'activity_version')
                                ->where('study_id', base64_decode($id))
                                ->where("para_codes.para_value", "=", 'PB')
                                ->where("study_schedules.is_active", "=", 1)
                                ->where("study_schedules.is_delete", "=", 0)
                                ->orderBy('activity_sequence_no', 'ASC')
                                ->get();

        $rwActivitySchedule = DB::table("study_schedules")
                                ->join("activity_masters", function($join){
                                    $join->on("study_schedules.activity_id", "=", "activity_masters.id");
                                })
                                ->join("para_codes", function($join){
                                    $join->on("para_codes.id", "=", "activity_masters.activity_type");
                                })
                                ->select('study_schedules.id','study_schedules.activity_name','study_schedules.group_no','study_schedules.period_no','study_schedules.require_days','study_schedules.scheduled_start_date','study_schedules.scheduled_end_date','study_schedules.study_id','activity_masters.activity_type','study_schedules.activity_sequence_no', 'study_schedules.is_milestone', 'is_start_milestone_activity', 'is_end_milestone_activity', 'activity_version')
                                ->where('study_id', base64_decode($id))
                                ->where("para_codes.para_value", "=", 'RW')
                                ->where("study_schedules.is_active", "=", 1)
                                ->where("study_schedules.is_delete", "=", 0)
                                ->orderBy('activity_sequence_no', 'ASC')
                                ->get();

        return view('admin.study.study_schedule.add_schedule_date', compact('crActivitySchedule', 'activity', 'studyNo', 'brActivitySchedule', 'rwActivitySchedule', 'pbActivitySchedule', 'id', 'psActivitySchedule'));
    }

    /*public function addStudyScheduleDate($id){

        $scheduleSequence = StudySchedule::where('study_id', base64_decode($id))
                                         ->with(['nextActivity'])
                                         ->orderBy('group_no', 'ASC')
                                         ->orderby('period_no', 'ASC')
                                         ->get();

        $x = 1;
        if (!is_null($scheduleSequence)) {
            foreach ($scheduleSequence as $sk => $sv) {
                $scheduleDate = StudySchedule::findOrFail($sv->id);
                $scheduleDate->activity_sequence_no = $x;
                $scheduleDate->save();
                $x++;
            }
        }
        
        $studyNo = Study::where('id', base64_decode($id))
                        ->with([
                                'sponsorName',
                                'projectManager',
                                'drugDetails' => function($q) {
                                    $q->with([
                                        'drugName',
                                        'drugDosageName',
                                        'drugUom',
                                        'drugType'
                                    ]);
                                }
                            ])
                        ->first();

        $activity = array();
        if (!is_null($scheduleSequence)) {
            foreach ($scheduleSequence as $key => $value) {
                $activity[$key]['id'] = $value->id;
                $activity[$key]['activity_id'] = $value->activity_id;
                $activity[$key]['name'] = $value->activity_name;
                $activity[$key]['group_no'] = $value->group_no;
                $activity[$key]['period_no'] = $value->period_no;
            }
        }

        $schedule = StudySchedule::where('study_id', base64_decode($id))
                                 ->with(['nextActivity'])
                                 ->orderBy('scheduled_start_date', 'ASC')
                                 ->get();

        return view('admin.study.study_schedule.add_schedule_date', compact('schedule', 'activity', 'studyNo'));
    }*/

    /**
        * Save study schedule date
        *
        * @param $scheduled_start_date, $scheduled_end_date, $require_days, $next_activity_id, $previous_activity_id, 
        *        $reference_parent_activity_id fields save in StudySchedule database
        *
        * @return to study schedule listing page with data store in StudySchedule database
    **/
    public function saveStudyScheduleDate(Request $request){

        if (!is_null($request->next_activity)) {
            foreach ($request->next_activity as $nk => $nv) {
                if (!is_null($nv)) {
                    foreach ($nv as $nak => $nav) {
                        $schedule = StudySchedule::where('id', $nk)->first();

                        $scheduleDate = StudySchedule::findOrFail($nk);
                        $scheduleDate->next_activity_id = $nav;
                        $scheduleDate->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $scheduleDate->save();

                        $studyScheduleTrail = new StudyScheduleTrail;
                        $studyScheduleTrail->study_id = $schedule->study_id;
                        $studyScheduleTrail->activity_id = $schedule->activity_id;
                        $studyScheduleTrail->next_activity_id = $nav;
                        $studyScheduleTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $studyScheduleTrail->save();
                    }
                }
            }
        }

        if (!is_null($request->previous_activity)) {
            foreach ($request->previous_activity as $pk => $pv) {
                if (!is_null($pv)) {
                    foreach ($pv as $prk => $prv) {
                        $schedule = StudySchedule::where('id', $pk)->first();

                        $scheduleDate = StudySchedule::findOrFail($pk);
                        $scheduleDate->previous_activity_id = $prv;
                        $scheduleDate->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $scheduleDate->save();

                        $studyScheduleTrail = new StudyScheduleTrail;
                        $studyScheduleTrail->study_id = $schedule->study_id;
                        $studyScheduleTrail->activity_id = $schedule->activity_id;
                        $studyScheduleTrail->previous_activity_id = $prv;
                        $studyScheduleTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $studyScheduleTrail->save();
                    }
                }
            }
        }

        if (!is_null($request->parent_activity)) {
            foreach ($request->parent_activity as $pk => $pv) {
                if (!is_null($pv)) {
                    foreach ($pv as $prk => $prv) {
                        $schedule = StudySchedule::where('id', $pk)->first();

                        $scheduleDate = StudySchedule::findOrFail($pk);
                        $scheduleDate->reference_parent_activity_id = $prv;
                        $scheduleDate->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $scheduleDate->save();

                        $studyScheduleTrail = new StudyScheduleTrail;
                        $studyScheduleTrail->study_id = $schedule->study_id;
                        $studyScheduleTrail->activity_id = $schedule->activity_id;
                        $studyScheduleTrail->reference_parent_activity_id = $prv;
                        $studyScheduleTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $studyScheduleTrail->save();
                    }
                }
            }
        }

        if (!is_null($request->activity_sequence)) {
            foreach ($request->activity_sequence as $ask => $asv) {
                if (!is_null($asv)) {
                    foreach ($asv as $key => $value) {
                        $schedule = StudySchedule::where('id', $ask)->first();

                        $scheduleDate = StudySchedule::findOrFail($ask);
                        $scheduleDate->activity_sequence_no = $value;
                        $scheduleDate->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $scheduleDate->save();

                        $studyScheduleTrail = new StudyScheduleTrail;
                        $studyScheduleTrail->study_id = $schedule->study_id;
                        $studyScheduleTrail->activity_id = $schedule->activity_id;
                        $studyScheduleTrail->activity_sequence_no = $value;
                        $studyScheduleTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                        $studyScheduleTrail->save();
                    }
                }
            }
        }

        return redirect(route('admin.addStudyScheduleDate', $request->id))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule Date',
                'message' => 'Study schedule date successfully added!',
            ],
        ]);
    }

    // Change schedule date with ajax code
    public function changeScheduleDate(Request $request){

        $firstDate = $request->date;
        $addOne = 1;
        $zero = 0;
        $checkStartHolidayDate = '';
        $checkEndHolidayDate = '';

        $scheduleActivityId = StudySchedule::where('id', $request->id)->first();
        $activityDayType = ActivityMaster::where('id', $scheduleActivityId->activity_id)->first();
        
        if ($activityDayType->activity_days == 'WORKING') {
            $checkStartHolidayDate = HolidayMaster::where('holiday_date', $this->convertDt($request->date))->first();
        }

        if ($checkStartHolidayDate != '') {
            $ffDate = Carbon::parse($firstDate)->addDay($addOne);
            $fDate = date('Y-m-d', strtotime($ffDate));         
        } else {
            $fDate = date('Y-m-d', strtotime($firstDate));
        }
        
        if ($activityDayType->activity_days == 'WORKING') {
            $checkFirstStartHolidayDate = HolidayMaster::where('holiday_date', $fDate)->count();
        } else {
            $checkFirstStartHolidayDate = 0;
        }

        if($checkFirstStartHolidayDate == 1){
            $fStartDate = Carbon::parse($fDate)->addDay($addOne); 
        } else {
            $fStartDate = $fDate;
        }

        $days = StudySchedule::where('id', $request->id)->where('activity_type', $request->activityType)->first();

        $data = date('Y/m/d H:i:s', strtotime($fStartDate));

        $newDateTime = Carbon::parse($data)->addDay($days->require_days-1);
        if ($activityDayType->activity_days == 'WORKING') {
            $holidayMaster = HolidayMaster::whereBetween('holiday_date', [$data, date('Y/m/d', strtotime($newDateTime->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
        } else {
            $holidayMaster = 0;
        }
        
        $totalDays = $holidayMaster + $days->require_days;
        $newDateTime1 = Carbon::parse($data)->addDay($totalDays-1);

        if ($activityDayType->activity_days == 'WORKING') {
            $holidayMaster1 = HolidayMaster::whereNotIn('holiday_date',[date('Y/m/d' ,strtotime($newDateTime->toDateTimeString()))])->whereBetween('holiday_date', [date('Y/m/d', strtotime($newDateTime->toDateTimeString())), date('Y/m/d', strtotime($newDateTime1->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
        } else {
            $holidayMaster1 = 0;
        }

        $totalDays1 = $holidayMaster1 + $totalDays;
        $newDateTime2 = Carbon::parse($data)->addDay($totalDays1-1);

        if ($activityDayType->activity_days == 'WORKING') {
            $checkEndHolidayDate = HolidayMaster::where('holiday_date', $newDateTime2)->first();
        }

        if ($checkEndHolidayDate != '') {
            $eDate = Carbon::parse($newDateTime2)->addDay($addOne);   
        } else {
            $eDate = $newDateTime2;
        }

        $endDateUpdate = StudySchedule::where('id', $request->id)
                                      ->where('activity_type', $request->activityType)
                                      ->update([
                                            'scheduled_start_date' => $fStartDate,
                                            'scheduled_end_date' => date('Y/m/d', strtotime($eDate->toDateTimeString()))
                                        ]);

        $endDateUpdateTrail = StudyScheduleTrail::where('study_schedule_id', $request->id)
                                                ->where('activity_type', $request->activityType)
                                                ->update([
                                                    'scheduled_start_date' => $fStartDate,
                                                    'scheduled_end_date' => date('Y/m/d', strtotime($eDate->toDateTimeString()))
                                                ]);

        if($days->scheduled_start_date == ''){
            $originalDateUpdate = StudySchedule::where('id', $request->id)
                                                ->where('activity_type', $request->activityType)
                                                ->update([
                                                    'original_schedule_start_date' => $fStartDate,
                                                    'original_schedule_end_date' => date('Y/m/d', strtotime($eDate->toDateTimeString()))
                                                ]);
        }

        $count = StudySchedule::count();
        $skip = 1;
        $limit = $count - $skip; // the limit
        $study = StudySchedule::where('study_id', $request->studyId)
                              ->where('activity_type', $request->activityType)
                              ->where('activity_version', 0)
                              ->where('activity_sequence_no','>',$request->SequenceNo)
                              ->orderBy('activity_sequence_no', 'ASC')
                              ->get();

        $oldDateTime1 = '';
        $checkStartHolidayDate1 = '';
        $checkStartHolidayDate2 = '';
        $checkEndHolidayDate1 = '';
        $checkEndHolidayDate2 = '';
        $checkStartHolidayDate3 = '';
        $checkStartHolidayDate4 = '';

        if (!is_null($study)) {
            foreach ($study as $sk => $sv) {
                
                if ($sk == 0) {
                    $scheduleActivityId1 = StudySchedule::where('id', $sv->id)->first();
                    $activityDayType1 = ActivityMaster::where('id', $scheduleActivityId1->activity_id)->first();
                    
                    $countDays = 1;
                    $oldDate = Carbon::parse($eDate)->addDay($countDays);

                    if ($activityDayType1->activity_days == 'WORKING') {
                        $checkStartHolidayDate1 = HolidayMaster::where('holiday_date', date('Y-m-d', strtotime($oldDate)))->first();
                    }

                    if ($checkStartHolidayDate1 != '') {
                        $fNewDate1 = Carbon::parse($oldDate)->addDay($countDays);            
                    } else {
                        $fNewDate1 = $oldDate;
                    }

                    if ($activityDayType1->activity_days == 'WORKING') {
                        $checkStartHolidayDate4 = HolidayMaster::where('holiday_date', date('Y-m-d', strtotime($fNewDate1)))->first();
                    }

                    if ($checkStartHolidayDate4 != '') {
                        $fDate1 = Carbon::parse($fNewDate1)->addDay($countDays);            
                    } else {
                        $fDate1 = $fNewDate1;
                    }

                    $newDateTimeForLoop1 = Carbon::parse($fDate1);
                    $newDateTimeForLoop12 = Carbon::parse($newDateTimeForLoop1)->addDay($sv->require_days-1);
                    
                    if ($activityDayType1->activity_days == 'WORKING') {
                        $holidayMasterForLoop1 = HolidayMaster::whereNotIn('holiday_date',[date('Y/m/d' ,strtotime($fDate1->toDateTimeString()))])->whereBetween('holiday_date', [$newDateTimeForLoop1, date('Y/m/d', strtotime($newDateTimeForLoop12->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
                    } else {
                        $holidayMasterForLoop1 = 0;
                    }

                    $totalDaysForLoop1 = $holidayMasterForLoop1 + $sv->require_days;

                    $newDateTimeForLoop2 = Carbon::parse($newDateTimeForLoop12)->addDay($holidayMasterForLoop1-1);
                    
                    if ($activityDayType1->activity_days == 'WORKING') {
                        $holidayMasterForLoop12 = HolidayMaster::whereNotIn('holiday_date',[date('Y/m/d' ,strtotime($newDateTimeForLoop12->toDateTimeString()))])->whereBetween('holiday_date', [date('Y/m/d', strtotime($newDateTimeForLoop12->toDateTimeString())), date('Y/m/d', strtotime($newDateTimeForLoop2->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
                    } else {
                        $holidayMasterForLoop12 = 0;
                    }

                    $totalDaysForLoop12 = $holidayMasterForLoop12 + $totalDaysForLoop1;
                    $newDateTimeForLoop21 = Carbon::parse($newDateTimeForLoop1)->addDay($totalDaysForLoop12-1);

                    if ($activityDayType1->activity_days == 'WORKING') {
                        $checkEndHolidayDate1 = HolidayMaster::where('holiday_date', date('Y/m/d', strtotime($newDateTimeForLoop21->toDateTimeString())))->first();
                    }

                    if ($checkEndHolidayDate1 != '') {
                        $eDate1 = Carbon::parse($newDateTimeForLoop21)->addDay($zero);            
                    } else {
                        $eDate1 = $newDateTimeForLoop21;
                    }

                    $update = StudySchedule::where('id', $sv->id)
                                            ->where('activity_type', $request->activityType)
                                            ->update([
                                                'scheduled_start_date' => date('Y/m/d', strtotime($fDate1->toDateTimeString())),
                                                'scheduled_end_date' =>  date('Y/m/d', strtotime($eDate1->toDateTimeString()))
                                            ]);

                    $updateTrail = StudyScheduleTrail::where('study_schedule_id', $sv->id)
                                                     ->where('activity_type', $request->activityType)
                                                     ->update([
                                                         'scheduled_start_date' => date('Y/m/d', strtotime($fDate1->toDateTimeString())),
                                                         'scheduled_end_date' =>  date('Y/m/d', strtotime($eDate1->toDateTimeString()))
                                                     ]);

                    if($days->scheduled_start_date == ''){
                        $originalDateUpdate = StudySchedule::where('id', $sv->id)
                                                            ->where('activity_type', $request->activityType)
                                                            ->update([
                                                                'original_schedule_start_date' => date('Y/m/d', strtotime($fDate1->toDateTimeString())),
                                                                'original_schedule_end_date' => date('Y/m/d', strtotime($eDate1->toDateTimeString()))
                                                            ]);
                    }

                    $scheduledEndDate = date('Y/m/d', strtotime($eDate1->toDateTimeString()));

                } else {

                    $scheduleActivityId2 = StudySchedule::where('id', $sv->id)->first();
                    $activityDayType2 = ActivityMaster::where('id', $scheduleActivityId2->activity_id)->first();
                    
                    $addDays = 1;
                    if($sk == 1){
                        $oldDateTime = $scheduledEndDate;
                    } else {
                        $oldDateTime = $oldDateTime1;
                    }


                    $newDateTimeForLoop3 = Carbon::parse(date('Y-m-d', strtotime($oldDateTime)))->addDay($addDays);
                    /*echo "<pre>";
                    print_r(date('Y-m-d', strtotime($newDateTimeForLoop3)));*/
                    

                    if ($activityDayType2->activity_days == 'WORKING') {
                        $checkStartHolidayDate2 = HolidayMaster::where('holiday_date', date('Y-m-d', strtotime($newDateTimeForLoop3)))->first();
                    }

                    if ($checkStartHolidayDate2 != '') {
                        $fNewDate2 = Carbon::parse($newDateTimeForLoop3)->addDay($addDays);            
                    } else {
                        $fNewDate2 = $newDateTimeForLoop3;
                    }

                    if ($activityDayType2->activity_days == 'WORKING') {
                        $checkStartHolidayDate3 = HolidayMaster::where('holiday_date', date('Y-m-d', strtotime($fNewDate2)))->first();
                    }

                    if ($checkStartHolidayDate3 != '') {
                        $fDate2 = Carbon::parse($fNewDate2)->addDay($addDays);            
                    } else {
                        $fDate2 = $fNewDate2;
                    }

                    /*echo "<pre>";
                    print_r(date('Y-m-d', strtotime($fDate2)));*/


                    $newDateTimeForLoop22 = Carbon::parse(date('Y-m-d', strtotime($fDate2)))->addDay($sv->require_days-1);
                    /*echo "<pre>";
                    print_r(date('Y-m-d', strtotime($newDateTimeForLoop22)));*/
                    
                    if ($activityDayType2->activity_days == 'WORKING') {
                        $holidayMasterForLoop2 = HolidayMaster::whereNotIn('holiday_date',[date('Y/m/d' ,strtotime($fDate2->toDateTimeString()))])->whereBetween('holiday_date', [$newDateTimeForLoop3, date('Y/m/d', strtotime($newDateTimeForLoop22->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
                    } else {
                        $holidayMasterForLoop2 = 0;
                    }
                    /*echo "<pre>";
                    print_r($holidayMasterForLoop2);*/

                    $totalDaysForLoop2 = $holidayMasterForLoop2 + $sv->require_days;
                    /*echo "<pre>";
                    print_r($totalDaysForLoop2);*/

                    $newDateTimeForLoop4 = Carbon::parse($newDateTimeForLoop3)->addDay($totalDaysForLoop2);

                    /*echo "<pre>";
                    print_r(date('Y-m-d', strtotime($newDateTimeForLoop4)));*/

                    if ($activityDayType2->activity_days == 'WORKING') {
                        $holidayMasterForLoop22 = HolidayMaster::whereNotIn('holiday_date',[date('Y/m/d' ,strtotime($newDateTimeForLoop22->toDateTimeString()))])->whereBetween('holiday_date', [date('Y/m/d', strtotime($newDateTimeForLoop22->toDateTimeString())), date('Y/m/d', strtotime($newDateTimeForLoop4->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
                    } else {
                        $holidayMasterForLoop22 = 0;
                    }

                    /*echo "<pre>";
                    print_r($holidayMasterForLoop22);*/

                    $totalDaysForLoop22 = $holidayMasterForLoop22 + $totalDaysForLoop2;
                    $newDateTimeForLoop23 = Carbon::parse($newDateTimeForLoop3)->addDay($totalDaysForLoop22-1);

                    /*echo "<pre>";
                    print_r(date('Y-m-d', strtotime($newDateTimeForLoop23)));*/
                    

                    if ($activityDayType2->activity_days == 'WORKING') {
                        $checkEndHolidayDate2 = HolidayMaster::where('holiday_date', date('Y/m/d', strtotime($newDateTimeForLoop23->toDateTimeString())))->first();
                    }

                   /*echo "<pre>";
                    print_r($checkEndHolidayDate2);*/
                    

                    if ($checkEndHolidayDate2 != '') {
                        $eDate2 = Carbon::parse($newDateTimeForLoop23)->addDay($zero-1);            
                    } else {
                        $eDate2 = $newDateTimeForLoop23;
                    }

                    /*echo "<pre>";
                    print_r(date('Y/m/d', strtotime($eDate2->toDateTimeString())));
                    echo "----------";*/

                    $update = StudySchedule::where('id', $sv->id)
                                           ->where('activity_type', $request->activityType)
                                           ->update([
                                                'scheduled_start_date' => date('Y/m/d', strtotime($fDate2->toDateTimeString())),
                                                'scheduled_end_date' => date('Y/m/d', strtotime($eDate2->toDateTimeString()))
                                           ]);

                    $updateTrail = StudyScheduleTrail::where('study_schedule_id', $sv->id)
                                                     ->where('activity_type', $request->activityType)
                                                     ->update([
                                                         'scheduled_start_date' => date('Y/m/d', strtotime($fDate2->toDateTimeString())),
                                                         'scheduled_end_date' => date('Y/m/d', strtotime($eDate2->toDateTimeString()))
                                                     ]);

                    if($days->scheduled_start_date == ''){
                        $originalDateUpdate = StudySchedule::where('id', $sv->id)
                                                            ->where('activity_type', $request->activityType)
                                                            ->update([
                                                                'original_schedule_start_date' => date('Y/m/d', strtotime($fDate2->toDateTimeString())),
                                                                'original_schedule_end_date' => date('Y/m/d', strtotime($eDate2->toDateTimeString()))
                                                            ]);
                    }

                    $oldDateTime1 = date('Y/m/d', strtotime($eDate2->toDateTimeString()));

                }
            }

            $study = Study::where('id', $request->studyId)
                            ->with([
                                'schedule' => function ($query) {
                                    $query->whereNotNull('scheduled_start_date')
                                          ->select('study_id', DB::raw('MIN(scheduled_start_date) as min_schedule_date'))
                                          ->groupBy('study_id');
                                }
                            ])
                            ->first();

            if (!is_null($study->schedule)) {
                foreach ($study->schedule as $sk => $sv) {
                    $minScheduleDate = $sv->min_schedule_date;
                    if ($minScheduleDate <= date('Y-m-d')) {
                        // The minimum schedule date is greater than or equal to the current date
                        $updateStudyStatus = Study::where('id', $study->id)->update(['study_status' => 'ONGOING']);
                    } else {
                        // The minimum schedule date is earlier than the current date
                        $updateStudyStatus = Study::where('id', $study->id)->update(['study_status' => 'UPCOMING']);
                    }
                }
            }

            $schedules = StudySchedule::where('study_id', $request->studyId)
                                      ->orderBy('scheduled_start_date', 'ASC')
                                      ->with('crActivity', 'brActivity', 'pbActivity', 'rwActivity', 'psActivity')
                                      ->get();

            $date = Carbon::now();
            $currentDate = date('Y-m-d', strtotime($date));

            if (!is_null($schedules)) {
                foreach ($schedules as $sk => $sv) {

                    if ($sv->activity_status == 'COMPLETED') {
                    } else if (($sv->actual_start_date == '') && ($sv->actual_end_date == '') && ($sv->scheduled_start_date == $currentDate)) {
                        $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'ONGOING']);
                    } else if (($sv->actual_end_date == '') && ($sv->actual_start_date != '') && ($sv->scheduled_start_date >= $sv->actual_start_date) && ($sv->scheduled_end_date >= $currentDate)) {
                        $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'ONGOING']);
                    } else if (($sv->actual_end_date == '') && ($sv->actual_start_date != '') && ($sv->scheduled_start_date < $sv->actual_start_date) && ($sv->scheduled_end_date >= $currentDate)) {
                        $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'ONGOING']);
                    } else if (($currentDate > $sv->scheduled_start_date) && ($sv->actual_start_date == '') && ($sv->scheduled_start_date != '')) {
                        $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'DELAY']);
                    } else if(($sv->actual_start_date == '') && ($sv->scheduled_start_date != '') && ($currentDate < $sv->scheduled_start_date)) {
                        $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'UPCOMING']);
                    } else {
                        $updateActivityStatus = $sv->activity_status;
                    }
                }
            }

            return 'true';
        }

    }

    public function addScheduleDelayModal(Request $request){

        $scheduleId = $request->id;
        $studyId = $request->studyId;
        $sequenceNo = $request->SequenceNo;
        $activityId = $request->activityId;
        $date = $request->date;
        $activityType = $request->activityType;

        $oldDate = StudySchedule::where('id', $scheduleId)->first();

        return view('admin.study.study_schedule.add_schedule_delay_remark_modal', compact('scheduleId', 'studyId', 'sequenceNo', 'activityId', 'date', 'oldDate', 'activityType'));
    }

    /*public function saveScheduleDelayRemark(Request $request){

        $saveScheduleRemark = new StudyScheduleDelayRemark;
        $saveScheduleRemark->study_id = $request->study_id;
        $saveScheduleRemark->activity_id = $request->activity_id;
        $saveScheduleRemark->schedule_delay_remark = $request->schedule_delay_remark;
        $saveScheduleRemark->old_schedule_date = $request->old_date;
        $saveScheduleRemark->new_schedule_date = $this->convertDateTime($request->date);
        $saveScheduleRemark->created_by_user_id = Auth::guard('admin')->user()->id;
        $saveScheduleRemark->save();

        return redirect(route('admin.addStudyScheduleDate', base64_encode($request->study_id)))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule',
                'message' => 'Study schedule successfully updated!',
            ],
        ]);
    }*/

    public function changeRequiredDays(Request $request){

        $requiredDays = StudySchedule::where('id', $request->id)->update(['require_days' => $request->require_days]);

        $getStudySchedule = StudySchedule::where('id', $request->id)->first();

        if (!is_null($getStudySchedule)) {
            $updateRequiredDays = StudyScheduleTrail::where('activity_name', $getStudySchedule->activity_name)
                                                    ->where('group_no', $getStudySchedule->group_no)
                                                    ->where('period_no', $getStudySchedule->period_no)
                                                    ->orderby('updated_at', 'DESC')
                                                    ->update([
                                                        'require_days' => $request->require_days
                                                    ]);
        }
        
        return $requiredDays ? 'true' : 'false';
    }

    public function changeMilestoneActivity(Request $request){
        
        $milestoneActivity = StudySchedule::where('id', $request->id)->update(['is_milestone' => $request->option]);

        $getStudySchedule = StudySchedule::where('id', $request->id)->first();

        if (!is_null($getStudySchedule)) {
            $updateRequiredDays = StudyScheduleTrail::where('activity_name', $getStudySchedule->activity_name)
                                                    ->where('group_no', $getStudySchedule->group_no)
                                                    ->where('period_no', $getStudySchedule->period_no)
                                                    ->orderby('updated_at', 'DESC')
                                                    ->update([
                                                        'is_milestone' => $request->option
                                                    ]);
        }

        return $milestoneActivity ? 'true' : 'false';
    }

    public function startMilestoneActivity(Request $request){
        
        $startMilestoneActivity = StudySchedule::where('id', $request->id)->update(['is_start_milestone_activity' => $request->option]);

        $getStudySchedule = StudySchedule::where('id', $request->id)->first();

        if (!is_null($getStudySchedule)) {
            $updateRequiredDays = StudyScheduleTrail::where('activity_name', $getStudySchedule->activity_name)
                                                    ->where('group_no', $getStudySchedule->group_no)
                                                    ->where('period_no', $getStudySchedule->period_no)
                                                    ->orderby('updated_at', 'DESC')
                                                    ->update([
                                                        'is_start_milestone_activity' => $request->option
                                                    ]);
        }

        return $startMilestoneActivity ? 'true' : 'false';
    }

    public function endMilestoneActivity(Request $request){
        
        $endMilestoneActivity = StudySchedule::where('id', $request->id)->update(['is_end_milestone_activity' => $request->option]);

        $getStudySchedule = StudySchedule::where('id', $request->id)->first();

        if (!is_null($getStudySchedule)) {
            $updateRequiredDays = StudyScheduleTrail::where('activity_name', $getStudySchedule->activity_name)
                                                    ->where('group_no', $getStudySchedule->group_no)
                                                    ->where('period_no', $getStudySchedule->period_no)
                                                    ->orderby('updated_at', 'DESC')
                                                    ->update([
                                                        'is_end_milestone_activity' => $request->option
                                                    ]);
        }

        return $endMilestoneActivity ? 'true' : 'false';
    }

    // Open study schedule delay remark modal
    public function addScheduleRemarkModal(Request $request){

        $studyId = $request->studyId;
        $activityId = $request->activityId;
        $scheduleId = $request->scheduleId;

        return view('admin.study.study_schedule.add_schedule_remark_modal', compact('studyId', 'activityId', 'scheduleId'));
    }
    
    // Save study schedule delay remark
    public function saveScheduleDelayRemark(Request $request){

        $saveScheduleRemark = new StudyScheduleDelayRemark;
        $saveScheduleRemark->study_id = $request->study_id;
        $saveScheduleRemark->activity_type_id = $request->activity_type_id;
        $saveScheduleRemark->remark = $request->schedule_delay_remark;
        $saveScheduleRemark->created_by_user_id = Auth::guard('admin')->user()->id;
        $saveScheduleRemark->save();

        $saveScheduleTrailRemark = new StudyScheduleDelayRemarkTrail;
        $saveScheduleTrailRemark->schedule_id = $request->schedule_id;
        $saveScheduleTrailRemark->study_id = $request->study_id;
        $saveScheduleTrailRemark->activity_type_id = $request->activity_type_id;
        $saveScheduleTrailRemark->remark = $request->schedule_delay_remark;
        $saveScheduleTrailRemark->created_by_user_id = Auth::guard('admin')->user()->id;
        $saveScheduleTrailRemark->save();

        $getStudySchedule = StudySchedule::where('study_id', $request->study_id)
                                         ->where('activity_type', $request->activity_type_id)
                                         ->get();

        if (!is_null($getStudySchedule)) {
            foreach ($getStudySchedule as $sk => $sv) {

                $updateScheduleTrail = new StudyScheduleTrail;
                $updateScheduleTrail->study_schedule_id = $request->schedule_id;
                $updateScheduleTrail->study_schedule_delay_remark_id = $saveScheduleRemark->id;
                $updateScheduleTrail->study_id = $sv->study_id;
                $updateScheduleTrail->activity_id = $sv->activity_id;
                $updateScheduleTrail->activity_name = $sv->activity_name;
                $updateScheduleTrail->require_days = $sv->require_days;
                $updateScheduleTrail->is_milestone = $sv->is_milestone;
                $updateScheduleTrail->scheduled_start_date = $sv->scheduled_start_date;
                $updateScheduleTrail->scheduled_end_date = $sv->scheduled_end_date;
                $updateScheduleTrail->group_no = $sv->group_no;
                $updateScheduleTrail->period_no = $sv->period_no;
                $updateScheduleTrail->activity_type = $sv->activity_type;
                $updateScheduleTrail->minimum_days_allowed = $sv->minimum_days_allowed;
                $updateScheduleTrail->maximum_days_allowed = $sv->maximum_days_allowed;
                if ($sv->next_activity_id != '') {
                    $updateScheduleTrail->next_activity_id = $sv->next_activity_id;
                }
                $updateScheduleTrail->responsibility_id = $sv->responsibility_id;
                if ($sv->previous_activity_id != '') {
                    $updateScheduleTrail->previous_activity_id = $sv->previous_activity_id;
                }
                $updateScheduleTrail->milestone_percentage = $sv->milestone_percentage;
                $updateScheduleTrail->milestone_amount = $sv->milestone_amount;
                $updateScheduleTrail->reference_parent_activity_id = $sv->reference_parent_activity_id;
                $updateScheduleTrail->is_parellel = $sv->is_parellel;
                $updateScheduleTrail->is_dependent = $sv->is_dependent;
                $updateScheduleTrail->is_group_specific = $sv->is_group_specific;
                $updateScheduleTrail->is_period_specific = $sv->is_period_specific;
                $updateScheduleTrail->activity_sequence_no = $sv->activity_sequence_no;
                $updateScheduleTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                $updateScheduleTrail->created_at = date('Y-m-d H:i:s');
                $updateScheduleTrail->updated_at = date('Y-m-d H:i:s');
                $updateScheduleTrail->save();
            }
        }

        return redirect(route('admin.addStudyScheduleDate', base64_encode($request->study_id)))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule',
                'message' => 'Study schedule successfully updated!',
            ],
        ]);
    }

    public function addCopyActivityModal(Request $request){

        $scheduleId = $request->id;

        return view('admin.study.study_schedule.add_copy_activity_modal', compact('scheduleId'));
    }

    // Copy any activity with new version
    public function copyStudyActivity(Request $request){

        $getActivity = StudySchedule::where('id', $request->schedule_id)
                                     ->where('is_active', 1)
                                     ->where('is_delete', 0)
                                     ->first();

        $countActivity = StudySchedule::where('study_id', $getActivity->study_id)
                                      ->where('activity_id', $getActivity->activity_id)
                                      ->whereNotNull('activity_version_type')
                                      ->where('activity_version_type', $request->activity_version_type)
                                      ->where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->count();

        $copyActivity = new StudySchedule;
        $copyActivity->study_id = $getActivity->study_id;
        $copyActivity->activity_id = $getActivity->activity_id;
        $copyActivity->activity_name = $getActivity->activity_name;
        $copyActivity->activity_sequence_no = $getActivity->activity_sequence_no;
        $copyActivity->responsibility_id = $getActivity->responsibility_id;
        $copyActivity->require_days = $getActivity->require_days;
        $copyActivity->minimum_days_allowed = $getActivity->minimum_days_allowed;
        $copyActivity->maximum_days_allowed = $getActivity->maximum_days_allowed;
        $copyActivity->previous_activity_id = $getActivity->previous_activity_id;
        $copyActivity->next_activity_id = $getActivity->next_activity_id;
        $copyActivity->is_milestone = 0;
        $copyActivity->is_start_milestone_activity = $getActivity->is_start_milestone_activity;
        $copyActivity->is_end_milestone_activity = $getActivity->is_end_milestone_activity;
        $copyActivity->milestone_percentage = $getActivity->milestone_percentage;
        $copyActivity->milestone_amount = $getActivity->milestone_amount;
        $copyActivity->buffer_days = $getActivity->buffer_days;
        $copyActivity->scheduled_start_date = Null;
        $copyActivity->original_schedule_start_date = Null;
        $copyActivity->actual_start_date = Null;
        $copyActivity->actual_start_date_time = Null;
        $copyActivity->start_difference = Null;
        $copyActivity->scheduled_end_date = Null;
        $copyActivity->original_schedule_end_date = Null;
        $copyActivity->actual_end_date = Null;
        $copyActivity->start_delay_reason_id = Null;
        $copyActivity->start_delay_remark = Null;
        $copyActivity->end_delay_reason_id = Null;
        $copyActivity->end_delay_remark = Null;
        $copyActivity->end_difference = Null;
        $copyActivity->reference_parent_activity_id = Null;
        $copyActivity->remarks = Null;
        $copyActivity->is_dependent = $getActivity->is_dependent;
        $copyActivity->is_parellel = $getActivity->is_parellel;
        $copyActivity->group_no = $getActivity->group_no;
        $copyActivity->period_no = $getActivity->period_no;
        $copyActivity->is_group_specific = $getActivity->is_group_specific;
        $copyActivity->is_period_specific = $getActivity->is_period_specific;
        $copyActivity->activity_status = Null;
        $copyActivity->action_by_id = $getActivity->action_by_id;
        $copyActivity->action_by_role_id = $getActivity->action_by_role_id;
        $copyActivity->action = $getActivity->action;
        $copyActivity->activity_type = $getActivity->activity_type;
        $copyActivity->activity_version = $countActivity + 1;
        $copyActivity->activity_version_type = $request->activity_version_type;
        $copyActivity->created_by_user_id = Auth::guard('admin')->user()->id;
        $copyActivity->updated_by_user_id = Null;
        $copyActivity->is_active = 1;
        $copyActivity->is_delete = 0;
        $copyActivity->save();

        $getActivityTrail = new StudyScheduleTrail;
        $getActivityTrail->study_schedule_id = $copyActivity->id;
        $getActivityTrail->study_id = $getActivity->study_id;
        $getActivityTrail->activity_id = $getActivity->activity_id;
        $getActivityTrail->activity_name = $getActivity->activity_name;
        $getActivityTrail->activity_sequence_no = $getActivity->activity_sequence_no;
        $getActivityTrail->responsibility_id = $getActivity->responsibility_id;
        $getActivityTrail->require_days = $getActivity->require_days;
        $getActivityTrail->minimum_days_allowed = $getActivity->minimum_days_allowed;
        $getActivityTrail->maximum_days_allowed = $getActivity->maximum_days_allowed;
        $getActivityTrail->previous_activity_id = $getActivity->previous_activity_id;
        $getActivityTrail->next_activity_id = $getActivity->next_activity_id;
        $getActivityTrail->is_milestone = 0;
        $getActivityTrail->is_start_milestone_activity = $getActivity->is_start_milestone_activity;
        $getActivityTrail->is_end_milestone_activity = $getActivity->is_end_milestone_activity;
        $getActivityTrail->milestone_percentage = $getActivity->milestone_percentage;
        $getActivityTrail->milestone_amount = $getActivity->milestone_amount;
        $getActivityTrail->buffer_days = $getActivity->buffer_days;
        $getActivityTrail->scheduled_start_date = Null;
        $getActivityTrail->actual_start_date = Null;
        $getActivityTrail->actual_start_date_time = Null;
        $getActivityTrail->start_difference = Null;
        $getActivityTrail->scheduled_end_date = Null;
        $getActivityTrail->actual_end_date = Null;
        $getActivityTrail->start_delay_reason_id = Null;
        $getActivityTrail->start_delay_remark = Null;
        $getActivityTrail->end_delay_reason_id = Null;
        $getActivityTrail->end_delay_remark = Null;
        $getActivityTrail->end_difference = Null;
        $getActivityTrail->reference_parent_activity_id = Null;
        $getActivityTrail->remarks = Null;
        $getActivityTrail->is_dependent = $getActivity->is_dependent;
        $getActivityTrail->is_parellel = $getActivity->is_parellel;
        $getActivityTrail->group_no = $getActivity->group_no;
        $getActivityTrail->period_no = $getActivity->period_no;
        $getActivityTrail->is_group_specific = $getActivity->is_group_specific;
        $getActivityTrail->is_period_specific = $getActivity->is_period_specific;
        $getActivityTrail->activity_status = Null;
        $getActivityTrail->action_by_id = $getActivity->action_by_id;
        $getActivityTrail->action_by_role_id = $getActivity->action_by_role_id;
        $getActivityTrail->action = $getActivity->action;
        $getActivityTrail->activity_type = $getActivity->activity_type;
        $getActivityTrail->activity_version_type = $request->activity_version_type;
        $getActivityTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        $getActivityTrail->updated_by_user_id = Null;
        $getActivityTrail->is_active = 1;
        $getActivityTrail->is_delete = 0;
        $getActivityTrail->activity_version = $countActivity + 1;
        $getActivityTrail->save();

        return redirect(route('admin.addStudyScheduleDate', base64_encode($getActivity->study_id)))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule',
                'message' => 'Study activity copied successfully',
            ],
        ]);

    }

    // Set Schedule start & end date auto calculate for version activities
    public function changeScheduleVersionDate(Request $request){

        $firstDate = $request->date;
        $addOne = 1;
        $zero = 0;
        $checkStartHolidayDate = '';
        $checkEndHolidayDate = '';

        $scheduleActivityId = StudySchedule::where('id', $request->id)->where('activity_version', $request->activityVersion)->first();
        $activityDayType = ActivityMaster::where('id', $scheduleActivityId->activity_id)->first();
        
        if ($activityDayType->activity_days == 'WORKING') {
            $checkStartHolidayDate = HolidayMaster::where('holiday_date', $this->convertDt($request->date))->first();
        }

        if ($checkStartHolidayDate != '') {
            $ffDate = Carbon::parse($firstDate)->addDay($addOne);
            $fDate = date('Y-m-d', strtotime($ffDate));         
        } else {
            $fDate = date('Y-m-d', strtotime($firstDate));
        }
        
        if ($activityDayType->activity_days == 'WORKING') {
            $checkFirstStartHolidayDate = HolidayMaster::where('holiday_date', $fDate)->count();
        } else {
            $checkFirstStartHolidayDate = 0;
        }

        if($checkFirstStartHolidayDate == 1){
            $fStartDate = Carbon::parse($fDate)->addDay($addOne); 
        } else {
            $fStartDate = $fDate;
        }

        $days = StudySchedule::where('id', $request->id)->where('activity_version', $request->activityVersion)->where('activity_type', $request->activityType)->first();

        $data = date('Y/m/d H:i:s', strtotime($fStartDate));

        $newDateTime = Carbon::parse($data)->addDay($days->require_days-1);
        if ($activityDayType->activity_days == 'WORKING') {
            $holidayMaster = HolidayMaster::whereBetween('holiday_date', [$data, date('Y/m/d', strtotime($newDateTime->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
        } else {
            $holidayMaster = 0;
        }
        
        $totalDays = $holidayMaster + $days->require_days;
        $newDateTime1 = Carbon::parse($data)->addDay($totalDays-1);

        if ($activityDayType->activity_days == 'WORKING') {
            $holidayMaster1 = HolidayMaster::whereNotIn('holiday_date',[date('Y/m/d' ,strtotime($newDateTime->toDateTimeString()))])->whereBetween('holiday_date', [date('Y/m/d', strtotime($newDateTime->toDateTimeString())), date('Y/m/d', strtotime($newDateTime1->toDateTimeString()))])->where('is_active', 1)->where('is_delete', 0)->count();
        } else {
            $holidayMaster1 = 0;
        }

        $totalDays1 = $holidayMaster1 + $totalDays;
        $newDateTime2 = Carbon::parse($data)->addDay($totalDays1-1);

        if ($activityDayType->activity_days == 'WORKING') {
            $checkEndHolidayDate = HolidayMaster::where('holiday_date', $newDateTime2)->first();
        }

        if ($checkEndHolidayDate != '') {
            $eDate = Carbon::parse($newDateTime2)->addDay($addOne);   
        } else {
            $eDate = $newDateTime2;
        }

        $endDateUpdate = StudySchedule::where('id', $request->id)
                                      ->where('activity_type', $request->activityType)
                                      ->update([
                                            'scheduled_start_date' => $fStartDate,
                                            'scheduled_end_date' => date('Y/m/d', strtotime($eDate->toDateTimeString()))
                                        ]);

        if($days->scheduled_start_date == ''){
            $originalDateUpdate = StudySchedule::where('id', $request->id)
                                                ->where('activity_type', $request->activityType)
                                                ->update([
                                                    'original_schedule_start_date' => $fStartDate,
                                                    'original_schedule_end_date' => date('Y/m/d', strtotime($eDate->toDateTimeString()))
                                                ]);
        }

        return 'true';
    }

    // excel export and download
    public function exportStudySchedule(){
        return Excel::download(new StudyScheduleExport, 'All Schedule Study  Study Management System.xlsx');
    }

}