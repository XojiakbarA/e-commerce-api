<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ShopOrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $shop_id)
    {
        $shop = $request->user()->shops()->findOrFail($shop_id);

        $orders = $shop->orders;

        return ShopOrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $shop_id, $order_id)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $shop_id, $shop_order_id)
    {
        $shop = $request->user()->shops()->findOrFail($shop_id);

        $order = $shop->orders()->findOrFail($shop_order_id);

        return new ShopOrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderStatusRequest $request, $shop_id, $shop_order_id)
    {
        $data = $request->validated();
        $status = $data['status'];

        $shop = $request->user()->shops()->findOrFail($shop_id);

        $order = $shop->orders()->findOrFail($shop_order_id);

        $order->update(['status' => $status]);

        return new ShopOrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
