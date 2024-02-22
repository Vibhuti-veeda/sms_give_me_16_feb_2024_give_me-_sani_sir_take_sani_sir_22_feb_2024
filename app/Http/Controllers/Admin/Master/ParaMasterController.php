<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\ParaCodesTrail;
use Illuminate\Http\Request;
use App\Models\ParaMaster;
use Auth;
use App\Models\ParaMasterTrail;
use App\Models\ParaCode;
use App\Models\RoleModuleAccess;
use App\Exports\ParaMasterExport;
use App\Exports\ParaCodeExport;
use Maatwebsite\Excel\Facades\Excel;

class ParaMasterController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    public function paraMasterList(Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $paras = ParaMaster::select('id', 'para_code', 'para_description', 'is_active')
                            ->where('is_delete', 0)
                            ->orderBy('id', 'DESC')
                            ->skip($offset)
                            ->limit($perPage)
                            ->get();

        $recordCount = ParaMaster::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','para-master')
                                      ->first();
        }

        return view('admin.masters.para_master.para_master_list', compact('paras', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    public function addParaMaster(){
        
        return view('admin.masters.para_master.add_para_master');
    }

    public function saveParaMaster(Request $request){

        $para = new ParaMaster;
        $para->para_code = $request->para_code;
        $para->para_description = $request->para_description;
        if (Auth::guard('admin')->user()->id != '') {
            $para->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $para->save();

        $paraTrail = new ParaMasterTrail;
        $paraTrail->para_master_id = $para->id;
        $paraTrail->para_code = $request->para_code;
        $paraTrail->para_description = $request->para_description;
        if (Auth::guard('admin')->user()->id != '') {
            $paraTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $paraTrail->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addParaMaster' : 'admin.paraMasterList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Para Master',
                'message' => 'Para master successfully added',
            ],
        ]);
    }

    public function editParaMaster($id){
        
        $para = ParaMaster::where('id', base64_decode($id))->first();

        return view('admin.masters.para_master.edit_para_master', compact('para'));
    }

    public function updateParaMaster(Request $request){
        
        $para = ParaMaster::findOrFail($request->id);
        $para->para_code = $request->para_code;
        $para->para_description = $request->para_description;
        if (Auth::guard('admin')->user()->id != '') {
            $para->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $para->save();

        $paraTrail = new ParaMasterTrail;
        $paraTrail->para_master_id = $para->id;
        $paraTrail->para_code = $request->para_code;
        $paraTrail->para_description = $request->para_description;
        if (Auth::guard('admin')->user()->id != '') {
            $paraTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $paraTrail->save();

        return redirect(route('admin.paraMasterList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Para Master',
                'message' => 'Para master successfully added',
            ],
        ]);
    }

    public function deleteParaMaster($id){

        $delete = ParaMaster::where('id',base64_decode($id))->update(['is_delete' => 1]);

        $deleteParaMaster = ParaMaster::where('id',base64_decode($id))->first();
        $deleteTrail = new ParaMasterTrail;
        $paraTrail->para_master_id = $deleteParaMaster->id;
        $deleteTrail->para_code = $deleteParaMaster->para_code;
        $deleteTrail->para_description = $deleteParaMaster->para_description;
        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $deleteTrail->is_delete = 1;
        $deleteTrail->save();

        if($delete){
            return redirect(route('admin.paraMasterList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Para Master',
                    'message' => 'Para master successfully deleted',
                ],
            ]);     
        }
    }

    public function changeParaMasterStatus(Request $request){

        $status = ParaMaster::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    public function paraCodeMasterList($id, Request $request){

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $paraCodes = ParaCode::select('id', 'para_master_id', 'para_value', 'is_active')
                               ->where('para_master_id', base64_decode($id))
                               ->where('is_delete', 0)
                               ->skip($offset)
                                ->limit($perPage)
                               ->get();

        $recordCount = ParaCode::where('para_master_id', base64_decode($id))->where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','para-master')
                                      ->first();
        }

        return view('admin.masters.para_code.para_code_master_list', compact('paraCodes', 'id', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    public function addParaCodeMaster($id){
        
        return view('admin.masters.para_code.add_para_code_master', compact('id'));
    }

    public function saveParaCodeMaster(Request $request){

        $para = ParaMaster::where('id', base64_decode($request->para_master_id))->first();

        $paraCode = new ParaCode;
        $paraCode->para_master_id = base64_decode($request->para_master_id);
        $paraCode->para_code = $para->para_code;
        $paraCode->para_value = $request->para_value;
        if (Auth::guard('admin')->user()->id != '') {
            $paraCode->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $paraCode->save();

        $paraCode->save();
        $paraCodeTrail = new ParaCodesTrail;
        $paraCodeTrail->para_code_id = $paraCode->id;
        $paraCodeTrail->para_master_id = base64_decode($request->para_master_id);
        $paraCodeTrail->para_code = $para->para_code;
        $paraCodeTrail->para_value = $request->para_value;
        if (Auth::guard('admin')->user()->id != '') {
            $paraCodeTrail->created_by_user_id = Auth::guard('admin')->user()->id;
        }
        $paraCodeTrail->save();

        return redirect(route('admin.paraCodeMasterList', $request->para_master_id))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Para Master',
                'message' => 'Para master successfully added',
            ],
        ]);

    }

    public function editParaCodeMaster($id){
        
        $paraCode = ParaCode::where('id', base64_decode($id))->first();

        return view('admin.masters.para_code.edit_para_code_master', compact('paraCode'));
    }

    public function updateParaCodeMaster(Request $request){

        $para = ParaMaster::where('id', base64_decode($request->para_master_id))->first();

        $paraCode = ParaCode::findOrFail($request->id);
        $paraCode->para_master_id = base64_decode($request->para_master_id);
        $paraCode->para_code = $para->para_code;
        $paraCode->para_value = $request->para_value;
        if (Auth::guard('admin')->user()->id != '') {
            $paraCode->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $paraCode->save();

        $paraCodeTrail = new ParaCodesTrail;
        $paraCodeTrail->para_code_id = $paraCode->id;
        $paraCodeTrail->para_master_id = base64_decode($request->para_master_id);
        $paraCodeTrail->para_code = $para->para_code;
        $paraCodeTrail->para_value = $request->para_value;
        if (Auth::guard('admin')->user()->id != '') {
            $paraCodeTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $paraCodeTrail->save();

        return redirect(route('admin.paraCodeMasterList', $request->para_master_id))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Para Master',
                'message' => 'Para master successfully updated',
            ],
        ]);
    }

    public function deleteParaCodeMaster($paraMasterId, $id){
        
        $delete = ParaCode::where('id',$id)->update(['is_delete' => 1]);

        $deleteParaCodeMaster = ParaCode::where('id',base64_decode($id))->first();
        
        $deleteTrail = new ParaCodesTrail;
        $deleteTrail->para_code_id = $deleteParaCodeMaster->id;
        $deleteTrail->para_master_id = base64_decode($id);
        $deleteTrail->para_code = $deleteParaCodeMaster->para_code;
        $deleteTrail->para_value = $deleteParaCodeMaster->para_value;
        if (Auth::guard('admin')->user()->id != '') {
            $deleteTrail->updated_by_user_id = Auth::guard('admin')->user()->id;
        }
        $deleteTrail->is_delete = 1;
        $deleteTrail->save();

        if($delete){
            return redirect(route('admin.paraCodeMasterList', base64_encode($paraMasterId)))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Para Code Master',
                    'message' => 'Para code master successfully deleted',
                ],
            ]);     
        }
    }

    public function changeParaCodeMasterStatus(Request $request){
        
        $status = ParaCode::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    // excel export and download
    public function exportParaMaster(){
        return Excel::download(new ParaMasterExport, 'All Para Master  Study Management System.xlsx');
    }

     // excel export and download
    public function exportParaCode(Request $request){
        $id = $request->id;
        $export = new ParaCodeExport($id);

        return Excel::download($export, 'All Para Master  Study Management System.xlsx');
    }
}
