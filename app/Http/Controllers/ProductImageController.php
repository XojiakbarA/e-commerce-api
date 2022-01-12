<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function show(ProductImage $productImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductImage $productImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductImage $productImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, ProductImage $productImage)
    {
        $productImage->delete();
        Storage::delete('public/images/products/' . $productImage->src);

        $mainImageSrc = $product->image->src;

        if ($mainImageSrc == 'main_' . $productImage->src) :

            $product->image->delete();
            Storage::delete('public/images/products/' . $mainImageSrc);

            $firstImage = $product->images->first();

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
