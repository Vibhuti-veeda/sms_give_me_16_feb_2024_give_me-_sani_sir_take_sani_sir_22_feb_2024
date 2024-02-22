<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMetadataTrail extends Model
{
    use HasFactory;

    protected $connection = 'mysql1';

    protected $guarded = ['id'];
}
