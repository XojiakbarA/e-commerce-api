<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Image;
use App\Models\User;

class UserShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('own_resource');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $shop = $user->shop;

        return new ShopResource($shop);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopRequest $request, User $user)
    {
        $data = $request->validated();
        $bg_image = $request->file('bg_image');
        $av_image = $request->file('av_image');

        $imageNames = [];

        $path = 'storage/images/shops/';

        if ($bg_image != null) :

            $imageName = 'big_' . $bg_image->hashName();
            $src = $path . $imageName;

            Image::makeImage($bg_image, $src, 1152, 330);
            array_push($imageNames, [
                'src' => $imageName,
                'status' => 'bg_big'
            ]);

            $imageName = 'small_' . $bg_image->hashName();
            $src = $path . $imageName;

            Image::makeImage($bg_image, $src, 750, 400);
            array_push($imageNames, [
                'src' => $imageName,
                'status' => 'bg_small'
            ]);
        endif;

        if ($av_image != null) :

            $imageName = $av_image->hashName();
            $src =  $path . $imageName;

            Image::makeImage($av_image, $src, 200, 200);
            array_push($imageNames, [
                'src' => $imageName,
                'status' => 'avatar'
            ]);
        endif;

        unset($data['bg_image']);
        unset($data['av_image']);

        $shop = $user->shop()->create($data);

        $shop->images()->createMany($imageNames);

        $user->update(['role' => 'vendor']);

        return new ShopResource($shop);
    }
}
