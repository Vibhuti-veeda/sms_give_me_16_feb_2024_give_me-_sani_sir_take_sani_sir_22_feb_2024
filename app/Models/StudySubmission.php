<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySubmission extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function paraSubmission(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'regulatory_submission');
    }

    public function regulatorySubmission(){
        return $this->hasMany('App\Models\ParaCode', 'id', 'regulatory_submission');
    }
}
