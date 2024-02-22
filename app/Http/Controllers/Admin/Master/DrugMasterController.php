<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugMaster;
use App\Models\DrugMasterTrail;
use App\Models\RoleModuleAccess;
use Auth;
use App\Exports\DrugMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class DrugMasterController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    /**
        * Drug master list
        *
        * @param mixed $drugs
        *
        * @return to drug master listing page
    **/
    public function drugMasterList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $drugs = DrugMaster::select('id','drug_name', 'drug_type', 'remarks', 'is_active')
                            ->where('is_delete', 0)
                            ->orderBy('id', 'DESC')
                            ->skip($offset)
                            ->limit($perPage)
                            ->get();

        $recordCount = DrugMaster::where('is_delete', 0)->count();
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
        
        return view('admin.masters.drug.drug_list', compact('drugs', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    /**
        * Add Drug
        *
        * @return to add drug master page
    **/
    public function addDrugMaster(){
        
        return view('admin.masters.drug.add_drug');
    }

    /**
        * Save drug master 
        *
        * @param $drug_name, $drug_type, $remarks, $created_by_user_id field save in DrugMaster database
        *
        * @return to drug master listing page with data store in DrugMaster database
    **/
    public function saveDrugMaster(Request $request){

        $drug = new DrugMaster;
        $drug->drug_name = $request->drug_name;
        $drug->drug_type = $request->drug_type;
        if ($request->remarks != '') {
            $drug->remarks = $request->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $drug->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $drug->save();

        $deleteTrail = new DrugMasterTrail;
        $deleteTrail->drug_master_id = $drug->id;
        $deleteTrail->drug_name = $request->drug_name;
        $deleteTrail->drug_type = $request->drug_type;
        if ($request->remarks != '') {
            $deleteTrail->remarks = $request->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $deleteTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addDrugMaster' : 'admin.drugMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Drug Master',
                'message' => 'Drug successfully added',
            ],
        ]);
    }

    public function editDrugMaster($id){
        
        $drug = DrugMaster::where('id', base64_decode($id))->first();

        return view('admin.masters.drug.edit_drug', compact('drug'));
    }

    public function updateDrugMaster(Request $request){

        $drug = DrugMaster::findOrFail($request->id);
        $drug->drug_name = $request->drug_name;
        $drug->drug_type = $request->drug_type;
        if ($request->remarks != '') {
            $drug->remarks = $request->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $drug->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $drug->save();

        $deleteTrail = new DrugMasterTrail;
        $deleteTrail->drug_master_id = $drug->id;
        $deleteTrail->drug_name = $request->drug_name;
        $deleteTrail->drug_type = $request->drug_type;
        if ($request->remarks != '') {
            $deleteTrail->remarks = $request->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $deleteTrail->save();

        return redirect(route('admin.drugMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Drug Master',
                'message' => 'Drug successfully updated',
            ],
        ]);
    }

    public function deleteDrugMaster($id){

        $delete = DrugMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteDrug = DrugMaster::where('id',base64_decode($id))->first();
        $deleteTrail = new DrugMasterTrail;
        $deleteTrail->drug_master_id = $deleteDrug->id;
        $deleteTrail->drug_name = $deleteDrug->drug_name;
        $deleteTrail->drug_type = $deleteDrug->drug_type;
        if ($deleteDrug->remarks != '') {
            $deleteTrail->remarks = $deleteDrug->remarks;
        }
        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $deleteTrail->is_delete = 1;
        $deleteTrail->save();

        if($delete){
            return redirect(route('admin.drugMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Drug Master',
                    'message' => 'Drug successfully deleted',
                ],
            ]);     
        }
    }

    public function changeDrugMasterStatus(Request $request){
        
        $status = DrugMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportDrugMaster(){
        return Excel::download(new DrugMasterExport, 'All Drugs  Study Management System.xlsx');
    }
}
