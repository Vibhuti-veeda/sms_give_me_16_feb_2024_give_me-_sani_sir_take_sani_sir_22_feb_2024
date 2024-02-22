<?php

namespace App\Http\Middleware;

use App\Models\RoleDefinedModule;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    **/
    public function handle(Request $request, Closure $next)
    {
        $checkPermission = RoleDefinedModule::where('role_id',Auth::guard('admin')->user()->role_id)->with(['moduleName'])->get();

        $module = array();

        if(!is_null($checkPermission)){
            foreach($checkPermission as $pk => $pv){
                $module[] = $pv->moduleName->slug;
            }
        }

        if(in_array(request()->segment(2),$module)){
            return $next($request);
        } else {
            abort('403');
        }
    }
}
