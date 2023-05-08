<?php

use App\Events\SendMessage;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('broadcast', function () {
    broadcast(new SendMessage());
});

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->name('verification.verify');
