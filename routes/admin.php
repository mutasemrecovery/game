<?php

use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\ProductSelectionController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;
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

define('PAGINATION_COUNT',11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {





 Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function(){
 Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
 Route::get('logout',[LoginController::class,'logout'])->name('admin.logout');


/*         start  update login admin                 */
Route::get('/admin/edit/{id}',[LoginController::class,'editlogin'])->name('admin.login.edit');
Route::post('/admin/update/{id}',[LoginController::class,'updatelogin'])->name('admin.login.update');
/*         end  update login admin                */

/// Role and permission
Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController',[ 'as' => 'admin']);
Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
Route::post('admin/role/delete', 'App\Http\Controllers\Admin\RoleController@delete')->name('admin.role.delete');

Route::get('/permissions/{guard_name}', function($guard_name){
    return response()->json(Permission::where('guard_name',$guard_name)->get());
});


// Route for ajax
Route::get('orders/available-products', [OrderController::class, 'getAvailableProducts'])
->name('orders.available-products');
// web.php
Route::get('/products/selection', [ProductSelectionController::class, 'index'])->name('products.selection');
Route::post('/products/filtered', [ProductSelectionController::class, 'getFilteredProducts'])->name('products.filtered');
// end

// Resource Route
Route::resource('users', UserController::class);
Route::resource('products', ProductController::class);
Route::resource('deliveries', DeliveryController::class);
Route::resource('offers', OfferController::class);
Route::resource('orders', OrderController::class);



});
});



Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'guest:admin'],function(){
    Route::get('login',[LoginController::class,'show_login_view'])->name('admin.showlogin');
    Route::post('login',[LoginController::class,'login'])->name('admin.login');

});







