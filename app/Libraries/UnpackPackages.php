<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/11
 * Time: 0:16
 */

namespace App\Libraries;


use App\Models\AppsUserList;
use App\Models\Package;
use App\Models\UserPackage;
use DB;
class UnpackPackages
{
    private $userPackage;
    private $package;
    private $appsUserList;

    public function __construct()
    {
        $this->userPackage = new UserPackage();
        $this->package = new Package();
        $this->appsUserList = new AppsUserList();
    }

    public function unpack($len)
    {
        $undecodePackages = $this->appsUserList->undecodePackages($len);
        DB::beginTransaction();
        foreach ($undecodePackages as $undecode){
            $packages = json_decode($undecode->packages);
            $this->userPackage->deleteUser($undecode->user_id);
            foreach ($packages as $package){
                $this->userPackage->attachUserPackage($undecode->user_id, $package[1]);
                $this->package->syncItem([$package[0],$package[1],$package[2]]);
            }
            $undecode->decode=1;
            $undecode->save();
        }
        DB::commit();

    }


}