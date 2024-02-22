<?php

namespace App\Http\Controllers\Admin\Study;

use App\Http\Controllers\Controller;
use App\Models\StudyTrail;
use App\Models\ParaCode;
use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use App\Models\StudySchedule;
use App\Models\Project;
use App\Models\ActivityMaster;
use Carbon\Carbon;
use App\Models\Study;
use DB;
use App\Models\StudyScheduleTrail;
use App\Models\SponsorMaster;
use App\Models\LocationMaster;
use App\Models\ParaMaster;
use App\Models\DrugMaster;
use App\Models\Role;
use App\Models\ClinicalWardMaster;
use App\Models\Admin;
use Auth;
use App\Models\ActivityStatusMaster;
use App\Jobs\SendStartMilestoneEmailToBdUser;
use App\Jobs\SendEndMilestoneEmailToBdUser;
use App\Models\ActivityMetadata;
use App\Models\ActivityMetadataTrail;
use App\Models\StudyActivityMetadata;
use App\Models\StudyActivityMetadataTrail;

class StudyScheduleMonitoringController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    public function studyScheduleMonitoringList(Request $request){

        $filter = 0;
        $ref = '';
        $userId = '';
        $ref = $request->ref;
        $pmId = '';
        $studyStatusName = '';
        $checkStatus = '';
        $status = '';

        $userId = base64_decode($request->id);
        $studyNo = Study::where('is_delete', 0)
                        ->where('is_active', 1)
                        ->whereHas('projectManager', 
                            function($q){
                                $q->where('is_active',1);
                            })
                        ->pluck('id');

        $studyStatus= Study::where('is_delete',0)
                            ->where('is_active',1)
                            ->where('study_status','!=',NULL)
                            ->groupBy('study_status')
                            ->get('study_status');
        
        $activityId = ActivityMaster::where('responsibility',Auth::guard('admin')->user()->role_id)->pluck('id');

        $multipleRoleActivities = explode(',', Auth::guard('admin')->user()->multiple_roles);
        
        $location = LocationMaster::where('id',Auth::guard('admin')->user()->location_id)->pluck('id');

        $query = Study::where('is_delete', 0)
                      ->whereIn('id',$studyNo);       

        $globalPriority = StudySchedule::where('is_delete', 0)
                                        ->where('is_active', 1)
                                        ->whereIn('study_id',$studyNo)
                                        ->whereNotNull('scheduled_start_date')
                                        ->with(['studyNo'])
                                        ->where('activity_name','Draft report to PM')
                                        ->orderBy('scheduled_end_date', 'ASC')
                                        ->orderBy('created_at')
                                        ->get();
        $i = 1;
        if(!is_null($globalPriority)){
            foreach ($globalPriority as $gk => $gv) {
                $gdata = $gv->studyNo->id;
                $global = Study::where('is_active',1)->where('id',$gdata)->update(['global_priority_no' => $i++]);
            }
        }
        if(isset($request->id) && $request->id != ''){
            $filter = 1;
            $studies = Study::where('is_active', 1)->where('is_delete', 0)->where('project_manager', $userId)->get();

            $studyId = array();
            if (!is_null($studies)) {
                foreach ($studies as $sk => $sv) {
                    $studyId[] = $sv->id;
                }
            }
            $query->whereIn('study_id', $studyId);
        }

        if(isset($request->ref) && $request->ref != ''){
            $filter = 1;
            $status = $request->ref;
            $query->where('study_status',$status);
        }

        if(isset($request->pm_id) && $request->pm_id != ''){
            $filter = 1;
            $pmId = $request->pm_id;
            $query->where('project_manager', base64_decode($pmId));
        }
        
        if(isset($request->study_status) && $request->study_status != ''){
            $filter = 1;
            $studyStatusName = $request->study_status;
            $query->where('study_status',$studyStatusName);
        }

        if($request->study_status == 'UPCOMING'){
            $studyStatusName = 'UPCOMING';
        } elseif ($request->study_status == 'COMPLETED') {
            $studyStatusName = 'COMPLETED';
        } elseif ($request->study_status == 'HOLD') {
            $studyStatusName = 'HOLD';
        } else if($status != ''){
            $studyStatusName = $status;
        } else {
            $studyStatusName = 'ONGOING';
            $query->where('study_status','ONGOING');
        }

        if (Auth::guard('admin')->user()->role_id == 3) {

            if(($request->ref == 'ONGOING') || ($request->ref == 'COMPLETED') || ($request->ref == 'UPCOMING')) {

                $studies = $query->with([
                                        'schedule' => function($q){
                                            $q->whereNotNull('scheduled_start_date');
                                        },
                                        'sponsorName' => function($q) {
                                            $q->select('sponsor_name');
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
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->orderBy('global_priority_no', 'ASC')
                                ->get();

            } else {

                $studies = $query->where('project_manager',Auth::guard('admin')->user()->id)
                                   ->with([
                                            'schedule' => function($q){
                                                $q->whereNotNull('scheduled_start_date');
                                            },
                                            'sponsorName' => function($q) {
                                                $q->select('id', 'sponsor_name');
                                            },
                                            'drugDetails' => function($q) {
                                                $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                                  ->with([
                                                    'drugName' => function($q){
                                                        $q->select('id','drug_name');
                                                    },
                                                    'drugDosageName' => function($q){
                                                        $q->select('id','para_value');
                                                    },
                                                    'drugUom' => function($q){
                                                        $q->select('id','para_value');
                                                    },
                                                    'drugType' => function($q){
                                                        $q->select('id', 'type');
                                                    },
                                                ]);
                                            }
                                        ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->orderBy('global_priority_no', 'ASC')
                                ->get();

            }

        } else if (Auth::guard('admin')->user()->role_id == 15 || Auth::guard('admin')->user()->role_id == 13) {

             if(($request->ref == 'ONGOING') || ($request->ref == 'COMPLETED') || ($request->ref == 'UPCOMING')){
                $studies = $query->with([
                                    'schedule' => function($q) use ($multipleRoleActivities){
                                        $q->whereNotNull('scheduled_start_date')
                                        ->whereIn('responsibility_id', $multipleRoleActivities);
                                    },
                                    'sponsorName' => function($q) {
                                        $q->select('id', 'sponsor_name');
                                    },
                                    'drugDetails' => function($q) {
                                        $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                          ->with([
                                            'drugName' => function($q){
                                                $q->select('id','drug_name');
                                            },
                                            'drugDosageName' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugUom' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugType' => function($q){
                                                $q->select('id', 'type');
                                            },
                                        ]);
                                    }
                                ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->get();
            } else{
                $studies = $query->with([
                                    'schedule' => function($q) use ($activityId){
                                        $q->whereNotNull('scheduled_start_date')
                                        ->where(function($q) use ($activityId) {
                                            if(Auth::guard('admin')->user()->is_hod == '0'){
                                                $q->whereIn('activity_id', $activityId);
                                            }
                                        });  
                                    },
                                    'sponsorName' => function($q) {
                                        $q->select('id', 'sponsor_name');
                                    },
                                    'drugDetails' => function($q) {
                                        $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                          ->with([
                                            'drugName' => function($q){
                                                $q->select('id','drug_name');
                                            },
                                            'drugDosageName' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugUom' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugType' => function($q){
                                                $q->select('id', 'type');
                                            },
                                        ]);
                                    }
                                ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->where(function($q) use ($location){
                                    if(Auth::guard('admin')->user()->is_hod == '0'){
                                        $q->whereIn('br_location',$location);
                                    }
                                })
                                ->get();
            }
                                
        } else if (Auth::guard('admin')->user()->role_id == 11 || Auth::guard('admin')->user()->role_id == 12) {
            
             if(($request->ref == 'ONGOING') || ($request->ref == 'COMPLETED') || ($request->ref == 'UPCOMING')){
                $studies = $query->with([
                                    'schedule' => function($q) use ($multipleRoleActivities){
                                        $q->whereNotNull('scheduled_start_date')
                                        ->whereIn('responsibility_id', $multipleRoleActivities);
                                    },
                                    'sponsorName' => function($q) {
                                        $q->select('id', 'sponsor_name');
                                    },
                                    'drugDetails' => function($q) {
                                        $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                          ->with([
                                            'drugName' => function($q){
                                                $q->select('id','drug_name');
                                            },
                                            'drugDosageName' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugUom' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugType' => function($q){
                                                $q->select('id', 'type');
                                            },
                                        ]);
                                    }
                                ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->get();
            } else{
                $studies = $query->with([
                                    'schedule' => function($q) use ($activityId){
                                        $q->whereNotNull('scheduled_start_date')
                                        ->where(function($q) use ($activityId) {
                                            if(Auth::guard('admin')->user()->is_hod == '0'){
                                                $q->whereIn('activity_id', $activityId);
                                            }
                                        });  
                                    },
                                    'sponsorName' => function($q) {
                                        $q->select('id', 'sponsor_name');
                                    },
                                    'drugDetails' => function($q) {
                                        $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                          ->with([
                                            'drugName' => function($q){
                                                $q->select('id','drug_name');
                                            },
                                            'drugDosageName' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugUom' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugType' => function($q){
                                                $q->select('id', 'type');
                                            },
                                        ]);
                                    }
                                ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->where(function($q) use ($location){
                                    if(Auth::guard('admin')->user()->is_hod == '0'){
                                        $q->whereIn('cr_location',$location);
                                    }
                                })
                                ->get();
            } 
        
        } else if ((Auth::guard('admin')->user()->role_id == 7) || (Auth::guard('admin')->user()->role_id == 8) || (Auth::guard('admin')->user()->role_id == 9)) {
            if(($request->ref == 'ONGOING') || ($request->ref == 'COMPLETED') || ($request->ref == 'UPCOMING')){
                $studies = $query->with([
                                    'schedule' => function($q)use($activityId){
                                        $q->whereNotNull('scheduled_start_date')
                                        ->whereIn('activity_id', $activityId);
                                    },
                                    'sponsorName' => function($q) {
                                        $q->select('id', 'sponsor_name');
                                    },
                                    'drugDetails' => function($q) {
                                        $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                          ->with([
                                            'drugName' => function($q){
                                                $q->select('id','drug_name');
                                            },
                                            'drugDosageName' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugUom' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugType' => function($q){
                                                $q->select('id', 'type');
                                            },
                                        ]);
                                    }
                                ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->orderBy('global_priority_no', 'ASC')
                                ->get();
            } else {   
                $studies = $query->with([
                                    'schedule' => function($q)use($activityId){
                                        $q->whereNotNull('scheduled_start_date')
                                        ->where(function($q) use ($activityId) {
                                            if(Auth::guard('admin')->user()->is_hod == '0'){
                                                $q->whereIn('activity_id', $activityId);
                                            }
                                        });  
                                    },
                                    'sponsorName' => function($q) {
                                        $q->select('id', 'sponsor_name');
                                    },
                                    'drugDetails' => function($q) {
                                        $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                          ->with([
                                            'drugName' => function($q){
                                                $q->select('id','drug_name');
                                            },
                                            'drugDosageName' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugUom' => function($q){
                                                $q->select('id','para_value');
                                            },
                                            'drugType' => function($q){
                                                $q->select('id', 'type');
                                            },
                                        ]);
                                    }
                                ])
                                ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                                ->orderBy('global_priority_no', 'ASC')
                                ->get();
            }
        } else {

            $studies = $query->with([
                                'schedule' => function($q){
                                    $q->whereNotNull('scheduled_start_date');
                                },
                                'sponsorName' => function($q) {
                                    $q->select('id', 'sponsor_name');
                                },
                                'drugDetails' => function($q) {
                                    $q->select('id', 'drug_id', 'dosage_form_id', 'uom_id', 'study_id', 'dosage')
                                      ->with([
                                        'drugName' => function($q){
                                            $q->select('id','drug_name');
                                        },
                                        'drugDosageName' => function($q){
                                            $q->select('id','para_value');
                                        },
                                        'drugUom' => function($q){
                                            $q->select('id','para_value');
                                        },
                                        'drugType' => function($q){
                                            $q->select('id', 'type');
                                        },
                                    ]);
                                }
                            ])
                            ->select('id','study_no', 'sponsor', 'study_status', 'study_result','global_priority_no')
                            ->orderBy('global_priority_no', 'ASC')
                            ->get();

        }

        $date = Carbon::now();
        $currentDate = date('Y-m-d', strtotime($date));

        /*$studyStartDate = $this->sales = DB::table('study_schedules')
                ->select(
                    \DB::raw('min(study_schedules.scheduled_start_date) as firstDay'),\DB::raw('max(study_schedules.scheduled_end_date) as lastDay'),\DB::raw('study_id as studyId')
                )
                ->groupBy('study_schedules.study_id')
                ->get();

        $study = Study::where('is_active', 1)->where('is_delete', 0)->get();

        if (!is_null($study)) {
            foreach ($study as $sk => $sv) {
                //$checkStatus = $sv->study_status;
                if ((!is_null($studyStartDate)) && (($sv->study_status != 'COMPLETED') || ($sv->study_status != 'HOLD'))) {
                    foreach ($studyStartDate as $ssk => $ssv) {
                        if($ssv->firstDay < $currentDate && $ssv->lastDay > $currentDate){
                            $study = Study::where('id', $ssv->studyId)->update(['study_status' => 'ONGOING']);
                        } else if ($ssv->firstDay > $currentDate){
                            $study = Study::where('id', $ssv->studyId)->update(['study_status' => 'UPCOMING']);
                        }
                    }
                }
            }
        }*/

               
        return view('admin.study.study_schedule_monitoring.study_schedule_monitoring', compact('filter','studies', 'currentDate', 'ref','globalPriority','studyStatus','studyStatusName'));
    }
    
    public function studyScheduleStatus($id){

        $srNo = 1;
        $studies = StudySchedule::where('study_id', base64_decode($id))
                                ->orderBy('scheduled_start_date', 'ASC')
                                ->with('crActivity', 'brActivity', 'pbActivity', 'rwActivity', 'psActivity')
                                ->get();

        $study = Study::where('id', base64_decode($id))
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

        $date = Carbon::now();
        $currentDate = date('Y-m-d', strtotime($date));

        if (!is_null($studies)) {
            foreach ($studies as $sk => $sv) {

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

        if (Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 3) {
            $activitySchedule = ParaCode::where('para_code','=','ActivityType')
                                        ->with([
                                            'studySchedule'=> function($q) use($id){ 
                                                $q->where('study_id', base64_decode($id))
                                                  ->where('is_active',1)
                                                  ->where('is_delete',0)
                                                  ->orderBy('activity_sequence_no', 'ASC')
                                                  ->orderBy('activity_version', 'ASC')
                                                  ->with([
                                                      'startDelayReason',
                                                      'endDelayReason',
                                                      'activityStatusName'
                                                  ]);
                                                },
                                            'reasons',
                                        ])
                                        ->get();
           
        } else {
            $activitySchedule = ParaCode::where('para_code','=','ActivityType')
                                        ->with([
                                            'studySchedule'=> function($q) use($id){ 
                                                $q->where('study_id', base64_decode($id))
                                                  ->where('is_active',1)
                                                  ->where('is_delete',0)
                                                  ->where('responsibility_id', Auth::guard('admin')->user()->role_id)
                                                  ->with([
                                                      'startDelayReason',
                                                      'endDelayReason',
                                                      'activityStatusName'
                                                  ])
                                                ->orderBy('activity_sequence_no', 'ASC')
                                                ->orderBy('activity_version', 'ASC');
                                                },
                                            'reasons',
                                        ])
                                        ->get();
        }

        return view('admin.study.study_schedule_monitoring.study_schedule_monitoring_details', compact('studies', 'currentDate', 'study', 'activitySchedule', 'srNo'));
    }

    public function addStudyScheduleActivityStatus($id){

        $schedule = StudySchedule::where('id', base64_decode($id))->first();
        $study = Study::where('id', $schedule->study_id)->first();

        return view('admin.study.study_schedule_monitoring.add_study_schedule_activity_status', compact('id', 'schedule', 'study'));    
    }

    public function saveStudyScheduleActivityStatus(Request $request){

        $checkStudy = StudySchedule::where('id', $request->id)->first();

        $actualStartDate = date('Y/m/d', strtotime($request->actual_start_date));
        $actualEndDate = date('Y/m/d', strtotime($request->actual_end_date));
        $scheduleStartDate = date('Y/m/d', strtotime($request->schedule_start_date));
        $scheduleEndDate = date('Y/m/d', strtotime($request->schedule_end_date));
        $currentDate = date('Y/m/d');

        $schedule = StudySchedule::findOrFail($request->id);
        if(($request->actual_start_date != '') && ($request->actual_end_date == '')){
            $schedule->actual_start_date = date('Y-m-d', strtotime($request->actual_start_date));
            $schedule->actual_start_date_time = date('Y-m-d H:i:s');
        }

        if ($request->actual_end_date != '') {
            $schedule->actual_end_date = $request->actual_end_date;
            $schedule->actual_end_date_time = date('Y-m-d H:i:s');
        }

        if (isset($request->start_delay_reason_id) && $request->start_delay_reason_id != '' && $request->start_delay_reason_id != '0') {
            $schedule->start_delay_reason_id = $request->start_delay_reason_id;
        } else if(isset($request->start_delay_reason_id) && $request->start_delay_reason_id == '0') {
            $schedule->start_delay_reason_id = 0;
        } else {
            $schedule->start_delay_reason_id = Null;
        }

        if ($request->end_delay_reason_id != ''  && $request->end_delay_reason_id != '0') {
            $schedule->end_delay_reason_id = $request->end_delay_reason_id;
        } else if(isset($request->end_delay_reason_id) && $request->end_delay_reason_id == '0') {
            $schedule->end_delay_reason_id = 0;
        } else {
            $schedule->end_delay_reason_id = Null;
        }

        if ($request->start_delay_remark != '') {
            $schedule->start_delay_remark = $request->start_delay_remark;
        } else {
            $schedule->start_delay_remark = Null;
        }

        if ($request->end_delay_remark != '') {
            $schedule->end_delay_remark = $request->end_delay_remark;
        } else {
            $schedule->end_delay_remark = Null;
        }

        if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate >= $actualStartDate) && ($scheduleEndDate >= $currentDate)) {
            $schedule->activity_status = "ONGOING";
        } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate < $actualStartDate) && ($scheduleEndDate >= $currentDate)) {
            $schedule->activity_status = "ONGOING";
        } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate <= $actualStartDate) && ($scheduleEndDate < $currentDate)) {
            $schedule->activity_status = "DELAY";
        } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate > $actualStartDate) && ($scheduleEndDate < $currentDate)) {
            $schedule->activity_status = "DELAY";
        } else if (($request->actual_start_date != '') && ($request->actual_end_date != '')) {
            $schedule->activity_status = "COMPLETED";
        }

        $schedule->save();
        
        $checkTrail = StudyScheduleTrail::where('study_schedule_id', $request->id)->first();

        if (!is_null($checkTrail)) {
            if(($request->actual_start_date != '') && ($request->actual_end_date == '')){
                $updateScheduleTrail = StudyScheduleTrail::where('study_schedule_id', $request->id)
                                                          ->where('study_id', $checkStudy->study_id)
                                                          ->where('activity_id', $checkStudy->activity_id)
                                                          ->update([
                                                                'actual_start_date' => $actualStartDate,
                                                                'actual_end_date' => $actualEndDate,
                                                                'start_delay_reason_id' => $schedule->start_delay_reason_id,
                                                                'start_delay_remark' => $request->start_delay_remark,
                                                                'end_delay_remark' => $request->end_delay_remark,
                                                                'actual_start_date_time' => date('Y-m-d H:i:s'),
                                                          ]);
            } else {
                $updateScheduleTrail = StudyScheduleTrail::where('study_schedule_id', $request->id)
                                                          ->where('study_id', $checkStudy->study_id)
                                                          ->where('activity_id', $checkStudy->activity_id)
                                                          ->update([
                                                                'actual_start_date' => $actualStartDate,
                                                                'actual_end_date' => $actualEndDate,
                                                                'start_delay_reason_id' => $schedule->start_delay_reason_id,
                                                                'end_delay_reason_id' => $schedule->end_delay_reason_id,
                                                                'start_delay_remark' => $request->start_delay_remark,
                                                                'end_delay_remark' => $request->end_delay_remark,
                                                                'actual_end_date_time' => date('Y-m-d H:i:s'),
                                                          ]);
            }
        }

        $checkStudyStatus = StudySchedule::where('study_id', $checkStudy->study_id)
                                        ->whereNull('actual_start_date')
                                        ->whereNull('actual_end_date')
                                        ->count();

        if ($checkStudyStatus == 0) {
            $updateStudyStatus = Study::where('id', $checkStudy->study_id)
                                      ->update(['study_status' => 'COMPLETED']);
        }

        $status = ActivityStatusMaster::where('activity_status_code', $schedule->activity_status)->first();

        if ($request->start != '') {
            $startMilestoneActivity = StudySchedule::where('id', $request->id)
                                                   ->where('is_milestone', 1)
                                                   ->where('is_start_milestone_activity', 1)
                                                   ->with([
                                                        'studyNo' => function($q){
                                                            $q->with([
                                                                'projectManager'
                                                            ]);
                                                        }
                                                    ])
                                                   ->first();

            if (!is_null($startMilestoneActivity)) {
                $bdUser = Admin::where('role_id', 14)->get();
                if (!is_null($bdUser)) {
                    foreach ($bdUser as $buk => $buv) {
                        $this->dispatch((new SendStartMilestoneEmailToBdUser($buv->email_id,$buv->name,$startMilestoneActivity->studyNo->study_no,$startMilestoneActivity->activity_name,$startMilestoneActivity->scheduled_start_date,$startMilestoneActivity->actual_start_date,$startMilestoneActivity->actual_start_date_time,$startMilestoneActivity->studyNo->projectManager->name))->delay(10));
                    }
                }

            }
        }

        if ($request->end != '') {
            $endMilestoneActivity = StudySchedule::where('id', $request->id)
                                                   ->where('is_milestone', 1)
                                                   ->where('is_end_milestone_activity', 1)
                                                   ->with([
                                                        'studyNo' => function($q){
                                                            $q->with([
                                                                'projectManager'
                                                            ]);
                                                        }
                                                    ])
                                                   ->first();

            if (!is_null($endMilestoneActivity)) {
                $bdUser = Admin::where('role_id', 14)->get();
                if (!is_null($bdUser)) {
                    foreach ($bdUser as $buk => $buv) {
                        $this->dispatch((new SendEndMilestoneEmailToBdUser($buv->email_id,$buv->name,$endMilestoneActivity->studyNo->study_no,$endMilestoneActivity->activity_name,$endMilestoneActivity->scheduled_end_date,$endMilestoneActivity->actual_end_date,$endMilestoneActivity->actual_end_date_time,$endMilestoneActivity->studyNo->projectManager->name))->delay(10));
                    }
                }

            }
        }

        return $status;

        /*return redirect(route('admin.studyScheduleStatus', base64_encode($request->study_id)))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Study Schedule Activity Status',
                'message' => 'Study schedule activity status successfully added!',
            ],
        ]);*/
    }

    public function studyActivityMonitoringList(Request $request){

        $ref = '';
        $userId = '';
        $filter = 0;
        $status='';
        $studyName = '';
        $activityName = array();
        $startDate = '';
        $endDate = '';
        $scheduleStartDate = '';
        $scheduleEndDate = '';
        $crLocationName = '';
        $brLocationName = '';
        $activityStatusName = '';
        $sponsorName = '';
        $actualStartDate = '';
        $actualEndDate = '';
        $studySubType = '';
        $actualEndStartDate = '';
        $actualEndEndDate = '';
        $projectManagerName = '';
        $ref = $request->ref;
        $userId = base64_decode($request->id);
        $pmId = '';
        $CDisc = '';
        
        $studies = Study::where('is_active', 1)->where('is_delete', 0)->get();
        $activities = ActivityMaster::where('is_active', 1)->where('is_delete', 0)->get();
        $sponsors = SponsorMaster::where('is_active', 1)->where('is_delete', 0)->get();
        $crLocation = LocationMaster::where('location_type', 'CRSITE')->where('is_active', 1)->where('is_delete', 0)->get();
        $brLocation = LocationMaster::where('location_type', 'BRSITE')->where('is_active', 1)->where('is_delete', 0)->get();
        $projectManagers = Admin::whereIn('role_id', ['2', '3'])->where('is_active', 1)->where('is_delete', 0)->get();
        $activityStatusMaster = ActivityStatusMaster::where('is_active', 1)->where('is_delete', 0)->get();

        $crlocationIds = Study::where('cr_location',Auth::guard('admin')->user()->location_id)->get('id')->toArray();
        $brlocationIds = Study::where('br_location',Auth::guard('admin')->user()->location_id)->get('id')->toArray();

        $studySubTypes = ParaCode::where('para_code', 'StudySubType')->where('is_active', 1)->where('is_delete', 0)->get();

        $studyNo = Study::where('is_delete', 0)
                        ->whereHas('projectManager', function($q){
                            $q->where('is_active',1);
                        })
                        ->pluck('id');

        $activityId = ActivityMaster::where('responsibility', Auth::guard('admin')->user()->role_id)->get('id')->toArray();

        $multipleRoleActivities = explode(',', Auth::guard('admin')->user()->multiple_roles); 

        $query = StudySchedule::where('is_active', 1)
                              ->where('is_delete', 0)
                              //->whereIn('study_id',$studyNo)
                              ->where('scheduled_start_date', '!=', '')
                              ->where('scheduled_end_date', '!=', '');
        
        if(isset($request->study_name) && $request->study_name != ''){

            $filter = 1;
            $studyName = $request->study_name;
            $query->where('study_id',$studyName);
        }
        
        if(isset($request->sponsor_id) && $request->sponsor_id != ''){
            $filter = 1;
            $sponsorName = $request->sponsor_id;
            $query->whereHas('studyNo', function($q) use($sponsorName){ $q->with(['sponsorName']); { $q->where('sponsor',$sponsorName);} } );
        }

        if(isset($request->activity_id) && $request->activity_id != ''){
            foreach($request->activity_id as $ak => $av){
                $filter = 1;
                $activityName[] = $av;
            }
            $query->whereIn('activity_id',$activityName);
        }

        if ($request->start_date != '' && $request->end_date != '') {
            $filter = 1;
                $startDate = $request->start_date;
                $endDate = $request->end_date;
            
            $query->where('scheduled_start_date','>=', $this->convertDateTime($startDate))->where('scheduled_start_date','<=', $this->convertDateTime($endDate));
        }

        if($request->schedule_start_date != '' && $request->schedule_end_date != ''){
            $filter = 1;
            $scheduleStartDate = $request->schedule_start_date;
            $scheduleEndDate = $request->schedule_end_date;
            $query->where('scheduled_end_date','>=', $this->convertDateTime($scheduleStartDate))->where('scheduled_end_date','<=', $this->convertDateTime($scheduleEndDate));
        }

        if(isset($request->cr_location) && $request->cr_location != ''){
            $filter = 1;
            $crLocationName = $request->cr_location;
            $query->whereHas('studyNo', function($q) use($crLocationName){ $q->where('cr_location',$crLocationName);});
        }

        if(isset($request->br_location) && $request->br_location != ''){
            $filter = 1;
            $brLocationName = $request->br_location;
            $query->whereHas('studyNo', function($q) use($brLocationName){ $q->where('br_location',$brLocationName);});
        }

        if(isset($request->activity_status) && $request->activity_status != ''){
            $filter = 1;
            $activityStatusName = $request->activity_status;
            $query->where('activity_status',$activityStatusName);
        }

        if(isset($request->id) && $request->id != ''){
            $filter = 1;
            $studies = Study::where('is_active', 1)->where('is_delete', 0)->where('project_manager', $userId)->get();

            $studyId = array();
            if (!is_null($studies)) {
                foreach ($studies as $sk => $sv) {
                    $studyId[] = $sv->id;
                }
            }
            $query->whereIn('study_id', $studyId);
        }
        
        if(isset($request->ref) && $request->ref != ''){
            $filter = 1;
            $status = $request->ref;
            if (Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 3 || Auth::guard('admin')->user()->role_id == 4 || Auth::guard('admin')->user()->role_id == 5 || Auth::guard('admin')->user()->role_id == 6 || Auth::guard('admin')->user()->role_id == 10 || Auth::guard('admin')->user()->role_id == 14) {
                $query->whereHas('studyNo', function($q) use($status){ $q->where('activity_status',$status);});
            } elseif(Auth::guard('admin')->user()->role_id == 11 || Auth::guard('admin')->user()->role_id == 12){
                $query->whereIn('activity_id',$activityId)->whereHas('studyNo', function($q) use($status){ $q->where('activity_status',$status)->where('cr_location',Auth::guard('admin')->user()->location_id);});
            } elseif(Auth::guard('admin')->user()->role_id == 13 || Auth::guard('admin')->user()->role_id == 15){
                $query->whereIn('activity_id',$activityId)->whereHas('studyNo', function($q) use($status){ $q->where('activity_status',$status)->where('br_location',Auth::guard('admin')->user()->location_id);});
            } else {
                $query->whereIn('activity_id',$activityId)->whereHas('studyNo', function($q) use($status){ $q->where('activity_status',$status);});
            }
        }

        if(isset($request->pm_id) && $request->pm_id != ''){
            $filter = 1;
            $pmId = $request->pm_id;
            $query->whereHas('studyNo', function($q) use($pmId){ $q->where('project_manager', base64_decode($pmId));});
        }

        if($request->actual_start_date != '' && $request->actual_end_date != ''){
            $filter = 1;
            $actualStartDate = $request->actual_start_date;
            $actualEndDate = $request->actual_end_date;
            $query->where('actual_start_date','>=', $this->convertDateTime($actualStartDate))->where('actual_start_date','<=', $this->convertDateTime($actualEndDate));
        }

        if($request->actual_end_start_date != '' && $request->actual_end_end_date != ''){
            $filter = 1;
            $actualEndStartDate = $request->actual_end_start_date;
            $actualEndEndDate = $request->actual_end_end_date;
            $query->where('actual_end_date','>=', $this->convertDateTime($actualEndStartDate))->where('actual_end_date','<=', $this->convertDateTime($actualEndEndDate));
        }

        if(isset($request->study_sub_type) && $request->study_sub_type != ''){
            $filter = 1;
            $studySubType = $request->study_sub_type;
            $query->whereHas('studyNo', function($q) use($studySubType){ $q->where('study_sub_type',$studySubType);});
        }

        if(isset($request->project_manager) && $request->project_manager != ''){
            $filter = 1;
            $projectManagerName = $request->project_manager;
            $query->whereHas('studyNo', function($q) use($projectManagerName){ $q->where('project_manager',$projectManagerName);});
        }

        if(isset($request->cdisc) && $request->cdisc != ''){
            $filter = 1;
            $CDisc = $request->cdisc;
            $query->whereHas('studyNo', function($q) use($CDisc){ $q->where('cdisc_require', $CDisc);});
        }

        if(($request->refPreStatus == 'ONGOING') || ($request->refPostStatus == 'ONGOING')){
            $activityStatusName = 'ONGOING';
        } else if(($request->refPreStatus == 'COMPLETED') || ($request->refPostStatus == 'COMPLETED')){
            $activityStatusName = 'COMPLETED';
        } else if(($request->refPreStatus == 'DELAY') || ($request->refPostStatus == 'DELAY')){
            $activityStatusName = 'DELAY';
        } else if(($request->refPreStatus == 'UPCOMING') || ($request->refPostStatus == 'UPCOMING')){
            $activityStatusName = 'UPCOMING';
        }

        if($request->refPreStatus != '') {

            $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 
                                    'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                    ->with([
                                        'activityName' => function($q) {
                                            $q->select('id', 'activity_status', 'activity_status_code');
                                        },
                                        'studyNo' => function($q) { 
                                            $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(
                                                [
                                                    'sponsorName' => function($q) {
                                                        $q->select('id', 'sponsor_name');
                                                    }, 
                                                    'projectManager' => function($q) {
                                                        $q->select('id', 'name');
                                                    }, 
                                                    'crLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    }, 
                                                    'brLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    },
                                                    'studyType' => function($q) {
                                                        $q->select('id', 'para_value');
                                                    },
                                                    'studySubTypeName' => function($q) {
                                                        $q->select('id', 'para_value', 'para_code');
                                                    },
                                                    'studyRegulatories' => function($q) {
                                                        $q->select('id', 'project_id', 'regulatory_submission')
                                                          ->with([
                                                            'regulatorySubmission' => function($q) {
                                                                $q->select('id', 'para_value');
                                                            }
                                                        ]);
                                                    }
                                                ]
                                            ); 
                                        },
                                        'startDelayReason' => function($q){
                                            $q->select('id', 'start_delay_remark');
                                        },
                                        'endDelayReason' => function($q){
                                            $q->select('id', 'end_delay_remark');
                                        },
                                    ])
                                    ->where('activity_type',123)
                                    ->where('activity_status',$request->refPreStatus)
                                    ->orderBy('scheduled_start_date', 'ASC')
                                    ->get();

        } else if(($request->refPostStatus != '') && ((Auth::guard('admin')->user()->role_id == '11') || (Auth::guard('admin')->user()->role_id == '12'))){
            $crLocationName = Auth::guard('admin')->user()->location_id;
            $brLocationName = Auth::guard('admin')->user()->location_id;
            $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 
                                    'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                    ->with([
                                        'activityName' => function($q) {
                                            $q->select('id', 'activity_status', 'activity_status_code');
                                        },
                                        'studyNo' => function($q) { 
                                            $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(
                                                [
                                                    'sponsorName' => function($q) {
                                                        $q->select('id', 'sponsor_name');
                                                    }, 
                                                    'projectManager' => function($q) {
                                                        $q->select('id', 'name');
                                                    }, 
                                                    'crLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    }, 
                                                    'brLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    },
                                                    'studyType' => function($q) {
                                                        $q->select('id', 'para_value');
                                                    },
                                                    'studySubTypeName' => function($q) {
                                                        $q->select('id', 'para_value', 'para_code');
                                                    },
                                                    'studyRegulatories' => function($q) {
                                                        $q->select('id', 'project_id', 'regulatory_submission')
                                                          ->with([
                                                            'regulatorySubmission' => function($q) {
                                                                $q->select('id', 'para_value');
                                                            }
                                                        ]);
                                                    }
                                                ]
                                            ); 
                                        },
                                        'startDelayReason' => function($q){
                                            $q->select('id', 'start_delay_remark');
                                        },
                                        'endDelayReason' => function($q){
                                            $q->select('id', 'end_delay_remark');
                                        },
                                    ])
                                    ->where(function ($q) use ($activityId, $crlocationIds, $multipleRoleActivities){
                                        if(Auth::guard('admin')->user()->is_hod != '1'){
                                            $q->whereIn('activity_id',$activityId)
                                            ->whereIn('study_id',$crlocationIds);
                                        } elseif((Auth::guard('admin')->user()->role_id == '12') && (Auth::guard('admin')->user()->is_hod == '1')){
                                            $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                        } elseif ((Auth::guard('admin')->user()->role_id == '11') && (Auth::guard('admin')->user()->is_hod == '1')){
                                            $q->whereIn('activity_id',$activityId);
                                        }
                                    })
                                    ->where('activity_status',$request->refPostStatus)
                                    ->orderBy('scheduled_start_date', 'ASC')
                                    ->get();

        } else if(($request->refPostStatus != '') && ((Auth::guard('admin')->user()->role_id == '15') || (Auth::guard('admin')->user()->role_id == '13'))){
            $crLocationName = Auth::guard('admin')->user()->location_id;
            $brLocationName = Auth::guard('admin')->user()->location_id;
            $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 
                                    'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                    ->with([
                                        'activityName' => function($q) {
                                            $q->select('id', 'activity_status', 'activity_status_code');
                                        },
                                        'studyNo' => function($q) { 
                                            $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(
                                                [
                                                    'sponsorName' => function($q) {
                                                        $q->select('id', 'sponsor_name');
                                                    }, 
                                                    'projectManager' => function($q) {
                                                        $q->select('id', 'name');
                                                    }, 
                                                    'crLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    }, 
                                                    'brLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    },
                                                    'studyType' => function($q) {
                                                        $q->select('id', 'para_value');
                                                    },
                                                    'studySubTypeName' => function($q) {
                                                        $q->select('id', 'para_value', 'para_code');
                                                    },
                                                    'studyRegulatories' => function($q) {
                                                        $q->select('id', 'project_id', 'regulatory_submission')
                                                          ->with([
                                                            'regulatorySubmission' => function($q) {
                                                                $q->select('id', 'para_value');
                                                            }
                                                        ]);
                                                    }
                                                ]
                                            ); 
                                        },
                                        'startDelayReason' => function($q){
                                            $q->select('id', 'start_delay_remark');
                                        },
                                        'endDelayReason' => function($q){
                                            $q->select('id', 'end_delay_remark');
                                        },
                                    ])
                                    ->where(function ($q) use ($activityId, $brlocationIds, $multipleRoleActivities){
                                        if(Auth::guard('admin')->user()->is_hod != '1'){
                                            $q->whereIn('activity_id', $activityId)
                                            ->whereIn('study_id',$brlocationIds);
                                        } elseif(Auth::guard('admin')->user()->is_hod == '1'){
                                            $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                        }
                                    })
                                    ->where('activity_status',$request->refPostStatus)
                                    ->orderBy('scheduled_start_date', 'ASC')
                                    ->get();

        } else if(($request->refPostStatus != '') && ((Auth::guard('admin')->user()->role_id == '7') || (Auth::guard('admin')->user()->role_id == '8') || (Auth::guard('admin')->user()->role_id == '9'))){
                $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 
                                        'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                        ->with([
                                            'activityName' => function($q) {
                                                $q->select('id', 'activity_status', 'activity_status_code');
                                            },
                                            'studyNo' => function($q) { 
                                                $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                                  ->where('is_active', 1)
                                                  ->where('is_delete', 0)
                                                  ->with(
                                                    [
                                                        'sponsorName' => function($q) {
                                                            $q->select('id', 'sponsor_name');
                                                        }, 
                                                        'projectManager' => function($q) {
                                                            $q->select('id', 'name');
                                                        }, 
                                                        'crLocationName' => function($q) {
                                                            $q->select('id', 'location_name');
                                                        }, 
                                                        'brLocationName' => function($q) {
                                                            $q->select('id', 'location_name');
                                                        },
                                                        'studyType' => function($q) {
                                                            $q->select('id', 'para_value');
                                                        },
                                                        'studySubTypeName' => function($q) {
                                                            $q->select('id', 'para_value', 'para_code');
                                                        },
                                                        'studyRegulatories' => function($q) {
                                                            $q->select('id', 'project_id', 'regulatory_submission')
                                                              ->with([
                                                                'regulatorySubmission' => function($q) {
                                                                    $q->select('id', 'para_value');
                                                                }
                                                            ]);
                                                        }
                                                    ]
                                                ); 
                                            },
                                            'startDelayReason' => function($q){
                                                $q->select('id', 'start_delay_remark');
                                            },
                                            'endDelayReason' => function($q){
                                                $q->select('id', 'end_delay_remark');
                                            },
                                        ])
                                        ->whereIn('activity_id', $activityId)
                                        ->where('activity_status',$request->refPostStatus)
                                        ->orderBy('scheduled_start_date', 'ASC')
                                        ->get();

        } else if($request->refPostStatus != ''){
            $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 
                                    'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                    ->with([
                                        'activityName' => function($q) {
                                            $q->select('id', 'activity_status', 'activity_status_code');
                                        },
                                        'studyNo' => function($q) { 
                                            $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(
                                                [
                                                    'sponsorName' => function($q) {
                                                        $q->select('id', 'sponsor_name');
                                                    }, 
                                                    'projectManager' => function($q) {
                                                        $q->select('id', 'name');
                                                    }, 
                                                    'crLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    }, 
                                                    'brLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    },
                                                    'studyType' => function($q) {
                                                        $q->select('id', 'para_value');
                                                    },
                                                    'studySubTypeName' => function($q) {
                                                        $q->select('id', 'para_value', 'para_code');
                                                    },
                                                    'studyRegulatories' => function($q) {
                                                        $q->select('id', 'project_id', 'regulatory_submission')
                                                          ->with([
                                                            'regulatorySubmission' => function($q) {
                                                                $q->select('id', 'para_value');
                                                            }
                                                        ]);
                                                    }
                                                ]
                                            ); 
                                        },
                                        'startDelayReason' => function($q){
                                            $q->select('id', 'start_delay_remark');
                                        },
                                        'endDelayReason' => function($q){
                                            $q->select('id', 'end_delay_remark');
                                        },
                                    ])
                                    ->whereIn('activity_type',[113,114,115,116])
                                    ->where('activity_status',$request->refPostStatus)
                                    ->orderBy('scheduled_start_date', 'ASC')
                                    ->get();

        } else if($filter == 1 || $request->ref != ''){
            $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 
                                    'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                    ->with([
                                        'activityName' => function($q) {
                                            $q->select('id', 'activity_status', 'activity_status_code');
                                        },
                                        'studyNo' => function($q) { 
                                            $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(
                                                [
                                                    'sponsorName' => function($q) {
                                                        $q->select('id', 'sponsor_name');
                                                    }, 
                                                    'projectManager' => function($q) {
                                                        $q->select('id', 'name');
                                                    }, 
                                                    'crLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    }, 
                                                    'brLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    },
                                                    'studyType' => function($q) {
                                                        $q->select('id', 'para_value');
                                                    },
                                                    'studySubTypeName' => function($q) {
                                                        $q->select('id', 'para_value', 'para_code');
                                                    },
                                                    'studyRegulatories' => function($q) {
                                                        $q->select('id', 'project_id', 'regulatory_submission')
                                                          ->with([
                                                            'regulatorySubmission' => function($q) {
                                                                $q->select('id', 'para_value');
                                                            }
                                                        ]);
                                                    }
                                                ]
                                            ); 
                                        },
                                        'startDelayReason' => function($q){
                                            $q->select('id', 'start_delay_remark');
                                        },
                                        'endDelayReason' => function($q){
                                            $q->select('id', 'end_delay_remark');
                                        },
                                    ])
                                    ->whereIn('activity_type',[113,114,115,116,123])
                                    ->orderBy('scheduled_start_date', 'ASC')
                                    ->get();

        } else {
            $startDate = date('d-m-Y');
            $endDate = date('d-m-Y');
            $activityStatus = $query->select('id', 'study_id', 'activity_id', 'activity_status', 'activity_name', 'group_no', 'period_no', 'scheduled_start_date', 
                                            'actual_start_date', 'scheduled_end_date', 'actual_end_date', 'start_delay_remark', 'end_delay_remark', 'start_delay_reason_id', 'end_delay_reason_id', 'actual_start_date_time', 'actual_end_date_time', 'activity_version_type', 'activity_version')
                                    ->with([
                                        'activityName' => function($q) {
                                            $q->select('id', 'activity_status', 'activity_status_code');
                                        },
                                        'studyNo' => function($q) { 
                                            $q->select('id', 'project_manager', 'sponsor', 'cr_location', 'br_location', 'study_no', 'study_type', 'study_sub_type')
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(
                                                [
                                                    'sponsorName' => function($q) {
                                                        $q->select('id', 'sponsor_name');
                                                    }, 
                                                    'projectManager' => function($q) {
                                                        $q->select('id', 'name');
                                                    }, 
                                                    'crLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    }, 
                                                    'brLocationName' => function($q) {
                                                        $q->select('id', 'location_name');
                                                    },
                                                    'studyType' => function($q) {
                                                        $q->select('id', 'para_value');
                                                    },
                                                    'studySubTypeName' => function($q) {
                                                        $q->select('id', 'para_value', 'para_code');
                                                    },
                                                    'studyRegulatories' => function($q) {
                                                        $q->select('id', 'project_id', 'regulatory_submission')
                                                          ->with([
                                                            'regulatorySubmission' => function($q) {
                                                                $q->select('id', 'para_value');
                                                            }
                                                        ]);
                                                    }
                                                ]
                                            ); 
                                        },
                                        'startDelayReason' => function($q){
                                            $q->select('id', 'start_delay_remark');
                                        },
                                        'endDelayReason' => function($q){
                                            $q->select('id', 'end_delay_remark');
                                        },
                                    ])
                                    ->where('scheduled_start_date', date('Y-m-d'))
                                    ->get();
        }

        $pmId = Auth::guard('admin')->user()->id;
        $activityId = ActivityMaster::where('responsibility',Auth::guard('admin')->user()->role_id)->pluck('id');
        
        if(Auth::guard('admin')->user()->role_id == 5 || Auth::guard('admin')->user()->role_id == 7 || Auth::guard('admin')->user()->role_id == 8 || Auth::guard('admin')->user()->role_id == 10 || Auth::guard('admin')->user()->role_id == 11 || Auth::guard('admin')->user()->role_id == 12 || Auth::guard('admin')->user()->role_id == 13){
            $projectManagerActivities = StudySchedule::where('is_active', 1)
                                                     ->where('is_delete', 0)
                                                     ->where('scheduled_start_date', '!=', '')
                                                     ->whereIn('activity_id',$activityId)
                                                     ->pluck('id')
                                                     ->toArray();
        } else if($request->refPreStatus != ''){
           
            $projectManagerActivities = StudySchedule::where('is_active', 1)
                                                     ->where('is_delete', 0)
                                                     ->where('scheduled_start_date', '!=', '')
                                                     ->whereHas(
                                                        'studyNo', function($q) use($pmId){
                                                            $q->where('project_manager',$pmId);
                                                        })
                                                     ->where('activity_type',123)
                                                     ->where('activity_status',$request->refPreStatus)
                                                     ->pluck('id')
                                                     ->toArray();

        } else if($request->refPostStatus != ''){
           
            $projectManagerActivities = StudySchedule::where('is_active', 1)
                                                     ->where('is_delete', 0)
                                                     ->where('scheduled_start_date', '!=', '')
                                                     ->whereHas(
                                                        'studyNo', function($q) use($pmId){
                                                            $q->where('project_manager',$pmId);
                                                        })
                                                     ->whereIn('activity_type',[113,114,115,116])
                                                     ->where('activity_status',$request->refPostStatus)
                                                     ->pluck('id')
                                                     ->toArray();
            
        } else {
            $projectManagerActivities = StudySchedule::where('is_active', 1)
                                                     ->where('is_delete', 0)
                                                     ->where('scheduled_start_date', '!=', '')
                                                     ->whereHas(
                                                        'studyNo', function($q) use($pmId){
                                                            $q->where('project_manager',$pmId);
                                                        })
                                                     ->pluck('id')
                                                     ->toArray();
        }

        return view('admin.study.study_schedule_monitoring.study_activity_monitoring_list', compact('activityStatus', 'ref', 'filter', 'status', 'studies', 'studyName', 'activities', 'activityName', 'startDate', 'endDate', 'scheduleStartDate', 'scheduleEndDate', 'sponsors', 'crLocation', 'crLocationName', 'brLocation', 'brLocationName', 'activityStatusName', 'sponsorName', 'actualStartDate', 'actualEndDate', 'actualEndStartDate', 'actualEndEndDate', 'projectManagers', 'projectManagerName', 'projectManagerActivities','activityStatusMaster', 'studySubTypes', 'studySubType', 'CDisc'));
    }

    public function studyDetailsModal(Request $request){

        /*$studyDetails = Study::where('id', $request->id)
                                ->with([
                                        'sponsorName',
                                        'studyType',
                                        'priorityName',
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
        
        return view('admin.study.study_details_modal', compact('studyDetails'));*/

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

        $study = Study::where('id', $request->id)
                      ->with([
                            'studyScope', 
                            'studyRegulatory',
                            'drugDetails'
                        ])
                      ->withCount(['drugDetails'])
                      ->first();

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

        return view('admin.study.study.study_details_modal', compact('sponsors', 'dosageform', 'scope', 'studyDesign', 'studySubType', 'subjectType', 'blindingStatus', 'crLocation', 'dosage', 'uom', 'regulatorySubmission', 'studyType', 'complexity', 'studyCondition', 'priority', 'brLocation', 'study', 'scopeId', 'regulatoryId', 'principle', 'bioanalytical', 'drug', 'clinicalWordLocation', 'projectManager'));
    }

    // open studyScheduleActualStartDateModal
    public function studyScheduleActualStartDateModal($id) {
    
        $studySheduleStartDate = StudySchedule::where('id', $id)
                                              ->where('is_active', 1)
                                              ->where('is_delete', 0)
                                              ->with(['startDelayReasons' =>function($q){
                                                    $q->where('is_active',1)
                                                      ->where('is_delete',0);
                                                }])
                                              ->first();

        $activityMetaDataActualStartDate = ActivityMetadata::where('activity_id',$studySheduleStartDate->activity_id)
                                                           ->where('is_activity','S')
                                                           ->where('is_active',1)
                                                           ->where('is_delete',0)
                                                           ->with([
                                                                'controlName' => function($q){
                                                                    $q->select('id', 'control_name', 'control_type', 'data_type');
                                                                },
                                                                'studyActivityMetadata' => function($q) use ($studySheduleStartDate){
                                                                    $q->select('id', 'study_schedule_id', 'activity_meta_id', 'actual_value')
                                                                      ->where('study_schedule_id', $studySheduleStartDate->id)
                                                                      ->where('is_active', 1)
                                                                      ->where('is_delete', 0);
                                                                }
                                                            ])
                                                           ->get();

        return view('admin.study.study_schedule_monitoring.study_schedule_actual_start_date_modal',compact('studySheduleStartDate', 'activityMetaDataActualStartDate')) ;      
    }

    // save saveStudyScheduleActualStartDateModal
    public function saveStudyScheduleActualStartDateModal(Request $request) {

        $actualStartDate = date('Y/m/d', strtotime($request->actual_start_date));
        $actualEndDate = date('Y/m/d', strtotime($request->actual_end_date));
        $scheduleStartDate = date('Y/m/d', strtotime($request->schedule_start_date));
        $scheduleEndDate = date('Y/m/d', strtotime($request->schedule_end_date));
        $currentDate = date('Y/m/d');
        $scheduleStatus = '';
        $actualstartDate = '';

        $saveStartDate = StudySchedule::where('id', $request->id)->where('is_active', 1)->where('is_delete', 0)->first();
        
        if(!is_null($saveStartDate)) {
            if((isset($request->actual_start_date)) && ($request->actual_start_date != '')) {
                $actualStart = date('d-m-Y', strtotime($request->actual_start_date));   
                $saveStartDate->actual_start_date = $this->convertDt($actualStart);
                $saveStartDate->actual_start_date_time = date('Y-m-d H:i:s');
            }

            if($saveStartDate->actual_start_date <= $saveStartDate->scheduled_start_date){
                $saveStartDate->start_delay_reason_id = null;
                $saveStartDate->start_delay_remark = null;
            } else if($saveStartDate->actual_start_date > $saveStartDate->scheduled_start_date){
                if(($request->start_delay_reason_id != '0')) {
                    $saveStartDate->start_delay_reason_id = $request->start_delay_reason_id;
                    $saveStartDate->start_delay_remark = null;
                } else if($request->start_delay_reason_id == '0'){
                    $saveStartDate->start_delay_reason_id = $request->start_delay_reason_id;
                    $saveStartDate->start_delay_remark = $request->start_delay_remark;
                } 
            }
            
            if (Auth::guard('admin')->user()->id != '') {
                $saveStartDate->updated_by_user_id = Auth::guard('admin')->user()->id;
            }

            if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate >= $actualStartDate) && ($scheduleEndDate >= $currentDate)) {
                $saveStartDate->activity_status = "ONGOING";
            } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate < $actualStartDate) && ($scheduleEndDate >= $currentDate)) {
                $saveStartDate->activity_status = "ONGOING";
            } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate <= $actualStartDate) && ($scheduleEndDate < $currentDate)) {
                $saveStartDate->activity_status = "DELAY";
            } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate > $actualStartDate) && ($scheduleEndDate < $currentDate)) {
                $saveStartDate->activity_status = "DELAY";
            } else if (($request->actual_start_date != '') && ($request->actual_end_date != '')) {
                $saveStartDate->activity_status = "COMPLETED";
            }
            $scheduleStatus = $saveStartDate->activity_status;  
            $actualstartDate = date('d M Y', strtotime($saveStartDate->actual_start_date));  
            $saveStartDate->save();
        }

        $saveStartDateTrail = StudyScheduleTrail::where('study_schedule_id', $request->id)
                                               ->where('is_active', 1)
                                               ->where('is_delete', 0)
                                               ->orderBy('id', 'DESC')
                                               ->first();

        if(!is_null($saveStartDateTrail)) {
            if((isset($request->actual_start_date)) && ($request->actual_start_date != '')) {
                $actualTrailStart = date('d-m-Y', strtotime($request->actual_start_date));     
                $saveStartDateTrail->actual_start_date = $this->convertDt($actualTrailStart);
                $saveStartDateTrail->actual_start_date_time = date('Y-m-d H:i:s');
            }

            if($saveStartDateTrail->actual_start_date <= $saveStartDateTrail->scheduled_start_date){
                $saveStartDateTrail->start_delay_reason_id = null;
                $saveStartDateTrail->start_delay_remark = null;
            } else if($saveStartDateTrail->actual_start_date > $saveStartDateTrail->scheduled_start_date){
                if(($request->start_delay_reason_id != '0')) {
                    $saveStartDateTrail->start_delay_reason_id = $request->start_delay_reason_id;
                    $saveStartDateTrail->start_delay_remark = null;
                } else if($request->start_delay_reason_id == '0') {
                    $saveStartDateTrail->start_delay_reason_id = $request->start_delay_reason_id;
                    $saveStartDateTrail->start_delay_remark = $request->start_delay_remark;
                } 
            }

            if (Auth::guard('admin')->user()->id != '') {
                $saveStartDateTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
            }
         
            $saveStartDateTrail->save();
        }

        $activityTypes = ['text', 'textarea', 'date', 'datetime', 'radio', 'select', 'checkbox', 'file'];

        if(!is_null($activityTypes)){
            foreach ($activityTypes as $type) {     
                if($request->has($type)) {
                    if($type == 'file') {
                        foreach ($request->file('file') as $amcvId => $file) {
                            $fileName = $this->uploadImage($file, 'activity_metadata/actual_start');

                            $activityMetadataFileExists = StudyActivityMetadata::where('study_schedule_id', $request->id)
                                                                                ->where('activity_meta_id', $amcvId)
                                                                                ->where('is_active', 1)
                                                                                ->where('is_delete', 0)
                                                                                ->whereHas('activityMetadata', function($q){
                                                                                    $q->where('is_activity', 'S');
                                                                                })
                                                                                ->first();

                            if (!is_null($activityMetadataFileExists)) {
                                $activityMetadataFileExists->update(['actual_value' => $fileName, 'updated_by_user_id' => Auth::guard('admin')->user()->id]);

                                $saveStudyActivityMetadataFileTrail = new StudyActivityMetadataTrail();
                                $saveStudyActivityMetadataFileTrail->study_activity_metadata_id = $activityMetadataFileExists->id;
                                $saveStudyActivityMetadataFileTrail->study_schedule_id = $request->id;
                                $saveStudyActivityMetadataFileTrail->activity_meta_id = $amcvId;
                                $saveStudyActivityMetadataFileTrail->actual_value = $fileName;
                                $saveStudyActivityMetadataFileTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                                $saveStudyActivityMetadataFileTrail->save();
                            } else {    
                                $saveActivityMetadataFile = new StudyActivityMetadata();
                                $saveActivityMetadataFile->study_schedule_id = $request->id;
                                $saveActivityMetadataFile->activity_meta_id = $amcvId;
                                $saveActivityMetadataFile->actual_value = $fileName;
                                $saveActivityMetadataFile->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveActivityMetadataFile->save();

                                $saveStudyActivityMetadataFileTrail = new StudyActivityMetadataTrail();
                                $saveStudyActivityMetadataFileTrail->study_activity_metadata_id = $saveActivityMetadataFile->id;
                                $saveStudyActivityMetadataFileTrail->study_schedule_id = $request->id;
                                $saveStudyActivityMetadataFileTrail->activity_meta_id = $amcvId;
                                $saveStudyActivityMetadataFileTrail->actual_value = $fileName;
                                $saveStudyActivityMetadataFileTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveStudyActivityMetadataFileTrail->save();
                            }
                        }
                    }

                    $data = $request->input($type, []);
                    
                    if(!is_null($data)){
                        foreach ($data as $key => $value) {
                            $actualValue = null;
                            if ($value != '') {
                                if($type == 'datetime') {
                                    $dateTimeFormat = date('d M Y H:i', strtotime($value));
                                    $actualValue = $dateTimeFormat;
                                } else if (is_array($value)) {
                                    $values = [];
                                    if (!is_null($value)) {
                                        foreach ($value as $k => $v) {
                                            if (!empty($v)) {
                                                array_push($values, $v);
                                            }
                                        }
                                    }
                                    $actualValue = (!empty($values)) ? implode('|', $values) : null;
                                } else {
                                    $actualValue = $value;
                                }
                            }

                            $activityMetadataExists = StudyActivityMetadata::where('study_schedule_id', $request->id)
                                                                           ->where('activity_meta_id', $key)
                                                                           ->where('is_active', 1)
                                                                           ->where('is_delete', 0)
                                                                           ->whereHas('activityMetadata', function($q){
                                                                               $q->where('is_activity', 'S');
                                                                            })
                                                                           ->first();

                            if (!is_null($activityMetadataExists)) {
                                $activityMetadataExists->update(['actual_value' => $actualValue, 'updated_by_user_id' => Auth::guard('admin')->user()->id]);

                                $saveStudyActivityMetadataTrail = new StudyActivityMetadataTrail();
                                $saveStudyActivityMetadataTrail->study_activity_metadata_id = $activityMetadataExists->id;
                                $saveStudyActivityMetadataTrail->study_schedule_id = $request->id;
                                $saveStudyActivityMetadataTrail->activity_meta_id = $key;
                                $saveStudyActivityMetadataTrail->actual_value = $actualValue;
                                $saveStudyActivityMetadataTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                                $saveStudyActivityMetadataTrail->save();
                            } else {
                                $saveActivityMetadata = new StudyActivityMetadata();
                                $saveActivityMetadata->study_schedule_id = $request->id;
                                $saveActivityMetadata->activity_meta_id = $key;
                                $saveActivityMetadata->actual_value = $actualValue;
                                $saveActivityMetadata->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveActivityMetadata->save();

                                $saveActivityMetadataTrail = new StudyActivityMetadataTrail();
                                $saveActivityMetadataTrail->study_activity_metadata_id = $saveActivityMetadata->id;
                                $saveActivityMetadataTrail->study_schedule_id = $request->id;
                                $saveActivityMetadataTrail->activity_meta_id = $key;
                                $saveActivityMetadataTrail->actual_value = $actualValue;
                                $saveActivityMetadataTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveActivityMetadataTrail->save();
                            }
                        }
                    }
                }
            }
        }

        if ($saveStartDate->id != '') {

            $startMilestoneActivity = StudySchedule::where('id', $request->id)
                                                   ->where('is_milestone', 1)
                                                   ->where('is_start_milestone_activity', 1)
                                                   ->with([
                                                        'studyNo' => function($q){
                                                            $q->with([
                                                                'projectManager'
                                                            ]);
                                                        }
                                                    ])
                                                   ->first();

            if (!is_null($startMilestoneActivity)) {
                $bdUser = Admin::where('role_id', 14)->get();
                if (!is_null($bdUser)) {
                    foreach ($bdUser as $buk => $buv) {
                        $this->dispatch((new SendStartMilestoneEmailToBdUser($buv->email_id,$buv->name,$startMilestoneActivity->studyNo->study_no,$startMilestoneActivity->activity_name,$startMilestoneActivity->scheduled_start_date,$startMilestoneActivity->actual_start_date,$startMilestoneActivity->actual_start_date_time,$startMilestoneActivity->studyNo->projectManager->name))->delay(10));
                    }
                }

            }
        }
        return response()->json(['success' => 'true', 'scheduleStatus' => $scheduleStatus, 'actualStartDate' => $actualstartDate]);
    }

    // open studyScheduleActualEndDateModal
    public function studyScheduleActualEndDateModal($id) {
    
        $studyScheduleEndDate = StudySchedule::where('id', $id)
                                             ->where('is_active', 1)
                                             ->where('is_delete', 0)
                                             ->with(['endDelayReasons' =>function($q){
                                                    $q->where('is_active',1)
                                                      ->where('is_delete',0);
                                                }])
                                              ->first();


        $activityMetaDataActualEndDate = ActivityMetadata::where('activity_id',$studyScheduleEndDate->activity_id)
                                                         ->where('is_activity','E')
                                                         ->where('is_active',1)
                                                         ->where('is_delete',0)
                                                         ->with([
                                                                'controlName' => function($q){
                                                                    $q->select('id', 'control_name', 'control_type', 'data_type');
                                                                },
                                                                'studyActivityMetadata' => function($q) use ($studyScheduleEndDate){
                                                                    $q->select('id', 'study_schedule_id', 'activity_meta_id', 'actual_value')
                                                                      ->where('study_schedule_id', $studyScheduleEndDate->id)
                                                                      ->where('is_active', 1)
                                                                      ->where('is_delete', 0);
                                                                }
                                                            ])                                                           
                                                         ->get();

        return view('admin.study.study_schedule_monitoring.study_schedule_actual_end_date_modal',compact('studyScheduleEndDate','activityMetaDataActualEndDate')) ;      
    }

    // save saveStudyScheduleActualEndDateModal
    public function saveStudyScheduleActualEndDateModal(Request $request) { 

        $actualStartDate = date('Y/m/d', strtotime($request->actual_start_date));
        $actualEndDate = date('Y/m/d', strtotime($request->actual_end_date));
        $scheduleStartDate = date('Y/m/d', strtotime($request->schedule_start_date));
        $scheduleEndDate = date('Y/m/d', strtotime($request->schedule_end_date));
        $currentDate = date('Y/m/d');
        $scheduleStatus = '';
        $actualendDate = '';
        
        $saveActualEndDate = StudySchedule::where('id', $request->id)->where('is_active', 1)->where('is_delete', 0)->first();

        if(!is_null($saveActualEndDate)) {
            if((isset($request->actual_end_date)) && ($request->actual_end_date != '')) {
                $actualEnd = date('d-m-Y', strtotime($request->actual_end_date));     
                $saveActualEndDate->actual_end_date = $this->convertDt($actualEnd);
                $saveActualEndDate->actual_end_date_time = date('Y-m-d H:i:s');
            }

            if($saveActualEndDate->actual_end_date <= $saveActualEndDate->scheduled_end_date){
                $saveActualEndDate->end_delay_reason_id = null;
                $saveActualEndDate->end_delay_remark = null;
            } else if($saveActualEndDate->actual_end_date > $saveActualEndDate->scheduled_end_date){
                if(($request->end_delay_reason_id != '0')) {
                    $saveActualEndDate->end_delay_reason_id = $request->end_delay_reason_id;
                    $saveActualEndDate->end_delay_remark = null;
                } else if($request->end_delay_reason_id == '0'){
                    $saveActualEndDate->end_delay_reason_id = $request->end_delay_reason_id;
                    $saveActualEndDate->end_delay_remark = $request->end_delay_remark;
                } 
            }
            
            if (Auth::guard('admin')->user()->id != '') {
                $saveActualEndDate->updated_by_user_id = Auth::guard('admin')->user()->id;
            }

            if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate >= $actualStartDate) && ($scheduleEndDate >= $currentDate)) {
                $saveActualEndDate->activity_status = "ONGOING";
            } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate < $actualStartDate) && ($scheduleEndDate >= $currentDate)) {
                $saveActualEndDate->activity_status = "ONGOING";
            } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate <= $actualStartDate) && ($scheduleEndDate < $currentDate)) {
                $saveActualEndDate->activity_status = "DELAY";
            } else if (($request->actual_end_date == '') && ($actualStartDate != '') && ($scheduleStartDate > $actualStartDate) && ($scheduleEndDate < $currentDate)) {
                $saveActualEndDate->activity_status = "DELAY";
            } else if (($request->actual_start_date != '') && ($request->actual_end_date != '')) {
                $saveActualEndDate->activity_status = "COMPLETED";
            }
            $scheduleStatus = $saveActualEndDate->activity_status; 
            $actualendDate = date('d M Y', strtotime($saveActualEndDate->actual_end_date)); 
            $saveActualEndDate->save();
        }

        $saveActualEndDateTrail = StudyScheduleTrail::where('study_schedule_id', $request->id)
                                                                 ->where('is_active', 1)
                                                                 ->where('is_delete', 0)
                                                                 ->orderBy('id', 'DESC')
                                                                 ->first();

        if(!is_null($saveActualEndDateTrail)) {
            if((isset($request->actual_end_date)) && ($request->actual_end_date != '')) {
                $actualTrailEnd = date('d-m-Y', strtotime($request->actual_end_date));     
                $saveActualEndDateTrail->actual_end_date = $this->convertDt($actualTrailEnd);
                $saveActualEndDateTrail->actual_end_date_time = date('Y-m-d H:i:s');
            }

            if($saveActualEndDateTrail->actual_end_date <= $saveActualEndDateTrail->scheduled_end_date){
                $saveActualEndDateTrail->end_delay_reason_id = null;
                $saveActualEndDateTrail->end_delay_remark = null;
            } else if($saveActualEndDateTrail->actual_end_date > $saveActualEndDateTrail->scheduled_end_date){
                if(($request->end_delay_reason_id != '0')) {
                    $saveActualEndDateTrail->end_delay_reason_id = $request->end_delay_reason_id;
                    $saveActualEndDateTrail->end_delay_remark = null;
                } else if($request->end_delay_reason_id == '0') {
                    $saveActualEndDateTrail->end_delay_reason_id = $request->end_delay_reason_id;
                    $saveActualEndDateTrail->end_delay_remark = $request->end_delay_remark;
                } 
            }

            if (Auth::guard('admin')->user()->id != '') {
                $saveActualEndDateTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
            }
         
            $saveActualEndDateTrail->save();
        }

        $activityTypes = ['text', 'textarea', 'date', 'datetime', 'radio', 'select', 'checkbox', 'file'];

        if (!is_null($activityTypes)) {
            foreach ($activityTypes as $type) {            
                if($request->has($type)) {
                    if($type == 'file') {
                        foreach ($request->file('file') as $amcvId => $file) {
                            $fileName = $this->uploadImage($file, 'activity_metadata/actual_end');

                            $activityMetadataFileExists = StudyActivityMetadata::where('study_schedule_id', $request->id)
                                                                               ->where('activity_meta_id', $amcvId)
                                                                               ->where('is_active', 1)
                                                                               ->where('is_delete', 0)
                                                                               ->whereHas('activityMetadata', function($q){
                                                                                    $q->where('is_activity', 'E');
                                                                                })
                                                                               ->first();

                            if (!is_null($activityMetadataFileExists)) {
                                $activityMetadataFileExists->update(['actual_value' => $fileName, 'updated_by_user_id' => Auth::guard('admin')->user()->id]);

                                $saveStudyActivityMetadataFileTrail = new StudyActivityMetadataTrail();
                                $saveStudyActivityMetadataFileTrail->study_activity_metadata_id = $activityMetadataFileExists->id;
                                $saveStudyActivityMetadataFileTrail->study_schedule_id = $request->id;
                                $saveStudyActivityMetadataFileTrail->activity_meta_id = $amcvId;
                                $saveStudyActivityMetadataFileTrail->actual_value = $fileName;
                                $saveStudyActivityMetadataFileTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                                $saveStudyActivityMetadataFileTrail->save();
                            } else {
                                $saveActivityMetadataFile = new StudyActivityMetadata();
                                $saveActivityMetadataFile->study_schedule_id = $request->id;
                                $saveActivityMetadataFile->activity_meta_id = $amcvId;
                                $saveActivityMetadataFile->actual_value = $fileName;
                                $saveActivityMetadataFile->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveActivityMetadataFile->save();

                                $saveStudyActivityMetadataFileTrail = new StudyActivityMetadataTrail();
                                $saveStudyActivityMetadataFileTrail->study_activity_metadata_id = $saveActivityMetadataFile->id;
                                $saveStudyActivityMetadataFileTrail->study_schedule_id = $request->id;
                                $saveStudyActivityMetadataFileTrail->activity_meta_id = $amcvId;
                                $saveStudyActivityMetadataFileTrail->actual_value = $fileName;
                                $saveStudyActivityMetadataFileTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveStudyActivityMetadataFileTrail->save();
                            }
                        }
                    }

                    $data = $request->input($type, []);
            
                    if (!is_null($data)) {
                        foreach ($data as $key => $value) {
                            $actualValue = null;
                            if ($value != '') {
                                if($type == 'datetime') {
                                    $dateTimeFormat = date('d M Y H:i', strtotime($value));
                                    $actualValue = $dateTimeFormat;
                                } else if (is_array($value)) {
                                    $values = [];
                                    if (!is_null($value)) {
                                        foreach ($value as $k => $v) {
                                            if (!empty($v)) {
                                                array_push($values, $v);
                                            }
                                        }
                                    }
                                    $actualValue = (!empty($values)) ? implode('|', $values) : null;
                                } else {
                                    $actualValue = $value;
                                }
                            }

                            $activityMetadataExists = StudyActivityMetadata::where('study_schedule_id', $request->id)
                                                                           ->where('activity_meta_id', $key)
                                                                           ->where('is_active', 1)
                                                                           ->where('is_delete', 0)
                                                                           ->whereHas('activityMetadata', function($q){
                                                                               $q->where('is_activity', 'E');
                                                                            })
                                                                           ->first();

                            if (!is_null($activityMetadataExists)) {
                                $activityMetadataExists->update(['actual_value' => $actualValue, 'updated_by_user_id' => Auth::guard('admin')->user()->id]);

                                $saveStudyActivityMetadataTrail = new StudyActivityMetadataTrail();
                                $saveStudyActivityMetadataTrail->study_activity_metadata_id = $activityMetadataExists->id;
                                $saveStudyActivityMetadataTrail->study_schedule_id = $request->id;
                                $saveStudyActivityMetadataTrail->activity_meta_id = $key;
                                $saveStudyActivityMetadataTrail->actual_value = $actualValue;
                                $saveStudyActivityMetadataTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
                                $saveStudyActivityMetadataTrail->save();
                            } else {
                                $saveActivityMetadata = new StudyActivityMetadata();
                                $saveActivityMetadata->study_schedule_id = $request->id;
                                $saveActivityMetadata->activity_meta_id = $key;
                                $saveActivityMetadata->actual_value = $actualValue;
                                $saveActivityMetadata->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveActivityMetadata->save();

                                $saveActivityMetadataTrail = new StudyActivityMetadataTrail();
                                $saveActivityMetadataTrail->study_activity_metadata_id = $saveActivityMetadata->id;
                                $saveActivityMetadataTrail->study_schedule_id = $request->id;
                                $saveActivityMetadataTrail->activity_meta_id = $key;
                                $saveActivityMetadataTrail->actual_value = $actualValue;
                                $saveActivityMetadataTrail->created_by_user_id = Auth::guard('admin')->user()->id;
                                $saveActivityMetadataTrail->save();
                            }
                        }
                    }
                }
            }
        }

        if ($saveActualEndDate->id != '') {
            $endMilestoneActivity = StudySchedule::where('id', $request->id)
                                                   ->where('is_milestone', 1)
                                                   ->where('is_end_milestone_activity', 1)
                                                   ->with([
                                                        'studyNo' => function($q){
                                                            $q->with([
                                                                'projectManager'
                                                            ]);
                                                        }
                                                    ])
                                                   ->first();

            if (!is_null($endMilestoneActivity)) {
                $bdUser = Admin::where('role_id', 14)->get();
                if (!is_null($bdUser)) {
                    foreach ($bdUser as $buk => $buv) {
                        $this->dispatch((new SendEndMilestoneEmailToBdUser($buv->email_id,$buv->name,$endMilestoneActivity->studyNo->study_no,$endMilestoneActivity->activity_name,$endMilestoneActivity->scheduled_end_date,$endMilestoneActivity->actual_end_date,$endMilestoneActivity->actual_end_date_time,$endMilestoneActivity->studyNo->projectManager->name))->delay(10));
                    }
                }

            }
        }

        return response()->json(['status' => 'true', 'scheduleStatus' => $scheduleStatus, 'actualEndDate' => $actualendDate]);
    }
}