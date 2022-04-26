<?php

namespace App\Http\Controllers;

use App\Http\Filters\ShopFilter;
use App\Http\Requests\FilterRequest\ShopFilterRequest;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Image;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Shop::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShopFilterRequest $request)
    {
        $query = $request->validated();
        $count = $request->query('count') ?? 9;
        $filter = app()->make(ShopFilter::class, ['queryParams' => array_filter($query)]);

        $shops = Shop::filter($filter)->latest()->paginate($count);

        return ShopResource::collection($shops);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        return new ShopResource($shop);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShopRequest $request, Shop $shop)
    {
        $data = $request->validated();
        $bg_image = $request->file('bg_image');
        $av_image = $request->file('av_image');

        $path = 'storage/images/shops/';

        if ($bg_image != null) :

            $imageName = 'big_' . $bg_image->hashName();
            $src = $path . $imageName;

            Image::makeImage($bg_image, $src, 1152, 330);
            
            if ($shop->bgImageBig) :
                Storage::delete('public/images/shops/' . $shop->bgImageBig->src);
                $shop->bgImageBig->update(['src' => $imageName]);
            else :
                $shop->bgImageBig()->create(['src' => $imageName, 'status' => 'bg_big']);
            endif;

            $imageName = 'small_' . $bg_image->hashName();
            $src = $path . $imageName;

            Image::makeImage($bg_image, $src, 750, 400);

            if ($shop->bgImageSmall) :
                Storage::delete('public/images/shops/' . $shop->bgImageSmall->src);
                $shop->bgImageSmall->update(['src' => $imageName]);
            else :
                $shop->bgImageSmall()->create(['src' => $imageName, 'status' => 'bg_small']);
            endif;
        endif;

        if ($av_image != null) :

            $imageName = $av_image->hashName();
            $src = $path . $imageName;

            Image::makeImage($av_image, $src, 200, 200);

            if ($shop->avImage) :
                Storage::delete('public/images/shops/' . $shop->avImage->src);
                $shop->avImage->update(['src' => $imageName]);
            else :
                $shop->avImage()->create(['src' => $imageName, 'status' => 'avatar']);
            endif;
        endif;

        unset($data['bg_image']);
        unset($data['av_image']);

        $shop->update($data);

        $shop->refresh();

        return new ShopResource($shop);
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
