<?php

use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\Products\ProductElectronicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [ApiAuthController::class,'login'])->name('fe.login');
    Route::post('logout', [ApiAuthController::class,'logout'])->name('fe.logout');
    Route::post('refresh', [ApiAuthController::class,'refresh'])->name('fe.refresh');
    Route::post('register', [ApiAuthController::class,'register'])->name('fe.register');

});

Route::group([
    'middleware' => ['api','jwt.vertify'],
    'prefix' => 'auth'
], function ($router) {

  Route::group(['prefix' => '/product'],function(){
    Route::post('/get-data', [ProductElectronicController::class,'getData'])->name('fe.product-electric.getData');
    Route::post('/save', [ProductElectronicController::class,'save'])->name('fe.product-electric.save');
  });

});
