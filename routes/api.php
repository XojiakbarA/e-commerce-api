<?php

use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopImageController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserImageController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserReviewController;
use App\Http\Controllers\UserShopController;
use App\Http\Controllers\UserSubOrderController;
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

Route::get('/me', MeController::class)->middleware(['auth:sanctum']);

Route::apiResource('products.images', ProductImageController::class)->only(['destroy']);
Route::apiResource('users.images', UserImageController::class)->only(['destroy']);
Route::apiResource('shops.images', ShopImageController::class)->only(['destroy']);
Route::apiResource('users.sub-orders', UserSubOrderController::class)->shallow()->only(['index', 'show', 'update']);
Route::apiResource('users.orders', UserOrderController::class)->shallow()->only(['index', 'store']);
Route::apiResource('users.shops', UserShopController::class)->shallow()->only(['index', 'store']);
Route::apiResource('users.products', UserProductController::class)->shallow()->only(['index', 'store']);
Route::apiResource('users.reviews', UserReviewController::class)->shallow()->only(['index', 'store']);
Route::apiResource('products', ProductController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('shops', ShopController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('banners', BannerController::class);
Route::apiResource('regions', RegionController::class);
Route::apiResource('brands', BrandController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('categories.sub-categories', SubCategoryController::class)->shallow();
Route::apiResource('regions.districts', DistrictController::class)->shallow();
Route::apiResource('shops.products', ShopProductController::class)->only(['index']);