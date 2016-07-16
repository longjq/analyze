<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    protected $table = 'user_packages';
    public $timestamps = false;
    protected $fillable = ['user_id', 'package_unique','package_title','md5'];

    public function package()
    {
        $this->belongsTo(Package::class,'package_unique','package_unique');
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
