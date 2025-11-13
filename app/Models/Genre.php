<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];
    
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
    use HasFactory;
}
