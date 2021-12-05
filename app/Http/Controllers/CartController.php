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
        return CartResource::collection($cart);
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
                'image' => $product->image
            ]
        ]);

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        return CartResource::collection($cart);
    }

    public function remove($id)
    {
        Cart::update($id, [
           'quantity' => -1
        ]);

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        return CartResource::collection($cart);
    }

    public function delete($id)
    {
        Cart::remove($id);

        $cart = Cart::getContent();
        $cart = Arr::sort($cart);
        return CartResource::collection($cart);
    }
}
