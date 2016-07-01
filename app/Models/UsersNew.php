<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UserHourSave;
// 新增用户model
class UsersNew extends Model
{
    use UserHourSave;
    protected $table = 'assistant_users_new';
    public $timestamps = false;
    protected $guarded = ['id'];

    // 日期区间查询新增用户数
    public function usersByDateRange($startDate, $endDate)
    {
        return $this->where('row_date', '>=', $startDate)
            ->where('row_date', '<=', $endDate)
            ->get();
    }
}
