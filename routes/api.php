<?php

use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\ApiDtrController;
use App\Http\Middleware\VerifyAppToken;
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
Route::group(['middleware' => [VerifyAppToken::class]], function(){
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/fetchDtr', [ApiDtrController::class, 'fetchDtr']);
});

// Route::group(['middleware' => ['auth:api']], function(){
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
// });

