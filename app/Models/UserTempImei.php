<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTempImei extends Model
{
    protected $table = 'user_temp_imeis';
    public $timestamps = false;

    public function countByDate($date)
    {
        return $this->where('row_date', $date)->where('imei','!=','')->count();
    }

    public function deleteByDate($date)
    {
        $this->where('row_date', $date)->delete();
    }
}
