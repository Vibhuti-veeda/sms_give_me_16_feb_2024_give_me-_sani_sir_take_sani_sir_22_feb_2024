<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModuleAccess extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function module_id(){
        return $this->hasOne('App\Models\RoleModule','slug','module_name');
    }
}