<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\FilterRequest\ProductFilterRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductFilterRequest $request)
    {
        $query = $request->validated();
        $count = $request->query('count') ?? 9;
        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($query)]);

        $products = Product::filter($filter)->latest()->paginate($count);

        return ProductResource::collection($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $images = $request->file('images');
        $imageNames = [];

        if ($images != null) :
            $mainImage = $product->image;

            $path = 'storage/images/products/';
            
            if (!$mainImage) :

                $imageName = 'main_' . $images[0]->hashName();

                $src = $path . $imageName;

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
    public function destroy(Product $product)
    {
        if ($product->image) :
            Storage::delete('public/images/products/' . $product->image->src);
        endif;
        
        if ($product->images) :
            foreach ($product->images as $image) :
                Storage::delete('public/images/products/' . $image->src);
            endforeach;
        endif;

        $deleted = $product->delete();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
