<?php 
// routes/passport.php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\ScopeController;
use Laravel\Passport\Http\Controllers\TransientTokenController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;

/*
|--------------------------------------------------------------------------
| OAuth2 Authorization Server Routes
|--------------------------------------------------------------------------
|
| Here is where you may register all of the routes for an OAuth2 authorization
| server such as issuing access tokens, clients, and personal access tokens.
| This is the default location for this package's routes which are loaded
| by the RouteServiceProvider within a group which is assigned the "api" middleware
| group. Enjoy building your OAuth2 server!
|
*/

Route::prefix('oauth')->group(function () {
    Route::middleware('api')->post('/authorize', [
        'uses' => AuthorizationController::class . '@authorize',
    ]);

    // ... Other Passport routes

    // The rest of the Passport routes are defined here
});


?>