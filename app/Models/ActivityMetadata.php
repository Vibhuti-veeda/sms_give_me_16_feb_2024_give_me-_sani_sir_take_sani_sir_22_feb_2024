<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMetadata extends Model
{
    use HasFactory;

    protected $table = 'activity_metadatas';

    protected $connection = 'mysql';

    public function activityName(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'activity_id');
    }

    public function controlName(){
        return $this->hasOne('App\Models\ControlMaster', 'id', 'control_id');
    }

    public function studyActivityMetadata(){
        return $this->hasOne('App\Models\StudyActivityMetadata', 'activity_meta_id', 'id');
    }
}
