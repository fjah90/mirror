<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Route::get('/', function () {
    $user = Auth::user();
    if (is_null($user)) {
        return view('auth.login');
    }

    return redirect('/dashboard');
});

Route::get('check-session', 'Auth\LoginController@checkSession');

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/500', function () {return view('500');});
    Route::get('/denied', function () {return view('access_denied');});

    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    //Mi Cuenta
    Route::get('/mi_cuenta', 'MiCuentaController@index')->name('mi_cuenta');
    Route::post('/mi_cuenta', 'MiCuentaController@update');

    //Catalogos
    Route::resource('/observacionesCotizacion', 'ObservacionesCotizacionController', [
        'only'       => ['store', 'destroy'],
        'parameters' => ['observacionesCotizacion' => 'observacion'],
    ]);
    Route::resource('/tiposClientes', 'TiposClientesController', ['parameters' => [
        'tiposClientes' => 'tipo',
    ]]);
    Route::resource('/tiposProveedores', 'TiposProveedoresController', ['parameters' => [
        'tiposProveedores' => 'tipo',
    ]]);
    Route::resource('/unidadesMedida', 'UnidadesMedidaController', ['parameters' => [
        'unidadesMedida' => 'unidad',
    ]]);
    Route::resource('/agentesAduanales', 'AgentesAduanalesController', ['parameters' => [
        'agentesAduanales' => 'agente',
    ]]);
    Route::resource('/condicionesCotizacion', 'CondicionesCotizacionController', [
        'only'       => ['update', 'destroy'],
        'parameters' => ['condicionesCotizacion' => 'condicion'],
    ]);
    Route::get('/clientes/crearNacional', 'ClientesController@create')->name('clientes.createNacional');
    Route::get('/clientes/crearExtranjero', 'ClientesController@createExtra')->name('clientes.createExtranjero');
    Route::post('/clientes/listado', 'ClientesController@listado');
    Route::resource('/clientes', 'ClientesController');
    Route::get('/proveedores/crearNacional', 'ProveedoresController@create')->name('proveedores.createNacional');
    Route::get('/proveedores/crearInternacional', 'ProveedoresController@createInter')->name('proveedores.createInternacional');
    Route::get('/proveedoresExtra', 'ProveedoresController@indexExtra')->name('proveedores.indexExtra');
    Route::resource('/proveedores', 'ProveedoresController', ['parameters' => ['proveedores' => 'proveedor']]);
    Route::resource('/contactos', 'ContactosController');
    Route::resource('/datosFacturacion', 'DatosFacturacionController');
    Route::resource('/emails', 'EmailsController');
    Route::resource('/telefonos', 'TelefonosController');
    Route::resource('/proyectos', 'ProyectosController');
    Route::resource('/subproyectos', 'SubProyectosController');
    Route::post('/productos/{producto}', 'ProductosController@update');
    Route::resource('/productos', 'ProductosController');
    Route::resource('/categorias', 'CategoriasController');
    Route::resource('/subcategorias', 'SubcategoriasController');

    //Administracion
    Route::middleware('role:Administrador')->group(function () {
        Route::post('/usuarios/{usuario}', 'UsuariosController@update');
        Route::resource('/usuarios', 'UsuariosController');

        Route::delete('/productos/{producto}', 'ProductosController@destroy');
        Route::delete('/clientes/{cliente}', 'ClientesController@destroy');
        Route::resource('/subcategorias', 'SubcategoriasController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/categorias', 'CategoriasController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/tiposClientes', 'TiposClientesController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/tiposProveedores', 'TiposProveedoresController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/proyectos', 'ProyectosController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/subproyectos', 'SubProyectosController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/unidadesMedida', 'UnidadesMedidaController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);

        Route::post('/prospectos/listado', 'ProspectosController@listado');
        Route::post('/cuentas-cobrar/listado', 'CuentasCobrarController@listado');
        Route::post('/cuentas-pagar/listado', 'CuentasPagarController@listado');
        Route::post('/ordenes-proceso/listado', 'OrdenesProcesoController@listado');
        Route::post('/dashboard/listado', 'DashboardController@listado');
        Route::post('/proyectos-aprobados/listado', 'ProyectosAprobadosController@listado');

        Route::get('/reportes/cotizaciones', 'ReportesController@cotizaciones');
        Route::get('/reportes/cobros', 'ReportesController@cobros');
        Route::get('/reportes/compras', 'ReportesController@compras');
        Route::get('/reportes/pagos', 'ReportesController@pagos');
        Route::get('/reportes/cuentaCliente', 'ReportesController@cuentaCliente');
        Route::get('/reportes/saldoProveedores', 'ReportesController@saldoProveedores');
        Route::get('/reportes/utilidades', 'ReportesController@utilidades');

    });

    //Prospectos
    Route::get('prospectos/regeneratePDF', 'ProspectosController@regeneratePDF');
    Route::get('prospectos/actualizarActividades', 'ProspectosController@actualizarActividades');
    Route::post('/prospectos/{prospecto}/guardarActividades', 'ProspectosController@guardarActividades');
    Route::get('/prospectos/{prospecto}/cotizar', 'ProspectosController@cotizar')->name('prospectos.cotizar');
    Route::post('/prospectos/{prospecto}/cotizacion', 'ProspectosController@cotizacion');
    Route::post('/prospectos/{prospecto}/cotizacion/{cotizacion}', 'ProspectosController@cotizacionUpdate');
    Route::post('/prospectos/{prospecto}/enviarCotizacion', 'ProspectosController@enviarCotizacion');
    Route::post('/prospectos/{prospecto}/aceptarCotizacion', 'ProspectosController@aceptarCotizacion');
    Route::post('/prospectos/{prospecto}/notasCotizacion', 'ProspectosController@notasCotizacion');
    Route::post('/prospectos/{prospecto}/copiarCotizacion', 'ProspectosController@copiarCotizacion');
    Route::delete('/prospectos/{prospecto}/cotizacion/{cotizacion}', 'ProspectosController@destroyCotizacion');
    Route::resource('/prospectos', 'ProspectosController');

    //Proyectos Aprobados
    Route::get('/proyectos-aprobados', 'ProyectosAprobadosController@index')->name('proyectos-aprobados.index');
    Route::delete('/proyectos-aprobados/{proyecto}', 'ProyectosAprobadosController@destroy');
    // Route::get('/proyectos-aprobados/{proyecto}/generarOrdenes', 'ProyectosAprobadosController@generarOrdenes');

    //Ordenes de Compra
    Route::get('ordenes-compra/checarDescripciones', 'OrdenesCompraController@checarDescripciones');
    Route::get('ordenes-compra/regeneratePDF', 'OrdenesCompraController@regeneratePDF');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/comprar', 'OrdenesCompraController@comprar');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/agregarArchivo', 'OrdenesCompraController@agregarArchivo');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/borrarArchivo', 'OrdenesCompraController@borrarArchivo');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/rechazar', 'OrdenesCompraController@rechazar');
    Route::get('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/aprobar', 'OrdenesCompraController@aprobar');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/confirmar', 'OrdenesCompraController@confirmar');
    Route::resource('/proyectos-aprobados.ordenes-compra', 'OrdenesCompraController', [
        'parameters' => ['proyectos-aprobados' => 'proyecto', 'ordenes-compra' => 'orden'],
    ]);

    //Cuentas cobrar
    Route::post('/cuentas-cobrar/{cuenta}/facturar', 'CuentasCobrarController@facturar');
    Route::post('/cuentas-cobrar/{cuenta}/pagar', 'CuentasCobrarController@pagar');
    Route::resource('/cuentas-cobrar', 'CuentasCobrarController', [
        'only'       => ['index', 'show', 'edit'],
        'parameters' => ['cuentas-cobrar' => 'cuenta'],
    ]);

    //Cuentas pagar
    Route::post('/cuentas-pagar/{cuenta}/facturar', 'CuentasPagarController@facturar');
    Route::post('/cuentas-pagar/{cuenta}/pagar', 'CuentasPagarController@pagar');
    Route::resource('/cuentas-pagar', 'CuentasPagarController', [
        'only'       => ['index', 'show', 'edit'],
        'parameters' => ['cuentas-pagar' => 'cuenta'],
    ]);

    //ordenes en proceso
    Route::get('/ordenes-proceso', 'OrdenesProcesoController@index');
    Route::post('/ordenes-proceso/{orden}/updateStatus', 'OrdenesProcesoController@updateStatus');
    Route::post('/ordenes-proceso/{orden}/fijarFechasEstimadas', 'OrdenesProcesoController@fijarFechasEstimadas');
    Route::post('/ordenes-proceso/{orden}/embarcar', 'OrdenesProcesoController@embarcar');
    Route::post('/ordenes-proceso/{orden}/frontera', 'OrdenesProcesoController@frontera');
    Route::post('/ordenes-proceso/{orden}/aduana', 'OrdenesProcesoController@aduana');
    Route::post('/ordenes-proceso/{orden}/entrega', 'OrdenesProcesoController@entrega');

});
