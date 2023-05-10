<?php

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\RegisterController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//route for register
Route::post('/register', [RegisterController::class, 'register']);
//route for category and mobil
Route::prefix('v1')->group(function () {
    Route::resource('/category', App\Http\Controllers\Api\CategoriesController::class);
    Route::resource('/mobil', App\Http\Controllers\Api\MobilController::class);
});
