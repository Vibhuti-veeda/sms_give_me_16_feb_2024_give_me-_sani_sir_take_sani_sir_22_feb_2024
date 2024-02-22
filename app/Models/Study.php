<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function sponsorName(){
        return $this->hasOne('App\Models\SponsorMaster', 'id', 'sponsor');
    }

    public function studyScope(){
        return $this->hasMany('App\Models\StudyScope', 'project_id', 'id')->where('is_active', 1)->where('is_delete', 0);
    }

    public function studyRegulatory(){
        return $this->hasMany('App\Models\StudySubmission', 'project_id', 'id');
    }

    public function studyRegulatories(){
        return $this->hasOne('App\Models\StudySubmission', 'project_id', 'id');
    }

    public function drugDetails(){
        return $this->hasMany('App\Models\StudyDrugDetails', 'study_id', 'id');
    }

    public function projectManager(){
        return $this->hasOne('App\Models\Admin', 'id', 'project_manager');
    }

    public function studyType(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'study_type');
    }

    public function priorityName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'priority');
    }

    public function crLocationName(){
        return $this->hasOne('App\Models\LocationMaster', 'id', 'cr_location');
    }

    public function brLocationName(){
        return $this->hasOne('App\Models\LocationMaster', 'id', 'br_location');
    }

    public function schedule(){
        return $this->hasMany('App\Models\StudySchedule', 'study_id', 'id');
    }

    public function studyDesignName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'study_design');
    }

    public function studySubTypeName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'study_sub_type');
    }

    public function subjectTypeName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'subject_type');
    }
    
    public function blindingStatusName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'blinding_status');
    }

    public function wardName(){
        return $this->hasOne('App\Models\ClinicalWardMaster', 'id', 'clinical_word_location');
    }

    public function complexityName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'complexity');
    }

    public function studyConditionName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'study_condition');
    }
    
    public function specialNotesName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'special_notes');
    }

    public function principleInvestigator(){
        return $this->hasOne('App\Models\Admin', 'id', 'principle_investigator');
    }
    
    public function bioanalyticalInvestigator(){
        return $this->hasOne('App\Models\Admin', 'id', 'bioanalytical_investigator');
    }

    public function lastSample(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Last sample');
    }

    public function checkIn(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Checkin')->where('group_no', 1)->where('period_no', 1);
    }
    
    public function CRtoQA(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data CR to QA');
    }
    
    public function QAtoCR(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data QA to CR');
    }
    
    public function BRtoQA(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data BR to QA');
    }
    
    public function QAtoBR(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data QA to BR');
    }
    
    public function BRtoPB(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data BR to PB');
    }

    public function PBtoQA(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data PB to QA');
    }

    public function QAtoPB(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Data QA to PB');
    }
    
    public function darftToSponsor(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Draft report to PM');
    }

    public function bioanalyticalStartEnd(){
        return $this->hasOne('App\Models\StudySchedule', 'study_id', 'id')->where('activity_name', 'Bioanalytical Analysis');
    }
    
    public function scheduleDelay(){
        return $this->hasMany('App\Models\StudySchedule', 'study_id', 'id')->where('activity_status', 'DELAY')->where('is_active', 1)->where('is_delete', 0);
    }
}
