<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\RentalController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['prefix' => 'book'], function () {
    Route::get('/', [BookController::class, 'index']);
    Route::post('/', [BookController::class, 'create']);
    Route::get('{id}', [BookController::class, 'detail']);
    Route::post('{id}/update', [BookController::class, 'update']);
    Route::delete('{id}/delete', [BookController::class, 'delete']);
});

Route::group(['prefix' => 'book_category'], function () {
    Route::get('/', [BookCategoryController::class, 'index']);
    Route::post('/', [BookCategoryController::class, 'create']);
    Route::get('{id}', [BookCategoryController::class, 'detail']);
    Route::post('{id}/update', [BookCategoryController::class, 'update']);
    Route::delete('{id}/delete', [BookCategoryController::class, 'delete']);
});

Route::group(['prefix' => 'rental', 'middleware' => ['auth']], function () {
    Route::get('/', [RentalController::class, 'list']);
    Route::post('create', [RentalController::class, 'create']);
    Route::post('return', [RentalController::class, 'return']);
});
