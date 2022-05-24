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
        return $this->belongsTo(Gym::class);
    }

    public function enterances()
    {
        return $this->belongsToMany(Enterance::class);
    }

    public function is_used()
    {
        foreach ($this->enterances as $enterance) {
            if ($enterance->exit != null) {
                return true;
            }
        }

        return false;
    }

    public function get_user()
    {
        foreach ($this->enterances as $enterance) {
            if ($enterance->exit != null) {
                return $enterance->user();
            }
        }

        return null;
    }
}
