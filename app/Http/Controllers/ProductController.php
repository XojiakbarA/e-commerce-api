<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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
            $products = Product::filter($filter)->latest()->where('shop_id', $shop->id)->with('image')->paginate($count)->withQueryString();
        else :
            $products = Product::filter($filter)->latest()->with('image')->paginate($count)->withQueryString();
        endif;

        return ProductResource::collection($products);
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
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $images = $request->file('images');
        $imageNames = [];

        if ($images != null) {

            $mainImage = $images[0];
            $mainImageName = 'main_' . $mainImage->hashName();
            $inter = Image::make($mainImage);
            $inter->fit(300, 300, function($constraint) {
                $constraint->upsize();
            });
            $inter->save('storage/images/products/' . $mainImageName);
            array_push($imageNames, [
                'src' => $mainImageName,
                'main' => 1
            ]);
            
            foreach ($images as $image) {
                $imageName = $image->hashName();
                $inter = Image::make($image);
                $inter->fit(500, 625, function($constraint) {
                    $constraint->upsize();
                });
                $inter->save('storage/images/products/' . $imageName);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 0
                ]);
            }
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
        return new ProductResource($product->load(['images']));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
