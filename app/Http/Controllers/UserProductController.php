<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\User;

class UserProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $products = $user->products()->latest()->paginate(5);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, User $user)
    {
        $data = $request->validated();
        $images = $request->file('images');

        $imageNames = [];

        if ($images != null) {
            $path = 'storage/images/products/';

            $imageName = 'main_' . $images[0]->hashName();

            $src = $path . $imageName;

            Image::makeImage($images[0], $src, 300, 300);
            array_push($imageNames, [
                'src' => $imageName,
                'main' => 1
            ]);

            foreach ($images as $image) :

                $imageName = $image->hashName();

                $src = $path . $imageName;

                Image::makeImage($image, $src, 500, 625);
                array_push($imageNames, [
                    'src' => $imageName,
                    'main' => 0
                ]);
            endforeach;
        }

        unset($data['images']);
        
        $product = $user->shop->products()->create($data);
        
        $product->images()->createMany($imageNames);

        return new ProductResource($product);
    }
}
