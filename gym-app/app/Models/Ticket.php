<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gym_id',
        'type',
        'name',
        'expiration',
        'used',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function gym() {
        return $this->belongsTo(Gym::class);
    }

    public function enterances() {
        return $this->hasMany(Enterance::class);
    }
}
