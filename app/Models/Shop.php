<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function AvImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('status', 'avatar');
    }

    public function BgImageBig()
    {
        return $this->morphOne(Image::class, 'imageable')->where('status', 'bg_big');
    }

    public function BgImageSmall()
    {
        return $this->morphOne(Image::class, 'imageable')->where('status', 'bg_small');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
