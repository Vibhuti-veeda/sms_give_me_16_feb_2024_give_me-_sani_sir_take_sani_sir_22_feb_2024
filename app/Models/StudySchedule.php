<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySchedule extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function sponsorName(){
        return $this->hasOne('App\Models\SponsorMaster', 'id', 'sponsor');
    }
    
    public function studyNo(){
        return $this->hasOne('App\Models\Study', 'id', 'study_id');
    }

    public function drugDetails(){
        return $this->hasMany('App\Models\StudyDrugDetails', 'study_id', 'study_id');
    }

    public function nextActivity(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'next_activity_id');
    }

    public function regulatorySubmission(){
        return $this->hasOne('App\Models\StudySubmission', 'project_id', 'study_id');
    }

    public function activities(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type');
    }

    public function crActivity(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'CR');
    }

    public function brActivity(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'BR');
    }    

    public function rwActivity(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'RW');
    }

    public function pbActivity(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'PB');
    }

    public function psActivity(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'activity_type')->where('para_value', 'PS');
    }

    public function activityName(){
        return $this->hasOne('App\Models\ActivityStatusMaster', 'activity_status_code', 'activity_status');
    }

    public function activityStatusName(){
        return $this->hasOne('App\Models\ActivityStatusMaster', 'activity_status_code', 'activity_status');
    }

    public function startDelayReason(){
        return $this->hasOne('App\Models\ReasonMaster', 'id', 'start_delay_reason_id');
    }

    public function startDelayReasons(){
        return $this->hasMany('App\Models\ReasonMaster', 'activity_id', 'activity_id');
    }

    public function endDelayReason(){
        return $this->hasOne('App\Models\ReasonMaster', 'id', 'end_delay_reason_id');
    }

    public function endDelayReasons(){
        return $this->hasMany('App\Models\ReasonMaster', 'activity_id', 'activity_id');
    }

    public function activityMaster(){
        return $this->hasOne('App\Models\ActivityMaster', 'id', 'activity_id');
    }

    public function response(){
        return $this->hasOne('App\Models\Role', 'id', 'responsibility_id');
    }

    public function userName(){
        return $this->hasMany('App\Models\Admin', 'role_id', 'responsibility_id');
    }

}
