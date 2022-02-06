<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function imageable()
    {
        return $this->morphTo();
    }

    public static function makeImage($image, $src, $width, $height)
    {
        $inter = \Intervention\Image\Facades\Image::make($image);

        $inter->fit($width, $height, function($constraint) {
            $constraint->upsize();
        });

        $inter->save($src);
    }
}
