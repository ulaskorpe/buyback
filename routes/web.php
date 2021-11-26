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
//Route::get('/parsecities',[\App\Http\Controllers\HomeController::class,'parsecities'])->name('parsecities');
Route::get('/login',[\App\Http\Controllers\HomeController::class,'login'])->name('login');
Route::post('/login',[\App\Http\Controllers\HomeController::class,'loginPost'])->name('admin.login');

Route::get('/check_image/{image}',[\App\Http\Controllers\HomeController::class,'checkImage'])->name('answers-order');

Route::group(['middleware'=>checkUser::class,'prefix'=>'admin'],function () {
    Route::get('/',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.index');
    Route::get('/profile',[\App\Http\Controllers\AdminController::class,'profile'])->name('admin.profile');
    Route::get('/password',[\App\Http\Controllers\AdminController::class,'password'])->name('admin.password');
    Route::get('/settings',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.settings');
    Route::post('/profile-update',[\App\Http\Controllers\AdminController::class,'profilePost'])->name('profile-update');
    Route::post('/password-update',[\App\Http\Controllers\AdminController::class,'passwordPost'])->name('password-update');
    Route::post('/settings-update',[\App\Http\Controllers\AdminController::class,'settingsPost'])->name('settings-update');
    Route::post('/logout',[\App\Http\Controllers\AdminController::class,'logout'])->name('logout');
    Route::group(['prefix'=>'data','as'=>'data.'],function (){
        Route::get('/get-models/{brand_id}',[\App\Http\Controllers\DataController::class,'getModels'])->name('get-models');
        Route::get('/get-questions/{brand_id}',[\App\Http\Controllers\DataController::class,'getQuestions'])->name('get-questions');
        Route::get('/get-offer/{model_id}/{answers}',[\App\Http\Controllers\DataController::class,'getOffer'])->name('get-offer');
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
