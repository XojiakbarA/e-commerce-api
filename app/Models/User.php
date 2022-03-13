<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subOrders()
    {
        return $this->hasManyThrough(SubOrder::class, Order::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Shop::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Order::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
