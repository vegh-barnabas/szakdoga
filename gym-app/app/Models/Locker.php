<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $fillable = [
        'gym_id',
        'gender',
        'user_id',
    ];

    use HasFactory;
}
