<?php
  
namespace App\View;
  
use Illuminate\Database\Eloquent\Model;
 
class VwPreStudyProjection extends Model
{
    public $table = "vw_pre_study_projection";

    public function studyNo(){
        return $this->hasOne('App\Models\Study', 'id', 'study_id');
    }
}