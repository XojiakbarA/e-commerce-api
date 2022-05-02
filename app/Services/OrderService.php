<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\SubOrderRequest;
use App\Models\Product;
use App\Models\SubOrder;
use App\Models\User;

class OrderService
{
    public function orderStore(OrderRequest $request, User $user)
    {
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

        return $order;
    }

    public function subOrderUpdate(SubOrderRequest $request, SubOrder $subOrder)
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

        return $subOrder;
    }
}