<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyableTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'quantity',
        'price',
        'hidden'
    ];

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
