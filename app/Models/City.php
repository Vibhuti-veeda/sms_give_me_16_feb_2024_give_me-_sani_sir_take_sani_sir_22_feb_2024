<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function stateName(){
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }
}
