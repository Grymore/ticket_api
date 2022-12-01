<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\SentEmailController;



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




Route::post('/payment', [ConcertController::class, "payment"]);
Route::get('/okedeh/{id}', [ConcertController::class, "okedeh"]);

Route::get('/redirect/{request}', [CustomerController::class, "callback"]);
Route::get('/print_ticket/{request}', [CustomerController::class, "callback"]);
Route::get('/download/{request}', [CustomerController::class, "print"]);

Route::get('/scanner', [ScannerController::class, "scanner"]);
Route::post('/validasi', [ScannerController::class, "validasi"]);

Route::get('/pesanemail', [SentEmailController::class, "kirimemail"]);

