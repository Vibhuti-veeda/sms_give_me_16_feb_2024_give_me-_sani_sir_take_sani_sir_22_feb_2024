<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\SlaActivityMasterTrail;
use Illuminate\Http\Request;
use App\Models\SlaActivityMaster;
use App\Models\Role;
use App\Models\ActivityMaster;
use App\Models\ParaMaster;
use App\Models\RoleModuleAccess;
use Auth;
use App\Exports\SlaActivityMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class SlaActivityMasterController extends Controller
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
    public function slaActivityMasterList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $activities = SlaActivityMaster::select('id','activity_id', 'study_design', 'no_from_subject', 'no_to_subject', 'is_cdisc', 'is_active')
                                        ->where('is_delete', 0)
                                        ->with([
                                            'activityName',
                                            'studyDesign'
                                        ])
                                        ->skip($offset)
                                        ->limit($perPage)
                                        ->get();

        $recordCount = SlaActivityMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);
                                            
        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','sla-activity-master')
                                      ->first();
        }

        return view('admin.masters.sla_activity.sla_activity_masters_list', compact('activities', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }
    public function addSlaActivityMaster(){

        $activities = ActivityMaster::where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $studyDesign = ParaMaster::where('para_code', 'StudyDesign')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->with(['paraCode'])
                                    ->first();

        
        return view('admin.masters.sla_activity.add_sla_activity_master', compact('activities','studyDesign'));
    }
    public function saveSlaActivityMaster(Request $request){

        $activity = new SlaActivityMaster;
        $activity->activity_id = $request->activity_name;
        $activity->study_design = $request->study_design;
        $activity->no_from_subject = $request->no_from_subject;
        $activity->no_to_subject = $request->no_to_subject;
        $activity->no_of_days = $request->no_of_days;

        if(isset($request->is_cdisc) && $request->is_cdisc != '') {
            $activity->is_cdisc = $request->is_cdisc;
        }
        
        $activity->save();

        $activitySlaTrail = new SlaActivityMasterTrail;
        $activitySlaTrail->activity_name = $request->activity_name;
        $activitySlaTrail->study_design = $request->study_design;
        $activitySlaTrail->no_from_subject = $request->no_from_subject;
        $activitySlaTrail->no_to_subject = $request->no_to_subject;
        $activitySlaTrail->no_of_days = $request->no_of_days;
        if (Auth::guard('admin')->user()->id != '') {
            $activitySlaTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        if(isset($request->is_cdisc) && $request->is_cdisc != '') {
            $activitySlaTrail->is_cdisc = $request->is_cdisc;
        } else {
            $activitySlaTrail->is_cdisc =0;
        }
        $activitySlaTrail->save();
        
        $route = $request->btn_submit == 'save_and_update' ? 'admin.addSlaActivityMaster' : 'admin.slaActivityMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'SLA Activity',
                'message' => 'SLA activity successfully added',
            ],
        ]);
    }
    public function editSlaActivityMaster($id){

        $activity = SlaActivityMaster::where('id', base64_decode($id))->first();
        $activities = ActivityMaster::where('is_active', 1)->where('is_delete', 0)->get();
        $studyDesign = ParaMaster::where('para_code', 'StudyDesign')
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->with(['paraCode'])
                                 ->first();
        

        return view('admin.masters.sla_activity.edit_sla_activity_master', compact('activities', 'studyDesign','activity'));
    }
    public function updateSlaActivityMaster(Request $request){

        $activity = SlaActivityMaster::findOrFail($request->id);
        $activity->activity_id = $request->activity_name;
        $activity->study_design = $request->study_design;
        $activity->no_from_subject = $request->no_from_subject;
        $activity->no_to_subject = $request->no_to_subject;
        $activity->no_of_days = $request->no_of_days;
        if(isset($request->is_cdisc) && $request->is_cdisc != '') {
            $activity->is_cdisc = $request->is_cdisc;
        } else {
            $activity->is_cdisc = 0;
        }
        $activity->save();

        $activitySLATrail = new SlaActivityMasterTrail;
        $activitySLATrail->activity_name = $request->activity_name;
        $activitySLATrail->study_design = $request->study_design;
        $activitySLATrail->no_from_subject = $request->no_from_subject;
        $activitySLATrail->no_to_subject = $request->no_to_subject;
        $activitySLATrail->no_of_days = $request->no_of_days;
        if (Auth::guard('admin')->user()->id != '') {
            $activitySLATrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        if(isset($request->is_cdisc) && $request->is_cdisc != '') {
            $activitySLATrail->is_cdisc = $request->is_cdisc;
        } else {
            $activitySLATrail->is_cdisc =0;
        }
        $activitySLATrail->save();

        return redirect(route('admin.slaActivityMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'SLA Activity',
                'message' => 'SLA activity successfully updated',
            ],
        ]);
    }

    public function deleteSlaActivityMaster($id){
        
        $delete = SlaActivityMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteSlaTrail = SlaActivityMaster::where('id',base64_decode($id))->first();

        $activitySlaTrail = new SlaActivityMasterTrail;
        $activitySlaTrail->activity_name = $deleteSlaTrail->activity_name;
        $activitySlaTrail->study_design = $deleteSlaTrail->study_design;
        $activitySlaTrail->no_from_subject = $deleteSlaTrail->no_from_subject;
        $activitySlaTrail->no_to_subject = $deleteSlaTrail->no_to_subject;
        $activitySlaTrail->no_of_days = $deleteSlaTrail->no_of_days;
        if (Auth::guard('admin')->user()->id != '') {
            $activitySlaTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        if(isset($deleteSlaTrail->is_cdisc) && $deleteSlaTrail->is_cdisc != '') {
            $activitySlaTrail->is_cdisc = $deleteSlaTrail->is_cdisc;
        } else{
            $activitySlaTrail->is_cdisc =0;
        }
        $activitySlaTrail->is_delete = 1;
        $activitySlaTrail->save();

        if($delete){
            return redirect(route('admin.slaActivityMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'SLA Activity Master',
                    'message' => 'SLA activity Master successfully deleted',
                ],
            ]);     
        }
    }
    /**
        * Activity Slotting master status change
        *
        * @param $id, $option
        *
        * @return to activity slotting master listing page change on toggle ActivityMaster active & deactive
    **/
    public function changeSlaActivityMasterStatus(Request $request){

        $status = SlaActivityMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportSlaActivityMaster(){
        return Excel::download(new SlaActivityMasterExport, 'All Sla Activity Master Study Management System.xlsx');
    }
}
