<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image');

        unset($data['image']);

        $banner = Banner::create($data);

        if ($image != null) :
            $imageName = $image->hashName();
            $path = 'storage/images/banners/';
            $src = $path . $imageName;

            Image::makeImage($image, $src, 450, 450);

            $banner->image()->create(['src' => $imageName]);
        endif;

        return new BannerResource($banner);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $data = $request->validated();
        $image = $request->file('image');

        if ($image != null) :
            $imageName = $image->hashName();
            $path = 'storage/images/banners/';
            $src = $path . $imageName;

            Image::makeImage($image, $src, 450, 450);
            
            Storage::delete('public/images/banners/' . $banner->image->src);

            $banner->image()->update(['src' => $imageName]);
        endif;

        $banner->update($data);

        $banner->refresh();

        return new BannerResource($banner);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        Storage::delete('public/images/banners/' . $banner->image->src);

        $banner->image()->delete();

        $deleted = $banner->delete();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
