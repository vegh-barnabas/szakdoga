<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gym_id',
        'buyable_ticket_id',
        'type', // For easier Eloquents methods
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

    public function buyable_ticket()
    {
        return $this->belongsTo(BuyableTicket::class);
    }

    public function isMonthly()
    {
        return $this->buyable_ticket->isMonthly();
    }

    public function get_type()
    {
        return $this->buyable_ticket->isMonthly() ? 'bérlet' : 'jegy';
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
        return ($this->expiration >= date('Y-m-d') && !$this->used());
    }

    public function expired()
    {
        return ($this->expiration < date('Y-m-d'));
    }

    public function bought()
    {
        $expiration = CarbonImmutable::Create($this->bought);

        return $expiration->format('Y. m. d.');
    }

    public function expiration()
    {
        $expiration = CarbonImmutable::Create($this->expiration);

        return $expiration->format('Y. m. d.');
    }

    public function use_date()
    {
        if ($this->isMonthly()) {
            return null;
        }

        $enterance = CarbonImmutable::Create($this->enterances->first()->enter);

        return $enterance->format('Y. m. d. H:i');
    }
}
