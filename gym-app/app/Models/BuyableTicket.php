<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyableTicket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'gym_id',
        'type',
        'name',
        'description',
        'quantity',
        'price',
        'hidden',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function isMonthly()
    {
        return $this->type == 'monthly';
    }
}
