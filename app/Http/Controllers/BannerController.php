<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Services\BannerService;

class BannerController extends Controller
{
    protected $service;

    public function __construct(BannerService $bannerService)
    {
        $this->authorizeResource(Banner::class);

        $this->service = $bannerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();

        return BannerResource::collection($banners);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $banner = $this->service->store($request);

        return new BannerResource($banner);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return new BannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Banner $banner)
    {
        $banner = $this->service->update($request, $banner);

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
        $deleted = $this->service->destroy($banner);

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
