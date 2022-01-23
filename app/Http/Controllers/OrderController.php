<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCancellationRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Transaction;
use Cart;
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
        $orders = $request->user()->orders()->paginate(5);

        return OrderResource::collection($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        //create order
        $data = $request->validated();
        $data = $request->safe()->except('pay_mode');
        $data['user_id'] = $request->user()->id;
        $data['total'] = Cart::getTotal();

        $order = Order::create($data);

        //create order products
        $cartProducts = Cart::getContent();

        foreach ($cartProducts as $product) :
            $order->orderProducts()->create([
                'product_id' => $product->id,
                'order_id' => $order->id,
                'price' => $product->price,
                'quantity' => $product->quantity
            ]);
        endforeach;

        //create transaction
        $pay_mode = $request->pay_mode;

        $order->transaction()->create([
            'user_id' => $request->user()->id,
            'pay_mode' => $pay_mode
        ]);

        //clear cart
        Cart::clear();

        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Order $order)
    {
        $order = $request->user()->orders()->findOrFail($order->id);

        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderCancellationRequest $request, Order $order)
    {
        $status = $request->status;
        $order->status = $status;
        $order->save();

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
