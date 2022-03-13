<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Resources\SubOrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sub_orders = $request->user()->subOrders()->paginate(5);

        return SubOrderResource::collection($sub_orders);
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
    public function show(Request $request, $sub_order_id)
    {
        $sub_order = $request->user()->subOrders()->findOrFail($sub_order_id);

        return new SubOrderResource($sub_order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderStatusRequest $request, $sub_order_id)
    {
        $data = $request->validated();

        $sub_order = $request->user()->subOrders()->findOrFail($sub_order_id);

        if ($request->has('quantity')) :
            foreach ($data['quantity'] as $id => $qty) :
                $sub_order->orderProducts()->findOrFail($id)->update(['quantity' => $qty]);
            endforeach;

            $sub_total = 0;
            foreach ($sub_order->orderProducts as $product) :
                $sub_total += $product->price * $product->quantity;
            endforeach;
            $sub_order->update(['total' => $sub_total]);

            $total = 0;
            foreach ($sub_order->order->orderProducts as $product) :
                $total += $product->price * $product->quantity;
            endforeach;
            $sub_order->order()->update(['total' => $total]);
        endif;

        if ($request->has('status')) :
            $sub_order->update(['status' => $data['status']]);
        endif;

        return new SubOrderResource($sub_order);
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
