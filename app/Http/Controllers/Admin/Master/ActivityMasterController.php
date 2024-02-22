<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\StudySchedule;
use Illuminate\Http\Request;
use App\Models\ActivityMaster;
use App\Models\Admin;
use App\Models\Role;
use Auth;
use App\Models\ActivityMasterTrail;
use App\Models\RoleModuleAccess;
use App\Models\ParaCode;
use App\Exports\ActivityMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityMasterController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    /**
        * Activity master list
        *
        * @param mixed $activities
        *
        * @return to activity master listing page
    **/
    public function activityMasterList(Request $request){
        
        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $activities = ActivityMaster::select('id', 'activity_name', 'days_required', 'next_activity', 'buffer_days', 'responsibility', 'activity_type', 'activity_days', 'sequence_no', 'previous_activity', 'is_milestone', 'parent_activity', 'is_active')
                                    ->where('is_delete', 0)
                                    ->with([
                                            'responsible', 
                                            'nextActivity', 
                                            'previousActivity', 
                                            'parentActivity',
                                            'activityType'
                                        ])
                                    ->skip($offset)
                                    ->limit($perPage)
                                    ->get();

        $recordCount = ActivityMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','activity-master')
                                      ->first();
        }

        return view('admin.masters.activity.activity_masters_list', compact('activities', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    /**
        * Add activity master
        *
        * @param mixed $activities, $responsibels
        *
        * @return to add activity master page
    **/
    public function addActivityMaster(){

        $activities = ActivityMaster::where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->get();

        $activityTypes = ParaCode::where('para_code', 'ActivityType')
                                   ->where('is_active', 1)
                                   ->where('is_delete', 0)
                                   ->get();

        $responsibels = Role::where('is_active', 1)->where('is_delete', 0)->get();

        return view('admin.masters.activity.add_activity_master', compact('activities', 'responsibels', 'activityTypes'));
    }

    /**
        * Save activity master
        *
        * @param $activity_name, $days_required, $minimum_days_allowed, $maximum_days_allowed, $buffer_days, $is_dependent, $responsibility, $previous_activity,
        *        $is_milestone, $milestone_percentage, $milestone_amount, $parent_activity, $is_parellel, $is_group_specific, $is_period_specific fields save in 
        *        ActivityMaster database
        *
        * @return to activity master listing page with data store in ActivityMaster database
    **/
    public function saveActivityMaster(Request $request){

        $activity = new ActivityMaster;
        $activity->activity_name = $request->activity_name;
        $activity->days_required = $request->days_required;
        $activity->minimum_days_allowed = $request->minimum_days_allowed;
        $activity->maximum_days_allowed = $request->maximum_days_allowed;
        $activity->buffer_days = $request->buffer_days;
        $activity->activity_type = $request->activity_type;
        $activity->activity_days = $request->activity_days;
        $activity->responsibility = $request->responsibility;

        if (isset($request->next_activity) && $request->next_activity != '') {
            $activity->next_activity = $request->next_activity;
        }

        if (isset($request->is_dependent) && $request->is_dependent != '') {
            $activity->is_dependent = $request->is_dependent;
        }

        if (isset($request->previous_activity) && $request->previous_activity != '') {
            $activity->previous_activity = $request->previous_activity;
        }

        if (isset($request->is_milestone) && $request->is_milestone != '') {
            $activity->is_milestone = $request->is_milestone;
            $activity->milestone_percentage = $request->milestone_percentage;
            $activity->milestone_amount = $request->milestone_amount;
        }

        if (isset($request->parent_activity) && $request->parent_activity != '') {
            $activity->parent_activity = $request->parent_activity;
        }

        if (isset($request->is_parellel) && $request->is_parellel != '') {
            $activity->is_parellel = $request->is_parellel;
        }

        if (isset($request->is_group_specific) && $request->is_group_specific != '') {
            $activity->is_group_specific = $request->is_group_specific;
        }

        if (isset($request->is_period_specific) && $request->is_period_specific != '') {
            $activity->is_period_specific = $request->is_period_specific;
        }
        
        if (isset($request->sequence_no) && $request->sequence_no != '') {
            $activity->sequence_no = $request->sequence_no;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $activity->created_by_user_id = Auth::guard('admin')->user()->id;
        }

        $activity->save();

        $activityTrail = new ActivityMasterTrail;
        $activityTrail->activity_master_id = $activity->id;
        $activityTrail->activity_name = $request->activity_name;
        $activityTrail->days_required = $request->days_required;
        $activityTrail->minimum_days_allowed = $request->minimum_days_allowed;
        $activityTrail->maximum_days_allowed = $request->maximum_days_allowed;
        $activityTrail->activity_type = $request->activity_type;
        $activityTrail->buffer_days = $request->buffer_days;
        $activityTrail->responsibility = $request->responsibility;
        $activityTrail->activity_days = $request->activity_days;


        if (isset($request->next_activity) && $request->next_activity != '') {
            $activityTrail->next_activity = $request->next_activity;
        }

        if (isset($request->is_dependent) && $request->is_dependent != '') {
            $activityTrail->is_dependent = $request->is_dependent;
        }

        if (isset($request->previous_activity) && $request->previous_activity != '') {
            $activityTrail->previous_activity = $request->previous_activity;
        }

        if (isset($request->is_milestone) && $request->is_milestone != '') {
            $activityTrail->is_milestone = $request->is_milestone;
            $activityTrail->milestone_percentage = $request->milestone_percentage;
            $activityTrail->milestone_amount = $request->milestone_amount;
        }

        if (isset($request->parent_activity) && $request->parent_activity != '') {
            $activityTrail->parent_activity = $request->parent_activity;
        }

        if (isset($request->is_parellel) && $request->is_parellel != '') {
            $activityTrail->is_parellel = $request->is_parellel;
        }

        if (isset($request->is_group_specific) && $request->is_group_specific != '') {
            $activityTrail->is_group_specific = $request->is_group_specific;
        }

        if (isset($request->is_period_specific) && $request->is_period_specific != '') {
            $activityTrail->is_period_specific = $request->is_period_specific;
        }

        if (isset($request->sequence_no) && $request->sequence_no != '') {
            $activityTrail->sequence_no = $request->sequence_no;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $activityTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }

        $activityTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addActivityMaster' : 'admin.activityMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Activity',
                'message' => 'Activity successfully added',
            ],
        ]);
    }

    /**
        * Edit activity master
        *
        * @param mixed $activities, $responsibels, $activity
        *
        * @return to edit activity master page
    **/
    public function editActivityMaster($id){

        $activities = ActivityMaster::where('is_active', 1)->where('is_delete', 0)->get();
        $responsibels = Role::where('is_active', 1)->where('is_delete', 0)->get();

        $activity = ActivityMaster::where('id', base64_decode($id))->first();

        $activityTypes = ParaCode::where('para_code', 'ActivityType')
                                   ->where('is_active', 1)
                                   ->where('is_delete', 0)
                                   ->get();
        
        return view('admin.masters.activity.edit_activity_master', compact('activities', 'responsibels', 'activity', 'activityTypes'));
    }

    /**
        * Update activity master
        *
        * @param $activity_name, $days_required, $minimum_days_allowed, $maximum_days_allowed, $buffer_days, $is_dependent, $responsibility, $previous_activity,
        *        $is_milestone, $milestone_percentage, $milestone_amount, $parent_activity, $is_parellel, $is_group_specific, $is_period_specific fields update 
        *        in ActivityMaster database
        *
        * @return to activity master listing page with data store in ActivityMaster database
    **/
    public function updateActivityMaster(Request $request){

        $activity = ActivityMaster::findOrFail($request->id);
        $activity->activity_name = $request->activity_name;
        $activity->days_required = $request->days_required;
        $activity->minimum_days_allowed = $request->minimum_days_allowed;
        $activity->maximum_days_allowed = $request->maximum_days_allowed;
        $activity->activity_type = $request->activity_type;
        $activity->buffer_days = $request->buffer_days;
        $activity->responsibility = $request->responsibility;
        $activity->activity_days = $request->activity_days;

        if (isset($request->next_activity) && $request->next_activity != '') {
            $activity->next_activity = $request->next_activity;
        }

        if (isset($request->is_dependent) && $request->is_dependent != '') {
            $activity->is_dependent = $request->is_dependent;
        } else {
            $activity->is_dependent = 0;
        }

        if (isset($request->previous_activity) && $request->previous_activity != '') {
            $activity->previous_activity = $request->previous_activity;
        }

        if (isset($request->is_milestone) && $request->is_milestone != '') {
            $activity->is_milestone = $request->is_milestone;
            $activity->milestone_percentage = $request->milestone_percentage;
            $activity->milestone_amount = $request->milestone_amount;
        } else {
            $activity->is_milestone = 0;
        }

        if (isset($request->parent_activity) && $request->parent_activity != '') {
            $activity->parent_activity = $request->parent_activity;
        }

        if (isset($request->is_parellel) && $request->is_parellel != '') {
            $activity->is_parellel = $request->is_parellel;
        } else {
            $activity->is_parellel = 0;
        }

        if (isset($request->is_group_specific) && $request->is_group_specific != '') {
            $activity->is_group_specific = $request->is_group_specific;
        } else {
            $activity->is_group_specific = 0;
        }
        
        if (isset($request->is_period_specific) && $request->is_period_specific != '') {
            $activity->is_period_specific = $request->is_period_specific;
        } else {
            $activity->is_period_specific = 0;
        }

        if (isset($request->sequence_no) && $request->sequence_no != '') {
            $activity->sequence_no = $request->sequence_no;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $activity->updated_by_user_id = Auth::guard('admin')->user()->id;
        }

        $activity->save();

        $activityTrail = new ActivityMasterTrail;
        $activityTrail->activity_master_id = $activity->id;
        $activityTrail->activity_name = $request->activity_name;
        $activityTrail->days_required = $request->days_required;
        $activityTrail->minimum_days_allowed = $request->minimum_days_allowed;
        $activityTrail->maximum_days_allowed = $request->maximum_days_allowed;
        $activityTrail->activity_type = $request->activity_type;
        $activityTrail->buffer_days = $request->buffer_days;
        $activityTrail->responsibility = $request->responsibility;
        $activityTrail->activity_days = $request->activity_days;

        if (isset($request->next_activity) && $request->next_activity != '') {
            $activityTrail->next_activity = $request->next_activity;
        }

        if (isset($request->is_dependent) && $request->is_dependent != '') {
            $activityTrail->is_dependent = $request->is_dependent;
        }

        if (isset($request->previous_activity) && $request->previous_activity != '') {
            $activityTrail->previous_activity = $request->previous_activity;
        }

        if (isset($request->is_milestone) && $request->is_milestone != '') {
            $activityTrail->is_milestone = $request->is_milestone;
            $activityTrail->milestone_percentage = $request->milestone_percentage;
            $activityTrail->milestone_amount = $request->milestone_amount;
        }

        if (isset($request->parent_activity) && $request->parent_activity != '') {
            $activityTrail->parent_activity = $request->parent_activity;
        }

        if (isset($request->is_parellel) && $request->is_parellel != '') {
            $activityTrail->is_parellel = $request->is_parellel;
        }

        if (isset($request->is_group_specific) && $request->is_group_specific != '') {
            $activityTrail->is_group_specific = $request->is_group_specific;
        }

        if (isset($request->is_period_specific) && $request->is_period_specific != '') {
            $activityTrail->is_period_specific = $request->is_period_specific;
        }

        if (isset($request->sequence_no) && $request->sequence_no != '') {
            $activityTrail->sequence_no = $request->sequence_no;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $activityTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }

        $activityTrail->save();

        $responsibilityNo = ActivityMaster::where('is_active',1)->where('is_delete',0)->get();
        foreach ($responsibilityNo as $rk => $rv) {
            $activityId = StudySchedule::where('activity_id',$rv->id)
                                        ->update([
                                            'responsibility_id' => $rv->responsibility,
                                        ]);
        }

        return redirect(route('admin.activityMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Activity',
                'message' => 'Activity successfully updated',
            ],
        ]);
    }

    /**
        * Delete activity master
        *
        * @param $id
        *
        * @return to activity master listing page with data delete from ActivityMaster database
    **/
    public function deleteActivityMaster($id){
        
        $delete = ActivityMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteActivity = ActivityMaster::where('id',base64_decode($id))->first();
        $deleteTrail = new ActivityMasterTrail;
        $deleteTrail->activity_master_id = $deleteActivity->id;
        $deleteTrail->activity_name = $deleteActivity->activity_name;
        $deleteTrail->days_required = $deleteActivity->days_required;
        $deleteTrail->minimum_days_allowed = $deleteActivity->minimum_days_allowed;
        $deleteTrail->maximum_days_allowed = $deleteActivity->maximum_days_allowed;
        $deleteTrail->activity_type = $deleteActivity->activity_type;
        $deleteTrail->buffer_days = $deleteActivity->buffer_days;
        $deleteTrail->responsibility = $deleteActivity->responsibility;
        $deleteTrail->activity_days = $deleteActivity->activity_days;

        if (isset($deleteActivity->next_activity) && $deleteActivity->next_activity != '') {
            $deleteTrail->next_activity = $deleteActivity->next_activity;
        }

        if (isset($deleteActivity->is_dependent) && $deleteActivity->is_dependent != '') {
            $deleteTrail->is_dependent = $deleteActivity->is_dependent;
        }

        if (isset($deleteActivity->previous_activity) && $deleteActivity->previous_activity != '') {
            $deleteTrail->previous_activity = $deleteActivity->previous_activity;
        }

        if (isset($deleteActivity->is_milestone) && $deleteActivity->is_milestone != '') {
            $deleteTrail->is_milestone = $deleteActivity->is_milestone;
            $deleteTrail->milestone_percentage = $deleteActivity->milestone_percentage;
            $deleteTrail->milestone_amount = $deleteActivity->milestone_amount;
        }

        if (isset($deleteActivity->parent_activity) && $deleteActivity->parent_activity != '') {
            $deleteTrail->parent_activity = $deleteActivity->parent_activity;
        }

        if (isset($deleteActivity->is_parellel) && $deleteActivity->is_parellel != '') {
            $deleteTrail->is_parellel = $deleteActivity->is_parellel;
        }

        if (isset($deleteActivity->is_group_specific) && $deleteActivity->is_group_specific != '') {
            $deleteTrail->is_group_specific = $deleteActivity->is_group_specific;
        }

        if (isset($deleteActivity->is_period_specific) && $deleteActivity->is_period_specific != '') {
            $deleteTrail->is_period_specific = $deleteActivity->is_period_specific;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        $deleteTrail->is_delete = 1;
        $deleteTrail->save();

        if($delete){
            return redirect(route('admin.activityMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Activity Master',
                    'message' => 'Activity Master successfully deleted',
                ],
            ]);     
        }
    }

    /**
        * Activity master status change
        *
        * @param $id, $option
        *
        * @return to activity master listing page change on toggle ActivityMaster active & deactive
    **/
    public function changeActivityMasterStatus(Request $request){

        $status = ActivityMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportActivityMaster(){
        return Excel::download(new ActivityMasterExport, 'All Team members  Study Management System.xlsx');
    }

}
