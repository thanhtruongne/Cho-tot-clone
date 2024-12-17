<?php
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductJobController;
use App\Http\Controllers\Api\Products\ProductElectronicController;
use App\Http\Controllers\Api\Products\ProductRentHouseController;
use App\Http\Controllers\Api\Type\PostingTypeController;
use App\Http\Controllers\ProductJobQuestionController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\CategoriesController;

Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{userId}', [PasswordResetController::class, 'showResetForm']);
Route::post('password/reset/{userID}', [PasswordResetController::class, 'resetPassword']);

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

// Route::post('/zalopay/create-order', [ZaloPayController::class, 'createOrder']);
Route::get('/', [PaymentController::class, 'index']);
Route::post('/vnpay/payment', [PaymentController::class, 'createPaymentLink']);
Route::get('/vnpay/handle-return-url', [PaymentController::class, 'handleReturnUrl']);

Route::post('/vnpay/subPayment',[PaymentController::class,'handleLoadVertifyPost'])->name('fe.loadBTtnPost');
Route::get('/vnpay/subPayment/handle-url', [PaymentController::class, 'handleUrlLoadPost'])->name('fe.load.subpayment');
// Route tạo payment
Route::post('paypal/create-payment', [PayPalController::class, 'createPayment'])
    ->name('paypal.create');

// Route xử lý sau khi thanh toán thành công
Route::get('paypal/success', [PayPalController::class, 'executePayment'])
    ->name('paypal.success');

// Route xử lý khi người dùng hủy thanh toán
Route::get('paypal/cancel', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Payment cancelled.'
    ], 400); // Trả về lỗi HTTP 400
})->name('paypal.cancel');

Route::group([
  // 'middleware' => 'api',
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
  Route::post('update/{id}', [ApiAuthController::class, 'updateUser'])->name('fe.updateUser');

  Route::get('/user', [ApiAuthController::class, 'me'])->name('fe.get-user');

  Route::group(['prefix' => '/product'], function () {


    Route::get('/get-data-post',[DashboardController::class,'getData'])->name('fe.get-data-post');

    // Route::post('/update-load-post-personal',[DashboardController::class,'loadDataPostCount'])->name('fe.update-load-post');

    //product_eletronics
    Route::post('/get-data', [ProductElectronicController::class, 'getData'])->name('fe.product-electric.getData');
    Route::post('/save', [ProductElectronicController::class, 'save'])->name('fe.product-electric.save');
    Route::post('/add-product', [ProductElectronicController::class, 'addPr oduct'])->name('fe.product-electric.addProduct');
    Route::post('/delete-product/{id}', [ProductElectronicController::class, 'deleteProduct'])->name('fe.product-electric.deleteProduct');
    Route::post('/update-product/{id}', [ProductElectronicController::class, 'updateProduct'])->name('fe.product-electric.updateProduct');
    //categories
    Route::get('/categories/get-data',[CategoriesController::class,'getData'])->name('fe.getData.Categories');

    //product_rent_house
    Route::post('/test', [ProductRentHouseController::class, 'test']);
    Route::get('/get-product-rent-user-id/{id}', [ProductRentHouseController::class, 'getDataProductRentGetUserId']);
    Route::get('/get-data-product-rent', [ProductRentHouseController::class, 'getDataProductRent']);
    Route::post('/add-product-rent', [ProductRentHouseController::class, 'addProductRent']);
    Route::post('/delete-product-rent/{id}', [ProductRentHouseController::class, 'deleteProductRent']);
    Route::post('/update-product-rent/{id}', [ProductRentHouseController::class, 'updateProductRent']);
    Route::get('get-product-rent-detail/{id}',[ProductRentHouseController::class,'getDetailProductRentById'])->name('fe.detail-post');

    Route::post('/change-status-post', [ProductRentHouseController::class, 'changeStatusPostData']);
    Route::post('/change-load-btn-post', [ProductRentHouseController::class, 'loadDataBtnPost']);

   


    Route::get('/get-data-location',[DashboardController::class, 'getLocation']);


    //posting_type
    Route::post('/add-posting-type', [PostingTypeController::class, 'addPostingType']);
    Route::post('/delete-posting-type/{id}', [PostingTypeController::class, 'deletePostingType']);
    Route::post('/update-posting-type/{id}', [PostingTypeController::class, 'updatePostingType']);
    Route::get('/get-data-posting-type', [PostingTypeController::class, 'getDataPostingType']);

    //product_jobs
    Route::post('/add-product-jobs', [ProductJobController::class, 'addProductJobs']);
    Route::post('/update-product-jobs/{id}', [ProductJobController::class, 'updateProductJobs']);
    Route::post('/delete-product-jobs/{id}', [ProductJobController::class, 'deleteProductJobs']);

    //product_job_question
    Route::post('/add-product-job-question', [ProductJobQuestionController::class, 'addProducJobQuestion']);
    Route::post('/update-product-job-question/{id}', [ProductJobQuestionController::class, 'updateProducJobQuestion']);
    Route::post('/delete-product-job-question/{id}', [ProductJobQuestionController::class, 'deleteProducJobQuestion']);
  });
});
