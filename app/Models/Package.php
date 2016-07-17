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

    public function lists()
    {
        return DB::table('packages')
            ->select(DB::raw('packages.package_title,packages.package_unique,COUNT(user_packages.user_id) as user_id_count'))
            ->leftJoin('user_packages', 'packages.package_unique', '=', 'user_packages.package_unique')
            ->groupBy('packages.package_unique')
            ->groupBy('packages.package_title')
            ->orderBy('user_id_count');
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

    public function usersPackage()
    {
        return $this->hasMany(UserPackage::class,'package_unique','package_unique');
    }
}
