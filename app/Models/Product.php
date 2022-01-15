<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Product extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = [];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->where('main', false);
    }

    public function image()
    {
        return $this->hasOne(ProductImage::class)->where('main', true);
    }

    public static function makeImage($image, $prefix, $width, $height)
    {
        $imageName = $prefix . $image->hashName();

        $inter = Image::make($image);
        $inter->fit($width, $height, function($constraint) {
            $constraint->upsize();
        });
        $inter->save('storage/images/products/' . $imageName);

        return $imageName;
    }
}
