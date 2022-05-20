<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permission',
        'credits',
        'gender',
        'exit_code',
        'prefered_gym',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function enterances()
    {
        return $this->hasMany(Enterance::class);
    }

    public function locker()
    {
        return $this->hasOne(Locker::class);
    }

    public function getPreferedGymName()
    {
        if ($this->prefered_gym == null) {
            return "";
        }

        return Gym::all()->where('id', $this->prefered_gym)->first()->name;
    }

    public function getUserType()
    {
        if ($this->permission == 'receptionist') {
            return "Recepciós";
        }

        if ($this->permission == 'admin') {
            return "Admin";
        }

        return "Vendég";
    }

    public function is_admin()
    {
        return $this->permission == 'admin';
    }

    public function is_receptionist()
    {
        return $this->permission == 'receptionist';
    }
}
