<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:subuser')->get('/subuser', function (Request $request) {    
    return $request->user();
});

Route::middleware('auth:admin')->get('/admin', function (Request $request) {
    return $request->user();
});

Route::post('/check/username', 'Users\CheckController@checkUsername');
Route::post('/register/create', 'Ghost\RegisterController@register');
Route::post('/register/forgetpassword', 'Ghost\RegisterController@sendForgetPassword');
Route::post('/register/forget/check', 'Ghost\RegisterController@forgetCheckHash');
Route::post('/register/forget/recover', 'Ghost\RegisterController@recoverPassword');



/*
 *  Pharmacy Admin Middleware
 *
 * ****************************/
Route::group(['middleware' => 'auth:api'], function() {
Route::prefix('u')->group(function () {


// Payment After Signup
Route::post('/register/payment', 'Ghost\RegisterController@payment');


// Get Medicinals 
Route::get('/medicinals', 'Users\MedicinalsController@get');
Route::get('/medicinals/{id}', 'Users\MedicinalsController@getMedicinal');
Route::post('/medicinals/create', 'Users\MedicinalsController@create');
Route::post('/medicinals/update', 'Users\MedicinalsController@update');
Route::post('/medicinals/export', 'Users\MedicinalsController@export');
Route::post('/medicinals/search', 'Users\MedicinalsController@search');
Route::post('/medicinals/sort', 'Users\MedicinalsController@sortby');


// POS
Route::get('/pos', 'Users\PosController@get');
Route::post('/pos/sort', 'Users\PosController@sortby');
Route::post('/pos/create', 'Users\PosController@create');


// Sales

Route::get('/sales', 'Users\SalesController@get');
Route::post('/sales/search', 'Users\SalesController@search');
Route::get('/sales/{id}', 'Users\SalesController@getBilling');
Route::post('/sales/update', 'Users\SalesController@update');
Route::post('/sales/export', 'Users\SalesController@export');

// Get Categoires
Route::get('/categories', 'Users\CategoriesController@get');
Route::post('/categories/create', 'Users\CategoriesController@create');
Route::post('/categories/update', 'Users\CategoriesController@update');
Route::delete('/categories/delete/{id}', 'Users\CategoriesController@delete');


// Get Suppliers
Route::get('/suppliers', 'Users\SupplierController@get');
Route::post('/supplier/create', 'Users\SupplierController@create');
Route::post('/supplier/update', 'Users\SupplierController@update');
Route::delete('/supplier/delete/{id}', 'Users\SupplierController@delete');


// Get Suppliers
Route::get('/users', 'Users\UsersController@get');
Route::post('/users/create', 'Users\UsersController@create');
Route::post('/users/update', 'Users\UsersController@update');
Route::delete('/users/delete/{id}', 'Users\UsersController@delete');

// Customers
Route::get('/customers', 'Users\CustomersController@get');
Route::get('/customer/{id}', 'Users\CustomersController@getCustomerOrders');

Route::post('/customer/create', 'Users\CustomersController@create');
Route::post('/customer/update', 'Users\CustomersController@update');
Route::post('/customer/search', 'Users\CustomersController@search');
Route::delete('/customer/delete/{id}', 'Users\CustomersController@delete');

Route::post('/customer/payment/create', 'Users\CustomersController@newPayment');
Route::post('/customer/billing', 'Users\CustomersController@getCustomerBilling');
Route::post('/customer/sell/order', 'Users\CustomersController@sellAlreadyOrder');

// Setting

Route::get('/settings/user/details', 'Users\SettingsController@getUserDetails');
Route::post('/settings/user/details/image/change', 'Users\SettingsController@changeImage');
Route::post('/settings/user/details/update', 'Users\SettingsController@updateDetails');
Route::post('/settings/user/details/password', 'Users\SettingsController@updatePassword');
Route::get('/settings/user/details/payment', 'Users\SettingsController@getPaymentInfo');
Route::get('/settings/user/details/payment/cancel', 'Users\SettingsController@cancelMembership');
Route::get('/settings/user/details/payment/resume', 'Users\SettingsController@resumeMembership');
Route::post('/settings/user/details/payment/update', 'Users\SettingsController@updatePaymnetCard');
Route::get('/settings/user/details/payment/billing', 'Users\SettingsController@getBillingDetails');

Route::get('/settings/user/details/invoice', 'Users\SettingsController@getSettingInvocie');
Route::post('/settings/user/details/invoice/update', 'Users\SettingsController@updateSettingInvocie');

});
});






/*
 *  Subuser Middleware
 *
 * ****************************/
Route::middleware(['auth:subuser'])->group(function () {
Route::prefix('s')->group(function () {


    // Get Medicinals
    Route::get('/medicinals', 'Subuser\MedicinalsController@get');
    Route::post('/medicinals/create', 'Subuser\MedicinalsController@create');
    Route::get('/medicinals/{id}', 'Subuser\MedicinalsController@getMedicinal');
    Route::post('/medicinals/update', 'Subuser\MedicinalsController@update');
    Route::post('/medicinals/export', 'Subuser\MedicinalsController@export');
    Route::post('/medicinals/search', 'Subuser\MedicinalsController@search');
    Route::post('/medicinals/sort', 'Subuser\MedicinalsController@sortby');

    // Get Categoires
    Route::get('/categories', 'Users\CategoriesController@get');
    Route::post('/categories/create', 'Users\CategoriesController@create');
    Route::delete('/categories/delete/{id}', 'Users\CategoriesController@delete');
    Route::post('/categories/update', 'Users\CategoriesController@update');

});
});
