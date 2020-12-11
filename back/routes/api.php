<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CodigoOrganizadorcontroller;
use App\Http\Controllers\CodigoProductorcontroller;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\OrganizadorController;
use App\Http\Controllers\ProductorController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\TipoRiesgoController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\TipoVigenciaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::resource('/administracion/companias', CompaniaController::class);
Route::post('/companias/busquedaCuit', [CompaniaController::class, 'search']);

Route::resource('/configuracion/organizadores', OrganizadorController::class);
Route::post('/organizadores/busquedaCuit', [OrganizadorController::class, 'searchCuit']);
Route::post('/organizadores/busquedaMatricula', [OrganizadorController::class, 'searchMatricula']);

Route::resource('/configuracion/productores', ProductorController::class);
Route::post('/productores/busquedaCuit', [ProductorController::class, 'searchCuit']);
Route::post('/productores/busquedaMatricula', [ProductorController::class, 'searchMatricula']);

Route::Resource('/administracion/codigo_organizadores', CodigoOrganizadorController::class);
Route::get('/codigo_organizador/compania/{id}', [CodigoOrganizadorController::class, 'indexFiltrado']);
Route::post('/codigo_organizador/busquedaCO', [CodigoOrganizadorController::class, 'busquedaCO']);

Route::Resource('/administracion/codigo_productores', CodigoProductorController::class);
Route::get('/codigo_productor/compania/{id}', [CodigoProductorController::class, 'indexFiltrado']);
Route::post('/codigo_productor/busquedaCP', [CodigoProductorController::class, 'busquedaCP']);

Route::Resource('/clientes', ClienteController::class);
Route::post('/clientes/busquedaCuit', [ClienteController::class, 'searchCuit']);
Route::post('/clientes/busquedaDNI', [ClienteController::class, 'searchDNI']);

Route::Resource('/polizas', PolizaController::class);
Route::get('/numerosolicitud', [PolizaController::class, 'numeroDeSolicitud']);

Route::Resource('/tiporiesgos', TipoRiesgoController::class);
Route::Resource('/formapagos', FormaPagoController::class);
Route::Resource('/tipovigencias', TipoVigenciaController::class);

Route::Resource('/localidades', LocalidadController::class);
