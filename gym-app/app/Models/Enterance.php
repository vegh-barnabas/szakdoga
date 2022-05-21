<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterance extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'user_id',
        'ticket_id',
        'enter',
        'exit',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exited()
    {
        return $this->exit != null;
    }

    public function enter()
    {
        $expiration = CarbonImmutable::Create($this->enter);

        return $expiration->format('Y. m. d. H:i');
    }

    public function exit()
    {
        $expiration = CarbonImmutable::Create($this->exit);

        return $expiration->format('Y. m. d. H:i');
    }
}
