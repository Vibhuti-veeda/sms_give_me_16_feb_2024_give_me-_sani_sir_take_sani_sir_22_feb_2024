<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParaMaster extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function paraCode(){
        return $this->hasMany('App\Models\ParaCode', 'para_master_id', 'id')->where('is_active', 1)->where('is_delete', 0)->orderBy('para_value');
    }
}
