<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function AvImage()
    {
        return $this->hasOne(ShopImage::class)->where('status', 'avatar');
    }

    public function BgImageBig()
    {
        return $this->hasOne(ShopImage::class)->where('status', 'bg_big');
    }

    public function BgImageSmall()
    {
        return $this->hasOne(ShopImage::class)->where('status', 'bg_small');
    }

    public function images()
    {
        return $this->hasMany(ShopImage::class);
    }

    public static function makeImage($image, $prefix, $width, $height)
    {
        $imageName = $prefix . $image->hashName();

        $inter = Image::make($image);
        $inter->fit($width, $height, function($constraint) {
            $constraint->upsize();
        });
        $inter->save('storage/images/shops/' . $imageName);

        return $imageName;
    }
}
