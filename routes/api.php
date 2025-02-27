<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;

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

//Rutas para la autenticación
Route::middleware('api')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);
});

//Rutas para las tiendas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tiendas', [TiendaController::class, 'store']);
    Route::get('/tiendas/{id}', [TiendaController::class, 'show']);
    Route::put('/tiendas/{id}', [TiendaController::class, 'update']);
    Route::delete('/tiendas/{id}', [TiendaController::class, 'destroy']);
});

//Rutas para los productos
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tiendas/{tiendaId}/productos', [ProductoController::class, 'store']);
    Route::get('/productos/{id}', [ProductoController::class, 'show']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
});


//Rutas para los carritos
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/carritos/agregar', [CarritoController::class, 'agregarProducto']);
    Route::get('/carritos/ver/{id}', [CarritoController::class, 'show']);
    Route::delete('/carritos/eliminar/{id}', [CarritoController::class, 'eliminarProducto']);
});

// Ruta para la finalizacion de la compra
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/compras/finalizar', [CompraController::class, 'finalizarCompra']);
    Route::get('/compras/historial', [CompraController::class, 'historialCompras']);
    Route::get('/ventas/historial', [CompraController::class, 'historialVentas']);
});
