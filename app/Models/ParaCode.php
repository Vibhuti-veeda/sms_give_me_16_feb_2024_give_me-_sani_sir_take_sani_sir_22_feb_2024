<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParaCode extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function activities(){
        return $this->hasMany('App\Models\ActivityMaster', 'activity_type', 'id');
    }

    public function studySchedule(){
        return $this->hasMany('App\Models\StudySchedule', 'activity_type', 'id');
    }

    public function reasons(){
        return $this->hasMany('App\Models\ReasonMaster', 'activity_type_id', 'id');
    }

}
