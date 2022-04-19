<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'enter',
        'exit'
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
