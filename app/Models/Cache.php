<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    protected $table = 'caches';
    protected $fillable = ['key','value'];
    public $timestamps = false;

    // 修改对应key的value值
    public function updateValue($key, $value)
    {
        return $this->where('key', $key)->update([
            'value' => $value
        ]);
    }
}
