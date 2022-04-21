<?php

use App\Http\Middleware\checkUser;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
Route::get('/', function () {
   // return view('welcome');
});
*/

Route::get('/',[\App\Http\Controllers\HomeController::class,'home'])->name('home');
Route::get('/react',[\App\Http\Controllers\HomeController::class,'react'])->name('react');
Route::get('/post-confirm',[\App\Http\Controllers\HomeController::class,'postConfirm'])->name('post-confirm');
Route::get('/kk-form',[\App\Http\Controllers\HomeController::class,'kkForm'])->name('kk-form');
Route::get('/ziraat-post',[\App\Http\Controllers\HomeController::class,'postConfirm'])->name('post-confirm');
Route::get('/customer-fix',[\App\Http\Controllers\HomeController::class,'customerFix'])->name('customer-fix');
Route::get('/send-email/{email?}',[\App\Http\Controllers\HomeController::class,'sendEmail'])->name('send-email-test');
Route::get('/get-products',[\App\Http\Controllers\HomeController::class,'getProducts'])->name('get-products-ekspar');
Route::get('/list-products',[\App\Http\Controllers\HomeController::class,'listProducts'])->name('list-products');
Route::get('/get-brands',[\App\Http\Controllers\HomeController::class,'getBrands'])->name('get-brands-ekspar');
Route::get('/list-brands',[\App\Http\Controllers\HomeController::class,'listBrands'])->name('list-brands');
Route::get('/get-models',[\App\Http\Controllers\HomeController::class,'getModels'])->name('get-models-ekspar');
Route::get('/get-colors',[\App\Http\Controllers\HomeController::class,'getColors'])->name('get-colors-ekspar');
Route::get('/get-memories',[\App\Http\Controllers\HomeController::class,'getMemories'])->name('get-memories-ekspar');
Route::get('/cc-payment',[\App\Http\Controllers\HomeController::class,'ccPayment'])->name('cc-payment');
Route::get('/banks-list',[\App\Http\Controllers\HomeController::class,'banksList'])->name('banks-list');
Route::get('/bank-purchases',[\App\Http\Controllers\HomeController::class,'bankPurchases'])->name('bank-purchases');
Route::get('/clear-product-memories',[\App\Http\Controllers\HomeController::class,'clearProductMemories'])->name('clear-product-memories');


Route::get('/logs',[\App\Http\Controllers\HomeController::class,'logs'])->name('logs');
//Route::get('/parsecities',[\App\Http\Controllers\HomeController::class,'parsecities'])->name('parsecities');
Route::get('/login',[\App\Http\Controllers\HomeController::class,'login'])->name('login');
Route::post('/login',[\App\Http\Controllers\HomeController::class,'loginPost'])->name('admin.login');

Route::get('/check_image/{image}',[\App\Http\Controllers\HomeController::class,'checkImage'])->name('answers-order');
Route::post('/create-buyback-post',[\App\Http\Controllers\HomeController::class,'createBuyBackPost'])->name('create-buyBack-post');
Route::get('/get-category/{model_id}',[\App\Http\Controllers\ProductController::class,'getCategory'])->name('get-category');
Route::group(['prefix'=>'data','as'=>'data.'],function (){
    Route::get('/get-models/{brand_id}/{selected?}',[\App\Http\Controllers\DataController::class,'getModels'])->name('get-models');
    Route::get('/get-brands/{selected?}',[\App\Http\Controllers\DataController::class,'getBrands'])->name('get-brands');
    Route::get('/get-questions/{model_id}/{model_answer_array?}/{buyback_id?}',[\App\Http\Controllers\DataController::class,'getQuestions'])
        ->name('get-questions');
    Route::get('/get-colors/{model_id}',[\App\Http\Controllers\DataController::class,'getColors'])->name('get-colors');
    Route::get('/get-offer/{model_id}/{answers}',[\App\Http\Controllers\DataController::class,'getOffer'])->name('get-offer');
    Route::get('/get-buyer-info/{model_id}/{calculate_result}/{price}/{imei}/{color_id}/{imei_id}',[\App\Http\Controllers\DataController::class,'getBuyerInfo'])
        ->name('get-buyer-info');
    Route::get('/get-towns/{city_id}/{selected?}',[\App\Http\Controllers\DataController::class,'getTowns'])->name('get-towns');
    Route::get('/get-districts/{town_id}/{selected?}',[\App\Http\Controllers\DataController::class,'getDistricts'])->name('get-districts');
    Route::get('/get-neighborhoods/{district_id}/{selected?}',[\App\Http\Controllers\DataController::class,'getNeigborhoods'])->name('get-neighborhoods');
    Route::get('/get-postalcode/{neighborhood_id}',[\App\Http\Controllers\DataController::class,'getPostalcode'])->name('get-postalcode');
    Route::get('/check-email/{email}',[\App\Http\Controllers\DataController::class,'checkEmail'])->name('check-email');
    Route::get('/check-tckn/{tckn}',[\App\Http\Controllers\DataController::class,'checkTckn'])->name('check-tckn');
    Route::get('/check-phone/{phone}',[\App\Http\Controllers\DataController::class,'checkPhone'])->name('check-phone');
    Route::get('/check-iban/{iban}',[\App\Http\Controllers\DataController::class,'checkIban'])->name('check-iban');
   // Route::get('/check-imei/{model_id?}/{imei?}',[\App\Http\Controllers\DataController::class,'checkImei'])->name('check-imei');
    Route::get('/check-imei-server',[\App\Http\Controllers\DataController::class,'checkImeiServer'])->name('check-imei-server');
    Route::get('/imei-query/{model_id}/{imei}',[\App\Http\Controllers\DataController::class,'imeiQuery'])->name('imei-query');
  ///  Route::get('/check-imei/{model_id}/{imei}',[\App\Http\Controllers\DataController::class,'checkImei'])->name('check-imei');
});


Route::group(['middleware'=>checkUser::class,'prefix'=>'admin'],function () {
    Route::get('/',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.index');
    Route::get('/profile',[\App\Http\Controllers\AdminController::class,'profile'])->name('admin.profile');
    Route::get('/password',[\App\Http\Controllers\AdminController::class,'password'])->name('admin.password');
    Route::get('/settings',[\App\Http\Controllers\AdminController::class,'settings'])->name('admin.settings');
    Route::post('/profile-update',[\App\Http\Controllers\AdminController::class,'profilePost'])->name('profile-update');
    Route::post('/password-update',[\App\Http\Controllers\AdminController::class,'passwordPost'])->name('password-update');
    Route::post('/settings-update',[\App\Http\Controllers\AdminController::class,'settingsPost'])->name('settings-update');
    Route::post('/logout',[\App\Http\Controllers\AdminController::class,'logout'])->name('logout');


    Route::group(['middleware'=>\App\Http\Middleware\userAuth::class,'prefix'=>'users','as'=>'users.'],function (){
        Route::get('/user-list',[\App\Http\Controllers\UserController::class,'userList'])->name('user-list');
        Route::get('/create',[\App\Http\Controllers\UserController::class,'create'])->name('user-create');
        Route::post('/create',[\App\Http\Controllers\UserController::class,'createPost'])->name('user-create-post');

        Route::get('/group-create',[\App\Http\Controllers\UserController::class,'groupCreate'])->name('group-create');
        Route::post('/group-create',[\App\Http\Controllers\UserController::class,'groupCreatePost'])->name('group-create-post');

        Route::get('/group-update/{id}',[\App\Http\Controllers\UserController::class,'groupUpdate'])->name('group-update');
        Route::post('/group-update',[\App\Http\Controllers\UserController::class,'groupUpdatePost'])->name('group-update-post');


        Route::get('/generate-pw',[\App\Http\Controllers\UserController::class,'generatePw'])->name('user-generate-pw');
        Route::get('/check-email/{email}/{id?}',[\App\Http\Controllers\UserController::class,'checkEmail'])->name('check-emil');
        Route::get('/update/{id}',[\App\Http\Controllers\UserController::class,'update'])->name('user-update');
        Route::post('/update',[\App\Http\Controllers\UserController::class,'updatePost'])->name('user-update-post');
        Route::get('/user-groups',[\App\Http\Controllers\UserController::class,'userGroups'])->name('user-groups');

    });


    Route::group(['middleware'=>\App\Http\Middleware\siteAuth::class,'prefix'=>'site','as'=>'site.'],function (){

        Route::group(['prefix'=>'hr'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'hrList'])->name('hr-list');
            Route::get('/delete/{hr_id}',[\App\Http\Controllers\SiteController::class,'hrDelete'])->name('hr-delete');

        });

        Route::group(['prefix'=>'slider'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'sliderList'])->name('slider-list');
            Route::get('/create',[\App\Http\Controllers\SiteController::class,'createSlider'])->name('create-slider');
            Route::post('/create-post',[\App\Http\Controllers\SiteController::class,'createSliderPost'])->name('create-slider-post');
            Route::get('/update/{id}',[\App\Http\Controllers\SiteController::class,'updateSlider'])->name('update-slider');
            Route::post('/update-post',[\App\Http\Controllers\SiteController::class,'updateSliderPost'])->name('update-slider-post');
        });

        Route::group(['prefix'=>'faq'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'faqList'])->name('faq-list');
            Route::get('/create',[\App\Http\Controllers\SiteController::class,'createFaq'])->name('create-faq');
            Route::post('/create-post',[\App\Http\Controllers\SiteController::class,'createFaqPost'])->name('create-faq-post');
            Route::get('/update/{id}',[\App\Http\Controllers\SiteController::class,'updateFaq'])->name('update-faq');
            Route::post('/update-post',[\App\Http\Controllers\SiteController::class,'updateFaqPost'])->name('update-faq-post');
        });

        Route::group(['prefix'=>'news'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'newsList'])->name('news-list');
            Route::get('/create',[\App\Http\Controllers\SiteController::class,'createNews'])->name('create-news');
            Route::post('/create-post',[\App\Http\Controllers\SiteController::class,'createNewsPost'])->name('create-news-post');
            Route::get('/update/{id}',[\App\Http\Controllers\SiteController::class,'updateNews'])->name('update-news');
            Route::post('/update-post',[\App\Http\Controllers\SiteController::class,'updateNewsPost'])->name('update-news-post');
        });

        Route::group(['prefix'=>'product'],function (){
            Route::get('/list/{brand_id?}/{model_id?}',[\App\Http\Controllers\SiteController::class,'productLocation'])->name('product-location');

            Route::get('/locate/{id}',[\App\Http\Controllers\SiteController::class,'locateProduct'])->name('locate-product');
            Route::get('/get-location-order/{product_id}/{location_id}',[\App\Http\Controllers\SiteController::class,'getLocationOrder'])->name('get-location-order');

            Route::get('/location-delete/{location_id}',[\App\Http\Controllers\SiteController::class,'locationOrder'])->name('location-delete');
            Route::get('/reorder-location/{location_id}/{product_id}/{order}',[\App\Http\Controllers\SiteController::class,'locationOrder'])
                ->name('reorder-location');
            Route::post('/add-product-location',[\App\Http\Controllers\SiteController::class,'addLocation'])->name('add-product-location-post');
            Route::post('/product-locate-post',[\App\Http\Controllers\SiteController::class,'productLocatePost'])->name('product-locate-post');
            Route::get('/delete-product-location/{location_id}',[\App\Http\Controllers\SiteController::class,'deleteLocation'])->name('delete-product-location');
            Route::get('/change-product-location-order/{location_id}/{new_order}',[\App\Http\Controllers\SiteController::class,'changeLocationOrder'])->name('change-product-location-order');

        });
        Route::group(['prefix'=>'settings'],function (){
        Route::get('/site-settings',[\App\Http\Controllers\SiteController::class,'siteSettings'])->name('site-settings');
        Route::get('/create-setting',[\App\Http\Controllers\SiteController::class,'createSetting'])->name('create-setting');
        Route::post('/create-setting-post',[\App\Http\Controllers\SiteController::class,'createSettingPost'])->name('create-setting-post');
            Route::get('/update-setting/{id}',[\App\Http\Controllers\SiteController::class,'updateSetting'])->name('update-setting');
            Route::post('/update-setting-post',[\App\Http\Controllers\SiteController::class,'updateSettingPost'])->name('update-setting-post');
        });

        Route::group(['prefix'=>'menu'],function (){
            Route::get('/menu-list/{menu_type?}',[\App\Http\Controllers\SiteController::class,'menuList'])->name('menu-list');
            Route::get('/create-menu-item',[\App\Http\Controllers\SiteController::class,'createMenu'])->name('create-menu');
            Route::post('/create-menu-item-post',[\App\Http\Controllers\SiteController::class,'createMenuPost'])->name('create-menu-post');
            Route::get('/update-menu-item/{id}',[\App\Http\Controllers\SiteController::class,'updateMenu'])->name('update-menu');
            Route::post('/update-menu-item-post',[\App\Http\Controllers\SiteController::class,'updateMenuPost'])->name('update-menu-post');
            Route::get('/get-menu-count/{location}/{add?}/{selected?}',[\App\Http\Controllers\SiteController::class,'getMenuCount'])->name('get-menu-count');



            Route::get('/create-sub-menu-item/{menu_id}',[\App\Http\Controllers\SiteController::class,'createSubMenu'])->name('create-sub-menu');
            Route::post('/create-sub-menu-item-post',[\App\Http\Controllers\SiteController::class,'createSubMenuPost'])->name('create-sub-menu-post');

            Route::get('/update-sub-menu-item/{menu_id}',[\App\Http\Controllers\SiteController::class,'updateSubMenu'])->name('update-sub-menu');
            Route::post('/update-sub-menu-item-post',[\App\Http\Controllers\SiteController::class,'updateSubMenuPost'])->name('update-sub-menu-post');
            Route::get('/delete-sub-menu-item/{menu_id}',[\App\Http\Controllers\SiteController::class,'deleteSubMenu'])->name('delete-sub-menu');
            Route::get('/delete-sub-link-item/{link_id}',[\App\Http\Controllers\SiteController::class,'deleteSubLink'])->name('delete-sub-link');

            Route::get('/create-sub-group/{sub_menu_id}',[\App\Http\Controllers\SiteController::class,'createSubGroup'])->name('create-sub-group');
            Route::post('/create-sub-group-post',[\App\Http\Controllers\SiteController::class,'createSubGroupPost'])->name('create-sub-group-post');
            Route::get('/delete-sub-group/{group_id}',[\App\Http\Controllers\SiteController::class,'deleteSubGroup'])->name('delete-sub-group');

            Route::get('/create-sub-menu-link/{group_id}',[\App\Http\Controllers\SiteController::class,'createSubLink'])->name('create-sub-link');
            Route::post('/create-sub-menu-link-post',[\App\Http\Controllers\SiteController::class,'createSubLinkPost'])->name('create-sub-link-post');

        });

        Route::group(['prefix'=>'area'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'areaList'])->name('area-list');
            Route::get('/create',[\App\Http\Controllers\SiteController::class,'createArea'])->name('create-area');
            Route::post('/create-post',[\App\Http\Controllers\SiteController::class,'createAreaPost'])->name('create-area-post');
            Route::get('/update/{id}',[\App\Http\Controllers\SiteController::class,'updateArea'])->name('update-area');
            Route::post('/update-post',[\App\Http\Controllers\SiteController::class,'updateAreaPost'])->name('update-area-post');


        });
        Route::group(['prefix'=>'banner'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'bannerList'])->name('banner-list');
            Route::get('/create',[\App\Http\Controllers\SiteController::class,'createBanner'])->name('create-banner');
            Route::post('/create-post',[\App\Http\Controllers\SiteController::class,'createBannerPost'])->name('create-banner-post');
            Route::get('/update/{id}',[\App\Http\Controllers\SiteController::class,'updateBanner'])->name('update-banner');
            Route::post('/update-post',[\App\Http\Controllers\SiteController::class,'updateBannerPost'])->name('update-banner-post');


        });
        Route::group(['prefix'=>'articles'],function (){
            Route::get('/list',[\App\Http\Controllers\ArticleController::class,'articleList'])->name('article-list');
            Route::get('/create',[\App\Http\Controllers\ArticleController::class,'createArticle'])->name('create-article');
            Route::post('/create-post',[\App\Http\Controllers\ArticleController::class,'createArticlePost'])->name('create-article-post');
            Route::get('/update/{id}',[\App\Http\Controllers\ArticleController::class,'updateArticle'])->name('update-article');
            Route::post('/update-post',[\App\Http\Controllers\ArticleController::class,'updateArticlePost'])->name('update-article-post');

            Route::get('/update-part/{part_id}',[\App\Http\Controllers\ArticleController::class,'updateArticlePart'])->name('update-article-part');
            Route::post('/update-part',[\App\Http\Controllers\ArticleController::class,'updateArticlePartPost'])->name('update-article-part-post');
            Route::get('/create-part/{article_id}',[\App\Http\Controllers\ArticleController::class,'createArticlePart'])->name('create-article-part');
            Route::get('/delete-part/{part_id}',[\App\Http\Controllers\ArticleController::class,'deletePart'])->name('delete-article-part');
            Route::post('/create-part-post',[\App\Http\Controllers\ArticleController::class,'createArticlePartPost'])->name('create-article-part-post');
        });

    });

    Route::group(['middleware'=>\App\Http\Middleware\buybackAuth::class,'prefix'=>'buyback','as'=>'buyback.'],function (){
        Route::get('/create',[\App\Http\Controllers\BuyBackController::class,'createBuyBack'])->name('create-buyBack');
        Route::post('/create',[\App\Http\Controllers\BuyBackController::class,'createBuyBackPost'])->name('create-buyBack-post');
        Route::get('/list/{user_id?}',[\App\Http\Controllers\BuyBackController::class,'buybackList'])->name('buyback-list');
        Route::get('/update/{bb_id}',[\App\Http\Controllers\BuyBackController::class,'buybackUpdate'])->name('buyback-update');
        Route::post('/update-user',[\App\Http\Controllers\BuyBackController::class,'updateUserPost'])->name('update-user');
        Route::post('/update',[\App\Http\Controllers\BuyBackController::class,'buybackUpdatePost'])->name('buybackupdate-post');
        Route::post('/update-answers',[\App\Http\Controllers\BuyBackController::class,'buybackUpdateAnswersPost'])->name('buybackupdateanswers-post');
        Route::get('/user-info/{user_id}',[\App\Http\Controllers\BuyBackController::class,'userInfo'])->name('user-info');
    });

    Route::group(['middleware'=>\App\Http\Middleware\productAuth::class,'prefix'=>'products','as'=>'products.'],function (){

        Route::get('/list/{brand_id?}/{model_id?}',[\App\Http\Controllers\ProductController::class,'productList'])->name('product-list');
        Route::get('/update/{product_id}/{selected?}/{brand_id?}/{model_id?}',[\App\Http\Controllers\ProductController::class,'productUpdate'])
            ->name('product-update');
        Route::post('/update',[\App\Http\Controllers\ProductController::class,'productUpdatePost'])->name('product-update-post');


        Route::get('/create/{brand_id?}/{model_id?}',[\App\Http\Controllers\ProductController::class,'createProduct'])->name('create-product');
        Route::post('/create-post',[\App\Http\Controllers\ProductController::class,'createProductPost'])->name('create-product-post');
        Route::get('/get-colors/{model_id}',[\App\Http\Controllers\ProductController::class,'getColors'])->name('get-colors');


        Route::post('/update-post',[\App\Http\Controllers\ProductController::class,'updateProductPost'])->name('update-product-post');
        Route::post('/add-product-image-post',[\App\Http\Controllers\ProductController::class,'addImage'])->name('add-product-image-post');

        Route::get('/add-brand/{product_id?}',[\App\Http\Controllers\ProductController::class,'addBrand'])->name('add-product-brand');
        Route::post('/add-brand-post',[\App\Http\Controllers\ProductController::class,'addBrandPost'])->name('add-product-brand-post');

        Route::get('/add-model/{brand_id}/{product_id?}',[\App\Http\Controllers\ProductController::class,'addModel'])->name('add-product-model');
        Route::post('/add-model-post',[\App\Http\Controllers\ProductController::class,'addModelPost'])->name('add-product-model-post');
        Route::post('/color-product-post',[\App\Http\Controllers\ProductController::class,'colorProduct'])->name('color-product-post');
        Route::post('/memory-product-post',[\App\Http\Controllers\ProductController::class,'memoryProduct'])->name('memory-product-post');

        Route::get('/change-image-order/{order}/{image_id}',[\App\Http\Controllers\ProductController::class,'changeImageOrder'])->name('change-image-order');
        Route::get('/change-first/{image_id}',[\App\Http\Controllers\ProductController::class,'changeFirst'])->name('change-first');
        Route::get('/delete-image/{image_id}',[\App\Http\Controllers\ProductController::class,'deleteImage'])->name('delete-image');

        Route::get('/add-variant/{group_id}/{product_id}',[\App\Http\Controllers\ProductController::class,'addVariant'])->name('add-variant');
        Route::post('/add-variant-post',[\App\Http\Controllers\ProductController::class,'addVariantPost'])->name('add-variant-post');

        Route::get('/add-variant-value/{variant_id}/{product_id}',[\App\Http\Controllers\ProductController::class,'addVariantValue'])->name('add-variant-value');
        Route::post('/add-variant-value-post',[\App\Http\Controllers\ProductController::class,'addVariantValuePost'])->name('add-variant-value-post');
        Route::post('/product-variant-value-post',[\App\Http\Controllers\ProductController::class,'productVariantValuePost'])->name('product-variant-value-post');
        Route::post('/product-stock-movement',[\App\Http\Controllers\ProductController::class,'productStockMovement'])->name('product-stock-movement');
        Route::post('/product-stock-movement-update',[\App\Http\Controllers\ProductController::class,'productStockMovementUpdate'])->name('product-stock-movement-update');
        Route::get('/check-stock/{product_id}/{color_id}/{memory_id}/{qty}/{stock_id?}',[\App\Http\Controllers\ProductController::class,'productStockCheck'])
            ->name('product-stock-check');
        Route::get('/update-stock/{stock_id}',[\App\Http\Controllers\ProductController::class,'updateStock'])->name('stock-update');

    });/////////////////////products

    Route::group(['middleware'=>\App\Http\Middleware\marketAuth::class,'prefix'=>'market-place','as'=>'market.'],function (){
        Route::get('/gittigidiyor',[\App\Http\Controllers\MarketPlaceController::class,'gittigidiyor'])->name('gittigidiyor');
        Route::get('/hepsi-burada',[\App\Http\Controllers\MarketPlaceController::class,'hepsiBurada'])->name('hepsi-burada');
        Route::get('/hepsi-burada-cats/{count?}',[\App\Http\Controllers\MarketPlaceController::class,'hepsiBuradaCats'])->name('hepsi-burada-cats');
        Route::get('/hepsi-burada-cat-detail/{cat_id}',[\App\Http\Controllers\MarketPlaceController::class,'hbCatDetail'])->name('hepsi-burada-cat-detail');
        Route::get('/hb-cat-values/{cat_id}',[\App\Http\Controllers\MarketPlaceController::class,'hbCatValues'])->name('hb-cat-values');

    });


    Route::group(['middleware'=>\App\Http\Middleware\systemAuth::class],function (){

    Route::group(['prefix'=>'brand','as'=>'brand.'],function (){
        Route::get('/list',[\App\Http\Controllers\DataController::class,'brandlist'])->name('brandlist');
    Route::get('/add',[\App\Http\Controllers\DataController::class,'brandadd'])->name('brandadd');
    Route::post('/add-post',[\App\Http\Controllers\DataController::class,'brandaddPost'])->name('brandadd-post');
    Route::get('/update/{id}',[\App\Http\Controllers\DataController::class,'brandupdate'])->name('brandupdate');
    Route::post('/update-post',[\App\Http\Controllers\DataController::class,'brandupdatePost'])->name('brandupdate-post');

    });

    Route::group(['prefix'=>'model','as'=>'model.'],function (){
        Route::get('/list',[\App\Http\Controllers\ProductModelController::class,'modelList'])->name('model-list');
        Route::get('/add',[\App\Http\Controllers\ProductModelController::class,'modelAdd'])->name('model-add');
        Route::post('/add-post',[\App\Http\Controllers\ProductModelController::class,'modelAddPost'])->name('model-add-post');
        Route::get('/update/{id}',[\App\Http\Controllers\ProductModelController::class,'modelUpdate'])->name('model-update');
        Route::post('/update-post',[\App\Http\Controllers\ProductModelController::class,'modelUpdatePost'])->name('model-update-post');
        Route::get('/questions_answers/{model_id}',[\App\Http\Controllers\ProductModelController::class,'modelQuestions'])->name('model-questions');
        Route::get('/reorder_model_question/{model_question_id}/{count}',[\App\Http\Controllers\ProductModelController::class,'reorderModelQuestions'])
            ->name('reorder-model-question');
        Route::get('/add_model_question/{question_id}/{model_id}/{count}',[\App\Http\Controllers\ProductModelController::class,'addModelQuestions'])
            ->name('add-model-question');
        Route::get('/delete_model_question/{model_question_id}',[\App\Http\Controllers\ProductModelController::class,'deleteModelQuestions'])
            ->name('delete-model-question');
        Route::get('/update_model_answer/{model_answer_id}/{value}',[\App\Http\Controllers\ProductModelController::class,'updateModelAnswer'])
            ->name('update-model-answer');
    });

    Route::group(['prefix'=>'color','as'=>'color.'],function (){
        Route::get('/list',[\App\Http\Controllers\DataController::class,'colorlist'])->name('color-list');
        Route::get('/add',[\App\Http\Controllers\DataController::class,'colorAdd'])->name('color-add');
        Route::post('/add-post',[\App\Http\Controllers\DataController::class,'colorAddPost'])->name('colorAdd-post');
        Route::get('/update/{id}',[\App\Http\Controllers\DataController::class,'colorUpdate'])->name('colorUpdate');
        Route::post('/update-post',[\App\Http\Controllers\DataController::class,'colorUpdatePost'])->name('colorUpdate-post');
    });

        Route::group(['prefix'=>'return','as'=>'return.'],function (){
            Route::get('/list',[\App\Http\Controllers\DataController::class,'returnList'])->name('return-list');
            Route::get('/add',[\App\Http\Controllers\DataController::class,'returnAdd'])->name('return-add');
            Route::post('/add-post',[\App\Http\Controllers\DataController::class,'returnAddPost'])->name('returnAdd-post');
            Route::get('/update/{id}',[\App\Http\Controllers\DataController::class,'returnUpdate'])->name('returnUpdate');
            Route::post('/update-post',[\App\Http\Controllers\DataController::class,'returnUpdatePost'])->name('returnUpdate-post');
        });

    Route::group(['prefix'=>'question','as'=>'question.'],function (){
        Route::get('/',[\App\Http\Controllers\DataController::class,'questionList'])->name('question-list');
        Route::get('/add',[\App\Http\Controllers\DataController::class,'questionAdd'])->name('questionadd');
        Route::post('/add-post',[\App\Http\Controllers\DataController::class,'questionAddPost'])->name('questionadd-post');
        Route::get('/update/{id}',[\App\Http\Controllers\DataController::class,'questionUpdate'])->name('questionupdate');
        Route::post('/update-post',[\App\Http\Controllers\DataController::class,'questionUpdatePost'])->name('questionupdate-post');
        Route::get('/update-answer/{id}/{value}',[\App\Http\Controllers\DataController::class,'updateAnswer'])->name('updateAnswer');
    });

    Route::group(['prefix'=>'memory','as'=>'memory.'],function (){
        Route::get('/',[\App\Http\Controllers\DataController::class,'memorylist'])->name('memorylist');
        Route::get('/add',[\App\Http\Controllers\DataController::class,'memoryadd'])->name('memoryadd');
        Route::post('/add-post',[\App\Http\Controllers\DataController::class,'memoryaddPost'])->name('memoryadd-post');
        Route::post('/update-post',[\App\Http\Controllers\DataController::class,'memoryupdatePost'])->name('memoryupdate-post');
        Route::get('/update/{id}',[\App\Http\Controllers\DataController::class,'memoryupdate'])->name('memoryupdate');
        Route::get('/check-memory/{val}/{id?}',[\App\Http\Controllers\DataController::class,'checkMemory'])->name('check-memory');

    });

    Route::group(['prefix'=>'cargo','as'=>'cargo.'],function (){
            Route::get('/',[\App\Http\Controllers\DataController::class,'cargoList'])->name('cargo-list');
            Route::get('/add',[\App\Http\Controllers\DataController::class,'cargoAdd'])->name('cargo-add');
            Route::post('/add-post',[\App\Http\Controllers\DataController::class,'cargoAddPost'])->name('cargo-add-post');
            Route::get('/update/{id}',[\App\Http\Controllers\DataController::class,'cargoUpdate'])->name('cargo-update');
            Route::get('/add-branch/{company_id}',[\App\Http\Controllers\DataController::class,'addBranch'])->name('cargo-add-branch');
            Route::post('/add-branch-post',[\App\Http\Controllers\DataController::class,'addBranchPost'])->name('cargo-add-branch-post');
            Route::get('/update-branch/{branch_id}',[\App\Http\Controllers\DataController::class,'updateBranch'])->name('cargo-update-branch');
            Route::post('/update-branch-post',[\App\Http\Controllers\DataController::class,'updateBranchPost'])->name('cargo-update-branch-post');

            Route::post('/update-post',[\App\Http\Controllers\DataController::class,'cargoUpdatePost'])->name('cargo-update-post');

        });
    });
    Route::get('/service-addresses',[\App\Http\Controllers\DataController::class,'serviceAddressesList'])->name('service-addresses-list');
    Route::get('/add-service-address',[\App\Http\Controllers\DataController::class,'addServiceAddress'])->name('add-service-address');
    Route::post('/add-service-address-post',[\App\Http\Controllers\DataController::class,'addServiceAddressPost'])->name('add-service-address-post');
    Route::get('/update-service-address/{address_id}',[\App\Http\Controllers\DataController::class,'updateServiceAddress'])->name('update-service-address');
    Route::post('/update-service-address-post',[\App\Http\Controllers\DataController::class,'updateServiceAddressPost'])->name('update-service-address-post');


    Route::group(['middleware'=>\App\Http\Middleware\customerAuth::class,'prefix'=>'customers','as'=>'customer.'],function (){
        Route::get('/',[\App\Http\Controllers\CustomerController::class,'customerList'])->name('customer-list');
        Route::get('/update/{customer_id}/{selected?}',[\App\Http\Controllers\CustomerController::class,'customerUpdate'])->name('customer-update');
        Route::get('/delete-cart-item/{cart_item_id}',[\App\Http\Controllers\CustomerController::class,'deleteCartItem'])->name('delete-cart-item');
        Route::post('/update-post',[\App\Http\Controllers\CustomerController::class,'customerUpdatePost'])->name('customer-update-post');
        Route::post('/update-pw',[\App\Http\Controllers\CustomerController::class,'customerUpdatePW'])->name('customer-update-pw');
        Route::get('/check-email/{email}/{customer_id}',[\App\Http\Controllers\CustomerController::class,'checkEmail'])->name('customer-check-email');
        Route::get('/address-update/{customer_id}/{address_id}',[\App\Http\Controllers\CustomerController::class,'addressUpdate'])->name('customer-address-update');
        Route::post('/update-address-post',[\App\Http\Controllers\CustomerController::class,'customerUpdateAddress'])->name('customer-update-address');
        Route::get('/orders',[\App\Http\Controllers\CustomerController::class,'orders'])->name('orders');
        Route::get('/guests',[\App\Http\Controllers\CustomerController::class,'guests'])->name('guests');
        Route::get('/newsletter',[\App\Http\Controllers\CustomerController::class,'newsletter'])->name('newsletter');
        Route::get('/contacts',[\App\Http\Controllers\CustomerController::class,'contacts'])->name('contacts');
        Route::get('/contact-detail/{id}',[\App\Http\Controllers\CustomerController::class,'contactDetail'])->name('contact-detail');
        Route::get('/delete-guest/{guid}',[\App\Http\Controllers\CustomerController::class,'deleteGuest'])->name('delete-guest');
        Route::get('/delete-newsletter/{id}',[\App\Http\Controllers\CustomerController::class,'deleteNewsletter'])->name('delete-newsletter');
        Route::get('/delete-contact/{id}',[\App\Http\Controllers\CustomerController::class,'deleteContact'])->name('delete-contact');
        Route::get('/cargo-branches/{cc_id}/{selected?}',[\App\Http\Controllers\CustomerController::class,'cargoBranchSelect'])->name('cargo-branch-select');
        Route::get('/branch-detail/{branch_id}',[\App\Http\Controllers\CustomerController::class,'branchDetail'])->name('branch-detail');
        Route::get('/customer-address-detail/{address_id}',[\App\Http\Controllers\CustomerController::class,'customerAddressDetail'])->name('customer-address-detail');
        Route::get('/service-address-detail/{address_id}',[\App\Http\Controllers\CustomerController::class,'serviceAddressDetail'])->name('service-address-detail');
        Route::get('/bank-account-detail/{address_id}',[\App\Http\Controllers\CustomerController::class,'bankAccountDetail'])->name('bank-account-detail');
        Route::get('/order-update/{order_id}/{selected?}',[\App\Http\Controllers\CustomerController::class,'orderUpdate'])->name('order-update');
        Route::get('/cargo-code-check/{cargo_code}/{order_id}',[\App\Http\Controllers\CustomerController::class,'cargoCodeCheck'])->name('cargo-code-check');
        Route::post('/order-update-post',[\App\Http\Controllers\CustomerController::class,'orderUpdatePost'])->name('order-update-post');
        Route::post('/order-cancel-update-post',[\App\Http\Controllers\CustomerController::class,'orderCancelUpdatePost'])->name('order-cancel-update-post');
    });
});
