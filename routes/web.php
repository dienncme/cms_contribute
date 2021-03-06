<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\ProductRecipientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(["owner"])->group(function () {
    Route::get('/contributor/index', [ContributorController::class, 'index']);
    Route::get('/contributor/create', [ContributorController::class, 'create']);
    Route::get('/contributor/edit/{id}', [ContributorController::class, 'edit']);
    Route::get('/contributor/delete/{id}', [ContributorController::class, 'delete']);
    Route::post('/contributor/store', [ContributorController::class, 'store']);
    Route::post('/contributor/update/{id}', [ContributorController::class, 'update']);
    Route::post('/contributor/destroy/{id}', [ContributorController::class, 'destroy']);
});

Route::middleware(["recipient", "owner"])->group(function () {
    Route::get('/recipients/home', [ContributorController::class, 'registerHome']);
    Route::get('/recipients/register_category', [ContributorController::class, 'registerCategory']);
    Route::post('/recipients/save_register_category', [ContributorController::class, 'saveRegisterCategory']);
    Route::get('/recipients/list/{id}', [ContributorController::class, 'listRecipient']);
});

Route::middleware(["contributor", "owner"])->group(function () {
    Route::get('/contributor/infor', [ContributorController::class, 'infor']);
    Route::get('/product/index', [ProductsController::class, 'index']);
    Route::get('/product/create', [ProductsController::class, 'create']);
    Route::get('/product/edit/{id}', [ProductsController::class, 'edit']);
    Route::get('/product/delete/{id}', [ProductsController::class, 'delete']);
});

Route::middleware(["ship", "owner"])->group(function () {

});


// l??u s???n ph???m
Route::post('/product/store', [ProductsController::class, 'store']);
// c???p nh???t s???n ph???m
Route::post('/product/update/{id}', [ProductsController::class, 'update']);
// x??a s???n ph???m
Route::post('/product/destroy/{id}', [ProductsController::class, 'destroy']);

Route::get('/category/create', [CategoryController::class, 'create']);
Route::get('/category/edit/{id}', [CategoryController::class, 'edit']);
Route::get('/category/delete/{id}', [CategoryController::class, 'delete']);
// l??u danh m???c
Route::post('/category/store', [CategoryController::class, 'store']);
// c???p nh???t danh m???c
Route::post('/category/update/{id}', [CategoryController::class, 'update']);
// x??a danh m???c
Route::post('/category/destroy/{id}', [CategoryController::class, 'destroy']);

Route::get('/', [UserLoginController::class, 'index']);
Route::get('/login', [UserLoginController::class, 'index']);
Route::post('/contributor/login', [UserLoginController::class, 'login']);
Route::get('/logout', [UserLoginController::class, 'logout']);


Route::get('/category/contribute', [CategoryController::class, 'categoryContribute']);
Route::get('/product/contribute/{category_id}/{recipient_id}', [ProductsController::class, 'productContribute']);
// Quy??n g??p s???n ph???m
Route::post('/contribute', [ProductRecipientController::class, 'store']);
//Hi???n th??? danh s??ch s???n ph???m quy??n g??p cho ng?????i nh???n
Route::get('/products/receive', [ProductsController::class, 'receive']);
//x??a danh m???c ng?????i nh???n ???? ????ng k??
Route::post('/delete/categoryRegister', [ContributorController::class, 'deleteCategoryRegister']);




