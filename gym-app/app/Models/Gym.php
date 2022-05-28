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
        'description',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function buyable_tickets()
    {
        return $this->hasMany(BuyableTicket::class);
    }

    public function lockers()
    {
        return $this->hasMany(Locker::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($gym) {
            $gym->buyable_tickets()->delete();
            $gym->tickets()->delete();

            $users = User::all()->where('prefered_gym', $gym->id);
            foreach ($users as $user) {
                if ($user->is_receptionist()) {
                    // delete receptionists
                    $user->delete();
                } else {
                    // detach favourite gym fields from users
                    $user->prefered_gym = null;
                    $user->save();
                }
            }

            $gym->categories()->detach();
            $gym->lockers()->delete();
        });
    }
}
