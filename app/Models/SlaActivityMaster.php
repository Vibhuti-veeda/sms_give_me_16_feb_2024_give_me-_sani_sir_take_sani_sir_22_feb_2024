<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaActivityMaster extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function activityName(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'activity_id');
    }
    public function studyDesign(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'study_design');
    }
}
