<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationMaster;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use Hash;
use Auth;
use App\Models\RoleModuleAccess;
use App\Exports\TeamMemberExport;
use Maatwebsite\Excel\Facades\Excel;

class TeamMemberController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    /**
        * Team member list
        *
        * @param mixed $filter, $status, $roleId, $roles, $members
        *
        * @return to team members listing page
    **/
    public function teamMemberList(Request $request){

        $filter = 0;
        $status = '';
        $roleId = '';

        $perPage = 25;
        if($request->page != ''){
            $page = $request->page;
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $roles = Role::where('is_active', 1)->where('is_delete',0)->get();
        $query = Admin::select('id', 'name', 'role_id', 'email', 'location_id', 'is_active')
                      ->where('is_delete', 0);

        $queryCount = Admin::where('is_delete', 0);              
        if(isset($request->status) && $request->status != ''){
            $filter = 1;
            $status = $request->status;
            $query->where('is_active',$status);
            $queryCount->where('is_active',$status);
        }

        if(isset($request->role) && $request->role != ''){
            $filter = 1;
            $roleId = $request->role;
            $query->where('role_id',$roleId);
            $queryCount->where('role_id', $roleId);
        }

        $members = $query->with(['role', 'location'])
                        ->skip($offset)
                        ->limit($perPage)
                        ->get();

        $recordCount = $queryCount->count();
        $pageCount = ceil($recordCount / $perPage);
        
        $admin = '';
        $access = '';
        if(Auth::guard('admin')->user()->role == 'admin'){
            $admin = 'yes';
        } else {
            $access = RoleModuleAccess::where('role_id', Auth::guard('admin')->user()->role_id)->where('module_name','team-member')->first();
        }

        return view('admin.masters.team.team_member_list', compact('members', 'filter', 'status', 'roles', 'roleId', 'admin', 'access', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    /**
        * Add tem member
        *
        * @param mixed $roles
        *
        * @return to add team member page
    **/
    public function addTeamMember(){

        $roles = Role::where('is_active', 1)->where('is_delete',0)->get();
        $locations = LocationMaster::where('is_active', 1)->where('is_delete',0)->get();

        return view('admin.masters.team.add_team_member', compact('roles', 'locations'));   
    }

    /**
        * Save team member 
        *
        * @param $name, $role_id, $login_id, $mobile, $email, $password, $employee_code, $department,
        *        $department_no, $designation, $designation_no fields save in Admin database
        *
        * @return to team members listing page with data store in Admin database
    **/
    public function saveTeamMember(Request $request){

        $member = new Admin;
        $member->name = $request->full_name;
        $member->login_id = $request->login_id;
        $member->employee_code = $request->employee_code;
        $member->department = $request->department;
        $member->department_no = $request->department_no;
        $member->designation = $request->designation;
        $member->designation_no = $request->designation_no;
        $member->mobile = $request->mobile;
        $member->email = $request->email;
        if($request->role_id != ''){
            $member->role_id = $request->role_id;
        } else {
            $member->role_id = Null;
        }
        if (isset($request->location_id) && $request->location_id != '') {
            $member->location_id = $request->location_id;
        } else {
            $member->location_id = 0;
        }
        $member->save();

        $route = $request->btn_submit == 'save_and_update' ? 'admin.addTeamMember' : 'admin.teamMemberList';

        return redirect(route($route))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Team Member',
                'message' => 'Team member successfully added',
            ],
        ]);
    }

    /**
        * Edit team member 
        *
        * @param mixed $member, $roles
        *
        * @return to edit team member page
    **/
    public function editTeamMember($id){

        $member = Admin::where('id', base64_decode($id))->first();
        $roles = Role::where('is_active', 1)->where('is_delete',0)->get();
        $locations = LocationMaster::where('is_active', 1)->where('is_delete',0)->get();

        return view('admin.masters.team.edit_team_member', compact('member', 'roles', 'locations'));   
    }

    /**
        * Update team member 
        *
        * @param $name, $role_id, $login_id, $mobile, $email, $password, $employee_code, $department,
        *        $department_no, $designation, $designation_no fields save in Admin database
        *
        * @return to team members listing page with data store in Admin database
    **/
    public function updateTeamMember(Request $request){

        $member = Admin::findOrFail($request->id);
        $member->name = $request->full_name;
        $member->login_id = $request->login_id;
        $member->employee_code = $request->employee_code;
        $member->department = $request->department;
        $member->department_no = $request->department_no;
        $member->designation = $request->designation;
        $member->designation_no = $request->designation_no;
        if($request->role_id != ''){
            $member->role_id = $request->role_id;
        } else {
            $member->role_id = Null;
        }
        $member->mobile = $request->mobile;
        $member->email = $request->email;
        if (isset($request->location_id) && $request->location_id != '') {
            $member->location_id = $request->location_id;
        } else {
            $member->location_id = 0;
        }
        $member->save();

        return redirect(route('admin.teamMemberList'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Team Member',
                'message' => 'Team member successfully updated',
            ],
        ]);
    }

    /**
        * Delete team member 
        *
        * @param $id
        *
        * @return to team member listing page with data delete from Admin database
    **/
    public function deleteTeamMember($id){

        $delete = Admin::where('id',base64_decode($id))->update(['is_delete' => 1]);

        if($delete){

            return redirect(route('admin.teamMemberList'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Team Member',
                    'message' => 'Team member successfully deleted',
                ],
            ]);     
        }
    }

    /**
        * Team member status change
        *
        * @param $id, $option
        *
        * @return to team member listing page change on toggle Admin active & deactive
    **/
    public function changeTeamMemberStatus(Request $request){

        $status = Admin::where('id',$request->id)->update(['is_active' => $request->option]);

        return $status ? 'true' : 'false';
    }

    /**
        * Check team member email exist
        *
        * @param $id, $email
        *
        * @return to team member add & edit page with if team member email exist or not
    **/
    public function checkTeamMemberEmailExist(Request $request){
        
        $query = Admin::where('is_delete',0)->where('email', $request->email);
        if(isset($request->id)) {
            $query->where('id','!=',$request->id);
        }
        $email = $query->first();

        return $email ? 'false' : 'true';
    }

    // excel export and download
    public function exportTeamMembers(){
        return Excel::download(new TeamMemberExport, 'All Team members  Study Management System.xlsx');
    }
}
