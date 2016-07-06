<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTempHot extends Model
{
    protected $table = 'user_temp_hots';
    public $timestamps = false;

    public function countByDate($date)
    {
        return $this->where('row_date', $date)->where('user_id','!=','')->count();
    }

    public function deleteByDate($date)
    {
        $this->where('row_date', $date)->delete();
    }

}
