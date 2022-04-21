<?php

namespace App\Models;

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
        'exit'
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function exited() {
        return $this->exit != null;
    }
}
