<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Shop;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ProductFilterRequest $request, Shop $shop)
    {
        $query = $request->validated();

        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($query)]);

        $products = $shop->products()->filter($filter)->latest()->paginate(9);

        return ProductResource::collection($products);
    }
}
