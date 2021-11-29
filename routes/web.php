<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Rutas de la clase Proveedor
Route::get('/proveedor/listar', "ProveedorController@listar") -> name('proveedor.listar');
Route::post('/proveedor/crear', "ProveedorController@crear")-> name('proveedor.crear');
Route::put('/proveedor/editar', "ProveedorController@editar")-> name('proveedor.editar');
Route::delete('/proveedor/eliminar', "ProveedorController@eliminar")-> name('proveedor.eliminar');
Route::get('/proveedor/index', "ProveedorController@index")-> name('proveedor.index');
Route::get('/proveedor/buscarporrut', "ProveedorController@buscarPorRut") -> name('proveedor.buscarporrut');
Route::get('/proveedor/productosproveedor', "ProveedorController@productosDeUnProveedor") -> name('proveedor.productos');
Route::get('/proveedor/listaralfabeticamente', "ProveedorController@listarAlfabeticamente") -> name('proveedor.alfabeticamente');


//Rutas de la clase Cliente
Route::get('/Cliente/listar', "ClienteController@listar")->name('cliente.listar');//llamado de controlador al metodo listar
Route::post('/Cliente/crear', "ClienteController@crear")->name('cliente.crear');;//llamado del controlador al metodo crear
Route::put('/Cliente/editar', "ClienteController@editar")->name('cliente.editar');;//llamado del controlador al metodo editar
Route::delete('/Cliente/eliminar', "ClienteController@eliminar")->name('cliente.eliminar');;//llamado del controlador  al metodo eliminar
Route::get('/Cliente/index', "ClienteController@index");//llamado del controlador al metodo index para el error de postman para saber que ya esta registrado el usuario
Route::get('/Cliente/buscarClientePorRut', "ClienteController@buscarClientePorRut") -> name('cliente.buscarclienteporrut');//llamado del controlador al metodo buscarClientesPorRut
Route::get('/Cliente/ventasDeUnCliente', "ClienteController@ventasDeUnCliente") -> name('cliente.ventasdeuncliente');//llamado del controlador al metodo ventasDeUnCliente
Route::get('/Cliente/cantidadventas', "ClienteController@cantidadDeVentasDeUnCliente") -> name('cliente.cantidadventas');
Route::get('/Cliente/ordenarClientesPorOrdenAlfabetico',"ClienteController@ordenarClientePorOrdenAlfabetico")->name('cliente.ordenalfabetico');


//rutas MedioDePago
Route::get('/MedioDePago/listar', "MedioDePagoController@listar")-> name('MedioDePago.listar');//llamado de controlador al metodo listar
Route::post('/MedioDePago/crear', "MedioDePagoController@crear")-> name('MedioDePago.crear');//llamado del controlador al metodo crear
Route::put('/MedioDePago/editar', "MedioDePagoController@editar")-> name('MedioDePago.editar');//llamado del controlador al metodo editar
Route::delete('/MedioDePago/eliminar', "MedioDePagoController@eliminar")-> name('MedioDePago.eliminar');//llamado del controlador  al metodo eliminar
Route::get('/MedioDePago/index', "MedioDePagoController@index")-> name('MedioDePago.index');//llamado del controlador al metodo index para el error de postman para saber que ya esta registrado el usuario

//Rutas de la clase Venta
Route::get('/venta/listar', "VentaController@listar") -> name('venta.listar');
Route::post('/venta/crear', "VentaController@crear") -> name('venta.crear');
Route::put('/venta/editar', "VentaController@editar") -> name('venta.editar');
Route::delete('/venta/eliminar', "VentaController@eliminar") -> name('venta.eliminar');
Route::get('/venta/index', "VentaController@index") -> name('venta.index');
Route::get('/venta/buscarpornumero', "VentaController@buscarPorNroDeDocumento") -> name('venta.buscarnumero');

//Rutas de la clase Producto
Route::get('/producto/listar', "ProductoController@listar") -> name('producto.listar');
Route::post('/producto/crear', "ProductoController@crear") -> name('producto.crear');
Route::put('/producto/editar', "ProductoController@editar") -> name('producto.editar');
Route::delete('/producto/eliminar', "ProductoController@eliminar") -> name('producto.eliminar');
Route::get('/producto/index', "ProductoController@index") -> name('producto.index');
Route::get('/producto/listaralfabeticamente', "ProductoController@listarAlfabeticamente") -> name('producto.alfabetico');
Route::get('/producto/listarporcantidad', "ProductoController@listarDeMenorAMayorCantidad") -> name('producto.cantidad');
Route::get('/producto/reporte', "ProductoController@reporteProductosVendidos") -> name('producto.reporte');


//Rutas de la clase Detalle venta
Route::get('/detalleventa/listar', "DetalleVentaController@listar") ->name('DetalleVenta.listar');
Route::post('/detalleventa/crear', "DetalleVentaController@crear") ->name('DetalleVenta.crear');
Route::put('/detalleventa/editar', "DetalleVentaController@editar")->name('DetalleVenta.editar');
Route::delete('/detalleventa/eliminar', "DetalleVentaController@eliminar")->name('DetalleVenta.eliminar');
Route::get('/detalleventa/index', "DetalleVentaController@index")->name('DetalleVenta.index');
Route::get('/detalleventa/listardemenoramayortotaldeventa', "DetalleVentaController@listarDeMenorAMayorTotalDeVenta") -> name('DetalleVenta.listarOrdenadoTotal');

//Rutas de la clase categoria
Route::get('/categoria/listar', "CategoriaController@listar") -> name('categoria.listar'); //llamado de controlador al metodo listar
Route::post('/categoria/crear', "CategoriaController@crear") -> name('categoria.crear'); //llamado de controlador al metodo crear
Route::get('/categoria/index', "CategoriaController@index") -> name('categoria.index'); //llamado de controlador al metodo index para obtener el token
Route::put('/categoria/editar', "CategoriaController@editar") -> name('categoria.editar'); //llamado de controlador al metodo ceditar
Route::delete('/categoria/eliminar', "CategoriaController@eliminar") -> name('categoria.eliminar'); //llamado de controlador al metodo eliminar
Route::get('/categoria/productoscategoria', "CategoriaController@productosDeUnaCategoria") -> name('categoria.productos');
Route::get('/categoria/listaralfabeticamente', "CategoriaController@listarAlfabeticamente") -> name('categoria.alfabetico');
