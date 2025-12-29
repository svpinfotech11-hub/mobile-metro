<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CFTController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\KMRateController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PickupboyController;
use App\Http\Controllers\Api\PolicyController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserEnquiryController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProductSubCategoryController;

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

Route::get('/', function () {
  return view('welcome');
});


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('store-login', [LoginController::class, 'login'])->name('store-login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {

  Route::get('app/admin-dashboard', [AdminDashboardController::class, 'index'])->name('app.admin-dashboard');
  Route::resource('app/admin-category', CategoryController::class)
    ->names([
      'index'   => 'app.admin-category.index',
      'create'  => 'app.admin-category.create',
      'store'   => 'app.admin-category.store',
      'show'    => 'app.admin-category.show',
      'edit'    => 'app.admin-category.edit',
      'update'  => 'app.admin-category.update',
      'destroy' => 'app.admin-category.destroy',
    ]);
  Route::resource('app/admin-subcategory', SubCategoryController::class)
    ->names([
      'index'   => 'app.admin-subCategory.index',
      'create'  => 'app.admin-subCategory.create',
      'store'   => 'app.admin-subCategory.store',
      'show'    => 'app.admin-subCategory.show',
      'edit'    => 'app.admin-subCategory.edit',
      'update'  => 'app.admin-subCategory.update',
      'destroy' => 'app.admin-subCategory.destroy',
    ]);

  Route::resource('app/admin-cftRate', CFTController::class)
    ->names([
      'index'   => 'app.admin-cftRate.index',
      'create'  => 'app.admin-cftRate.create',
      'store'   => 'app.admin-cftRate.store',
      'show'    => 'app.admin-cftRate.show',
      'edit'    => 'app.admin-cftRate.edit',
      'update'  => 'app.admin-cftRate.update',
      'destroy' => 'app.admin-cftRate.destroy',
    ]);

  Route::resource('app/admin-kmRate', KMRateController::class)
    ->names([
      'index'   => 'app.admin-kmRate.index',
      'create'  => 'app.admin-kmRate.create',
      'store'   => 'app.admin-kmRate.store',
      'show'    => 'app.admin-kmRate.show',
      'edit'    => 'app.admin-kmRate.edit',
      'update'  => 'app.admin-kmRate.update',
      'destroy' => 'app.admin-kmRate.destroy',
    ]);
  Route::resource('app/admin-banner', BannerController::class)
    ->names([
      'index'   => 'app.admin-banner.index',
      'create'  => 'app.admin-banner.create',
      'store'   => 'app.admin-banner.store',
      'show'    => 'app.admin-banner.show',
      'edit'    => 'app.admin-banner.edit',
      'update'  => 'app.admin-banner.update',
      'destroy' => 'app.admin-banner.destroy',
    ]);
  Route::resource('app/admin-product', ProductController::class)
    ->names([
      'index'   => 'app.admin-product.index',
      'create'  => 'app.admin-product.create',
      'store'   => 'app.admin-product.store',
      'show'    => 'app.admin-product.show',
      'update'  => 'app.admin-product.update',
      'destroy' => 'app.admin-product.destroy'
    ]);
  Route::resource('app/admin-services', ServiceController::class)
    ->names([
      'index'   => 'app.admin-services.index',
      'create'  => 'app.admin-services.create',
      'store'   => 'app.admin-services.store',
      'show'    => 'app.admin-services.show',
      'update'  => 'app.admin-services.update',
      'destroy' => 'app.admin-services.destroy',
    ]);
  // web.php
  Route::get('/get-subcategories/{id}', [ServiceController::class, 'getSubcategories']);
  Route::get('/customer-list', [CustomerController::class, 'get_customer'])->name('admin.customer-list'); //customer List

  Route::resource('app/admin-enquiry', EnquiryController::class)
    ->names([
      'index'  =>  'app.admin-enquiry.index',
      'show'    => 'app.admin-enquiry.show',
    ]);
  Route::get('get-service-enquiry', [EnquiryController::class, 'get_service_enquiry'])
    ->name('get-service-enquiry');

  Route::delete(
    'get-enquiry-destroy/{id}',
    [EnquiryController::class, 'get_service_enquiry_destroy']
  )->name('get-enquiry-destroy');

  Route::delete(
    'get-service-enquiry-destroy/{id}',
    [ServiceController::class, 'destroy']
  )->name('get-service-enquiry-destroy');

  Route::delete(
    'get-service-other-enquiry-destroy/{id}',
    [EnquiryController::class, 'get_other_service_enquiry_delete']
  )->name('get-service-other-enquiry-destroy');


  Route::delete(
    'admin-packers-movers-destroy/{id}',
    [EnquiryController::class, 'getServiceEnquiryDestroy']
  )->name('admin-packers-movers-destroy');

  // Route::get('km-rate/cft/{id}', [KMRateController::class, 'showKmDetails'])->name('app.admin-kmRate.showKmDetails');
Route::get('km-rate/cft/{cftId}', [KMRateController::class, 'showKmDetails'])->name('app.admin-kmRate.showKmDetails');


  Route::delete('km-rate/{id}', [KMRateController::class, 'destroy'])->name('app.admin-kmRate.destroy');

  Route::get('get-other-enquiry', [EnquiryController::class, 'get_other_enquiry'])
    ->name('get-other-enquiry');

  Route::get('get-vendor', [VendorController::class, 'get_vendors'])
    ->name('admin.vendors.get-vendor');

  Route::delete('/vendor/destroy/{id}', [VendorController::class, 'destroy'])
    ->name('vendor.destroy');

  Route::delete('destroy/{id}', [CustomerController::class, 'destroy'])
    ->name('destroy');

  Route::post('/admin/customer/{id}/block', [CustomerController::class, 'block'])->name('admin.customer.block');
  Route::post('/admin/customer/{id}/unblock', [CustomerController::class, 'unblock'])->name('admin.customer.unblock');

  Route::get('total-cft', [ProductController::class, 'total'])
    ->name('total-cft');

Route::get('/get-subcategory/{service_id}', [ProductController::class, 'getSubcategory']);

Route::get('field-report/{id}', [EnquiryController::class, 'fieldReport'])
    ->name('admin.enquiry.field-report');


  Route::resource('app/admin-pickupboy', PickupboyController::class)
    ->names([
      'index'  =>  'app.admin-pickupboy.index',
      'create' =>  'app.admin-pickupboy.create',
      'store'  =>  'app.admin-pickupboy.store',
      'show'   =>  'app.admin-pickupboy.show',
      'edit'   =>  'app.admin-pickupboy.edit',
      'update' =>  'app.admin-pickupboy.update',
      'destroy' =>  'app.admin-pickupboy.destroy'
    ]);

    Route::get('pages/users/create', [UserProductController::class, 'create'])
    ->name('pages.users.create');

Route::post('pages/users/create', [UserProductController::class, 'store'])
    ->name('pages.users.store');

Route::get('/users/{id}/otp', [UserProductController::class, 'showOtpForm'])
    ->name('pages.users.otp');

Route::post('/users/otp-verify', [UserProductController::class, 'verifyOtp'])
    ->name('users.otp.verify');

Route::get('pages/users/form', [UserEnquiryController::class, 'formss'])
    ->name('pages.users.form');
});


  Route::get('policies/create', [PolicyController::class, 'create'])
    ->name('policies.create');
  Route::post('policies/create', [PolicyController::class, 'store'])
    ->name('policies.store');
  Route::get('policies/index', [PolicyController::class, 'index'])
    ->name('policies.index');
  Route::get('policies/edit/{id}', [PolicyController::class, 'edit'])
    ->name('policies.edit');
  Route::put('policies/update/{id}', [PolicyController::class, 'update'])
    ->name('policies.update');
  Route::delete('policies/destroy/{id}', [PolicyController::class, 'destroy'])
    ->name('policies.destroy');


    Route::get('/get-subcategories/{service_id}', [ProductController::class, 'getSubCategories']);
Route::get('/get-inventory/{sub_category_id}', [ProductController::class, 'getInventory']);
// Route::resource('product_subcategory', ProductSubCategoryController::class);
Route::get('product_subcategory/create', [ProductSubCategoryController::class, 'create'])
    ->name('admin.product_subcategory.create');
  Route::post('product_subcategory/create', [ProductSubCategoryController::class, 'store'])
    ->name('admin.product_subcategory.store');
  Route::get('product_subcategory/index', [ProductSubCategoryController::class, 'index'])
    ->name('admin.product_subcategory.index');
  Route::get('product_subcategory/edit/{id}', [ProductSubCategoryController::class, 'edit'])
    ->name('admin.product_subcategory.edit');
  Route::put('product_subcategory/update/{id}', [ProductSubCategoryController::class, 'update'])
    ->name('admin.product_subcategory.update');
  Route::delete('product_subcategory/destroy/{id}', [ProductSubCategoryController::class, 'destroy'])
    ->name('admin.product_subcategory.destroy');


  Route::get('/get-product-subcategory/{service_id}', function($service_id){
  return DB::table('tbl_product_subcategory')
      ->where('service_id', $service_id)
      ->get();
});
    Route::get('/admin/enquiries', [UserEnquiryController::class, 'index'])->name('admin.enquiries.index');

    Route::get('/admin/services', [UserEnquiryController::class, 'servicesMethod'])->name('admin.enquiries.services');


Route::delete('/enquiries/{id}', [UserEnquiryController::class, 'destroy'])
    ->name('enquiries.destroy');



Route::get('/api/subcategory-detail/{id}', [UserProductController::class, 'getSubCategoryDetail']);

