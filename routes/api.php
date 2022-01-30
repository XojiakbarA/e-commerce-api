<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\User\UserController;
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

// User routes
Route::apiResources(['users' => UserController::class], ['middleware' => 'auth:sanctum']);

Route::middleware('auth:sanctum')->prefix('user')->group(function() {
    Route::apiResources([
        'orders' => App\Http\Controllers\User\OrderController::class,
        'shops' => App\Http\Controllers\User\ShopController::class,
        'user-images' => App\Http\Controllers\User\UserImageController::class,
    ]);
});

// Vendor routes
Route::middleware(['auth:sanctum', 'is_vendor'])->prefix('vendor')->group(function() {
    Route::apiResources([
        'shops' => App\Http\Controllers\User\ShopController::class,
        'shops.orders' => App\Http\Controllers\Vendor\OrderController::class,
        'shops.products' => App\Http\Controllers\Vendor\ProductController::class,
        'shops.products.product-images' => App\Http\Controllers\Vendor\ProductImageController::class,
    ]);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);

Route::apiResource('shops', ShopController::class)->only(['index', 'show']);

Route::get('shops/{shop}/products', App\Http\Controllers\Shop\ProductController::class);

Route::apiResources([
    'categories' => CategoryController::class,
    'brands' => BrandController::class,
    'banners' => BannerController::class,
    'products.reviews' => ReviewController::class,
    'regions' => RegionController::class,
    'regions.districts' => DistrictController::class,
]);

Route::get('/cart', [CartController::class, 'get']);
Route::post('/cart/{id}', [CartController::class, 'add']);
Route::put('/cart/{id}', [CartController::class, 'remove']);
Route::delete('/cart/{id}', [CartController::class, 'delete']);
Route::delete('/cart', [CartController::class, 'clear']);

Route::get('/wishlist', [WishlistController::class, 'get']);
Route::post('/wishlist/{id}', [WishlistController::class, 'add']);
Route::delete('/wishlist/{id}', [WishlistController::class, 'delete']);
