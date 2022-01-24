<?php

namespace App\Http\Controllers\User\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Shop $shop)
    {
        $shop = $user->shops()->findOrFail($shop->id);

        $products = $shop->products()->latest()->paginate(5);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, User $user, Shop $shop)
    {
        $shop = $user->shops()->findOrfail($shop->id);

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
        
        $product = $shop->products()->create($data);
        
        $product->images()->createMany($imageNames);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Shop $shop, Product $product)
    {
        $shop = $user->shops()->findOrFail($shop->id);

        $product = $shop->products()->findOrFail($product->id);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, User $user, Shop $shop, Product $product)
    {
        $shop = $user->shops()->findOrfail($shop->id);
        $product = $shop->products()->findOrFail($product->id);

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
    public function destroy(User $user, Shop $shop, Product $product)
    {
        $shop = $user->shops()->findOrfail($shop->id);
        $product = $shop->products()->findOrFail($product->id);
        
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
