<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    protected $service;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth:sanctum');

        $this->service = $productService;
    }

    public function destroy(Request $request, Product $product, Image $image)
    {
        if ($request->user->id !== $image->imageable->shop->user_id  && !$request->user->isAdmin()) :
            abort(403, 'Forbidden');
        endif;
        if ($product->id !== $image->imageable_id) :
            abort(404, 'Not Found');
        endif;

        $deleted = $this->service->imageDestroy($product, $image);

        if ($deleted) :
            return response(null, 204);
        endif;
    }
}
