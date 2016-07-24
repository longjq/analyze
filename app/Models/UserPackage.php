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
        $notInPackageUniques = [
            'android',
            'com.svox.pico',
            'org.simalliance.openmobileapi.service',
            'androidhwext',
            'com.fingerprints.service',
            'org.codeaurora.ims',
            'com.amap.android.location',
            'com.dsi.ant.server',
            'com.fw.upgrade.sysoper',
            'com.fw.upgrade',
            'com.mtk.telephony',
            'com.lesports.glivesports',
            'com.dolby',
            'com.dolby.daxappUI',
            'com.uei.quicksetsdk.letv',
            'sina.mobile.tianqitongletv',
            'com.uei.quicksetsdk.letv',
            'com.wdstechnology.android.kryten'
        ];
        $notLikePackageUniques = [
            'com.mediatek%',
            'com.example%',
            'com.letv%'
        ];
        $query = $this->select(DB::raw('package_unique,COUNT(user_id) as user_count_group'))
            ->whereNotIn('package_unique', $notInPackageUniques);

        foreach ($notLikePackageUniques as $item){
            $query = $query->where('package_unique', 'not like',$item);
        }

       return $query->groupBy('package_unique')->orderBy('user_count_group', 'desc');
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
