<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
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
       Route::get('/cagetories/get-data',[CategoriesController::class,'getData'])->name('categories.getdata'); 
       Route::post('/cagetories/remove',[CategoriesController::class,'remove'])->name('categories.remove');    
       Route::post('/cagetories/form/{id}',[CategoriesController::class,'form'])->name('categories.form');    
    });
   
});
