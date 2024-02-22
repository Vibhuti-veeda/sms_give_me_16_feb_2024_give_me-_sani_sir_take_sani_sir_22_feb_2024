<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use App\Models\RoleModule;
use App\Models\RoleDetails;
use App\Models\RoleModuleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'admin'){

        $module = array();

        if(!Auth::guard($guard)->check()){
            return redirect(Route('admin.login'));
        } else {
            if(Auth::guard($guard)->user()->role_id == 'admin'){
                $roleModules = RoleModule::all();
                if(!is_null($roleModules)){
                    foreach ($roleModules as $rk => $rv) {
                        $module[] = $rv->name;
                    }
                }

                View::share('module',$module);
                return $next($request);
            } else {
                $role = Auth::guard($guard)->user()->role_id;

                $check_role = Admin::where('email', Auth::guard($guard)->user()->email)->where('role_id',$role)->first();
                
                if($check_role->is_active == 1 && $check_role->is_delete == 0){

                    $role_modules = RoleModuleAccess::where('role_id', $role)->get();
                    if(!is_null($role_modules)){
                        foreach ($role_modules as $rk => $rv) {
                            $module[] = $rv->module_name;
                        }
                    }

                    View::share('module',$module);

                    $default_access = array();

                    $default_access[] = 'editprofile';
                    $default_access[] = 'update-profile';
                    $default_access[] = 'change-admin-password';
                    $default_access[] = 'update-admin-password';
                    $default_access[] = 'password';

                    if(request()->segment(2) != ''){
                        if(in_array(request()->segment(2), $default_access)){
                            return $next($request);
                        }
                        if(in_array(request()->segment(2), $module)){
                            $action_access_array = array();
                            $action_access = RoleModuleAccess::where('role_id', $role)->where('module_name',request()->segment(2))->get();

                            if(!is_null($action_access)){
                                foreach ($action_access as $ak => $av) {
                                    if($av->add == '1'){
                                        $action_access_array[] = 'add';
                                    }
                                    if($av->edit == '1'){
                                        $action_access_array[] = 'edit';
                                    }
                                    if($av->delete == '1'){
                                        $action_access_array[] = 'delete';
                                    }
                                    $action_access_array[] = 'view';
                                }
                            }
                            
                            if(in_array(request()->segment(3), $action_access_array)){
                                return $next($request);
                            } else {
                                return abort(403);
                            }
                            
                        } else {
                            return abort(403);
                        }
                    } else {
                        return $next($request);
                    }
                    
                } else {

                    return redirect(route('admin.login'))->with('messages', [
                        [
                            'type' => 'error',
                            'title' => 'Login',
                            'message' => 'Your role is deleted or inactive',
                        ],
                    ]);
                    
                }
            }
        } 
    }
}