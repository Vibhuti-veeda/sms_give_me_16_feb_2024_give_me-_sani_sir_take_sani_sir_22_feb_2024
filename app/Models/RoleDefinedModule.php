<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleDefinedModule extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function moduleName(){
        return $this->hasOne('App\Models\RoleModule','id','module_id');
    }

    public function module_name(){
        return $this->hasOne('App\Models\RoleModule','id','module_id');
    }
}
