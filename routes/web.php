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


Route::get('/',[\App\Http\Controllers\HomeController::class,'index'])->name('index');
Route::get('/logs',[\App\Http\Controllers\HomeController::class,'logs'])->name('logs');
//Route::get('/parsecities',[\App\Http\Controllers\HomeController::class,'parsecities'])->name('parsecities');
Route::get('/login',[\App\Http\Controllers\HomeController::class,'login'])->name('login');
Route::post('/login',[\App\Http\Controllers\HomeController::class,'loginPost'])->name('admin.login');

Route::get('/check_image/{image}',[\App\Http\Controllers\HomeController::class,'checkImage'])->name('answers-order');
Route::post('/create-buyback-post',[\App\Http\Controllers\HomeController::class,'createBuyBackPost'])->name('create-buyBack-post');

Route::group(['prefix'=>'data','as'=>'data.'],function (){
    Route::get('/get-models/{brand_id}',[\App\Http\Controllers\DataController::class,'getModels'])->name('get-models');
    Route::get('/get-questions/{model_id}/{model_answer_array?}/{buyback_id?}',[\App\Http\Controllers\DataController::class,'getQuestions'])
        ->name('get-questions');
    Route::get('/get-colors/{model_id}',[\App\Http\Controllers\DataController::class,'getColors'])->name('get-colors');
    Route::get('/get-offer/{model_id}/{answers}',[\App\Http\Controllers\DataController::class,'getOffer'])->name('get-offer');
    Route::get('/get-buyer-info/{model_id}/{calculate_result}/{price}/{imei}/{color_id}/{imei_id}',[\App\Http\Controllers\DataController::class,'getBuyerInfo'])->name('get-buyer-info');
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
    Route::get('/check-imei/{model_id}/{imei}',[\App\Http\Controllers\DataController::class,'checkImei'])->name('check-imei');
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

    Route::group(['prefix'=>'users','as'=>'users.'],function (){
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

    Route::group(['prefix'=>'site','as'=>'site.'],function (){
        Route::group(['prefix'=>'slider'],function (){
            Route::get('/list',[\App\Http\Controllers\SiteController::class,'sliderList'])->name('slider-list');
            Route::get('/create',[\App\Http\Controllers\SiteController::class,'createSlider'])->name('create-slider');
            Route::post('/create-post',[\App\Http\Controllers\SiteController::class,'createSliderPost'])->name('create-slider-post');
            Route::get('/update/{id}',[\App\Http\Controllers\SiteController::class,'updateSlider'])->name('update-slider');
            Route::post('/update-post',[\App\Http\Controllers\SiteController::class,'updateSliderPost'])->name('update-slider-post');
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
    Route::group(['prefix'=>'buyback','as'=>'buyback.'],function (){
        Route::get('/create',[\App\Http\Controllers\BuyBackController::class,'createBuyBack'])->name('create-buyBack');
        Route::post('/create',[\App\Http\Controllers\BuyBackController::class,'createBuyBackPost'])->name('create-buyBack-post');
        Route::get('/list/{user_id?}',[\App\Http\Controllers\BuyBackController::class,'buybackList'])->name('buyback-list');
        Route::get('/update/{bb_id}',[\App\Http\Controllers\BuyBackController::class,'buybackUpdate'])->name('buyback-update');
        Route::post('/update-user',[\App\Http\Controllers\BuyBackController::class,'updateUserPost'])->name('update-user');
        Route::post('/update',[\App\Http\Controllers\BuyBackController::class,'buybackUpdatePost'])->name('buybackupdate-post');
        Route::post('/update-answers',[\App\Http\Controllers\BuyBackController::class,'buybackUpdateAnswersPost'])->name('buybackupdateanswers-post');
        Route::get('/user-info/{user_id}',[\App\Http\Controllers\BuyBackController::class,'userInfo'])->name('user-info');
    });

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

});
