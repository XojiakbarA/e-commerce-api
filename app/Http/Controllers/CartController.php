<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Product;
use Cart;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function get()
    {
        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        $total = Cart::getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function add($id)
    {
        $product = Product::find($id);

        Cart::add([
            'id' => $product->id,
            'name' => $product->title,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->image,
                'sale_price' => $product->sale_price
            ]
        ]);

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        $total = Cart::getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function remove($id)
    {
        $prod = Cart::get($id);

        if ($prod->quantity == 1) :
            Cart::remove($id);
        else :
            Cart::update($id, [
                'quantity' => -1
            ]);
        endif;

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        $total = Cart::getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function delete($id)
    {
        Cart::remove($id);

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        $total = Cart::getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function clear()
    {
        Cart::clear();

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        $total = Cart::getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }
}
