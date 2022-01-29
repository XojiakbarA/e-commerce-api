<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Cart;

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
        $data = $request->validated();
        $data = $request->safe()->except('pay_mode');
        $user = $request->user();
        $cartProducts = Cart::getContent();
        $data['total'] = Cart::getTotal();

        //create order
        $order = $user->orders()->create($data);

        $shop_id_array = [];

        foreach ($cartProducts as $product) :
            $shop_id = $product->attributes->shop_id;
            array_push($shop_id_array, $shop_id);
        endforeach;

        $shop_id_array_unique = array_unique($shop_id_array);

        //create shop_orders and order products
        foreach ($shop_id_array_unique as $shop_id) :
            $total = 0;
            
            foreach ($cartProducts as $product) :
                if ($product->attributes->shop_id === $shop_id) :
                    $total += $product->price * $product->quantity;
                endif;
            endforeach;

            $shopOrder = $order->shopOrders()->create(['shop_id' => $shop_id, 'total' => $total]);

            foreach ($cartProducts as $product) :
                if ($product->attributes->shop_id === $shop_id) {
                    $shopOrder->orderProducts()->create([
                        'product_id' => $product->id,
                        'price' => $product->price,
                        'quantity' => $product->quantity
                    ]);
                }
            endforeach;
        endforeach;

        //create transaction
        $pay_mode = $request->pay_mode;

        $order->transaction()->create([
            'user_id' => $user->id,
            'pay_mode' => $pay_mode
        ]);

        //clear cart
        Cart::clear();

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $order_id)
    {
        $order = $request->user()->orders()->findOrFail($order_id);

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
    public function update(OrderStatusRequest $request, $shop_order_id)
    {
        $user = $request->user();
        $data = $request->validated();
        $status = $data['status'];

        $shopOrder = $user->shopOrders()->findOrFail($shop_order_id);

        $shopOrder->update(['status' => $status]);

        $order = $shopOrder->order;

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
