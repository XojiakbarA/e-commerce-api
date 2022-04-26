<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\Product\FilterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Shop;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $request, Shop $shop)
    {
        $query = $request->validated();

        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($query)]);

        $products = $shop->products()->filter($filter)->latest()->paginate(9);

        return ProductResource::collection($products);
    }
}
