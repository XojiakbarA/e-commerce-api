<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WishlistController extends Controller
{
    public function get()
    {
        $wishlist = app('wishlist');
        $wishlist = Arr::sort($wishlist->getContent());
        return CartResource::collection($wishlist);
    }

    public function add($id)
    {
        $product = Product::find($id);
        $wishlist = app('wishlist');

        $wishlist->add([
            'id' => $product->id,
            'name' => $product->title,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->image,
                'rating' => $product->rating
            ]
        ]);

        $wishlist = Arr::sort($wishlist->getContent());
        return CartResource::collection($wishlist);
    }

    public function delete($id)
    {
        $wishlist = app('wishlist');
        $wishlist->remove($id);

        $wishlist = Arr::sort($wishlist->getContent());
        return CartResource::collection($wishlist);
    }
}
