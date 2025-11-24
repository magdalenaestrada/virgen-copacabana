<?php

use App\Http\Controllers\AccionController;
use App\Http\Controllers\AlmacensController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\GaritaController;
use App\Http\Controllers\LqLiquidacionController;
use App\Http\Controllers\MotivoController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\TipoComprobanteController;
use App\Http\Controllers\TipoVehiculoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ProgramacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RanchoController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\AbonadoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InventarioingresoController;
use App\Http\Controllers\InventariosalidaController;
use App\Http\Controllers\ProductosFamiliaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioingresoaperturaController;
use App\Http\Controllers\InventarioprestamosalidaController;
use App\Http\Controllers\InventarioprestamoingresoController;
use App\Http\Controllers\InvingresosrapidosController;
use App\Http\Controllers\InvsalidasrapidasController;
use App\Http\Controllers\InvherramientasController;
use App\Http\Controllers\PesoController;
use App\Http\Controllers\PosicionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\LqAdelantoController;
use App\Http\Controllers\LqClienteController;
use App\Http\Controllers\LqDevolucionController;
use App\Http\Controllers\LqSociedadController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PesoOtraBalController;
use App\Http\Controllers\PlProgramacionController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ReactivoController;
use App\Http\Controllers\ReactivoDetalleController;
use App\Http\Controllers\TsBancoController;
use App\Http\Controllers\TscajaController;
use App\Http\Controllers\TsCuentaController;
use App\Http\Controllers\TsIngresocuentaController;
use App\Http\Controllers\TsIngresosCajasController;
use App\Http\Controllers\TsMicajaController;
use App\Http\Controllers\TsMotivoController;
use App\Http\Controllers\TsReporteDiarioCuentasController;
use App\Http\Controllers\TsSalidacuentaController;
use App\Http\Controllers\TsReposicioncajaController;
use App\Http\Controllers\TsSalidacajaController;
use App\Models\TsReposicioncaja;
use App\Models\TsSalidaCuenta;

//Route::group(['middleware' => ['role:super-admin|admin']], function(){
Route::group(['middleware' => ['auth']], function () {

    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    Route::resource('roles', RoleController::class);
    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);

    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);
    Route::get('/productos/filter', [ProductoController::class, 'filter'])->name('productos.filter');

    Route::resource('users', UserController::class);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/buscar-documento', [RegistroController::class, 'buscarDocumento'])->name('buscar.documento');
    Route::resource('acciones', AccionController::class);
    Route::resource('estados', EstadoController::class);
    Route::resource('garitas', GaritaController::class);
    Route::resource('motivos', MotivoController::class);
    Route::resource('registros', RegistroController::class);
    Route::resource('vehiculos', TipoVehiculoController::class);
    Route::resource('almacenes', AlmacensController::class);
    Route::resource('productos', ProductoController::class)->parameters(['page' => 'page']);
    Route::resource('programacion', ProgramacionController::class);
    Route::resource('abonados', AbonadoController::class);
    Route::resource('ranchos', RanchoController::class);
    Route::resource('inventarioingresos', InventarioingresoController::class);
    Route::resource('inventariosalidas', InventariosalidaController::class);
    Route::resource('productosfamilias', ProductosFamiliaController::class);
    Route::resource('inventarioingresoapertura', InventarioingresoaperturaController::class);
    Route::resource('inventarioprestamosalida', InventarioprestamosalidaController::class);
    Route::resource('inventarioprestamoingreso', InventarioprestamoingresoController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('invingresosrapidos', InvingresosrapidosController::class);
    Route::resource('invsalidasrapidas', InvsalidasrapidasController::class);
    Route::resource('invherramientas', InvherramientasController::class);
    Route::resource('pesos', PesoController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('posiciones', PosicionController::class);
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('tsbancos', TsBancoController::class);
    Route::resource('tsmotivos', TsMotivoController::class);
    Route::resource('tiposcomprobantes', TipoComprobanteController::class);
    Route::resource('tscuentas', TsCuentaController::class);
    Route::resource('tsingresoscuentas', TsIngresocuentaController::class);
    Route::resource('tssalidascuentas', TsSalidacuentaController::class);
    Route::resource('tscajas', TscajaController::class);
    Route::resource('tsreposicionescajas', TsReposicionCajaController::class);
    Route::resource('tssalidascajas', TsSalidacajaController::class);
    Route::resource('tsingresoscajas', TsIngresosCajasController::class);
    Route::resource('tsmiscajas', TsMicajaController::class);
    Route::resource('lqclientes', LqClienteController::class);
    Route::resource('lqsociedades', LqSociedadController::class);
    Route::resource('lqadelantos', controller: LqAdelantoController::class);
    Route::resource('tscuentasreportesdiarios', controller: TsReporteDiarioCuentasController::class);
    Route::resource('lqliquidaciones', controller: LqLiquidacionController::class);
    Route::resource('lqdevoluciones', controller: LqDevolucionController::class);

    Route::get('tscuentasreportesdiarios/{id}/filter', [TsReporteDiarioCuentasController::class, 'filter'])->name('tscuentasreportesdiarios.filter');

    //GET SALIDAS MIS CAJAS ROUTE

    Route::get('/tssalidasmiscajas', [TsMicajaController::class, 'indexsalidas'])->name('tsmiscajas.salidas');
    Route::get('/tsingresosmiscajas', [TsMicajaController::class, 'indexingresos'])->name('tsmiscajas.ingresos');
    Route::get('/tsreposicionesmiscajas', [TsMicajaController::class, 'indexreposiciones'])->name('tsmiscajas.reposiciones');

    Route::get('/data-query', [DataController::class, 'query'])->name('data.query');
    Route::get('/programacion/{id}/createrequerimiento', [ProgramacionController::class, 'createRequerimiento'])->name('programacion.createrequerimiento');
    Route::post('/programacion/{id}/storerequerimiento', [ProgramacionController::class, 'storeRequerimiento'])->name('programacion.storerequerimiento');
    Route::delete('/programacion/{programacion}/productos/{producto}/pivot/{pivotId}', [ProgramacionController::class, 'destroyrequerimiento'])->name('programacion.destroyrequerimiento');

    Route::get('/inventarioingresos/{id}/cancelar', [InventarioingresoController::class, 'cancelar'])->name('inventarioingresos.cancelar');
    Route::put('/inventarioingresos/{id}/updatecancelar', [InventarioingresoController::class, 'updatecancelar'])->name('inventarioingresos.updatecancelar');

    Route::get('/inventarioingresos/{id}/recepcionar', [InventarioingresoController::class, 'recepcionar'])->name('inventarioingresos.recepcionar');
    Route::put('/inventarioingresos/{id}/updaterecepcionar', [InventarioingresoController::class, 'updaterecepcionar'])->name('inventarioingresos.updaterecepcionar');

    Route::get('/inventarioingresos/{id}/cancelaralcredito', [InventarioingresoController::class, 'cancelaralcredito'])->name('inventarioingresos.cancelaralcredito');
    Route::put('/inventarioingresos/{id}/updatecancelaralcredito', [InventarioingresoController::class, 'updatecancelaralcredito'])->name('inventarioingresos.updatecancelaralcredito');

    Route::get('/inventarioingresos/{id}/cancelaracuenta', [InventarioingresoController::class, 'cancelaracuenta'])->name('inventarioingresos.cancelaracuenta');
    Route::put('/inventarioingresos/{id}/updatecancelaracuenta', [InventarioingresoController::class, 'updatecancelaracuenta'])->name('inventarioingresos.updatecancelaracuenta');

    Route::get('/inventariosalidas/{id}/entregar', [InventariosalidaController::class, 'entregar'])->name('inventariosalidas.entregar');
    Route::put('/inventariosalidas/{id}/updateentregar', [InventariosalidaController::class, 'updateentregar'])->name('inventariosalidas.updateentregar');

    //DEPOSITAR ROUTE
    Route::post('depositar', [TsSalidacuentaController::class, 'depositar'])->name('tssalidascuentas.depositar');

    //PRINT ROUTES
    Route::get('/ranchos/{id}/prnpriview', [RanchoController::class, 'prnpriview'])->name('ticket.prnpriview');
    Route::get('/abonados/{id}/prnpriview', [AbonadoController::class, 'prnpriview'])->name('cancelacion.prnpriview');
    Route::get('/inventarioingresos/{id}/prnpriview', [InventarioingresoController::class, 'prnpriview'])->name('inventarioingreso.prnpriview');
    Route::get('/inventarioprestamosalida/{id}/printdoc', [InventarioprestamosalidaController::class, 'printdoc'])->name('inventarioprestamosalida.printdoc');
    Route::get('/inventariosalidas/{id}/printdoc', [InventariosalidaController::class, 'printdoc'])->name('inventariosalidas.printdoc');
    Route::get('/invsalidasrapidas/{id}/printdoc', [InvsalidasrapidasController::class, 'prnpriview'])->name('invsalidasrapidas.prnpriview');
    Route::get('/inventarioprestamoingreso/{id}/printdoc', [InventarioprestamoingresoController::class, 'printdoc'])->name('inventarioprestamoingreso.printdoc');
    Route::get('/lqadelantos/{id}/printdoc', [LqAdelantoController::class, 'printdoc'])->name('lqadelantos.printdoc');
    Route::get('/tsreposicionescajas/{id}/printdoc', [TsReposicioncajaController::class, 'printdoc'])->name('tsreposicionescajas.printdoc');
    Route::get('/tsingresoscuentas/{id}/printdoc', [TsIngresocuentaController::class, 'printdoc'])->name('tsingresoscuentas.printdoc');
    Route::get('/tssalidascuentas/{id}/printdoc', [TsSalidacuentaController::class, 'printdoc'])->name('tssalidascuentas.printdoc');
    Route::get('/lqliquidaciones/{id}/printdoc', [LqLiquidacionController::class, 'printdoc'])->name('lqliquidaciones.printdoc');
    Route::get('/lqdevoluciones/{id}/printdoc', [LqDevolucionController::class, 'printdoc'])->name('lqdevoluciones.printdoc');
    Route::get('/tssalidasmiscajas/{id}/printdoc', [TsMicajaController::class, 'printdocsalida'])->name('tsmiscajas.printdocsalida');

    //EXPORT EXCEL ROUTES
    Route::get('export-excel-productos', [ProductoController::class, 'export_excel'])->name('productos.export-excel');
    Route::get('export-excel-detallesinventarioingresos', [InventarioingresoController::class, 'export_excel'])->name('inventarioingreso.export-excel');
    Route::get('export-excel-detinvsalidasrapidas', [InvsalidasrapidasController::class, 'export_excel'])->name('invsalidarapida.export-excel');
    Route::get('export-excel-pesos', [PesoController::class, 'export_excel'])->name('pesos.export-excel');
    Route::get('export-excel-tssalidascuentas', [TsSalidacuentaController::class, 'export_excel'])->name('tssalidascuentas.export-excel');
    Route::get('export-excel-tsingresoscuentas', [TsIngresocuentaController::class, 'export_excel'])->name('tsingresoscuentas.export-excel');
    Route::get('export-excel-lqadelantos', [LqAdelantoController::class, 'export_excel'])->name('lqadelantos.export-excel');
    Route::get('export-excel-tsreposicionescajas', [TsReposicioncajaController::class, 'export_excel'])->name('tsreposicionescajas.export-excel');
    Route::get('export-excel-tssalidascajas', [TsSalidacajaController::class, 'export_excel'])->name('tssalidascajas.export-excel');
    Route::get('export-excel-miscajasreporte', [TsMicajaController::class, 'export_excel'])->name('tsmiscajas.export-excel');
    Route::get('export-excel-otrascajasreporte/{id}', [TsReposicioncajaController::class, 'export_excel_otra'])->name('tsotrascajas.export-excel');
    Route::get('export-excel-reportescuentas', [TsReporteDiarioCuentasController::class, 'export_excel'])->name('tsreportescuentas.export-excel');
    Route::get('export-contable-excel-reportescuentas', [TsReporteDiarioCuentasController::class, 'export_excel_contable'])->name('tsreporteContablecuentas.export-excel');


    //ANULAR ROUTES
    Route::get('/inventarioingresos/{id}/anular', [InventarioingresoController::class, 'anular'])->name('inventarioingresos.anular');
    Route::get('/invsalidasrapidas/{id}/anular', [InvsalidasrapidasController::class, 'anular'])->name('invsalidasrapidas.anular');
    Route::get('/invingresosrapidos/{id}/anular', [InvingresosrapidosController::class, 'anular'])->name('invingresosrapidos.anular');
    Route::get('/inventarioprestamoingreso/{id}/anular', [InventarioprestamoingresoController::class, 'anular'])->name('inventarioprestamoingreso.anular');

    //FOR AJAX

    //FOR SUNAT API
    Route::get('/findPersona', [RanchoController::class, 'get_persona'])->name('findPersona');
    Route::get('/get-selling-price', [InventarioingresoController::class, 'getSellingPrice'])->name('get_selling_price');

    //FOR DATABASE
    Route::get('/get-product-by-barcode/{barcode}', [InventarioingresoController::class, 'getProductByBarcode'])->name('get.product.by.barcode');
    Route::get('/get-product-image-by-product/{product}', [InventarioingresoController::class, 'getProductImageByProduct'])->name('get.product-image.by.product');

    Route::get('/get-sociedad-nombre-by-code/{sociedad}', [LqAdelantoController::class, 'getSociedadByCode'])->name('get.sociedad.nombre.by.code');
    Route::get('/get-cliente-documento-by-nombre/{cliente}', [LqClienteController::class, 'getLqClienteByNombre'])->name('get.lqcliente.documento.by.nombre');

    //SEARCH ROUTES
    Route::get('/search-product', [ProductoController::class, 'searchProduct'])->name('search.product');
    Route::get('/search-salidarapida', [InvsalidasrapidasController::class, 'searchSalidaRapida'])->name('search.salidarapida');
    Route::get('/search-ingreso', [InventarioingresoController::class, 'searchIngreso'])->name('search.ingreso');
    Route::get('/search-tssalidacaja', [TsSalidacajaController::class, 'searchSalidaCaja'])->name('search.tssalidascajas');
    Route::get('/searcha-lqadelantos', [LqAdelantoController::class, 'searchAdelanto'])->name('searcha.lqadelantos');
    Route::get('/search-lqliquidaciones', [LqLiquidacionController::class, 'searchLiquidacion'])->name('search.lqliquidacion');
    Route::get('/search-lqdevoluciones', [LqDevolucionController::class, 'searchDevolucion'])->name('search.lqdevolucion');
    Route::get('/search-lqsociedades', [LqSociedadController::class, 'searchSociedad'])->name('search.lqsociedades');
    Route::get('/search-tsreposicionescajas', [TsReposicioncajaController::class, 'searchReposicionesCajas'])->name('search.tsreposicionescajas');
    Route::get('/search/reportes-cuentas/{id}', [TsReporteDiarioCuentasController::class, 'searchReportesCuentas'])
        ->name('search.tsreportescuentas');
    Route::get('/search-tssalidascuentas', [TsSalidacuentaController::class, 'searchSalidaCuenta'])->name('search.salidascuentas');
    Route::get('/search-tsingresoscuentas', [TsIngresoCuentaController::class, 'searchIngresosCuentas'])->name('search.ingresoscuentas');
    Route::get('/search-salidasmiscajas', [TsMicajaController::class, 'searchSalidasMisCajas'])->name('search.salidasmiscajas');
    //AQUI ESTÁ LA CORRECION DE INGRESOS RAPIDOS

    Route::get('/search-ingreso-rapido', [InvingresosrapidosController::class, 'buscar'])
        ->name('invingresosrapidos.buscar');

    //FIND DOCUMENTO BY PERSONA AJAX
    Route::get('/search-documento-persona-by-name', [PersonaController::class, 'searchPersonaDocumento'])->name('autocompdoc.persona');

    //FIND PROVEEDOR BY DATOS PROVEEDOR AJAX
    Route::get('/search-proveedor-by-razonsocial', [ProveedorController::class, 'searchProveedor'])->name('autocomp.proveedor');
    //FIND PROVEEDOR BY DOCUMENTO PROVEEDOR AJAX
    Route::get('/search-proveedor-by-ruc', [ProveedorController::class, 'searchProveedorByRuc'])->name('autocompbyruc.proveedor');

    //FIND CLIENTE BY NOMBRE CLIENTE AJAX
    Route::get('/search-cliente-by-nombre', [TsIngresocuentaController::class, 'findCliente'])->name('autocomp.cliente');

    //FIND BENEFICIARII BY NOMBRE BENEFICIARIO AJAX
    Route::get('/search-beneficiario-by-nombre', [TsSalidacuentaController::class, 'findBeneficiario'])->name('autocomp.beneficiario');

    //FIND REPRESENTANTE ADELANTO BY NOMBRE REPRESENTANTE AJAX
    Route::get('/search-representantead-by-nombre', [LqAdelantoController::class, 'findRepresentante'])->name('autocomp.representadelanto');

    //FIND REPRESENTANTE LIQUIDACIÓN BY NOMBRE REPRESENTANTE AJAX
    Route::get('/search-representantelq-by-nombre', [LqLiquidacionController::class, 'findRepresentante'])->name(name: 'autocomp.representliquidacion');

    //FOR AJAX GET ADELANTOS
    Route::get('/get-adelantos-by-sociedad', [LqLiquidacionController::class, 'getAdelantos'])->name('get.adelantos.by.sociedad');


    //AJAX MODAL CAJAS
    Route::get('/get-reposiciones-caja', [TscajaController::class, 'getReposiciones'])->name('get.reposiciones');

    //CHATS
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/message', [ChatController::class, 'storeMessage'])->name('chats.storeMessage');
    Route::post('/chats/start', [ChatController::class, 'startChat'])->name('chats.start'); // Route to start a new chat
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

    //DAILY ROTATIONS PRODUCTS 
    Route::get('/dailyrotation/{id}/products', [ProductoController::class, 'dailyrotation'])->name('product.dailyrotation');

    //ORDENES DE SERVICIO
    Route::get('/orden-servicio', [OrdenServicioController::class, 'index'])->name('orden-servicio.index');
    Route::get('/orden-servicio/formulario', [OrdenServicioController::class, 'formulario'])->name('orden-servicio.formulario');
    Route::post('/orden-servicio/store', [OrdenServicioController::class, 'store'])->name('orden-servicio.store');
    Route::get('/orden-servicio/datatable', [OrdenServicioController::class, 'datatable'])->name('orden-servicio.datatable');
    Route::post('/orden-servicio/{id}/cancelar', [OrdenServicioController::class, 'cancelar'])->name('orden-servicio.cancelar');
    Route::get('/orden-servicio/export-excel', [OrdenServicioController::class, 'exportExcel'])->name('orden-servicio.export-excel');
    Route::get('/orden-servicio/show', [OrdenServicioController::class, 'show'])->name('orden-servicio.show');
    Route::get('/orden-servicio/edit/{id}', [OrdenServicioController::class, 'edit'])
        ->name('orden-servicio.edit');
    Route::put('/orden-servicio/{id}', [OrdenServicioController::class, 'update'])
        ->name('orden-servicio.update');
    Route::post('/orden-servicio/{id}/anular', [OrdenServicioController::class, 'anular'])->name('orden-servicio.anular');
    Route::get('/orden-servicio/search', [OrdenServicioController::class, 'search'])
        ->name('search.ordenservicio');
    Route::get('/orden-servicio/{id}/print', [OrdenServicioController::class, 'print'])->name('orden-servicio.print');
    Route::get('/orden-servicio/{id}/comprobante', [OrdenServicioController::class, 'comprobante'])->name('orden-servicio.comprobante');
    Route::post('/orden-servicio/{id}/cancelar', [OrdenServicioController::class, 'cancelar'])->name('orden-servicio.cancelar');
    Route::post('/orden-servicio/{id}/proceso', [OrdenServicioController::class, 'proceso'])->name('orden-servicio.proceso');
    Route::post('/orden-servicio/{id}/finalizar', [OrdenServicioController::class, 'finalizar'])->name('orden-servicio.finalizar');

    //reactivo 
    Route::post('/reactivos', [ReactivoController::class, 'store'])->name('reactivos.store');
    Route::get('/reactivos', [ReactivoController::class, 'index'])->name('reactivos');
    Route::post('/reactivosdetalles', [ReactivoDetalleController::class, 'store'])->name('reactivosdetalles.store');
    Route::delete('/reactivos/destroy', [ReactivoController::class, 'destroy'])->name('reactivos.destroy');
    Route::delete('/reactivosdetalles/destroy', [ReactivoDetalleController::class, 'destroy'])->name('reactivosdetalles.destroy');


    //lotes
    Route::post('/lotes', [LoteController::class, 'store'])->name('lotes.store');
    Route::get('/lotes', [LoteController::class, 'index'])->name('lotes');
    Route::put('/lotes/{id}', [LoteController::class, 'update'])->name('lotes.update');
    Route::delete('/lotes/destroy', [LoteController::class, 'destroy'])->name('lotes.destroy');
    Route::get('/lotes/buscar', [LoteController::class, 'buscar'])->name('lotes.buscar');
    Route::get('/lotes/{id}/pesos', [LoteController::class, 'pesosEnCancha'])->name('lotes.pesos');

    //procesos
    Route::get('/procesos', [ProcesoController::class, 'index'])->name('procesos');
    Route::get('/procesos/{id}/edit', [ProcesoController::class, 'edit'])->name('procesos.edit');
    Route::post('/procesos', [ProcesoController::class, 'store'])->name('procesos.store');
    Route::get('/procesos/{id}/eliminar', [ProcesoController::class, 'destroy'])->name('procesos.delete');

    //pesos
    Route::get('/pesos', [PesoController::class, 'index'])->name('pesos.index');
    Route::post('/pesos', [PesoController::class, 'pesos'])->name('pesos');
    Route::put('/pesos-update/{id}', [PesoController::class, 'update'])->name('pesos.update');

    Route::prefix('programaciones')->group(function () {
        Route::get('/', [PlProgramacionController::class, 'index'])->name('programaciones.index');
        Route::post('/', [PlProgramacionController::class, 'store'])->name('programaciones.store');
        Route::put('/{id}', [PlProgramacionController::class, 'update'])->name('programaciones.update');
        Route::delete('/{id}', [PlProgramacionController::class, 'destroy'])->name('programaciones.destroy');
        Route::get('/{id}/pesos', [PesoController::class, 'pesosByProgramacion']);
        Route::get('/{id}/pesos', [PlProgramacionController::class, 'datatable']);
    });

    Route::post('/otras-balanza/{id}', [PesoOtraBalController::class, 'store'])->name('otrasBalanza.store');
    Route::delete('/otras-balanza/{id}', [PesoOtraBalController::class, 'destroy'])->name('otrasBalanza.destroy');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['no.cache'])->group(function () {
    Auth::routes();
});
