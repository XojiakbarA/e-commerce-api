<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Product;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function get(Request $request)
    {
        if ($request->user()) :
            $user_id = $request->user()->id;
        else :
            $user_id = Session::getId();
        endif;

        $cart = Cart::session($user_id)->getContent();
        $cart = Arr::sort($cart);
        $total = Cart::session($user_id)->getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function add(Request $request, $id)
    {
        $product = Product::find($id);
        if ($request->user()) :
            $user_id = $request->user()->id;
        else :
            $user_id = Session::getId();
        endif;

        Cart::session($user_id)->add([
            'id' => $product->id,
            'name' => $product->title,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->image
            ]
        ]);

        $cart = Cart::session($user_id)->getContent();
        $cart = Arr::sort($cart);
        $total = Cart::session($user_id)->getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function remove(Request $request, $id)
    {
        if ($request->user()) :
            $user_id = $request->user()->id;
        else :
            $user_id = Session::getId();
        endif;

        $prod = Cart::session($user_id)->get($id);

        if ($prod->quantity == 1) :
            Cart::remove($id);
        else :
            Cart::update($id, [
               'quantity' => -1
            ]);
        endif;

        $cart = Cart::session($user_id)->getContent();
        $cart = Arr::sort($cart);
        $total = Cart::session($user_id)->getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->user()) :
            $user_id = $request->user()->id;
        else :
            $user_id = Session::getId();
        endif;

        Cart::session($user_id)->remove($id);

        $cart = Cart::session($user_id)->getContent();
        $cart = Arr::sort($cart);
        $total = Cart::session($user_id)->getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }

    public function clear(Request $request)
    {
        if ($request->user()) :
            $user_id = $request->user()->id;
        else :
            $user_id = Session::getId();
        endif;

        Cart::session($user_id)->clear();

        $cart = Cart::session($user_id)->getContent();
        $cart = Arr::sort($cart);
        $total = Cart::session($user_id)->getTotal();
        return CartResource::collection($cart)->additional(['total' => $total]);
    }
}
