<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppsUserList extends Model
{
    protected $table = 'apps_user_lists';
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'packages', 'decode'];

    
    public function undecodePackages($len)
    {
        return $this->where('decode',0)->take($len)->get();
    }
    
}
