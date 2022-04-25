<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use App\Models\User;

class UserOrderController extends Controller
{
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
        $data = $request->validated();
        $data = $request->safe()->except(['pay_mode', 'products']);
        $cartProducts = $request->products;
        $cartProductIDs = array_map(fn ($item) => $item['id'], $cartProducts);
        $baseProducts = Product::find($cartProductIDs)->toArray();
        $shopIDs = array_map(fn ($item) => $item['shop_id'], $baseProducts);
        $shopIDs_unique = array_unique($shopIDs);
        $pay_mode = $request->pay_mode;

        // create order
        $order = $user->orders()->create($data);

        // create sub_orders
        $products = array_map(function ($item) use ($cartProducts) {
            $key = array_search($item['id'], array_column($cartProducts, 'id'));
            return array_merge($item, $cartProducts[$key]);
        }, $baseProducts);

        foreach ($shopIDs_unique as $shop_id) :
            $total = 0;
            
            foreach ($products as $product) :
                if ($product['shop_id'] === $shop_id) :
                    $total += ($product['sale_price'] ?? $product['price']) * $product['quantity'];
                endif;
            endforeach;

            $subOrder = $order->subOrders()->create(['shop_id' => $shop_id, 'total' => $total]);

            foreach ($products as $product) :
                if ($product['shop_id'] === $shop_id) :
                    $subOrder->orderProducts()->create([
                        'product_id' => $product['id'],
                        'price' => $product['sale_price'] ?? $product['price'],
                        'quantity' => $product['quantity']
                    ]);
                endif;
            endforeach;
        endforeach;

        //create transaction
        $order->transaction()->create([
            'user_id' => $user->id,
            'pay_mode' => $pay_mode
        ]);

        return new OrderResource($order);
    }
}
