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
Route::post('/cc-test', [ \App\Http\Controllers\DataController::class, 'ccTest'])->name('cc-test');
Route::post('/add-tmp', [ \App\Http\Controllers\DataController::class, 'addTmp'])->name('add-tmp');
Route::post('/update-tmp', [ \App\Http\Controllers\DataController::class, 'updateTmp'])->name('update-tmp');
Route::get('/get-tmp/{id}', [ \App\Http\Controllers\DataController::class, 'getTmp'])->name('get-tmp');
Route::delete('/delete-tmp/{id}', [ \App\Http\Controllers\DataController::class, 'deleteTmp'])->name('delete-tmp');
Route::get('/list-tmp', [ \App\Http\Controllers\DataController::class, 'listTmp'])->name('list-tmp');
Route::post('/country-list', [ \App\Http\Controllers\DataController::class, 'countryList'])->name('country-list');
Route::post('/product-list', [ \App\Http\Controllers\ProductController::class, 'productListApi'])->name('product-list-api');

Route::get('/get-cities', [ \App\Http\Controllers\ApiController::class, 'getCities'])->name('get-cities-api');
Route::get('/get-towns/{city_id}', [ \App\Http\Controllers\ApiController::class, 'getTowns'])->name('get-towns-api');
Route::get('/get-districts/{town_id}', [ \App\Http\Controllers\ApiController::class, 'getDistricts'])->name('get-districts-api');
Route::get('/get-neighborhoods/{district_id}', [ \App\Http\Controllers\ApiController::class, 'getNeighborhoods'])->name('get-neighborhoods-api');
Route::get('/service-addresses', [ \App\Http\Controllers\DataController::class, 'serviceAddresses'])->name('service-addresses-api');
Route::get('/return-problems', [ \App\Http\Controllers\DataController::class, 'returnProblems'])->name('return-problems-api');
Route::get('/cargo-companies', [ \App\Http\Controllers\DataController::class, 'cargoCompanies'])->name('cargo-companies-api');
Route::get('/bank-accounts', [ \App\Http\Controllers\DataController::class, 'bankAccounts'])->name('bank-accounts-api');
Route::get('/bankalar-list', [ \App\Http\Controllers\DataController::class, 'bankalarList'])->name('bankalar-list-api');

Route::get('/bankalar-taksitler/{bank_id?}', [ \App\Http\Controllers\DataController::class, 'bankaTaksitler'])->name('banka-taksitler');
Route::post('/banka-taksit-hesapla', [ \App\Http\Controllers\DataController::class, 'bankaTaksitHesapla'])->name('banka-taksit-hesapla');
Route::get('/contact-info', [ \App\Http\Controllers\DataController::class, 'contactInfo'])->name('contact-info-api');
Route::get('/get-banks-purchases', [ \App\Http\Controllers\ApiController::class, 'banksPurchases'])->name('banks-purchases-api');



Route::group(['prefix' => 'site'], function () {
    Route::get('/setting-list/{id?}', [ \App\Http\Controllers\ApiController::class, 'settingList'])->name('setting-list-api');
    Route::get('/slider-list', [ \App\Http\Controllers\ApiController::class, 'sliderList'])->name('slider-list-api');
    Route::get('/brands-list', [ \App\Http\Controllers\ApiController::class, 'brandsList'])->name('brands-list-api');
    Route::get('/menu-list/{location?}', [ \App\Http\Controllers\ApiController::class, 'menuList'])->name('menu-list-api');
    Route::get('/get-article/{article_id?}', [ \App\Http\Controllers\ApiController::class, 'getArticle'])->name('get-article-api');
    Route::get('/get-area/{code?}', [ \App\Http\Controllers\ApiController::class, 'getArea'])->name('get-area-api');
    Route::get('/location/{keyword?}', [ \App\Http\Controllers\ApiController::class, 'locationList'])->name('location-products-api');
    Route::get('/banners', [ \App\Http\Controllers\ApiController::class, 'banners'])->name('banners-api');
    Route::get('/mid-banner', [ \App\Http\Controllers\ApiController::class, 'midBanner'])->name('mid-banner-api');

    Route::get('/super-offer', [ \App\Http\Controllers\ApiController::class, 'superOffer'])->name('super-offer-api');
    Route::get('/left-menu', [ \App\Http\Controllers\ApiController::class, 'leftMenu'])->name('left-menu-api');
    Route::get('/top-menu', [ \App\Http\Controllers\ApiController::class, 'topMenu'])->name('top-menu-api');
    Route::get('/header-menu', [ \App\Http\Controllers\ApiController::class, 'headerMenu'])->name('top-menu-api');
    Route::get('/footer-menu', [ \App\Http\Controllers\ApiController::class, 'footerMenu'])->name('footer-menu-api');
    Route::get('/mobile-menu', [ \App\Http\Controllers\ApiController::class, 'mobileMenu'])->name('mobile-menu-api');
    Route::get('/shoppage-menu', [ \App\Http\Controllers\ApiController::class, 'shoppageMenu'])->name('shoppage-menu-api');
    Route::get('/shoppage-header', [ \App\Http\Controllers\ApiController::class, 'shoppageHeader'])->name('shoppage-header-api');
    Route::get('/social-icons', [ \App\Http\Controllers\ApiController::class, 'socialIcons'])->name('social-icons-api');
    Route::get('/contact-info', [ \App\Http\Controllers\ApiController::class, 'contactInfo'])->name('contact-info-api');
    Route::get('/news/{page?}/{page_count?}/{keyword?}', [ \App\Http\Controllers\ApiController::class, 'getNews'])->name('get-news-api');
    Route::get('/news-detail/{id}', [ \App\Http\Controllers\ApiController::class, 'detailNews'])->name('detail-news-api');
    Route::get('/faq-list', [ \App\Http\Controllers\ApiController::class, 'faqList'])->name('faq-list-api');
    Route::post('/human-resource-post', [ \App\Http\Controllers\ApiController::class, 'humanResourcePost'])->name('human-resource-post');


});

Route::group(['prefix' => 'products'], function () {

    Route::post('/all-products', [ \App\Http\Controllers\ApiProductController::class, 'allProducts'])->name('all-products-api');
    Route::post('/best-sellers', [ \App\Http\Controllers\ApiProductController::class, 'bestSellers'])->name('best-sellers-api');
    Route::post('/weekly-deals', [ \App\Http\Controllers\ApiProductController::class, 'weeklyDeals'])->name('weekly-deals-api');
    Route::post('/new-products', [ \App\Http\Controllers\ApiProductController::class, 'newProducts'])->name('new-products-api');
    Route::post('/highest-rated', [ \App\Http\Controllers\ApiProductController::class, 'highestRated'])->name('highest-rated-api');
    Route::post('/super-offer', [ \App\Http\Controllers\ApiProductController::class, 'superOffer'])->name('super-offer-api');
    Route::post('/color-filter', [ \App\Http\Controllers\ApiProductController::class, 'colorFilter'])->name('color-filter-api');
    Route::post('/memory-filter', [ \App\Http\Controllers\ApiProductController::class, 'memoryFilter'])->name('memory-filter-api');
    Route::post('/product-seeder', [ \App\Http\Controllers\ApiProductController::class, 'productSeeder'])->name('product-seeder-api');
    Route::get('/middle-products', [ \App\Http\Controllers\ApiProductController::class, 'middleProducts'])->name('middle-products-api');
    Route::get('/product-filters', [ \App\Http\Controllers\ApiProductController::class, 'productFilters'])->name('product-filters-api');
    Route::get('/product-detail/{product_id}', [ \App\Http\Controllers\ApiProductController::class, 'productDetail'])->name('product-detail-api');
    Route::post('/imei-query',[\App\Http\Controllers\ApiProductController::class,'imeiQuery'])->name('api-imei-query');
    Route::get('/product-detail/{product_id}', [ \App\Http\Controllers\ApiProductController::class, 'productDetail'])->name('product-detail-api');
    Route::get('/get-brands', [ \App\Http\Controllers\ApiProductController::class, 'getBrands'])->name('get-brands-api');
    Route::get('/get-colors', [ \App\Http\Controllers\ApiProductController::class, 'getColors'])->name('get-colors-api');
    Route::get('/get-memories', [ \App\Http\Controllers\ApiProductController::class, 'getMemories'])->name('get-memories-api');
    Route::get('/get-models/{brand_id}', [ \App\Http\Controllers\ApiProductController::class, 'getModels'])->name('get-models-api');
    Route::get('/get-questions/{model_id}', [ \App\Http\Controllers\ApiProductController::class, 'getQuestions'])->name('get-questions-api');
    Route::post('/calculate-answers', [ \App\Http\Controllers\ApiProductController::class, 'calculateAnswers'])->name('calculate-answers-api');
});

Route::group(['prefix' => 'customers'], function () {

    Route::post('/create', [ \App\Http\Controllers\ApiCustomerController::class, 'create'])->name('create-customer-api');
    Route::post('/login', [ \App\Http\Controllers\ApiCustomerController::class, 'login'])->name('login-customer-api');
    Route::post('/activate', [ \App\Http\Controllers\ApiCustomerController::class, 'activate'])->name('activate-customer-api');
    Route::post('/resend-activate', [ \App\Http\Controllers\ApiCustomerController::class, 'resendActivate'])->name('resend-activate-api');
    Route::post('/forget-password', [ \App\Http\Controllers\ApiCustomerController::class, 'forgetPassword'])->name('forget-pw-api');
    Route::post('/update', [ \App\Http\Controllers\ApiCustomerController::class, 'updateProfile'])->name('update-profile-api');
    Route::post('/update-password', [ \App\Http\Controllers\ApiCustomerController::class, 'updatePassword'])->name('update-password-api');
    Route::post('/newsletter', [ \App\Http\Controllers\ApiCustomerController::class, 'newsletterPost'])->name('newsletter-post-api');
    Route::post('/contact-post', [ \App\Http\Controllers\ApiCustomerController::class, 'contactPost'])->name('contact-post-api');


    Route::group(['prefix' => 'buyback'], function () {

    });


Route::group(['prefix' => 'cart'], function () {
    Route::post('/show', [ \App\Http\Controllers\ApiCustomerController::class, 'showCart'])->name('show-cart-api');
    Route::post('/add', [ \App\Http\Controllers\ApiCustomerController::class, 'addToCart'])->name('add-to-cart-api');
    Route::post('/remove', [ \App\Http\Controllers\ApiCustomerController::class, 'removeFromCart'])->name('remove-cart-api');
    Route::post('/update', [ \App\Http\Controllers\ApiCustomerController::class, 'UpdateCart'])->name('update-cart-api');
    Route::post('/place-order', [ \App\Http\Controllers\ApiCustomerController::class, 'placeOrder'])->name('place-order-api');
    Route::post('/get-order-code', [ \App\Http\Controllers\ApiCustomerController::class, 'getOrderCode'])->name('get-order-code');

    Route::post('/add-order-address', [ \App\Http\Controllers\ApiCustomerController::class, 'addOrderAddress'])->name('add-order-address');
    Route::post('/payment-confirm', [ \App\Http\Controllers\ApiCustomerController::class, 'paymentConfirm'])->name('payment-confirm-api');
    Route::post('/cancel-order', [ \App\Http\Controllers\ApiCustomerController::class, 'cancelOrder'])->name('cancel-order-api');
    Route::post('/order-summary', [ \App\Http\Controllers\ApiCustomerController::class, 'orderSummary'])->name('order-summary-api');
    Route::post('/order-history', [ \App\Http\Controllers\ApiCustomerController::class, 'orderHistory'])->name('order-history-api');


});

Route::group(['prefix' => 'favorites'], function () {
    Route::post('/show', [ \App\Http\Controllers\ApiCustomerController::class, 'showFavorites'])->name('show-favorites-api');
    Route::post('/add', [ \App\Http\Controllers\ApiCustomerController::class, 'addToFavorites'])->name('add-to-favorites-api');
    Route::post('/remove', [ \App\Http\Controllers\ApiCustomerController::class, 'removeFromFavorites'])->name('remove-from-favorites-api');
});

Route::group(['prefix' => 'address'], function () {
    Route::post('/get', [ \App\Http\Controllers\ApiCustomerController::class, 'getAddress'])->name('get-address-api');
    Route::post('/show', [ \App\Http\Controllers\ApiCustomerController::class, 'showAddresses'])->name('show-addresses-api');
    Route::post('/add', [ \App\Http\Controllers\ApiCustomerController::class, 'addAddress'])->name('add-address-api');
    Route::post('/update', [ \App\Http\Controllers\ApiCustomerController::class, 'updateAddress'])->name('update-address-api');
    Route::post('/delete', [ \App\Http\Controllers\ApiCustomerController::class, 'deleteAddress'])->name('delete-address-api');
});


});
