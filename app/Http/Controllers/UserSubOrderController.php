<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubOrderRequest;
use App\Http\Resources\SubOrderResource;
use App\Models\SubOrder;
use App\Models\User;

class UserSubOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
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
        $data = $request->validated();

        if ($request->has('quantity')) :
            foreach ($data['quantity'] as $id => $qty) :
                $order_product = $subOrder->orderProducts()->findOrFail($id);
                $order_product->update(['quantity' => $qty]);
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
}
