<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CodigoOrganizadorController;
use App\Http\Controllers\CodigoProductorController;
use App\Http\Controllers\CoberturaController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\OrganizadorController;
use App\Http\Controllers\ProductorController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\TipoRiesgoController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\TipoVigenciaController;
use App\Http\Controllers\EndosoController;
use App\Http\Controllers\TipoEndosoController;
use App\Http\Controllers\DetalleEndosoController;
use App\Http\Controllers\AutomotorMarcaController;
use App\Http\Controllers\AnioController;
use App\Http\Controllers\AutomotorModeloController;
use App\Http\Controllers\AutomotorAnioController;
use App\Http\Controllers\AutomotorVersionController;
use App\Http\Controllers\SiniestroAutomotorController;
use App\Http\Controllers\RiesgoAutomotorController;
use App\Http\Controllers\ImagenRAController;
use App\Http\Controllers\EstadoPolizaController;
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

Route::apiResource('/administracion/companias', CompaniaController::class);
Route::post('/companias/busquedaCuit', [CompaniaController::class, 'search']);

Route::apiResource('/configuracion/organizadores', OrganizadorController::class);
Route::post('/organizadores/busquedaCuit', [OrganizadorController::class, 'searchCuit']);
Route::post('/organizadores/busquedaMatricula', [OrganizadorController::class, 'searchMatricula']);

Route::apiResource('/configuracion/productores', ProductorController::class);
Route::post('/productores/busquedaCuit', [ProductorController::class, 'searchCuit']);
Route::post('/productores/busquedaMatricula', [ProductorController::class, 'searchMatricula']);

Route::apiResource('/administracion/codigo_organizadores', CodigoOrganizadorController::class)->parameters([
    'codigo_organizadores' => 'codigo_organizadores'
]);
Route::get('/codigo_organizador/compania/{id}', [CodigoOrganizadorController::class, 'indexFiltrado']);
Route::post('/codigo_organizador/busquedaCO', [CodigoOrganizadorController::class, 'busquedaCO']);

Route::apiResource('/administracion/codigo_productores', CodigoProductorController::class);
Route::get('/codigo_productor/compania/{id}', [CodigoProductorController::class, 'indexFiltrado']);
Route::post('/codigo_productor/busquedaCP', [CodigoProductorController::class, 'busquedaCP']);

Route::apiResource('/administracion/coberturas', CoberturaController::class);
Route::get('/coberturas/compania/{id}', [CoberturaController::class, 'indexFiltrado']);
Route::get('/coberturas_activas/compania/{id}', [CoberturaController::class, 'indexFiltradoActivo']);
Route::post('/coberturas/busquedaCob', [CoberturaController::class, 'busquedaCob']);

Route::apiResource('/clientes', ClienteController::class);
Route::post('/clientes/busquedaCuit', [ClienteController::class, 'searchCuit']);
Route::post('/clientes/busquedaDNI', [ClienteController::class, 'searchDNI']);

Route::get('/numerosolicitud', [PolizaController::class, 'numeroDeSolicitud']);
Route::apiResource('/polizas', PolizaController::class);

Route::apiResource('/riesgo_automotor', RiesgoAutomotorController::class);
Route::post('/imagenes_r_a', [ImagenRAController::class, 'uploadFile']);
Route::delete('/imagenes_r_a/{id}', [ImagenRAController::class, 'destroy']);
Route::get('/riesgo_automotores/{id}', [RiesgoAutomotorController::class, 'indexFiltrado']);
Route::get('/polizas/busquedaPolizaId/{id}', [RiesgoAutomotorController::class, 'searchPoliza']);

Route::apiResource('/endosos', EndosoController::class);
Route::get('/endosos/indexFiltrado/{poliza_id}', [EndosoController::class, 'indexFiltrado']);
Route::apiResource('/tipoendosos', TipoEndosoController::class);
Route::apiResource('/detalleendosos', DetalleEndosoController::class);

Route::apiResource('/siniestros', SiniestroAutomotorController::class);
Route::get('/siniestros/indexFiltrado/{poliza_id}', [SiniestroAutomotorController::class, 'indexFiltrado']);

Route::apiResource('/administracion/marcas', AutomotorMarcaController::class);
Route::apiResource('/administracion/modelos', AutomotorModeloController::class);
Route::apiResource('/administracion/versiones', AutomotorVersionController::class);
Route::apiResource('/administracion/anios', AnioController::class);
Route::get('/modelos/filtrar/{id}', [AutomotorModeloController::class, 'filtroMarca']);
Route::post('/versiones/filtrar/', [AutomotorVersionController::class, 'filtro']);
Route::post('/anios/filtrar/', [AnioController::class, 'filtro']);
Route::get('/anios/filtrar/{id}', [AutomotorAnioController::class, 'filtroXAnio']);
Route::post('/marcas/busquedaMarca', [AutomotorMarcaController::class, 'searchMarca']);
Route::get('/marcas/marcaxanio/{anio}', [AutomotorMarcaController::class, 'filtroXanio']);
Route::post('/modelos/busquedaModelo', [AutomotorModeloController::class, 'searchModelo']);
Route::post('/versiones/busquedaVersion', [AutomotorVersionController::class, 'searchVersion']);

Route::apiResource('/tiporiesgos', TipoRiesgoController::class);
Route::apiResource('/formapagos', FormaPagoController::class);
Route::apiResource('/tipovigencias', TipoVigenciaController::class);
Route::apiResource('/estado_polizas', EstadoPolizaController::class);

Route::apiResource('/localidades', LocalidadController::class);
