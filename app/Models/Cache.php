<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    protected $table = 'caches';
    protected $guarded = ['id'];
    public $timestamps = false;
}
