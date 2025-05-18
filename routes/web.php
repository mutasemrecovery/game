<?php

use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group whichf
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/migrate-refresh', function () {
//     // Run the migration command
//     Artisan::call('migrate:fresh --seed');

//     // Get the output of the command
//     $output = Artisan::output();

//     // Return a response with the output
//     return response()->json(['message' => 'Migration and seeding completed successfully', 'output' => $output]);
// });


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {



    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('userOrders', OrderController::class);
    Route::get('orders/available-products', [OrderController::class, 'getAvailableProductsForUser'])
    ->name('user.orders.available-products');

    Route::get('/order/success/{order}', [OrderController::class, 'orderSuccess'])->name('user.order.success');
    Route::get('/order/details/{order}', [OrderController::class, 'orderDetails'])->name('user.order.details');

    
});
