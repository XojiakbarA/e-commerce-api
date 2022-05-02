<?php

namespace App\Http\Controllers;

use App\Http\Filters\ShopFilter;
use App\Http\Requests\Shop\FilterRequest;
use App\Http\Requests\Shop\UpdateRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $service;

    public function __construct(ShopService $shopService)
    {
        $this->authorizeResource(Shop::class);

        $this->service = $shopService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $request)
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
    public function update(UpdateRequest $request, Shop $shop)
    {
        $shop = $this->service->update($request, $shop);

        return new ShopResource($shop);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        $deleted = $this->service->destroy($shop);

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
