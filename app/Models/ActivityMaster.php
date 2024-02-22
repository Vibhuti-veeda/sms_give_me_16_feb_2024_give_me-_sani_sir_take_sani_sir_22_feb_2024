<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMaster extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function responsible(){
        return $this->hasOne('App\Models\Role', 'id', 'responsibility');
    }

    public function nextActivity(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'next_activity');
    }    

    public function previousActivity(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'previous_activity');
    }

    public function parentActivity(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'parent_activity');
    }

    public function crCode(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'CR');
    }

    public function brCode(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'BR');
    }

    public function rwCode(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'RW');
    }

    public function pbCode(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'PB');
    }

    public function psCode(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'PS');
    }

    public function activityType(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type');
    }

    public function studySchedule(){
        return $this->hasMany('App\Models\StudySchedule', 'activity_id', 'id');
    }

    public function reasonMaster(){
        return $this->hasMany('App\Models\ReasonMaster', 'activity_id', 'id');
    }

}
