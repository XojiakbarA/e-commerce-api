<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $request, Shop $shop)
    {
        $query = $request->validated();
        $count = $request->query('count') ?: 9;
        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($query)]);
        if ($shop->exists) :
            $products = Product::filter($filter)->latest()->where('shop_id', $shop->id)->paginate($count);
        else :
            $products = Product::filter($filter)->latest()->paginate($count);
        endif;

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
            $mainImageName = Product::makeImage($images[0], 'main_', 300, 300);
            array_push($imageNames, [
                'src' => $mainImageName,
                'main' => 1
            ]);

            foreach ($images as $image) :
                $imageName = Product::makeImage($image, null, 500, 625);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 0
                ]);
            endforeach;
        }

        unset($data['images']);
        unset($data['category_id']);
        
        $product = Product::create($data);
        $product->images()->createMany($imageNames);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $images = $request->file('images');
        $imageNames = [];

        if ($images != null) :
            $mainImage = $product->image;

            if (!$mainImage) :
                $mainImageName = Product::makeImage($images[0], 'main_', 300, 300);
                array_push($imageNames, [
                    'src' => $mainImageName,
                    'main' => 1
                ]);
            endif;

            foreach ($images as $image) :
                $imageName = Product::makeImage($image, null, 500, 625);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 0
                ]);
            endforeach;
        endif;

        unset($data['images']);
        unset($data['category_id']);

        $product->update($data);
        $product->images()->createMany($imageNames);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop, Product $product)
    {
        if ($product->image) :
            Storage::delete('public/images/products/' . $product->image->src);
        endif;
        
        if ($product->images) :
            foreach ($product->images as $image) :
                Storage::delete('public/images/products/' . $image->src);
            endforeach;
        endif;

        $product->delete();

        return new ShopResource($shop);
    }
}
