<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateMaster extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function countryName(){
    	return $this->hasOne('App\Models\CountryMaster', 'id', 'country_id');
    }
}
