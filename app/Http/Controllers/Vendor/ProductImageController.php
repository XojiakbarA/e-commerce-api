<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $shop_id, $product_id, $image_id)
    {
        $shop = $request->user()->shops()->findOrFail($shop_id);

        $product = $shop->products()->findOrFail($product_id);

        $productImage = $product->images()->findOrFail($image_id);

        $productImage->delete();

        Storage::delete('public/images/products/' . $productImage->src);

        $mainImageSrc = $product->image->src;

        if ($mainImageSrc === 'main_' . $productImage->src) :

            $product->image()->delete();
            Storage::delete('public/images/products/' . $mainImageSrc);

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
        
        return new ProductResource($product->load(['images']));
    }
}
