<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyScope extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function scopeName(){
        return $this->hasOne('App\Models\ParaCode', 'id', 'scope');
    }
}
