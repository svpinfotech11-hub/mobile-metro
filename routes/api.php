<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PolicyController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\EnquiryController;
use App\Http\Controllers\Api\MainApiController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('customers',CustomerController::class)->only(['store']);

// OTP sub-resource
Route::prefix('customers/{mobile}')->group(function(){
    Route::post('/otp',[CustomerController::class,'sendotp']); //send otp
    Route::put('/otpverify',[CustomerController::class,'verifyotp']); //verify otp
});
 Route::post('customers/login',[CustomerController::class,'login']); //login
 Route::post('customers_details',[CustomerController::class,'get_customer']); //login

Route::get('category', [MainApiController::class, 'get_category']);
Route::get('subCategory/{id}', [MainApiController::class, 'get_subcategory']);

Route::get('Services/{id}',[MainApiController::class,'get_service']);

Route::get('Product/{id}',[MainApiController::class,'get_product']);

Route::get('banner',[MainApiController::class,'get_banner']);

Route::prefix('enquiry')->name('enquiry.')->group(function () {
    Route::post('/', [EnquiryController::class, 'store'])->name('store');
    Route::get('/customer-list/{customer_id}', [EnquiryController::class, 'customerEnquiryList'])->name('customer_list');
    Route::post('/storeServiceEnquiry', [EnquiryController::class,'storeServiceEnquiry'])->name('storeServiceEnquiry');
});

Route::post('/vendors/register', [VendorController::class, 'store']);

// Route::get('/test-google-distance', function (Request $request) {
//     $pickup = 'Mumbai';
//     $drop = 'Pune';
//     $apiKey = env('GOOGLE_MAPS_API_KEY');

//     $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$pickup}&destinations={$drop}&key={$apiKey}";
//     $response = file_get_contents($url);
//     return response()->json(json_decode($response, true));
// });

Route::get('/customer/{customer_id}', [CustomerController::class, 'getCustomerById']);

Route::post('/payment/initiate', [PaymentController::class, 'initiate']);
Route::post('/payment/verify', [PaymentController::class, 'verify']);
// Route::post('/payment/webhook', [PaymentController::class, 'webhook']);
Route::get('/payment/status/{order_no}', [PaymentController::class, 'status']);

Route::get('/policy/{type}', [PolicyController::class, 'show']);
Route::post('/policy/update/{type}', [PolicyController::class, 'update']);
Route::get('/policies', [PolicyController::class, 'all']);
Route::get('/my-requests/{customer_id}', [EnquiryController::class, 'myRequests']);

Route::post('/products', [ProductController::class, 'store']);

Route::get('/service/{service_id}/subcategories', [EnquiryController::class, 'getSubcategoriesByService']);

Route::get('/products', [EnquiryController::class, 'getProducts']);

Route::get('/enquiry/{id}', [EnquiryController::class, 'showdata']);
Route::put('/enquiry-update/{id}', [EnquiryController::class, 'updateData']);
