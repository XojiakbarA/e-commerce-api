<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $request->user()->products()->latest()->paginate(5);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $images = $request->file('images');

        $imageNames = [];

        if ($images != null) {
            $path = 'storage/images/products/';

            $imageName = 'main_' . $images[0]->hashName();

            $src = $path . $imageName;

            Image::makeImage($images[0], $src, 300, 300);
            array_push($imageNames, [
                'src' => $imageName,
                'main' => 1
            ]);

            foreach ($images as $image) :

                $imageName = $image->hashName();

                $src = $path . $imageName;

                Image::makeImage($image, $src, 500, 625);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 0
                ]);
            endforeach;
        }

        unset($data['images']);
        
        $product = $request->user()->products()->create($data);
        
        $product->images()->createMany($imageNames);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $product_id)
    {
        $product = $request->user()->products()->findOrFail($product_id);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $product_id)
    {
        $product = $request->user()->products()->findOrFail($product_id);

        $data = $request->validated();
        $images = $request->file('images');
        $imageNames = [];

        if ($images != null) :
            $mainImage = $product->image;

            $path = 'storage/images/products/';
            
            if (!$mainImage) :

                $imageName = $images[0]->hashName();

                $src = $path . 'main_' . $imageName;

                Image::makeImage($images[0], $src, 300, 300);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 1
                ]);
            endif;

            foreach ($images as $image) :
                $imageName = $image->hashName();

                $src = $path . $imageName;

                Image::makeImage($image, $src, 500, 625);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 0
                ]);
            endforeach;
        endif;

        unset($data['images']);

        $product->update($data);

        $product->images()->createMany($imageNames);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $shop_id, $product_id)
    {
        $product = $request->user()->products()->findOrFail($product_id);
        
        if ($product->image) :
            Storage::delete('public/images/products/' . $product->image->src);
        endif;
        
        if ($product->images) :
            foreach ($product->images as $image) :
                Storage::delete('public/images/products/' . $image->src);
            endforeach;
        endif;

        $product->delete();
    }
}
