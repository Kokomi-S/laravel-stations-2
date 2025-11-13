<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_url',
        'genre_id',
        'published_year',
        'is_showing',
        'description',
    ];

    protected $casts = [
        'is_showing' => 'boolean',
        'published_year' => 'integer',
    ];
    
    protected $attributes = [
        'is_showing' => false,
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function genre() 
    {
        return $this->belongsTo(Genre::class);
    }
}
