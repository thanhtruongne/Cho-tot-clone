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

use Modules\Permission\Http\Controllers\PermissionController;

Route::group(['prefix' => 'system/permissions','middleware' => ['auth']],function() {
    Route::get('/', [PermissionController::class,'index'])->name('permission.index');
    Route::get('/getData', [PermissionController::class,'getData'])->name('permission.getData');
});
