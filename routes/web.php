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
Route::get('/login',[\App\Http\Controllers\HomeController::class,'login'])->name('login');
Route::post('/login',[\App\Http\Controllers\HomeController::class,'loginPost'])->name('admin.login');
Route::get('/check_image/{image}',[\App\Http\Controllers\HomeController::class,'checkImage'])->name('answers-order');

Route::group(['middleware'=>checkUser::class,'prefix'=>'admin'],function () {
    Route::get('/',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.index');

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
        Route::get('/questions_answers/{model_id}',[\App\Http\Controllers\ProductModelController::class,'questionsAnswers'])->name('questions-answers');
    });

});
