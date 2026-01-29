<?php

use App\Http\Controllers\CotizacionesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Contratos;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\PagosController;


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
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

//contratos
Route::get('/getContratos', [Contratos::class, 'getContratos']);
Route::post('/getContratoById', [Contratos::class, 'getContratoById']);
Route::post('/addContrato', [Contratos::class, 'addContrato']);
Route::post('/editContrato', [Contratos::class, 'editContrato']);

//clientes
Route::get('/getClientes', [ClientesController::class, 'getClientes']);
Route::get('/obtenerClientes', [ClientesController::class, 'obtenerClientes']);
Route::post('/addCliente', [ClientesController::class, 'addCliente']);
Route::post('/updateCliente', [ClientesController::class, 'updateCliente']);
Route::post('/deleteCliente', [ClientesController::class, 'deleteCliente']);


//productos
Route::post('/addProducto', [ProductosController::class, 'addProducto']);
Route::get('/getProductos', [ProductosController::class, 'getProductos']);
Route::get('/getProductosList', [ProductosController::class, 'getProductosList']);
Route::post('/updateProducto', [ProductosController::class, 'updateProducto']);
Route::post('/deleteProducto', [ProductosController::class, 'deleteProducto']);
Route::get('/categoriasProducto', [ProductosController::class, 'categoriasProducto']);

//proveedores
Route::get('/getProvedores', [ProveedoresController::class, 'getProvedores']);
Route::get('/obtenerProveedores', [ProveedoresController::class, 'obtenerProveedores']);
Route::post('/addProveedor', [ProveedoresController::class, 'addProveedor']);
Route::post('/updateProvedores', [ProveedoresController::class, 'updateProvedores']);
Route::post('/deleteProveedor', [ProveedoresController::class, 'deleteProveedor']);

//cotizaciones
Route::get('/getCotizaciones', [CotizacionesController::class, 'getCotizaciones']);
Route::post('/addCotizacion', [CotizacionesController::class, 'addCotizacion']);
Route::post('/updateCotizacion', [CotizacionesController::class, 'updateCotizacion']);
Route::post('/confirmarCotizacion', [CotizacionesController::class, 'confirmarCotizacion']);

//ventas
Route::get('/getVentas', [CotizacionesController::class, 'getVentas']);
Route::post('/productosCotizacion', [CotizacionesController::class, 'productosCotizacion']);
Route::post('/marcarPagada', [CotizacionesController::class, 'marcarPagada']);
Route::post('/cancelarVenta', [CotizacionesController::class, 'cancelarVenta']);

//compras
Route::get('/getCompras', [ComprasController::class, 'getCompras']);
Route::post('/addCompra', [ComprasController::class, 'addCompra']);
Route::post('/updateCompra', [ComprasController::class, 'updateCompra']);
Route::post('/confirmarCompra', [ComprasController::class, 'confirmarCompra']);
Route::post('/productosCompra', [ComprasController::class, 'productosCompra']);

//usuarios
Route::get('/getUsuarios', [UsuariosController::class, 'getUsuarios']);
Route::post('/addUsuario', [UsuariosController::class, 'addUsuario']);
Route::post('/updateUsuario', [UsuariosController::class, 'updateUsuario']);
Route::post('/deleteUsuario', [UsuariosController::class, 'deleteUsuario']);

//pagos
Route::post('/getPagosByCotizacion', [PagosController::class, 'getPagosByCotizacion']);
Route::post('/addPago', [PagosController::class, 'addPago']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
