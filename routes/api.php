<?php

use App\Http\Controllers\Api\Auth\GoogleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ErrorController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SetQuestionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login/google', GoogleController::class);
});

Route::middleware('auth:api')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'me']);
    Route::get('users', [UserController::class, 'getAll']);
    Route::get('users/{id}', [UserController::class, 'showUser']);

    Route::post('rooms/create', [RoomController::class, 'create'])->name("room.create");

    Route::group([
        "middleware" => 'idIsInteger',
        "prefix" => "rooms/{id}"
    ], function () {
        Route::get('', [RoomController::class, 'open']);
        Route::get('questions', [QuestionController::class, 'getQuestions']);
        Route::post('next-question', [QuestionController::class, 'nextQuestion']);
        Route::post('questions/answers', [QuestionController::class, 'pushAnswer']);
        Route::get('questions/view-result', [QuestionController::class, 'viewResult']);
    });

    Route::put('questions', [QuestionController::class, 'update']);

    Route::prefix('set-questions')->group(function(){
        Route::get('/', [SetQuestionController::class, "getAll"]);
        Route::get('/{id}/questions',[SetQuestionController::class,"getQuestions"]);

        Route::put('/{id}', [SetQuestionController::class, "update"]);
        Route::post('/', [SetQuestionController::class, "create"]);
        Route::delete('/{id}', [SetQuestionController::class, "delete"]);
    });
});

Route::get("errors", [ErrorController::class, 'redirectTokenExpired'])->name('errors.jwt.expired');
Route::get("errors", [ErrorController::class, 'InvalidateId'])->name('errors.id.invalidate');
