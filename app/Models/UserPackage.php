<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserPackage extends Model
{
    protected $table = 'user_packages';
    public $timestamps = false;
    protected $fillable = ['user_id', 'package_unique','package_title','md5'];

    public function package()
    {
        return $this->belongsTo(Package::class,'package_unique','package_unique');
    }
    
    // 大数据量分页
    public function lists()
    {
       return $this->select(DB::raw('package_unique,COUNT(user_id) as user_count_group'))->groupBy('package_unique')->orderBy('user_count_group', 'desc');
    }

    public function deleteUser($userId)
    {
        $this->where('user_id',$userId)->delete();
    }

    public function attachUserPackage($userId, $package)
    {
        $this->create([
            'user_id' => $userId,
            'package_unique' => $package[1],
        ]);
    }
}
