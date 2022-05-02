<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shop\StoreRequest;
use App\Http\Resources\ShopResource;
use App\Models\User;
use App\Services\ShopService;

class UserShopController extends Controller
{
    protected $service;

    public function __construct(ShopService $shopService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('own_resource');

        $this->service = $shopService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $shop = $user->shop;

        return new ShopResource($shop);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $user)
    {
        if ($user->shop) :
            abort(409, 'You have already created a shop. You can create only one shop.');
        endif;
        $shop = $this->service->store($request, $user);

        return new ShopResource($shop);
    }
}
