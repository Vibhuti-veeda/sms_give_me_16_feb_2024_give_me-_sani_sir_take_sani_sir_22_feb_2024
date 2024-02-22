<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function options(){
        return $this->hasMany('App\Models\AttributeOption','attribute_id','id');
    }
}
