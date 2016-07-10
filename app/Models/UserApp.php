<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    protected $table = 'user_apps';
    public $timestamps = false;
    protected $fillable = ['user_id','app_name','app_unique','md5'];

    public function saveItem($userId, $appName,$appUnique,$md5)
    {
        $this->create([
            'user_id' => $userId,
            'app_name' => $appName,
            'app_unique' => $appUnique,
            'md5' => $md5,
        ]);
    }
}
