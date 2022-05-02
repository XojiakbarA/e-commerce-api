<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\User;
use App\Services\OrderService;

class UserOrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('own_resource');

        $this->service = $orderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $orders = $user->orders()->paginate(5);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request, User $user)
    {
        $order = $this->service->orderStore($request, $user);

        return new OrderResource($order);
    }
}
