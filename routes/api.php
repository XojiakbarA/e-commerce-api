<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products/search', [ProductController::class, 'index']);
Route::apiResources([
    'categories' => CategoryController::class,
    'brands' => BrandController::class,
    'products' => ProductController::class,
    'banners' => BannerController::class,
    'products.reviews' => ReviewController::class,
    'shops' => ShopController::class,
    'shops.products' => ProductController::class
]);

Route::get('/cart', [CartController::class, 'get']);
Route::post('/cart/{id}', [CartController::class, 'add']);
Route::put('/cart/{id}', [CartController::class, 'remove']);
Route::delete('/cart/{id}', [CartController::class, 'delete']);

Route::get('/wishlist', [WishlistController::class, 'get']);
Route::post('/wishlist/{id}', [WishlistController::class, 'add']);
Route::delete('/wishlist/{id}', [WishlistController::class, 'delete']);
