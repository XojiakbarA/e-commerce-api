<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->morphMany(Image::class, 'imageable');
    }

    public function mainImage()
    {
        return $this->images()->where('main', true)->first();
    }

    public function getMainImageAttribute()
    {
        if ($this->images) :
            $mainImage = $this->mainImage();

            if($mainImage) :
                $urlArr = explode('/', $mainImage->src);

                $index = count($urlArr) - 1;

                array_splice($urlArr, $index, 0, 'main');

                return implode('/', $urlArr);
            endif;
        endif;
        return null;
    }
}
