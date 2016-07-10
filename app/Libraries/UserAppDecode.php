<?php
/**
 * Created by PhpStorm.
 * User: longjq
 * Date: 2016/7/8
 * Time: 14:04
 */

namespace App\Libraries;


use App\Models\UserApp;

class UserAppDecode
{
    private $appUser;

    public function __construct()
    {
        $this->appUser = new UserApp();
    }

    public function decodeApp()
    {
        
    }
}