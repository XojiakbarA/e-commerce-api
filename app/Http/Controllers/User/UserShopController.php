<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Illuminate\Http\Request;

class UserShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shops = $request->user()->shops;

        return ShopResource::collection($shops);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShopRequest $request)
    {
        $user = $request->user();
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

        unset($data['bg_image']);
        unset($data['av_image']);

        $shop = $user->shops()->create($data);

        $shop->images()->createMany($imageNames);

        $user->update(['role' => 'vendor']);

        return new ShopResource($shop);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $shop = $request->user()->shops()->findOrFail($id);

        return new ShopResource($shop);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
