<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Package extends Model
{
    protected $table = 'packages';
    public $timestamps = false;
    protected $fillable = ['package_title','package_unique','md5','user_count'];

    public function listsByUserId($userId)
    {
        $packages = DB::table('apps_user_lists')->where('user_id', $userId)->lists('packages');
        return isset($packages[0]) ? json_decode($packages[0]) : null;
    }

    public function packagesList($package, $name)
    {
        if (!empty($package) && !empty($name)) {
            return $this->where('package_unique', $package)->where('package_title', $name)->get();
        }
        if (!empty($package)) {
            return $this->where('package_unique', $package)->get();
        }
        if (!empty($name)) {
            return $this->where('package_title', 'like', "%{$name}%")->get();
        }
    }


    public function syncItem(array $item)
    {
        DB::insert('INSERT INTO packages(package_title,package_unique,md5,user_count) VALUES(?,?,?,1)
ON DUPLICATE KEY UPDATE package_title=VALUES(`package_title`),
md5=VALUES(`md5`),user_count=user_count+1', $item);
    }
}
