<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\Products\ProductRentHouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Type\PostingTypeController;
use App\Models\PostingType;

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




    Route::group(['middleware' => ['auth', 'logvisit:web']], function () {
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

        //type_posting
        Route::get('/type-posting', [PostingTypeController::class, 'index'])->name('type-posting');
        Route::post('/type-posting-add', [PostingTypeController::class, 'addPostingType'])->name('type-posting-add');
        Route::get('/type-posting-data', [PostingTypeController::class, 'PostingTypesData'])->name('type-posting.data');
        Route::get('/type-posting-data-edit/{id}', [PostingTypeController::class, 'PostingTypesEdit'])->name('type-posting.edit');
        Route::post('/type-posting-data-update/{id}', [PostingTypeController::class, 'updatePostingType'])->name('type-posting-data-update');
        //thong ke



        Route::get('manage-users', [UserController::class, 'index'])->name('manage-users');
        Route::get('user/getData', [UserController::class, 'getData'])->name('manage-users.getData');
        Route::post('user/change-status', [UserController::class, 'getData'])->name('manage-users.changeStatus');
        Route::post('user/remove', [UserController::class, 'remove'])->name('manage-users.remove');
        Route::get('user/form', [UserController::class, 'form'])->name('manage-users.form');
    });
});

Route::get('storage/video/{file}', [DashboardController::class, 'videoStreaming'])->name('fe.video-streaming');
