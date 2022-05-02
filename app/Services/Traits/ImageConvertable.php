<?php

namespace App\Services\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

trait ImageConvertable
{
    public function convertSaveImage(UploadedFile $image, string $path, int $width, int $height) : string
    {
        $inter = Image::make($image);

        $inter->fit($width, $height, function($constraint) {
            $constraint->upsize();
        });

        return $inter->save($path)->basePath();
    }
}