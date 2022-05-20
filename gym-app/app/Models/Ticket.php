<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'gym_id',
        'type_id',
        'bought',
        'expiration',
        'code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function enterances()
    {
        return $this->hasMany(Enterance::class);
    }

    public function type()
    {
        return $this->belongsTo(BuyableTicket::class);
    }

    public function isMonthly()
    {
        return $this->type->isMonthly();
    }

    public function get_type()
    {
        return $this->type->isMonthly() ? 'bérlet' : 'jegy';
    }

    public function used()
    {
        if (!$this->isMonthly() && $this->enterances->count() > 0) {
            return true;
        }

        return false;
    }

    public function useable()
    {
        return ($this->expiration >= date('Y-m-d H:i:s') && !$this->used());
    }

    public function expired()
    {
        return (!$this->useable() || $this->used());
    }
}
