<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonMaster extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function reasonmaster(){
        return $this->hasMany('App\Models\ReasonMaster', 'reason_master_id', 'id')->where('is_active', 1)->where('is_delete', 0);
    }

    public function activityType(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type_id');
    }
    
    public function activityName(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'activity_id');
    }

}
