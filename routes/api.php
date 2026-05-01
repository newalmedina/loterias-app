<?php

use App\Http\Controllers\Api\ApiLoteriesController;
use App\Http\Controllers\AppiAppointmentController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiVentasController;
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

Route::middleware(['auth:sanctum', 'check.active'])->group(function () {
    Route::get('user', [ApiAuthController::class, 'userInformation']);


    Route::get('/loteries/get-loteries', [ApiLoteriesController::class, 'getLoteries']);
    Route::get('/loteries/get-center-loteries-disponibles', [ApiLoteriesController::class, 'getCenterLoteriesDisponibles']);
    Route::get('/loteries/get-results', [ApiLoteriesController::class, 'getResults']);

    Route::get('validate-token', [ApiAuthController::class, 'validateToken']);
    Route::post('/ventas/finalizar', [ApiVentasController::class, 'finalizarVenta']);
    Route::patch('/ventas/pagar/{id}', [ApiVentasController::class, 'pagarVenta']);
    Route::delete('/ventas/delete/{id}', [ApiVentasController::class, 'eliminarVenta']);
    Route::get('/ventas/search-venta', [ApiVentasController::class, 'searchVenta']);
    Route::get('/ventas/find/{id}', [ApiVentasController::class, 'findVenta']);
    Route::get('/ventas/find-uuid/{uuid}', [ApiVentasController::class, 'findByUuid']);

    Route::post('logout', [ApiAuthController::class, 'logout']);
    // Route::get(
    //     'appointments/by-range-date',
    //     [AppiAppointmentController::class, 'getAppointmentByRangeOfDate']
    // );
});
