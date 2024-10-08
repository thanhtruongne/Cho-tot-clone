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

use Modules\ProductElectronic\Http\Controllers\ProductElectronicController;

Route::prefix('system')->middleware('auth')->group(function() {
    Route::get('/products/electronic',[ProductElectronicController::class,'index'])->name('products.electronic');
    Route::get('/products/electronic/getData',[ProductElectronicController::class,'getData'])->name('products.electronic.getData');
    Route::post('/products/electronic/save',[ProductElectronicController::class,'save'])->name('products.electronic.save');
});
