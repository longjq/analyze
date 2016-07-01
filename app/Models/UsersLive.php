<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLive extends Model
{
    protected $table = 'assistant_users_live';
    protected $guarded = [];
    public $timestamps = false;

    public function usersByDateRange($start, $end)
    {

        return $this->whereBetween('row_date', [$start, $end])
            ->where('live','!=','0')
            ->whereNotNull('live')
            ->get();
    }
}
