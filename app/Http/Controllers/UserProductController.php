<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\User;
use App\Services\ProductService;

class UserProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('own_resource');

        $this->service = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $products = $user->products()->latest()->paginate(5);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $user)
    {
        $product = $this->service->store($request, $user);

        return new ProductResource($product);
    }
}
