<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleDefinedDashboardElement extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function elementName(){
        return $this->hasOne('App\Models\RoleDashboardElements','id','elements_id');
    }
}
