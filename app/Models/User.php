<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Intervention\Image\Facades\Image;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function shopOrders()
    {
        return $this->hasManyThrough(ShopOrder::class, Order::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Order::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function image()
    {
        return $this->hasOne(UserImage::class);
    }

    public static function makeImage($image, $width, $height)
    {
        $imageName = $image->hashName();
        $inter = Image::make($image);
        $inter->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        });
        $inter->save('storage/images/users/' . $imageName);

        return $imageName;
    }
}
