<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyActivityMetadata extends Model
{
    use HasFactory;

    protected $table = 'study_activity_metadatas';
 
    protected $connection = 'mysql';
 
    protected $guarded = ['id'];

    public function activityMetadata(){
        return $this->hasOne('App\Models\ActivityMetadata', 'id', 'activity_meta_id');
    }

    public function studySchedule(){
        return $this->hasOne('App\Models\StudySchedule', 'id', 'study_schedule_id');
    }
}
