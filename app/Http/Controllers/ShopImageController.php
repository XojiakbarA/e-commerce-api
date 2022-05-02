<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Shop;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopImageController extends Controller
{
    protected $service;

    public function __construct(ShopService $shopService)
    {
        $this->middleware('auth:sanctum');


        $this->service = $shopService;
    }

    public function destroy(Request $request, Shop $shop, Image $image)
    {
        if ($request->user->id !== $image->imageable->user_id && !$request->user->isAdmin()) :
            abort(403, 'Forbidden');
        endif;
        if ($shop->id !== $image->imageable_id) :
            abort(404, 'Not Found');
        endif;

        $deleted = $this->service->imageDestroy($shop, $image);

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
