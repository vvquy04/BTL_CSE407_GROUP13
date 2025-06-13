<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Watch creation routes
Route::prefix('watches')->group(function () {
    Route::post('/men', [ProductController::class, 'createMenWatch']);
    Route::post('/women', [ProductController::class, 'createWomenWatch']);
    Route::post('/smart', [ProductController::class, 'createSmartWatch']);
    Route::post('/sport', [ProductController::class, 'createSportWatch']);
});
