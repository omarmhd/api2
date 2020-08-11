<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login','Api\AuthController@login');
Route::post('register','Api\AuthController@register');
Route::apiResource('user','Api\UserController');

Route::group(['middleware' => ['auth:api']], function () {

    Route::apiResource('company','Api\CompanyController');
    Route::get('user-profile','Api\UserController@profile');


});
Route::apiResource('Expense','Api\ExpenseController');
    Route::apiResource('ExpenseType','Api\ExpenseTypeController');
    Route::post('/Expense_date','Api\ExpenseController@tofrom');
Route::apiResource('/receivables','Api\ReceivableController');

Route::apiResource('/eaqaar_sale','Api\SoldEaqaarController');

Route::apiResource('/eaqaar_sale','Api\SoldEaqaarController');
Route::apiResource('/eaqaar','Api\RegistrationEaqaarController');
Route::apiResource('/type','Api\TypeController');
Route::apiResource('/plan','Api\PlanController');

