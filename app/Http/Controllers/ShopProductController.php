<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;

class ShopProductController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(FilterRequest $request, Shop $shop)
    {
        $query = $request->validated();

        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($query)]);

        $products = Product::filter($filter)->where('shop_id', $shop->id)->latest()->paginate(9);

        return ProductResource::collection($products);
    }
}
