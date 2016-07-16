<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Package extends Model
{
    protected $table = 'packages';
    public $timestamps = false;
    protected $fillable = ['package_title','package_unique','md5','user_count'];

    public function listsByUserId($userId,$isMd5 = false)
    {
        $packages = DB::table('apps_user_lists')->where('user_id', $userId)->lists('packages');
        $packages = $packages[0];
        if ($isMd5){
            $packages = json_decode($packages[0]);
            $packages = array_filter($packages,function($item){
                return isset($item[3]);
            });
        }
        return isset($packages) ? $packages : null;
    }

    public function packagesList($package, $name, $isMd5 = false)
    {
        $query = $this->newQuery();
        if ($isMd5){
            $query = $query->whereNotNull('md5');
        }
        if (!empty($package) && !empty($name)) {
            return $query->where('package_unique', $package)->where('package_title', $name)->get();
        }
        if (!empty($package)) {
            return $query->where('package_unique', $package)->get();
        }
        if (!empty($name)) {
            return $query->where('package_title', 'like', "%{$name}%")->get();
        }
    }


    public function syncItem(array $item)
    {
        DB::insert('INSERT INTO packages(package_title,package_unique,md5,user_count) VALUES(?,?,?,1)
ON DUPLICATE KEY UPDATE package_title=VALUES(`package_title`),
md5=VALUES(`md5`),user_count=user_count+1', $item);
    }
}
