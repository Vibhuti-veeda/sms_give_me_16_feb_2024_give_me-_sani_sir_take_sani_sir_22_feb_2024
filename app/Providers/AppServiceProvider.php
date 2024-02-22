<?php

namespace App\Providers;

use App\Models\RoleDefinedModule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view){
            
            if (Auth::guard('admin')->user()) {
                
                $checkPermission = RoleDefinedModule::where('role_id',Auth::guard('admin')->user()->role_id)->with(['moduleName'])->get();

                $module = array();

                if(!is_null($checkPermission)){
                    foreach($checkPermission as $pk => $pv){
                        $module[] = $pv->moduleName->slug;
                    }
                }

                View::share('module',$module);
            }
        });
    }
}
