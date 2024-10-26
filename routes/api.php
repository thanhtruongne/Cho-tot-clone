<?php

use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\BrokerController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductJobController;
use App\Http\Controllers\Api\ProductRentHouseCommentController;
use App\Http\Controllers\Api\ProductRentHouseLikeController;
use App\Http\Controllers\Api\Products\ProductElectronicController;
use App\Http\Controllers\Api\Products\ProductRentHouseController;
use App\Http\Controllers\Api\Type\BathroomTypeController;
use App\Http\Controllers\Api\Type\BedroomTypeController;
use App\Http\Controllers\Api\Type\MainDoorHouseController;
use App\Http\Controllers\Api\Type\PostingTypeController;
use App\Http\Controllers\Api\Type\TypeJobCategoryController;
use App\Http\Controllers\Api\Type\TypeOfHouseController;
use App\Http\Controllers\ProductJobQuestionController;
use App\Http\Controllers\ProductJobUserViewCvController;
use App\Models\ProductRentHouseComment;
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
Route::post('/zalopay/payment', [PaymentController::class, 'createPaymentLink']);
Route::get('/zalopay/handle-return-url', [PaymentController::class, 'handleReturnUrl']);

Route::group([
  'middleware' => 'api',
  'prefix' => 'auth'
], function ($router) {

  Route::post('login', [ApiAuthController::class, 'login'])->name('fe.login');
  Route::post('logout', [ApiAuthController::class, 'logout'])->name('fe.logout');
  Route::post('refresh', [ApiAuthController::class, 'refresh'])->name('fe.refresh');
  Route::post('register', [ApiAuthController::class, 'register'])->name('fe.register');
});

Route::group([
  'middleware' => ['api', 'jwt.vertify'],
  'prefix' => 'auth'
], function ($router) {
  Route::get('/user', [ApiAuthController::class, 'me'])->name('fe.get-user');
  
  Route::group(['prefix' => '/product'], function () {
    //product_eletronics
    Route::post('/get-data', [ProductElectronicController::class, 'getData'])->name('fe.product-electric.getData');
    Route::post('/save', [ProductElectronicController::class, 'save'])->name('fe.product-electric.save');
    Route::post('/add-product', [ProductElectronicController::class, 'addPr oduct'])->name('fe.product-electric.addProduct');
    Route::post('/delete-product/{id}', [ProductElectronicController::class, 'deleteProduct'])->name('fe.product-electric.deleteProduct');
    Route::post('/update-product/{id}', [ProductElectronicController::class, 'updateProduct'])->name('fe.product-electric.updateProduct');

    //product_rent_house
    Route::post('/test', [ProductRentHouseController::class, 'test']);
    Route::get('/get-product-rent-user-id/{id}', [ProductRentHouseController::class, 'getDataProductRentGetUserId']);
    Route::get('/get-data-product-rent', [ProductRentHouseController::class, 'getDataProductRent']);
    Route::post('/add-product-rent', [ProductRentHouseController::class, 'addProductRent']);
    Route::post('/delete-product-rent/{id}', [ProductRentHouseController::class, 'deleteProductRent']);
    Route::post('/update-product-rent/{id}', [ProductRentHouseController::class, 'updateProductRent']);

    //bathroom_type
    Route::post('/add-bathroom-type', [BathroomTypeController::class, 'addBathroomType']);
    Route::post('/delete-bathroom-type/{id}', [BathroomTypeController::class, 'deleteBathroomType']);
    Route::post('/update-bathroom-type/{id}', [BathroomTypeController::class, 'updateBathroomType']);

    //bedroom_type
        Route::post('/add-bedroom-type', [BedroomTypeController::class, 'addBedroomType']);
    Route::post('/delete-bedroom-type/{id}', [BedroomTypeController::class, 'deleteBedroomType']);
    Route::post('/update-bedroom-type/{id}', [BedroomTypeController::class, 'updateBedroomType']);

    //posting_type
    Route::post('/add-posting-type', [PostingTypeController::class, 'addPostingType']);
    Route::post('/delete-posting-type/{id}', [PostingTypeController::class, 'deletePostingType']);
    Route::post('/update-posting-type/{id}', [PostingTypeController::class, 'updatePostingType']);
    Route::get('/get-data-posting-type', [PostingTypeController::class, 'getDataPostingType']);
    //type_job_category
    Route::post('/add-type-job-category', [TypeJobCategoryController::class, 'addTypeJobCategory']);
    Route::post('/delete-type-job-category/{id}', [TypeJobCategoryController::class, 'deleteTypeJobCategory']);
    Route::post('/update-type-job-category/{id}', [TypeJobCategoryController::class, 'updateTypeJobCategory']);

    //type_of_house
    Route::post('/add-type-of-house', [TypeOfHouseController::class, 'addTypeOfHouse']);
    Route::post('/delete-type-of-house/{id}', [TypeOfHouseController::class, 'deleteTypeOfHouse']);
    Route::post('/update-type-of-house/{id}', [TypeOfHouseController::class, 'updateTypeOfHouse']);

    //main_door_house
    Route::post('/add-main-door-house', [MainDoorHouseController::class, 'addMainDoorHouse']);
    Route::post('/delete-main-door-house/{id}', [MainDoorHouseController::class, 'deleteMainDoorHouse']);
    Route::post('/update-main-door-house/{id}', [MainDoorHouseController::class, 'updateMainDoorHouse']);

    //broker
    Route::post('/add-broker', [BrokerController::class, 'addBroker']);
    Route::post('/delete-broker/{id}', [BrokerController::class, 'deleteBroker']);
    Route::post('/update-broker/{id}', [BrokerController::class, 'updateBroker']);

    //product_rent_house_like

    Route::post('/add-product-rent-house-like', [ProductRentHouseLikeController::class, 'addProductRentHouseLike']);
    Route::post('/delete-product-rent-house-like/{id}', [ProductRentHouseLikeController::class, 'deleteProductRentHouseLike']);
    Route::post('/update-product-rent-house-like/{id}', [ProductRentHouseLikeController::class, 'updateProductRentHouseLike']);

    //product_rent_house_comment
    Route::post('/add-product-rent-house-comment', [ProductRentHouseCommentController::class, 'addProductRentHouseComment']);
    Route::post('/delete-product-rent-house-comment/{id}', [ProductRentHouseCommentController::class, 'deleteProductRentHouseComment']);
    Route::post('/update-product-rent-house-comment/{id}', [ProductRentHouseCommentController::class, 'updateProductRentHouseComment']);

    //product_jobs
    Route::post('/add-product-jobs', [ProductJobController::class, 'addProductJobs']);
    Route::post('/update-product-jobs/{id}', [ProductJobController::class, 'updateProductJobs']);
    Route::post('/delete-product-jobs/{id}', [ProductJobController::class, 'deleteProductJobs']);

    //product_job_question
    Route::post('/add-product-job-question', [ProductJobQuestionController::class, 'addProducJobQuestion']);
    Route::post('/update-product-job-question/{id}', [ProductJobQuestionController::class, 'updateProducJobQuestion']);
    Route::post('/delete-product-job-question/{id}', [ProductJobQuestionController::class, 'deleteProducJobQuestion']);

    //product_job_view_cv

    Route::post('/add-product-job-user-view-cv', [ProductJobUserViewCvController::class, 'addProducJobUserViewCv']);
    Route::post('/update-product-job-user-view-cv/{id}', [ProductJobUserViewCvController::class, 'updateProducJobUserViewCv']);
    Route::post('/delete-product-job-user-view-cv/{id}', [ProductJobUserViewCvController::class, 'deleteProducJobUserViewCv']);

  });
});