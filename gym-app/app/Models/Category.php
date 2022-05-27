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
        'name',
        'style',
    ];

    public function gyms()
    {
        return $this->belongsToMany(Gym::class)->withTimestamps();
    }
}
