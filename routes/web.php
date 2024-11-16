<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductElectronicController;
use App\Http\Controllers\Api\Products\ProductRentHouseController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('system')->group(function () {
    Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');

    Route::post('/login',[LoginController::class,'login'])->name('post.login');


    Route::group(['middleware' => ['auth']],function(){
       Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

       Route::get('/categories',[CategoriesController::class,'index'])->name('categories');

       Route::get('/quanli',[CategoriesController::class,'quanli'])->name('quanli');


       Route::get('/cagetories/get-data',[CategoriesController::class,'getData'])->name('categories.getdata');

       Route::post('/cagetories/remove',[CategoriesController::class,'remove'])->name('categories.remove');
       Route::post('/cagetories/save',[CategoriesController::class,'save'])->name('categories.save');
       Route::get('/cagetories/edit',[CategoriesController::class,'form'])->name('categories.edit');
       Route::get('/cagetories/create',[CategoriesController::class,'form'])->name('categories.create');
       Route::post('/categories/change-status',[CategoriesController::class,'changeStatus'])->name('categories.change.status');
       Route::post('/categories/remove',[CategoriesController::class,'remove'])->name('categories.remove');
       Route::post('/categories/removeSelectAll',[CategoriesController::class,'removeSelectAll'])->name('categories.remove.select');


       //product rent
       Route::get("/manage-postings", [ProductRentHouseController::class, 'managePostings'])->name('manage-postings');
       Route::post("/manage-postings-delete/{id}", [ProductRentHouseController::class, 'deletemanagePostings'])->name('manage-postings-delete');


       //manage Users
       Route::get('/manage-users',action: [LoginController::class,'manageUsers'])->name('manage-users');
       Route::post('/manage-users-add',action: [LoginController::class,'manageUsersAdd'])->name('manage-users-add');
       Route::post('/manage-users-delete/{id}',action: [LoginController::class,'manageUsersDelete'])->name('manage-users-delete');
       Route::get('/manage-users-edit/{id}',action: [LoginController::class,'manageUsersEdit'])->name('manage-users-edit');
       Route::post('/manage-users-update/{id}',action: [LoginController::class,'manageUsersUpdate'])->name('manage-users-update');
    });

});
