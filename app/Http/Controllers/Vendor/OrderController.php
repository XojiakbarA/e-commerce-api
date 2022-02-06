<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SubOrderResource;
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

        $orders = $shop->subOrders()->paginate(5);

        return SubOrderResource::collection($orders);
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
    public function show(Request $request, $shop_id, $sub_order_id)
    {
        $shop = $request->user()->shops()->findOrFail($shop_id);

        $order = $shop->subOrders()->findOrFail($sub_order_id);

        return new SubOrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderStatusRequest $request, $shop_id, $sub_order_id)
    {
        $shop = $request->user()->shops()->findOrFail($shop_id);
        $subOrder = $shop->subOrders()->findOrFail($sub_order_id);

        $data = $request->validated();
        
        if ($request->has('quantity')) :
            foreach ($data['quantity'] as $id => $qty) :
                $subOrder->orderProducts()->findOrFail($id)->update(['quantity' => $qty]);
            endforeach;

            $sub_total = 0;
            foreach ($subOrder->orderProducts as $product) :
                $sub_total += $product->price * $product->quantity;
            endforeach;
            $subOrder->update(['total' => $sub_total]);

            $total = 0;
            foreach ($subOrder->order->orderProducts as $product) :
                $total += $product->price * $product->quantity;
            endforeach;
            $subOrder->order()->update(['total' => $total]);
        endif;

        if ($request->has('status')) :
            $subOrder->update(['status' => $data['status']]);
        endif;

        return new SubOrderResource($subOrder);
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
