<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\NameRequest;
use App\Http\Resources\RegionResource;
use App\Models\Region;

class RegionController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Region::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::all();

        return RegionResource::collection($regions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NameRequest $request)
    {
        $data = $request->validated();

        $region = Region::create($data);

        return new RegionResource($region);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        return new RegionResource($region);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(NameRequest $request, Region $region)
    {
        $data = $request->validated();

        $region->update($data);

        return new RegionResource($region);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $deleted = $region->delete();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
