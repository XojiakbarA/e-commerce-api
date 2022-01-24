<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\User\Shop\ProductImageController;
use App\Http\Controllers\User\Shop\UserShopProductController;
use App\Http\Controllers\UserImageController;
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
Route::apiResources([
    'users' => UserController::class,
    'orders' => OrderController::class,
    'users.user-images' => UserImageController::class,
    ],
    ['middleware' => 'auth:sanctum']
);

// Vendor routes
Route::apiResources([
    'users.shops.products' => UserShopProductController::class,
    'products.product-images' => ProductImageController::class,
    ],
    ['middleware' => 'auth:sanctum']
);

Route::apiResource('products', ProductController::class)->only(['index', 'show']);

Route::get('shops/{shop}/products', ShopProductController::class);

Route::apiResources([
    'categories' => CategoryController::class,
    'brands' => BrandController::class,
    'banners' => BannerController::class,
    'products.reviews' => ReviewController::class,
    'shops' => ShopController::class,
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
