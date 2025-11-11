<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sheet extends Model
{
    protected $fillable = [
        'id',
        'column',
        'row',
    ];

    public $timestamps = false;

    use HasFactory;
}
