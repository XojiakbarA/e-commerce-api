<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\NameRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use App\Models\Region;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Region $region)
    {
        return DistrictResource::collection($region->districts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NameRequest $request, Region $region)
    {
        $data = $request->validated();

        $district = $region->districts()->create($data);

        return new DistrictResource($district);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(NameRequest $request, District $district)
    {
        $data = $request->validated();

        $district->update($data);

        return new DistrictResource($district);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        $deleted = $district->delete();

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
