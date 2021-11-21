<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use Filterable;

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)
                    ->where('main', false);
    }

    public function image()
    {
        return $this->hasOne(ProductImage::class)
                    ->where('main', true)
                    ->withDefault(['src' => 'no_image.jpeg']);
    }
}
