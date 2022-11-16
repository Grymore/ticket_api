<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

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


Route::get('/customer', [CustomerController::class, "index"] );
Route::post('/customer', [CustomerController::class, "store"] );
Route::put('/customer/{id}', [CustomerController::class, "update"]);
Route::get('/customer/{id}', [CustomerController::class, "show"]);
Route::delete('/customer/{id}', [CustomerController::class, "destroy"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
