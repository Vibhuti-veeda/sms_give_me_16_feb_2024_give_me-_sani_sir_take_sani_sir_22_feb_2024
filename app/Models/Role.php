<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function definedModules(){
        return $this->hasMany('App\Models\RoleDefinedModule','role_id','id');
    }

    public function definedElements(){
        return $this->hasMany('App\Models\RoleDefinedDashboardElement','role_id','id');
    }

    public function principleInvestigator(){
        return $this->hasMany('App\Models\Admin', 'role_id', 'id');
    }
    
    public function bioanalyticalInvestigator(){
        return $this->hasMany('App\Models\Admin', 'role_id', 'id');
    }

    public function projectHead(){
        return $this->hasMany('App\Models\Admin', 'role_id', 'id');
    }

    public function defined_module(){
        return $this->hasMany('App\Models\RoleDefinedModule','role_id','id');
    }

    public function defined_elements(){
        return $this->hasMany('App\Models\RoleDefinedDashboardElement','role_id','id');
    }

    public function module_access(){
        return $this->hasMany('App\Models\RoleModuleAccess','role_id','id');
    }

    public function activities(){
        return $this->hasMany('App\Models\ActivityMaster','responsibility','id');
    }  
}
