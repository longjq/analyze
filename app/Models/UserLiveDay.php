<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLiveDay extends Model
{
    protected $table = 'live_count';
    public $timestamps = false;
    protected $fillable = ['row_date','year','mouth','day','live_count'];
}
