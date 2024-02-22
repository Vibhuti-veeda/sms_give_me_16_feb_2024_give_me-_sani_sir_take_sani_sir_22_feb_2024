<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\LocationMasterTrail;
use Illuminate\Http\Request;
use App\Models\LocationMaster;
use App\Http\Controllers\GlobalController;
use App\Models\RoleModuleAccess;
use Auth;
use App\Exports\LocationMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class LocationMasterController extends GlobalController
{
    
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    public function locationMasterList(Request $request){
        
        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

    	$locationlist = LocationMaster::select('id', 'location_name', 'location_type', 'location_address', 'is_active')
                                        ->where('is_delete', 0)
                                        ->orderBy('id', 'DESC')
                                        ->skip($offset)
                                        ->limit($perPage)
                                        ->get();
                                        
        $recordCount = LocationMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);
                                        
        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','location-master')
                                      ->first();
        }

        return view('admin.masters.location.location_master_list',compact('locationlist', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    public function addLocationMaster(){

    	return view('admin.masters.location.add_location_master');
    }

    public function saveLocationMaster(Request $request){

    	$location = new LocationMaster;
        $location->location_name = $request->location_name;
        $location->location_type = $request->location_type;
        if ($request->location_address != '') {
            $location->location_address = $request->location_address;
        }
        if ($request->remarks != '') {
            $location->remarks = $request->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $location->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $location->save();

        $locationTrail = new LocationMasterTrail;
        $locationTrail->location_master_id = $location->id;
        $locationTrail->location_name = $request->location_name;
        if ($request->location_address != '') {
            $locationTrail->location_address = $request->location_address;
        }
        if ($request->location_type != '') {
            $locationTrail->location_type = $request->location_type;
        }
        if ($request->remarks != '') {
            $locationTrail->remarks = $request->remarks;
        }
        
        if (Auth::guard('admin')->user()->id != '') {
            $locationTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $locationTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addLocationMaster' : 'admin.locationMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Location Master',
                'message' => 'Location master successfully added',
            ],
        ]);
        
    }

    public function editLocationMaster($id){
    
        $location = LocationMaster::where('id',base64_decode($id))->first();
        return view('admin.masters.location.edit_location_master',compact('location'));
    }

    public function updateLocationMaster(Request $request){    

        $location = LocationMaster::findorFail($request->id);
        $location->location_name = $request->location_name;
        $location->location_type = $request->location_type;
        if ($request->location_address != '') {
            $locationlist->location_address = $request->location_address;
        }
        if ($request->remarks != '') {
            $location->remarks = $request->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $location->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $location->save();

        $locationTrail = new LocationMasterTrail;
        $locationTrail->location_master_id = $location->id;
        $locationTrail->location_name = $request->location_name;
        if ($request->location_address != '') {
            $locationTrail->location_address = $request->location_address;
        }
        if ($request->location_type != '') {
            $locationTrail->location_type = $request->location_type;
        }
        if ($request->remarks != '') {
            $locationTrail->remarks = $request->remarks;
        }
        
        if (Auth::guard('admin')->user()->id != '') {
            $locationTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $locationTrail->save();

        return redirect(route('admin.locationMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Location Master',
                'message' => 'Location master successfully updated',
            ],
        ]);
    }

    public function deleteLocationMaster($id){

        $delete = LocationMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteLocation = LocationMaster::where('id',base64_decode($id))->first();

        $locationTrail = new LocationMasterTrail;
        $locationTrail->location_master_id = base64_decode($id);
        $locationTrail->location_name = $deleteLocation->location_name;
        if ($deleteLocation->location_type != '') {
            $locationTrail->location_type = $deleteLocation->location_type;
        }
        if ($deleteLocation->location_address != '') {
            $locationTrail->location_address = $deleteLocation->location_address;
        }
        if ($deleteLocation->remarks != '') {
            $locationTrail->remarks = $deleteLocation->remarks;
        }
        
        if (Auth::guard('admin')->user()->id != '') {
            $locationTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $locationTrail->is_delete = 1;
        $locationTrail->save();

        if($delete){
            return redirect(route('admin.locationMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Location Master',
                    'message' => 'Location master successfully deleted',
                ],
            ]);     
        }
    }

    public function changeLocationMasterStatus(Request $request){
        
        $status = LocationMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }
    
    // excel export and download
    public function exportLocationMaster(){
        return Excel::download(new LocationMasterExport, 'All Location Master  Study Management System.xlsx');
    }
}
