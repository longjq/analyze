<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;

class RecordGrid extends Model
{
    protected $table = 'user_live_grid';
    public $timestamps = false;
    protected $primaryKey = 'row_date';
    protected $fillable = ['year','month','day','row_date','d1','d3','d7','d15','d30','users'];

    public function saveLiveGrid($dayField, DateHelper $dateHelper)
    {
        $grid = $this->firstOrNew([
            'year' => $dateHelper->getYear(),
            'month' => $dateHelper->getMonth(),
            'day' => $dateHelper->getDay(),
            'row_date' => $dateHelper->getDateFormat()
        ]);
        $grid->{$dayField} = intval($grid->{$dayField}) + 1;
        $grid->save();
    }
}
