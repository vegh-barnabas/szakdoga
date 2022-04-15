<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description'
    ];

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
