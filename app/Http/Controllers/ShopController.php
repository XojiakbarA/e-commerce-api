<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Shop::paginate(9);
        return ShopResource::collection($shop);
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
    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();
        $bg_image = $request->file('bg_image');
        $av_image = $request->file('av_image');

        $imageNames = [];

        if ($bg_image != null) :
            $imageName = Shop::makeImage($bg_image, 'big_', 1152, 330);
            array_push($imageNames, [
                'src' => $imageName,
                'status' => 'bg_big'
            ]);

            $imageName = Shop::makeImage($bg_image, 'small_', 750, 400);
            array_push($imageNames, [
                'src' => $imageName,
                'status' => 'bg_small'
            ]);
        endif;

        if ($av_image != null) :
            $imageName = Shop::makeImage($av_image, null, 200, 200);
            array_push($imageNames, [
                'src' => $imageName,
                'status' => 'avatar'
            ]);
        endif;

        $data['user_id'] = Auth::user()->id;
        unset($data['bg_image']);
        unset($data['av_image']);

        $shop = Shop::create($data);
        $shop->images()->createMany($imageNames);

        return new ShopResource($shop);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        return new ShopResource($shop);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shop $shop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        //
    }
}
