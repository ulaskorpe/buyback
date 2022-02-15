<?php

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

Route::post('/return-key', [ \App\Http\Controllers\DataController::class, 'returnKey'])->name('return-key');
Route::post('/add-tmp', [ \App\Http\Controllers\DataController::class, 'addTmp'])->name('add-tmp');
Route::post('/update-tmp', [ \App\Http\Controllers\DataController::class, 'updateTmp'])->name('update-tmp');
Route::get('/get-tmp/{id}', [ \App\Http\Controllers\DataController::class, 'getTmp'])->name('get-tmp');
Route::delete('/delete-tmp/{id}', [ \App\Http\Controllers\DataController::class, 'deleteTmp'])->name('delete-tmp');
Route::get('/list-tmp', [ \App\Http\Controllers\DataController::class, 'listTmp'])->name('list-tmp');
Route::post('/country-list', [ \App\Http\Controllers\DataController::class, 'countryList'])->name('country-list');
Route::post('/product-list', [ \App\Http\Controllers\ProductController::class, 'productListApi'])->name('product-list-api');
Route::get('/product-detail/{count?}', [ \App\Http\Controllers\ProductController::class, 'productDetailApi'])->name('product-detail-api');


Route::group(['prefix' => 'site'], function () {
    Route::get('/setting-list/{id?}', [ \App\Http\Controllers\ApiController::class, 'settingList'])->name('setting-list-api');
    Route::get('/slider-list', [ \App\Http\Controllers\ApiController::class, 'sliderList'])->name('slider-list-api');
    Route::get('/brands-list', [ \App\Http\Controllers\ApiController::class, 'brandsList'])->name('brands-list-api');
    Route::get('/menu-list/{location?}', [ \App\Http\Controllers\ApiController::class, 'menuList'])->name('menu-list-api');
    Route::get('/get-article/{code?}', [ \App\Http\Controllers\ApiController::class, 'getArticle'])->name('get-article-api');
    Route::get('/get-area/{code?}', [ \App\Http\Controllers\ApiController::class, 'getArea'])->name('get-area-api');
    Route::get('/location/{keyword?}', [ \App\Http\Controllers\ApiController::class, 'locationList'])->name('location-products-api');
    Route::get('/banners', [ \App\Http\Controllers\ApiController::class, 'banners'])->name('banners-api');
    Route::get('/mid-banner', [ \App\Http\Controllers\ApiController::class, 'midBanner'])->name('mid-banner-api');

    Route::get('/super-offer', [ \App\Http\Controllers\ApiController::class, 'superOffer'])->name('super-offer-api');
    Route::get('/left-menu', [ \App\Http\Controllers\ApiController::class, 'leftMenu'])->name('left-menu-api');
    Route::get('/top-menu', [ \App\Http\Controllers\ApiController::class, 'topMenu'])->name('top-menu-api');
    Route::get('/footer-menu', [ \App\Http\Controllers\ApiController::class, 'footerMenu'])->name('footer-menu-api');
    Route::get('/mobile-menu', [ \App\Http\Controllers\ApiController::class, 'mobileMenu'])->name('mobile-menu-api');
    Route::get('/shoppage-menu', [ \App\Http\Controllers\ApiController::class, 'shoppageMenu'])->name('shoppage-menu-api');
    Route::get('/shoppage-header', [ \App\Http\Controllers\ApiController::class, 'shoppageHeader'])->name('shoppage-header-api');
    Route::get('/social-icons', [ \App\Http\Controllers\ApiController::class, 'socialIcons'])->name('social-icons-api');

});
Route::group(['prefix' => 'products'], function () {
    Route::get('/new-arrivals', [ \App\Http\Controllers\ApiProductController::class, 'newArrivals'])->name('new-arrivals-api');
    Route::post('/all-products', [ \App\Http\Controllers\ApiProductController::class, 'allProducts'])->name('all-products-api');
    Route::post('/product-seeder', [ \App\Http\Controllers\ApiProductController::class, 'productSeeder'])->name('product-seeder-api');
    Route::get('/middle-products', [ \App\Http\Controllers\ApiProductController::class, 'middleProducts'])->name('middle-products-api');
    Route::get('/product-filters', [ \App\Http\Controllers\ApiProductController::class, 'productFilters'])->name('product-filters-api');
});

