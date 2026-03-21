<?php

use App\Http\Controllers\Api\ApiLoteriesController;
use App\Http\Controllers\AppiAppointmentController;
use App\Http\Controllers\Api\ApiAuthController;
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

Route::post('login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [ApiAuthController::class, 'userInformation']);


    Route::get('/loteries/get-loteries', [ApiLoteriesController::class, 'getLoteries']);
    Route::get('/loteries/get-center-loteries', [ApiLoteriesController::class, 'getCenterLoteries']);
    Route::get('/loteries/get-results', [ApiLoteriesController::class, 'getResults']);

    Route::post('logout', [ApiAuthController::class, 'logout']);

    // Route::get(
    //     'appointments/by-range-date',
    //     [AppiAppointmentController::class, 'getAppointmentByRangeOfDate']
    // );
});
