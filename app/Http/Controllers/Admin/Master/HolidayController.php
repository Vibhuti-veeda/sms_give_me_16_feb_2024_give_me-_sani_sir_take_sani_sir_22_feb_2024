<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HolidayMaster;
use App\Http\Controllers\GlobalController;
use App\Models\RoleModuleAccess;
use Auth;
use App\Models\HolidayMasterTrail;
use App\Exports\HolidayMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class HolidayController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }
	
    public function holidayMasterList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $holidaylist = HolidayMaster::select('id', 'holiday_year', 'holiday_name', 'holiday_type', 'holiday_date', 'is_active')
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->skip($offset)
                                    ->limit($perPage)
                                    ->get();

        $recordCount = HolidayMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);
                                    
        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','holiday-master')
                                      ->first();
        }

        return view('admin.masters.holiday_master.holiday_master_list',compact('holidaylist', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    public function addHolidayMaster(){

    	return view('admin.masters.holiday_master.add_holiday_master');
    }

    public function saveHolidayMaster(Request $request){

    	$holiday = new HolidayMaster;
        $holiday->holiday_year = $request->holiday_year;
        $holiday->holiday_name = $request->holiday_name;
        $holiday->holiday_type = $request->holiday_type;
        $holiday->holiday_date = $this->convertDate($request->holiday_date);
        $holiday->remarks = $request->remarks;
        if (Auth::guard('admin')->user()->id != '') {
            $holiday->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $holiday->save();

        $holidayTrail = new HolidayMasterTrail;
        $holidayTrail->holiday_master_id = $holiday->id;
        $holidayTrail->holiday_year = $request->holiday_year;
        $holidayTrail->holiday_name = $request->holiday_name;
        $holidayTrail->holiday_type = $request->holiday_type;
        $holidayTrail->holiday_date = $this->convertDate($request->holiday_date);
        $holidayTrail->remarks = $request->remarks;
        if (Auth::guard('admin')->user()->id != '') {
            $holidayTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $holidayTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addHolidayMaster' : 'admin.holidayMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Holiday Master',
                'message' => 'Holiday master successfully added',
            ],
        ]);
        
    }

    public function editHolidayMaster($id){
    
        $holiday = HolidayMaster::where('id',base64_decode($id))->first();
        return view('admin.masters.holiday_master.edit_holiday_master',compact('holiday'));
    }

    public function updateHolidayMaster(Request $request){    

        $holiday = HolidayMaster::findorFail($request->id);
        $holiday->holiday_year = $request->holiday_year;
        $holiday->holiday_name = $request->holiday_name;
        $holiday->holiday_date = $this->convertDate($request->holiday_date);
        $holiday->remarks = $request->remarks;
        if (Auth::guard('admin')->user()->id != '') {
            $holiday->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $holiday->save();

        $holidayTrail = new HolidayMasterTrail;
        $holidayTrail->holiday_master_id = $holiday->id;
        $holidayTrail->holiday_year = $request->holiday_year;
        $holidayTrail->holiday_name = $request->holiday_name;
        $holidayTrail->holiday_date = $this->convertDate($request->holiday_date);
        $holidayTrail->remarks = $request->remarks;
        if (Auth::guard('admin')->user()->id != '') {
            $holidayTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $holidayTrail->save();

        return redirect(route('admin.holidayMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Holiday Master',
                'message' => 'Holiday master successfully updated',
            ],
        ]);

    }

    public function deleteHolidayMaster($id){

        $delete = HolidayMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteHoliday = HolidayMaster::where('id',base64_decode($id))->first();

        $holidayTrail = new HolidayMasterTrail;
        $holidayTrail->holiday_master_id = base64_decode($id);
        $holidayTrail->holiday_year = $deleteHoliday->holiday_year;
        $holidayTrail->holiday_name = $deleteHoliday->holiday_name;
        $holidayTrail->holiday_date = $deleteHoliday->holiday_date;
        $holidayTrail->remarks = $deleteHoliday->remarks;
        if (Auth::guard('admin')->user()->id != '') {
            $holidayTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $holidayTrail->is_delete = 1;
        $holidayTrail->save();

        if($delete){
            return redirect(route('admin.holidayMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Holiday Master',
                    'message' => 'Holiday master successfully deleted',
                ],
            ]);     
        }
    }

    public function changeHolidayMasterStatus(Request $request){
        
        $status = HolidayMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    public function checkHolidayMasterDateExist(Request $request){

        $query = HolidayMaster::where('is_delete',0)->where('holiday_date', $this->convertDate($request->holiday_date));
        if(isset($request->id)) {
            $query->where('id','!=',$request->id);
        }
        $date = $query->first();

        return $date ? 'false' : 'true';
    }

    // excel export and download
    public function exportHolidayMaster(){
        return Excel::download(new HolidayMasterExport, 'All Holiday Masters  Study Management System.xlsx');
    }
}
