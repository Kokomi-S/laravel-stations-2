<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'image_url',
        'genre_id',
        'published_year',
        'is_showing',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'is_showing' => false,
    ];

    public function genre() {
        return $this->belongsTo(Genre::class);
    }
}
