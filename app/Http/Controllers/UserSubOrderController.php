<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubOrderRequest;
use App\Http\Resources\SubOrderResource;
use App\Models\SubOrder;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;

class UserSubOrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(SubOrder::class);

        $this->service = $orderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        if ($request->user()->id !== $user->id) :
            abort(403, 'Forbidden');
        endif;
        $subOrders = $user->subOrders()->paginate(5);

        return SubOrderResource::collection($subOrders);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubOrder $subOrder)
    {
        return new SubOrderResource($subOrder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubOrderRequest $request, SubOrder $subOrder)
    {
        $subOrder = $this->service->subOrderUpdate($request, $subOrder);

        return new SubOrderResource($subOrder);
    }
}
