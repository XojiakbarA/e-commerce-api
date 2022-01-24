<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $request)
    {
        $query = $request->validated();

        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($query)]);

        $products = Product::filter($filter)->latest()->paginate(9);

        return ProductResource::collection($products);
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
}
