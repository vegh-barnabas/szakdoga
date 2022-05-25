<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'enterance_id',
        'number',
        'gender',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function enterance()
    {
        return $this->belongsTo(Enterance::class);
    }

    public function is_used()
    {
        if ($this->enterance == null) {
            return false;
        }

        return true;
    }

    public function get_user()
    {
        if ($this->enterance == null) {
            return false;
        }

        if ($this->enterance->exit != null) {
            return $this->enterance->user();
        }

        return null;
    }
}
