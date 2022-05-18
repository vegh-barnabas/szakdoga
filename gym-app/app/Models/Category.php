<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;

    protected $fillable = [
        'name',
        'style',
    ];

    public function gyms()
    {
        return $this->belongsToMany(Gym::class)->withTimestamps();
    }
}
