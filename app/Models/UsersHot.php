<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 活跃用户model
class UsersHot extends Model
{

    protected $table = 'assistant_users_hot';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function usersByDateRange($startDate, $endDate)
    {
        return $this->where('row_date', '>=', $startDate)
            ->where('row_date', '<=', $endDate)
            ->get();
    }

}
