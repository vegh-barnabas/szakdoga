<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locker extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillabe = [
        'gym_id',
        'number',
        'gender',
    ];

    public function gym()
    {
        $this->belongsTo(Gym::class)->withTimestamps();
    }
}
