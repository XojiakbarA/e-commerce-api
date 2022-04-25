<?php

namespace App\Http\Controllers;

use App\Models\Image as ModelsImage;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductImageController extends Controller
{
    public function destroy(Product $product, ModelsImage $image)
    {
        $mainImageSrc = $product->image->src;

        if ($mainImageSrc === 'main_' . $image->src) :

            Storage::delete('public/images/products/' . $mainImageSrc);

            $product->image()->delete();

            $product->refresh();

            $firstImage = $product->images()->first();

            if ($firstImage) :
                $firstImageSrc = $firstImage->src;
                $mainImageSrc = 'main_' . $firstImageSrc;

                $inter = Image::make('storage/images/products/' . $firstImageSrc);
                $inter->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                });
                $inter->save('storage/images/products/' . $mainImageSrc);

                $product->images()->create(['src' => $mainImageSrc, 'main' => 1]);
            endif;
        endif;

        Storage::delete('public/images/products/' . $image->src);

        $deleted = $image->delete();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
