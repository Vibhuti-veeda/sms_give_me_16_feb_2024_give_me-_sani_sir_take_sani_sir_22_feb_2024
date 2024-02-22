<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyDrugDetails extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function drugName(){
        return $this->hasOne('App\Models\DrugMaster', 'id', 'drug_id');
    }

    public function drugDosageName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'dosage_form_id');
    }

    public function drugUOM(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'uom_id');
    }

    public function drugType(){
        return $this->hasOne('App\Models\StudyDrugDetails', 'id', 'id');
    }
}
