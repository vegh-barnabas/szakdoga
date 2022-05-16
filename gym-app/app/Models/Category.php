<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const styles = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'dark',
    ];

    use HasFactory;

    protected $fillable = [
        'gym_id',
        'name',
        'style',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
