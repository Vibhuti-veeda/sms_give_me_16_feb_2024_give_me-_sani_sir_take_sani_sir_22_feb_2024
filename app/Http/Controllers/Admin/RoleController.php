<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleModule;
use App\Models\Role;
use App\Models\RoleModuleAccess;
use App\Models\RoleDashboardElements;
use App\Models\RoleDefinedDashboardElement;
use App\Models\RoleDefinedModule;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Exports\RoleExport;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{
    public function __construct(){
       $this->middleware('admin');
    }

    // Role List
    public function roleList(Request $request){

        $perPage = 10;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $data = Role::select('id', 'name', 'is_active')
                    ->where('is_delete',0)
                    ->with([
                        'defined_module' => function($q){ 
                            $q->with([
                                'module_name'
                            ]);
                        },
                        'defined_elements' => function($q){ 
                            $q->with([
                                'elementName'
                            ]);
                        }
                    ])
                    ->skip($offset)
                    ->limit($perPage)
                    ->get();

        $recordCount = Role::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);

        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)
                                      ->where('module_name','role')
                                      ->first();
        }

        return view('admin.masters.role.role_list',compact('data','admin','access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    // Add Role
    public function addRole(){

        Session::forget('temp');
        $role_modules = RoleModule::where('is_active', 1)->where('is_delete', 0)->get();
        $role_dashboard_elements = RoleDashboardElements::where('is_active', 1)->where('is_delete', 0)->get();
        return view('admin.masters.role.add_role',compact('role_modules','role_dashboard_elements'));
    }

    // Save Role
    public function saveRole(Request $request){
        
        $data = new Role;
        $data->name = $request->role_name;
        $data->save();

        if(!is_null($request->role_modules)){
            foreach ($request->role_modules as $key => $value) {
                $define_module_data = new RoleDefinedModule;
                $define_module_data->role_id = $data->id;
                $define_module_data->module_id = $value;
                $define_module_data->save();
            }
        }

        if(!is_null($request->role_dashboard_elements)){
            foreach ($request->role_dashboard_elements as $k => $val) {
                $element_data = new RoleDefinedDashboardElement;
                $element_data->role_id = $data->id;
                $element_data->elements_id = $val;
                $element_data->save();
            }
        }

        if(!is_null($request->role_modules)){
            foreach ($request->role_modules as $key => $value) {
                $module_data = new RoleModuleAccess;
                $mod_name = RoleModule::where('id',$value)->first();
                $module_data->role_id = $data->id;
                $module_data->module_name = $mod_name->slug;

                if($mod_name->slug == 'policy'){
                    $module_data->add = 1;
                    $module_data->edit = 1;
                    $module_data->delete = 1;
                } else {
                    if (!is_null($request->add) && array_key_exists($value,$request->add)){
                        $module_data->add = 1;
                    }
                    if (!is_null($request->edit) && array_key_exists($value,$request->edit)){
                        $module_data->edit = 1;
                    }
                    if (!is_null($request->delete) && array_key_exists($value,$request->delete)){
                        $module_data->delete = 1;
                    }
                }
                $module_data->view = 1;

                $module_data->save();
            }
        }

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addRole' : 'admin.roleList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Role',
                'message' => 'Role successfully added',
            ],
        ]);
    }

    // Edit Role
    public function editRole($id){

        Session::forget('temp');
        $role_modules = RoleModule::where('is_active', 1)->where('is_delete', 0)->get();
        $role_dashboard_elements = RoleDashboardElements::where('is_active', 1)->where('is_delete', 0)->get();

        $data = Role::where('id',$id)
                    ->where('is_delete',0)
                    ->with([
                        'defined_module' => function($q){ 
                            $q->with([
                                'module_name'
                            ]);
                        },
                        'defined_elements' => function($q){ 
                            $q->with([
                                'elementName'
                            ]);
                        }
                    ])
                    ->first();
        
        $module_id = array();
        $elements_id = array();
        if(!is_null($data->defined_module)){
            foreach ($data->defined_module as $key => $value) {
                $module_id[] = $value->module_id;
            }
        }
        if(!is_null($data->defined_elements)){
            foreach ($data->defined_elements as $key => $value) {
                $elements_id[] = $value->elements_id;
            }
        }
        $module_access = RoleModuleAccess::where('role_id',$id)->with(['module_id'])->get();

        return view('admin.masters.role.edit_role',compact('data','role_modules','role_dashboard_elements','module_id','elements_id','module_access'));
    }

    // Save Edited Role
    public function updateRole(Request $request){

        $update_data = Role::findOrFail($request->role_id);

        $update_data->name = $request->role_name;
        $update_data->save();

        RoleDefinedModule::where('role_id',$update_data->id)->delete();
        RoleDefinedDashboardElement::where('role_id',$update_data->id)->delete();
        RoleModuleAccess::where('role_id',$update_data->id)->delete();

        if(!is_null($request->role_modules)){
            foreach ($request->role_modules as $key => $value) {
                
                $define_module_data = new RoleDefinedModule;
                $define_module_data->role_id = $update_data->id;
                $define_module_data->module_id = $value;
                $define_module_data->save();
            }
        }

        if(!is_null($request->role_dashboard_elements)){
            foreach ($request->role_dashboard_elements as $key => $value) {
                
                $element_data = new RoleDefinedDashboardElement;
                $element_data->role_id = $update_data->id;
                $element_data->elements_id = $value;
                $element_data->save();
            }
        }

        if(!is_null($request->role_modules)){
            foreach ($request->role_modules as $key => $value) {
                
                $module_data = new RoleModuleAccess;
                $mod_name = RoleModule::where('id',$value)->first();
                $module_data->role_id = $update_data->id;
                $module_data->module_name = $mod_name->slug;

                if($mod_name->slug == 'policy'){
                    $module_data->add = 1;
                    $module_data->edit = 1;
                    $module_data->delete = 1;
                } else {
                    if (!is_null($request->add) && array_key_exists($value,$request->add)){
                        $module_data->add = 1;
                    }
                    if (!is_null($request->edit) && array_key_exists($value,$request->edit)){
                        $module_data->edit = 1;
                    }
                    if (!is_null($request->delete) && array_key_exists($value,$request->delete)){
                        $module_data->delete = 1;
                    }
                }
                $module_data->view = 1;
                
                $module_data->save();
            }
        }

        return redirect(route('admin.roleList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Role',
                'message' => 'Role successfully updated',
            ],
        ]); 
    }

    // Delete Role
    public function deleteRole($id){

        $deleteProject = Role::where('id',$id)->update(['is_delete' => 1]);

        if($deleteProject){

            return redirect(route('admin.roleList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Role',
                    'message' => 'Role successfully deleted',
                ],
            ]);     
        }
    }
    
    // Show Modules to provide Access
    public function moduleAccessChange(Request $request){
        
        $temp = Session::get('temp');
        $add_array = [];
        $edit_array = [];
        $delete_array = [];
        $view_array = [];

        // add array
        if(!empty($temp[0]['add_option'])){
            foreach($temp[0]['add_option'] as $ak => $av){
                if(isset($av)){
                    $add_array[$ak] = $av;
                }
            }
        }

        // edit array
        if(!empty($temp[0]['edit_option'])){
            foreach($temp[0]['edit_option'] as $ek => $ev){
                if(isset($ev)){
                    $edit_array[$ek] = $ev;
                }
            }
        }

        // delete array
        if(!empty($temp[0]['delete_option'])){
            foreach($temp[0]['delete_option'] as $dk => $dv){
                if(isset($dv)){
                    $delete_array[$dk] = $dv;
                }
            }
        }

        // view array
        if(!empty($temp[0]['view_option'])){
            foreach($temp[0]['view_option'] as $vk => $vv){
                if(isset($vv)){
                    $view_array[$vk] = $vv;
                }
            }
        }

        $html = array();
        $i = 0;
        $j = 1;
        
        if(!is_null($request->modules)){
            foreach ($request->modules as $key => $value) {
                $getName = RoleModule::where('id',$value)->first();
                $getId = $getName->id;
                $module_name = '';
            
                $module_name =$getName->name;

                $html[] ='<tr><th scope="row">'.$j.'</th><td id="module_name" value="'.$getName->id.'">'.ucfirst($module_name).'</td><td><div class="form-check mb-3" wfd-id="141"><input class="form-check-input add_func add_value" type="checkbox" data-id="'.$getName->id.'" '. ((array_key_exists($getId,$add_array))? 'checked' : '' ).' name="add['.$getId.']" id="add_'.$getName->id.'" wfd-id="365" '.($getName->name == 'policy' ? 'disabled checked' : '').'></div></td><td><div class="form-check mb-3" wfd-id="141"><input class="form-check-input add_func edit_value" type="checkbox" data-id="'.$getName->id.'" name="edit['.$getId.']" id="edit_'.$getName->id.'" '. ((array_key_exists($getId,$edit_array))? 'checked' : '' ).' wfd-id="365" '.($getName->name == 'policy' ? 'disabled checked' : '').'></div></td><td><div class="form-check mb-3" wfd-id="141"><input class="form-check-input add_func delete_value" type="checkbox" data-id="'.$getName->id.'" '. ((array_key_exists($getId,$delete_array))? 'checked' : '' ).' name="delete['.$getId.']" id="delete_'.$getName->id.'" wfd-id="365" '.($getName->name == 'policy' ? 'disabled checked' : '').'></div></td><td><div class="form-check mb-3" wfd-id="141"><input disabled class="form-check-input view_value" type="checkbox" name="view['.$getId.']" checked id="view_'.$getName->id.'" wfd-id="365"></div></td></tr>';
                $i++;
                $j++;
            }
        }
        
        return response()->json(['html'=>$html]);
    }

    // Role Status Change - Ajax Call
    public function roleStatusChange(Request $request){

        $changeStatus = Role::where('id',$request->id)->update(['is_active' => $request->option]);

        return $changeStatus ? 'true' : 'false';
    }

    // Save Module Access in Session - Ajax Call
    public function sessionStore(Request $request){
        
        Session::forget('temp');
        Session::push('temp', $request->all());
    }

    // Remove Particular Module Access When unselect from the Multi-Select - Ajax Call
    public function removeTempArray(Request $request){

        $add = Session::get('temp.0.add_option');
        $edit = Session::get('temp.0.edit_option');
        $delete = Session::get('temp.0.delete_option');
        $view = Session::get('temp.0.view_option');
        $id = array();
        if(!empty($request->id)){
            foreach($request->id as $ik => $iv){
                $id[] = $iv;
            }
            
            // Ad array
            if(!empty($add)){
                foreach($add as $ak => $av){
                    if (!in_array($ak,$id)) {
                        unset($add[$ak]);
                        Session::put('temp.0.add_option',$add);
                    }
                }
            }

            // Edit array
            if(!empty($edit)){
                foreach($edit as $ek => $ev){
                    if (!in_array($ek,$id)) {
                        unset($edit[$ek]);
                        Session::put('temp.0.edit_option',$edit);
                    }
                }
            }

            // Delete array
            if(!empty($delete)){
                foreach($delete as $dk => $dv){
                    if (!in_array($dk,$id)) {
                        unset($delete[$dk]);
                        Session::put('temp.0.delete_option',$delete);
                    }
                }
            }

            // View array
            if(!empty($view)){
                foreach($view as $vk => $vv){
                    if (!in_array($vk,$id)) {
                        unset($view[$vk]);
                        Session::put('temp.0.view_option',$view);
                    }
                }
            }
        }
    }

    // Check role exists or not
    public function checkRoleExist(Request $request)
    {   

        $query = Role::where('is_delete',0)->where('name', $request->role_name);
        if(isset($request->role_name)) {
            $query->where('name','!=',$request->role_name);
        }
        $role = $query->first();

        return $role ? 'false' : 'true';
    }
    
    // excel export and download
    public function exportRoles(){
        return Excel::download(new RoleExport, 'All Roles  Study Management System.xlsx');
    }

}