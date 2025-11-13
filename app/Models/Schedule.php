<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('start_time', 'asc');
    }

    protected $fillable = [
        'id',           // ID
        'movie_id',     // 列
        'start_time',   // 上映開始時間
        'end_time',     // 上映終了時間 
        'created_at',   // 作成日時
        'updated_at',   // 更新日時
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function movie() 
    {
        return $this->belongsTo(Movie::class);
    }

    public function sheets()
    {
        return $this->hasMany(Sheet::class);
    }

    use HasFactory;
}
