<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductElectronicController;
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
 

    Route::group(['middleware' => ['auth','logvisit:admin']],function(){
        Route::post('/logout',[LoginController::class,'logout'])->name('logout');

 

       Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

       Route::get('/categories',[CategoriesController::class,'index'])->name('categories');
       Route::get('/cagetories/get-data',[CategoriesController::class,'getData'])->name('categories.getdata'); 

       Route::post('/cagetories/remove',[CategoriesController::class,'remove'])->name('categories.remove'); 
       Route::post('/cagetories/save',[CategoriesController::class,'save'])->name('categories.save');       
       Route::get('/cagetories/edit',[CategoriesController::class,'form'])->name('categories.edit');    
       Route::get('/cagetories/create',[CategoriesController::class,'form'])->name('categories.create');    
       Route::post('/categories/change-status',[CategoriesController::class,'changeStatus'])->name('categories.change.status');
       Route::post('/categories/remove',[CategoriesController::class,'remove'])->name('categories.remove');
       Route::post('/categories/removeSelectAll',[CategoriesController::class,'removeSelectAll'])->name('categories.remove.select');
   



       //User
       Route::get('/user',[UserController::class,'index'])->name('user.index');
       Route::get('/user/getData',[UserController::class,'getData'])->name('user.getData');
       Route::post('/user/remove',[UserController::class,'remove'])->name('user.remove');
   
    });
   
});
