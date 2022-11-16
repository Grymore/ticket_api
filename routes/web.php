<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConcertController;

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
    return view('index');
});

Route::get('/test', function() {
    return view('test');
});




Route::post('/payment', 'App\Http\Controllers\ConcertController@payment');
// Route::get('/testing/{$id}', 'App\Http\Controllers\ConcertController@testing');
Route::get('/okedeh/{id}', [ConcertController::class, "testing"]);




