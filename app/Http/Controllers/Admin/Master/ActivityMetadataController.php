<?php

namespace App\Http\Controllers\Admin\Master;

use Illuminate\Http\Request;
use App\Models\ControlMaster;
use App\Models\StudySchedule;
use App\Models\ActivityMaster;
use App\Models\ActivityMetadata;
use App\Models\RoleModuleAccess;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StudyActivityMetadata;
use App\Models\ActivityMetadataTrail; 
use App\Exports\ActivityMetadataExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityMetadataController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    // Activity Metadata List
    public function activityMetadataList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $activityMetadataList = ActivityMetadata::select('id', 'activity_id', 'control_id', 'source_value', 'source_question', 'is_mandatory', 'input_validation', 'is_activity', 'is_active')
                                                ->where('is_delete', 0)
                                                ->with([
                                                    'activityName' => function($q){
                                                        $q->select('id', 'activity_name')
                                                          ->where('is_active', 1)
                                                          ->where('is_delete', 0);
                                                    },
                                                    'controlName' => function($q){
                                                        $q->select('id', 'control_name')
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0);
                                                    }
                                                ])
                                                ->orderBy('id', 'DESC')
                                                ->skip($offset)
                                                ->limit($perPage)
                                                ->get();

        $recordCount = ActivityMetadata::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);
                                                
        $admin = '';
        $access = '';

        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','activity-metadata')
                                      ->first();
        }

        return view('admin.masters.activity_metadata.activity_metadata_list', compact('admin', 'access', 'activityMetadataList', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    // Add ActivityMetadata
    public function addActivityMetadata(){

        $activities = ActivityMaster::select('id', 'activity_name')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $controls = ControlMaster::select('id', 'control_name', 'control_type', 'data_type')
                                ->where('is_active', 1)
                                ->where('is_delete', 0)
                                ->get();

        return view('admin.masters.activity_metadata.add_activity_metadata', compact('activities', 'controls'));
    }

    // Save ActivityMetadata with ActivityMetadata trail
    public function saveActivityMetadata(Request $request){

        $saveActivityMetadata = new ActivityMetadata();
        $saveActivityMetadata->activity_id = $request->activity_id;
        $saveActivityMetadata->control_id = $request->control_id;
        $saveActivityMetadata->source_question = $request->source_question;

        if(!is_null($request->source_value)){
            $saveActivityMetadata->source_value = implode(',', $request->source_value);
        }
        
        if($request->is_mandatory == 'yes'){
            $saveActivityMetadata->is_mandatory = 1;
        }

        // $saveActivityMetadata->input_validation = $request->input_validation;
        $saveActivityMetadata->is_activity = $request->is_activity;
        $saveActivityMetadata->created_by_user_id = Auth::guard('admin')->user()->id;
        $saveActivityMetadata->save();

        $saveActivityMetadataTrail = new ActivityMetadataTrail();
        $saveActivityMetadataTrail->activity_metadata_id = $saveActivityMetadata->id;
        $saveActivityMetadataTrail->activity_id = $request->activity_id;
        $saveActivityMetadataTrail->control_id = $request->control_id;
        $saveActivityMetadataTrail->source_question = $request->source_question;

        if(!is_null($request->source_value)){
            $saveActivityMetadataTrail->source_value = implode(',', $request->source_value);
        }

        if($request->is_mandatory == 'yes'){
            $saveActivityMetadataTrail->is_mandatory = 1;
        }

        // $saveActivityMetadataTrail->input_validation = $request->input_validation;
        $saveActivityMetadataTrail->is_activity = $request->is_activity;
        $saveActivityMetadataTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        $saveActivityMetadataTrail->save();

        $route = $request->btn_submit == 'save_and_new' ? 'admin.addActivityMetadata' : 'admin.activityMetadataList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Activity Metadata',
                'message' => 'Activity metadata successfully added',
            ],
        ]);
    }

    // Delete ActivityMetadata with ActivityMetadata trail
    public function deleteActivityMetadata($id){

        $delete = ActivityMetadata::where('id', base64_decode($id))->update(['updated_by_user_id' => Auth::guard('admin')->user()->id, 'is_delete' => 1]);
        $deleteActivityMetadataTrail = ActivityMetadataTrail::where('activity_metadata_id', base64_decode($id))->orderBy('id', 'DESC')->first();

        if(!is_null($deleteActivityMetadataTrail)){
            $deleteActivityMetadataTrail->update(['updated_by_user_id' => Auth::guard('admin')->user()->id, 'is_delete' => 1]);
        }
 
        if($delete){
            return redirect(route('admin.activityMetadataList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Activity Metadata',
                    'message' => 'Activity metadata successfully deleted',
                ],
            ]);
        }
    }

    // Change Status of ActivityMetadata with ActivityMetadata trail
    public function changeActivityMetadataStatus(Request $request){
 
        $status = ActivityMetadata::where('id', $request->id)->update(['updated_by_user_id' => Auth::guard('admin')->user()->id, 'is_active' => $request->option]);
        $activityMetadataTrailStatus = ActivityMetadataTrail::where('activity_metadata_id', $request->id)->orderBy('id', 'DESC')->first();

        if(!is_null($activityMetadataTrailStatus)){
            $activityMetadataTrailStatus->update(['updated_by_user_id' => Auth::guard('admin')->user()->id, 'is_active' => $request->option]);
        }
 
        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportActivityMetadata(){
        return Excel::download(new ActivityMetadataExport, 'All Team members  Study Management System.xlsx');
    }
}
