<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParaCode;
use App\Models\ParaMaster;
use App\Models\ActivityMaster;
use App\Models\ReasonMaster;
use App\Models\ReasonMasterTrail;
use App\Models\RoleModuleAccess;
use Auth;
use App\Exports\ReasonMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class ReasonMasterController extends Controller
{   
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    // Reason Master List
    public function reasonMasterList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $reasonMaster = ReasonMaster::select('id', 'activity_type_id', 'activity_id', 'start_delay_remark', 'end_delay_remark', 'is_active')
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->with([
                                        'activityType',
                                        'activityName'
                                    ])
                                    ->skip($offset)
                                    ->limit($perPage)
                                    ->get();

        $recordCount = ReasonMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);
                                    
        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','drug-master')
                                      ->first();
        }

        return view('admin.masters.reason_master.reason_master_list',compact('reasonMaster','admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    // Add Reason Master
    public function addReasonMaster(){

        $activities = ActivityMaster::where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->get();

        $activityTypes = ParaCode::where('para_code', 'ActivityType')
                                   ->where('is_active', 1)
                                   ->where('is_delete', 0)
                                   ->get();

        return view('admin.masters.reason_master.add_reason_master',compact('activities','activityTypes'));
    }

    // Save Reason Master and Reason Master Trail
    public function saveReasonMaster(Request $request){

        // Reason Master 
        $reasonMaster = new ReasonMaster;
        $reasonMaster->activity_type_id = $request->activity_type_id;
        $reasonMaster->activity_id = $request->activity_id;
        if ($request->start_delay_remark != '') {
            $reasonMaster->start_delay_remark = $request->start_delay_remark;
        }
        if ($request->end_delay_remark != '') {
            $reasonMaster->end_delay_remark = $request->end_delay_remark;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $reasonMaster->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $reasonMaster->save();

        // Reason Master trail
        $reasonMasterTrail = new ReasonMasterTrail;
        $reasonMasterTrail->reason_master_id = $reasonMaster->id;
        $reasonMasterTrail->activity_type_id = $request->activity_type_id;
        $reasonMasterTrail->activity_id = $request->activity_id;
        $reasonMasterTrail->start_delay_remark = $request->start_delay_remark;
        $reasonMasterTrail->end_delay_remark = $request->end_delay_remark;

        if (Auth::guard('admin')->user()->id != '') {
            $reasonMasterTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $reasonMasterTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addReasonMaster' : 'admin.reasonMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Reason Master',
                'message' => 'Reason master successfully added',
            ],
        ]);
    }

    // Edit Reason Master
    public function editReasonMaster($id){

        $activities = ActivityMaster::where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->get();

        $activityTypes = ParaCode::where('para_code', 'ActivityType')
                                   ->where('is_active', 1)
                                   ->where('is_delete', 0)
                                   ->get();

        $reasonMaster = ReasonMaster::where('id',base64_decode($id))->first(); 

        return view('admin.masters.reason_master.edit_reason_master', compact('activities', 'activityTypes', 'reasonMaster'));
    }

    // Update Reason Master
    public function updateReasonMaster(Request $request){
        // Reason Master 
        $reasonMaster = ReasonMaster::findOrFail($request->id);
        $reasonMaster->activity_type_id = $request->activity_type_id;
        $reasonMaster->activity_id = $request->activity_id;
        if ($request->start_delay_remark != '') {
            $reasonMaster->start_delay_remark = $request->start_delay_remark;
        }
        if ($request->end_delay_remark != '') {
            $reasonMaster->end_delay_remark = $request->end_delay_remark;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $reasonMaster->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $reasonMaster->save();

        // Reason Master trail
        $reasonMasterTrail = new ReasonMasterTrail;
        $reasonMasterTrail->reason_master_id = $reasonMaster->id;
        $reasonMasterTrail->activity_type_id = $request->activity_type_id;
        $reasonMasterTrail->activity_id = $request->activity_id;
        $reasonMasterTrail->start_delay_remark = $request->start_delay_remark;
        $reasonMasterTrail->end_delay_remark = $request->end_delay_remark;

        if (Auth::guard('admin')->user()->id != '') {
            $reasonMasterTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }

        $reasonMasterTrail->save();

        return redirect(route('admin.reasonMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'reasonMaster',
                'message' => 'reasonMaster successfully updated',
            ],
        ]);
    }

    // Delete Reason Master
    public function deleteReasonMaster($id){

        $delete = ReasonMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteReasonMaster = ReasonMaster::where('id',base64_decode($id))->first();

        $deleteTrail = new ReasonMasterTrail;
        $deleteTrail->reason_master_id = base64_decode($id);
        $deleteTrail->activity_type_id = $deleteReasonMaster->activity_type_id;
        $deleteTrail->activity_id = $deleteReasonMaster->activity_id;
        $deleteTrail->start_delay_remark = $deleteReasonMaster->start_delay_remark;
        $deleteTrail->end_delay_remark = $deleteReasonMaster->end_delay_remark;
        $deleteTrail->is_delete = 1;

        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }

        $deleteTrail->save();

        if($delete){
            return redirect(route('admin.reasonMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Reason Master',
                    'message' => 'Reason master successfully deleted',
                ]
            ]); 
        }
    }

    // Chage Reason Master Status
    public function changeReasonMasterStatus(Request $request){

        $reasonMaster = ReasonMaster::where('id',$request->id)->update(['is_active' => $request->status]);

        $statusReasonMaster = ReasonMaster::where('id',$request->id)->first();

        $reasonMasterTrail =  ReasonMasterTrail::where('reason_master_id',$statusReasonMaster->id)->first();
        $reasonMasterTrail->is_active  = $statusReasonMaster->is_active;
        if (Auth::guard('admin')->user()->id != '') {
            $reasonMasterTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $reasonMasterTrail->save();

        return $reasonMaster ? 'true' : 'false';
    }

    // excel export and download
    public function exportReasonMaster(){
        return Excel::download(new ReasonMasterExport, 'All Reason Master  Study Management System.xlsx');
    }
}
