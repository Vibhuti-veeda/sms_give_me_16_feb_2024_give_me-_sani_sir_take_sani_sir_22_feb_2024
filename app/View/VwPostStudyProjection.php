<?php
  
namespace App\View;
  
use Illuminate\Database\Eloquent\Model;
 
class VwPostStudyProjection extends Model
{
    public $table = "vw_post_study_projection";

    public function studyNo(){
        return $this->hasOne('App\Models\Study', 'id', 'study_id');
    }
}