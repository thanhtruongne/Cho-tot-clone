<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\Products\ProductRentHouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Routes for password reset


Route::prefix('system')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    Route::post('/login', [LoginController::class, 'login'])->name('post.login');


    Route::group(['middleware' => ['auth', 'logvisit:admin']], function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');

        Route::get('/quanli', [CategoriesController::class, 'quanli'])->name('quanli');


        Route::get('/cagetories/get-data', [CategoriesController::class, 'getData'])->name('categories.getdata');

        Route::post('/cagetories/remove', [CategoriesController::class, 'remove'])->name('categories.remove');
        Route::post('/cagetories/save', [CategoriesController::class, 'save'])->name('categories.save');
        Route::get('/cagetories/edit', [CategoriesController::class, 'form'])->name('categories.edit');
        Route::get('/cagetories/create', [CategoriesController::class, 'form'])->name('categories.create');
        Route::post('/categories/change-status', [CategoriesController::class, 'changeStatus'])->name('categories.change.status');
        Route::post('/categories/remove', [CategoriesController::class, 'remove'])->name('categories.remove');
        Route::post('/categories/removeSelectAll', [CategoriesController::class, 'removeSelectAll'])->name('categories.remove.select');


        //product rentProductRentHouseController
        Route::get('manage-postings', [ProductRentHouseController::class, 'managePostings'])->name('manage-postings');
        Route::get('manage-postings-get-data', [ProductRentHouseController::class, 'getProductData'])->name('manage-postings.data');
        Route::post('manage-postings-delete/{id}', [ProductRentHouseController::class, 'deletePosting'])->name('manage-postings-delete');

        //manage Users
        Route::get('manage-users', [UserController::class, 'index'])->name('manage-users');
        Route::get('user/getData', [UserController::class, 'getData'])->name('manage-users.getData');
        Route::post('user/change-status', [UserController::class, 'getData'])->name('manage-users.changeStatus');
        Route::post('user/remove', [UserController::class, 'remove'])->name('manage-users.remove');
        Route::get('user/form',[UserController::class, 'form'])->name('manage-users.form');
        
    
        // Route::get('/manage-users-data', [LoginController::class, 'manageUsersData'])->name('manage-users.data');
        // Route::post('/manage-users-add', [LoginController::class, 'manageUsersAdd'])->name('manage-users-add');
        // Route::post('/manage-users-delete/{id}', [LoginController::class, 'manageUsersDelete'])->name('manage-users-delete');
        // Route::get('/manage-users-edit/{id}', [LoginController::class, 'manageUsersEdit'])->name('manage-users-edit');
        // Route::post('/manage-users-update/{id}', [LoginController::class, 'manageUsersUpdate'])->name('manage-users-update');
    });
});

Route::get('storage/video/{file}',[DashboardController::class,'videoStreaming'])->name('fe.video-streaming');
