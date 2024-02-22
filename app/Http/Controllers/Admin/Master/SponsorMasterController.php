<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\SponsorMasterTrail;
use Illuminate\Http\Request;
use App\Models\SponsorMaster;
use App\Models\RoleModuleAccess;
use Auth;
use App\Exports\SponsorMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class SponsorMasterController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    /**
        * Sponsor master list
        *
        * @param mixed $sponsors
        *
        * @return to sponsor master listing page
    **/
    public function sponsorMasterList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $sponsors = SponsorMaster::select('id','sponsor_name', 'sponsor_type', 'is_active')
                                   ->where('is_delete', 0)
                                   ->orderBy('id', 'DESC')
                                   ->skip($offset)
                                   ->limit($perPage)
                                   ->get();

        $recordCount = SponsorMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','sponsor-master')
                                      ->first();
        }

        return view('admin.masters.sponsor_master.sponsor_master_list', compact('sponsors', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    /**
        * Add Sponsor
        *
        * @return to add sponsor page
    **/
    public function addSponsorMaster(){

        return view('admin.masters.sponsor_master.add_sponsor_master');
    }

    /**
        * Save sponsor master 
        *
        * @param $sponsor_name, $sponsor_address, $sponsor_type, $contact_person_1, $contact_mobile_1, 
        *        $contact_email_1, $contact_person_2, $contact_mobile_2, $contact_email_2, $landline_no, 
        *        $remarks field save in SponsorMaster database
        *
        * @return to sponsor listing page with data store in SponsorMaster database
    **/
    public function saveSponsorMaster(Request $request){
            
        $sponsor = new SponsorMaster;
        $sponsor->sponsor_name = $request->sponsor_name;
        
        if ($request->sponsor_address != '') {
            $sponsor->sponsor_address = $request->sponsor_address;
        }
        
        $sponsor->sponsor_type = $request->sponsor_type;
        
        if ($request->contact_person_1 != '') {
            $sponsor->contact_person_1 = $request->contact_person_1;
        }
        
        if ($request->contact_mobile_1 != '') {
            $sponsor->contact_mobile_1 = $request->contact_mobile_1;
        }
        
        if ($request->contact_email_1 != '') {
            $sponsor->contact_email_1 = $request->contact_email_1;
        }

        if ($request->contact_person_2 != '') {
            $sponsor->contact_person_2 = $request->contact_person_2;
        }
        
        if ($request->contact_mobile_2 != '') {
            $sponsor->contact_mobile_2 = $request->contact_mobile_2;
        }
        
        if ($request->contact_email_2 != '') {
            $sponsor->contact_email_2 = $request->contact_email_2;
        }

        if ($request->landline_no != '') {
            $sponsor->landline_no = $request->landline_no;
        }

        if ($request->remarks != '') {
            $sponsor->remarks = $request->remarks;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $sponsor->created_by_user_id = Auth::guard('admin')->user()->id;
        }

        $sponsor->save();

        $sponsorTrail = new SponsorMasterTrail;
        $sponsorTrail->sponsor_master_id = $sponsor->id;
        $sponsorTrail->sponsor_name = $request->sponsor_name;
        if ($request->sponsor_address != '') {
            $sponsorTrail->sponsor_address = $request->sponsor_address;
        }
        if ($request->sponsor_type != '') {
            $sponsorTrail->sponsor_type = $request->sponsor_type;
        }
        if ($request->contact_person_1 != '') {
            $sponsorTrail->contact_person_1 = $request->contact_person_1;
        }
        
        if ($request->contact_mobile_1 != '') {
            $sponsorTrail->contact_mobile_1 = $request->contact_mobile_1;
        }
        
        if ($request->contact_email_1 != '') {
            $sponsorTrail->contact_email_1 = $request->contact_email_1;
        }

        if ($request->contact_person_2 != '') {
            $sponsorTrail->contact_person_2 = $request->contact_person_2;
        }
        
        if ($request->contact_mobile_2 != '') {
            $sponsorTrail->contact_mobile_2 = $request->contact_mobile_2;
        }
        
        if ($request->contact_email_2 != '') {
            $sponsorTrail->contact_email_2 = $request->contact_email_2;
        }

        if ($request->landline_no != '') {
            $sponsorTrail->landline_no = $request->landline_no;
        }

        if ($request->remarks != '') {
            $sponsorTrail->remarks = $request->remarks;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $sponsorTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $sponsorTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addSponsorMaster' : 'admin.sponsorMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Sponsor Master',
                'message' => 'Sponsor successfully added',
            ],
        ]);

    }

    /**
        * Edit Sponsor 
        *
        * @param mixed $sponsor
        *
        * @return to edit sponsor page
    **/
    public function editSponsorMaster($id){

        $sponsor = SponsorMaster::where('id', base64_decode($id))->first();

        return view('admin.masters.sponsor_master.edit_sponsor_master', compact('sponsor'));
    }

    /**
        * Update sponsor master 
        *
        * @param $sponsor_name, $sponsor_address, $sponsor_type, $contact_person_1, $contact_mobile_1, 
        *        $contact_email_1, $contact_person_2, $contact_mobile_2, $contact_email_2, $landline_no, 
        *        $remarks field update in SponsorMaster database
        *
        * @return to sponsor listing page with data store in SponsorMaster database
    **/
    public function updateSponsorMaster(Request $request){

        $sponsor = SponsorMaster::findOrFail($request->id);
        $sponsor->sponsor_name = $request->sponsor_name;
        
        if ($request->sponsor_address != '') {
            $sponsor->sponsor_address = $request->sponsor_address;
        }
        
        $sponsor->sponsor_type = $request->sponsor_type;
        
        if ($request->contact_person_1 != '') {
            $sponsor->contact_person_1 = $request->contact_person_1;
        }
        
        if ($request->contact_mobile_1 != '') {
            $sponsor->contact_mobile_1 = $request->contact_mobile_1;
        }
        
        if ($request->contact_email_1 != '') {
            $sponsor->contact_email_1 = $request->contact_email_1;
        }

        if ($request->contact_person_2 != '') {
            $sponsor->contact_person_2 = $request->contact_person_2;
        }
        
        if ($request->contact_mobile_2 != '') {
            $sponsor->contact_mobile_2 = $request->contact_mobile_2;
        }
        
        if ($request->contact_email_2 != '') {
            $sponsor->contact_email_2 = $request->contact_email_2;
        }

        if ($request->landline_no != '') {
            $sponsor->landline_no = $request->landline_no;
        }

        if ($request->remarks != '') {
            $sponsor->remarks = $request->remarks;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $sponsor->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        
        $sponsor->save();

        $sponsorTrail = new SponsorMasterTrail;
        $sponsorTrail->sponsor_master_id = $sponsor->id;
        $sponsorTrail->sponsor_name = $request->sponsor_name;
        if ($request->sponsor_address != '') {
            $sponsorTrail->sponsor_address = $request->sponsor_address;
        }
        if ($request->sponsor_type != '') {
            $sponsorTrail->sponsor_type = $request->sponsor_type;
        }
        if ($request->contact_person_1 != '') {
            $sponsorTrail->contact_person_1 = $request->contact_person_1;
        }
        
        if ($request->contact_mobile_1 != '') {
            $sponsorTrail->contact_mobile_1 = $request->contact_mobile_1;
        }
        
        if ($request->contact_email_1 != '') {
            $sponsorTrail->contact_email_1 = $request->contact_email_1;
        }

        if ($request->contact_person_2 != '') {
            $sponsorTrail->contact_person_2 = $request->contact_person_2;
        }
        
        if ($request->contact_mobile_2 != '') {
            $sponsorTrail->contact_mobile_2 = $request->contact_mobile_2;
        }
        
        if ($request->contact_email_2 != '') {
            $sponsorTrail->contact_email_2 = $request->contact_email_2;
        }

        if ($request->landline_no != '') {
            $sponsorTrail->landline_no = $request->landline_no;
        }

        if ($request->remarks != '') {
            $sponsorTrail->remarks = $request->remarks;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $sponsorTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $sponsorTrail->save();

        return redirect(route('admin.sponsorMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Sponsor Master',
                'message' => 'Sponsor successfully updated',
            ],
        ]);
    }

    /**
        * Delete Sponsor 
        *
        * @param $id
        *
        * @return to sponsor listing page with data delete from SponsorMaster database
    **/
    public function deleteSponsorMaster($id){

        $delete = SponsorMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteSponsor = SponsorMaster::where('id',base64_decode($id))->first();
        $sponsorTrail = new SponsorMasterTrail;
        $sponsorTrail->sponsor_master_id = base64_decode($id);
        $sponsorTrail->sponsor_name = $deleteSponsor->sponsor_name;
        if ($deleteSponsor->sponsor_address != '') {
            $sponsorTrail->sponsor_address = $deleteSponsor->sponsor_address;
        }
        if ($deleteSponsor->sponsor_type != '') {
            $sponsorTrail->sponsor_type = $deleteSponsor->sponsor_type;
        }
        if ($deleteSponsor->contact_person_1 != '') {
            $sponsorTrail->contact_person_1 = $deleteSponsor->contact_person_1;
        }
        
        if ($deleteSponsor->contact_mobile_1 != '') {
            $sponsorTrail->contact_mobile_1 = $deleteSponsor->contact_mobile_1;
        }
        
        if ($deleteSponsor->contact_email_1 != '') {
            $sponsorTrail->contact_email_1 = $deleteSponsor->contact_email_1;
        }

        if ($deleteSponsor->contact_person_2 != '') {
            $sponsorTrail->contact_person_2 = $deleteSponsor->contact_person_2;
        }
        
        if ($deleteSponsor->contact_mobile_2 != '') {
            $sponsorTrail->contact_mobile_2 = $deleteSponsor->contact_mobile_2;
        }
        
        if ($deleteSponsor->contact_email_2 != '') {
            $sponsorTrail->contact_email_2 = $deleteSponsor->contact_email_2;
        }

        if ($deleteSponsor->landline_no != '') {
            $sponsorTrail->landline_no = $deleteSponsor->landline_no;
        }

        if ($deleteSponsor->remarks != '') {
            $sponsorTrail->remarks = $deleteSponsor->remarks;
        }

        if (Auth::guard('admin')->user()->id != '') {
            $sponsorTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $sponsorTrail->is_delete = 1;
        $sponsorTrail->save();

        if($delete){
            return redirect(route('admin.sponsorMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Sponsor Master',
                    'message' => 'Sponsor successfully deleted',
                ],
            ]);     
        }
    }

    /**
        * Sponsor status change
        *
        * @param $id, $option
        *
        * @return to sponsor listing page change on toggle sponsor active & deactive
    **/
    public function changeSponsorMasterStatus(Request $request){

        $status = SponsorMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportSponsorMaster(){
        return Excel::download(new SponsorMasterExport, 'All Sponsor Master  Study Management System.xlsx');
    }
}
