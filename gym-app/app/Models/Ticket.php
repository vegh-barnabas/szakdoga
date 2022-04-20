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
        'type_id',
        'expiration',
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

    public function type() {
        return $this->belongsTo(BuyableTicket::class);
    }

    public function used() {
        if($this->type == "jegy" && $this->enterances->count() > 0) return true;

        return false;
    }
}
