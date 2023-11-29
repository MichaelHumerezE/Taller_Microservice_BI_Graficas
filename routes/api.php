<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\Api\NegocioController;
use App\Http\Controllers\Api\ReservaApiController;
use App\Http\Controllers\EntradaApiController;
use App\Http\Controllers\OrdenRepuestoController;
use App\Http\Controllers\OrdenController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//lista de items de uan orden de repuestos de una orden de trabajo
Route::get('itemsOrdenRepuesto/{id}', [OrdenRepuestoController::class, 'getItems'])->name('getItems');
Route::post('changeEstadoOrdenRepuesto', [OrdenRepuestoController::class, 'changeEstadoOrdenRepuesto']);

//Obtener el rol del usuario logueado en el móvil
Route::get('getRole/{id}', [LoginApiController::class, 'getRole'])->name('getRole');

//Devolver una orden de trabajo
Route::get('getOrdenTrabajo/{id}', [OrdenController::class, 'getOrdenTrabajo'])->name('getOrdenTrabajo');

//Devolver ordenes de trabajo by user id
Route::get('getOrdenesTrabajoByIdUser/{id}', [OrdenController::class, 'getOrdenesTrabajoByIdUser'])->name('getOrdenesTrabajoByIdUser');
Route::get('getClienteByIdUser/{id}', [OrdenController::class, 'getClienteByIdUser'])->name('getClienteByIdUser');

//Obtener ordenes de repuestos de una orden de trabajo
Route::get('getOrdenesRepuestos/{id}', [OrdenRepuestoController::class, 'getOrdenesRepuestos'])->name('getOrdenesRepuestos');

//Obtener la lista de items de una orden repuesto
Route::get('getItemsOrdenRepuestos/{id}', [OrdenRepuestoController::class, 'getItemsOrdenRepuestos'])->name('getItemsOrdenRepuestos');

//BUSINESS INTELLIGENT
Route::get('getReportes', [NegocioController::class, 'getReportes'])->name('getReportes');
Route::get('getReportes2', [NegocioController::class, 'getReportes2'])->name('getReportes2');