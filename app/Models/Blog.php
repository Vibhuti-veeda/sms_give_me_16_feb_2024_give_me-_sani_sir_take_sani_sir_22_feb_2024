<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    public function blogCategory(){
        return $this->hasOne('App\Models\BlogCategory', 'id', 'blog_category_id');
    }
}
