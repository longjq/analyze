<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Package extends Model
{
    protected $table = 'apps';
    public $timestamps = false;
    protected $fillable = ['name', 'package', 'user_count', 'md5', 'users'];

    public function packagesListByUid($userId)
    {
        $packages = DB::table('apps_user_list')->where('user_id', $userId)->lists('packages');
        return isset($packages[0]) ? json_decode($packages[0]) : null;
    }

    public function packagesList($package, $name)
    {
        if (!empty($package) && !empty($name)) {
            return $this->where('package', $package)->where('name', $name)->get();
        }
        if (!empty($package)) {
            return $this->where('package', $package)->get();
        }
        if (!empty($name)) {
            return $this->where('name', 'like', "%{$name}%")->get();
        }
    }

    public function userIdsByPackage($package)
    {
        return Cache::getInstance()->lists('package_' . $package, 999);
    }

    public function packagesByUserId($userId)
    {
        return json_decode(Cache::getInstance()->getHashItem('user_link_packages', $userId), false);
    }


    public function users()
    {
        return $this->belongsToMany(\App\Models\UsersList::class, 'apps_users', 'app_id', 'user_id');
    }

    public function lists()
    {
        return DB::table('apps')->leftJoin('apps_users', 'apps.id', '=', 'app_id')
            ->groupBy('id')->groupBy('package')->groupBy('name')
            ->select(DB::raw('apps.id,apps.package,apps.name,count(user_id) as uid_count'));
    }


    public function usersByPackage($package)
    {
        DB::setFetchMode(\PDO::FETCH_ASSOC);
        return DB::table('apps_users')
            ->join('apps', 'app_id', '=', 'id')
            ->where('package', $package)
            ->select('user_id')
            ->get();
    }

    public function packagesByUid($uid)
    {
        DB::setFetchMode(\PDO::FETCH_ASSOC);
        return DB::table('apps_users')
            ->join('apps', 'app_id', '=', 'id')
            ->where('user_id', $uid)
            ->select('apps.*')
            ->get();
    }
}
