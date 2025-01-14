<?php

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

use Modules\ProductRentHouse\Http\Controllers\ProductRentHouseController;

Route::group(["prefix" => "system/productRentHouse", 'middleware' => ['auth']], function () {
    Route::get('/getData', [ProductRentHouseController::class, 'getData'])->name('productRentHouse.getData');
    Route::post('/remove', [ProductRentHouseController::class, 'remove'])->name('productRentHouse.remove');
});
