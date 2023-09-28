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

// Route::get('/', function () {
//     $user = Auth::user();
//     if (is_null($user)) {
//         return view('auth.login');
//     }
//     return redirect('/dashboard');
//     //return redirect('/denied');
// });

Route::get('/', 'Auth\LoginController@getAuthUser');

Route::get('check-session', 'Auth\LoginController@checkSession');
Route::post('login2', 'UsuariosController@login2')->name('login2');

Auth::routes();

Route::middleware('auth')->group(function () {
    session(['database_name' => 'mysql2']);

    Route::get('/500', function () {
        return view('500');
    });
    Route::get('/denied', function () {
        return view('access_denied');
    });

    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    //Historial
    Route::resource('/historialCompra', 'HistorialCompraController', ['parameters' => ['historialCompra' => 'historial']]);
    //fin de ruta

    //Mi Cuenta

    Route::get('/mi_cuenta', 'MiCuentaController@index')->name('mi_cuenta');
    Route::post('/mi_cuenta', 'MiCuentaController@update');

    Route::resource('/categoriaClientes', 'CategoriaClientesController', [
        'parameters' => [
            'categoriaClientes' => 'categoria',
        ]
    ]);


    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('usuarios/permisos', 'UsuariosController@permisos');
    Route::get('/permisos/{rol}/edit', 'UsuariosController@editpermisos')->name('permisos.edit');
    Route::get('/permisosusuarios/{user}/edit', 'UsuariosController@editpermisosusuarios')->name('usuarios.permisos');
    Route::post('/permisos/{rol}/actualizar', 'UsuariosController@updatepermisos');
    Route::post('/permisosusuarios/{rol}/actualizar', 'UsuariosController@updatepermisosusuarios');
    Route::get('/permisos/{rol}/create', 'UsuariosController@create'); ///
    Route::resource('/permisos', 'PermisosController', [
        'parameters' => [
            'permisos' => 'permiso'
        ]
    ]);


    //Catalogos
    Route::resource('/observacionesCotizacion', 'ObservacionesCotizacionController', [
        'only'       => ['store', 'destroy'],
        'parameters' => ['observacionesCotizacion' => 'observacion'],
    ]);
    Route::resource('/tiposClientes', 'TiposClientesController', [
        'parameters' => [
            'tiposClientes' => 'tipo',
        ]
    ]);
    /*categoria del cliente*/
    Route::resource('/categoriaClientes', 'CategoriaClientesController', [
        'parameters' => [
            'categoriaClientes' => 'categoria',
        ]
    ]);
    /*categoria del cliente*/
    Route::resource('/tiposProveedores', 'TiposProveedoresController', [
        'parameters' => [
            'tiposProveedores' => 'tipo',
        ]
    ]);
    Route::resource('/unidadesMedida', 'UnidadesMedidaController', [
        'parameters' => [
            'unidadesMedida' => 'unidad',
        ]
    ]);
    Route::resource('/agentesAduanales', 'AgentesAduanalesController', [
        'parameters' => [
            'agentesAduanales' => 'agente',
        ]
    ]);

    Route::resource('/vendedores', 'VendedoresController', [
        'parameters' => [
            'vendedores' => 'vendedor',
        ]
    ]);

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
    Route::post('/productos/{producto}/updateVisibilidad', 'ProductosController@updateVisibilidad');


    Route::get('productos/{id}/activar', 'ProductosController@activar')->name('productos.activar');

    Route::get('productos/inactivos', 'ProductosController@productosinactivos')->name('productos.inactivo');

    Route::get('productos/{id}/desactivar', 'ProductosController@desactivar')->name('productos.desactivar');

    Route::resource('/productos', 'ProductosController');
    Route::resource('/categorias', 'CategoriasController');
    Route::resource('/subcategorias', 'SubcategoriasController');


    Route::post('/proyectos-aprobados/listado', 'ProyectosAprobadosController@listado');
    Route::post('/prospectos/listado', 'ProspectosController@listado');
    Route::post('/prospectos/listado3', 'ProspectosController@listado3');
    Route::post('/prospectos/listadoprospectos', 'ProspectosController@listadoprospectos');
    Route::post('/prospectos/listadocot', 'ProspectosController@listadocot');
    Route::post('/cuentas-cobrar/listado', 'CuentasCobrarController@listado');
    Route::post('/cuentas-pagar/listado', 'CuentasPagarController@listado');
    Route::post('/ordenes-proceso/listado', 'OrdenesProcesoController@listado');


    //Administracion
    Route::middleware('role:Administrador')->group(function () {
        Route::post('/usuarios/{usuario}', 'UsuariosController@update');
        Route::resource('/usuarios', 'UsuariosController');

        Route::get('usuarios/{id}/activar', 'UsuariosController@activar', [
            'parameters' => ['usuarios' => 'usuario'],
        ])->name('usuarios.activar');

        Route::get('usuarios/{id}/desactivar', 'UsuariosController@desactivar', [
            'parameters' => ['usuarios' => 'usuario'],
        ])->name('usuarios.desactivar');

        Route::get('usuarios/permisos', 'UsuariosController@permisos')->name('permisos.usuarios');
        Route::get('/permisos/{rol}/edit', 'UsuariosController@editpermisos')->name('permisos.edit');
        Route::post('/permisos/{rol}/actualizar', 'UsuariosController@updatepermisos');
        Route::get('/permisos/{rol}/create', 'UsuariosController@create'); ///
        Route::resource('/permisos', 'PermisosController', [
            'parameters' => [
                'permisos' => 'permiso'
            ]
        ]);

        Route::delete('/productos/{producto}', 'ProductosController@destroy');
        Route::delete('/clientes/{cliente}', 'ClientesController@destroy');
        Route::resource('/subcategorias', 'SubcategoriasController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/categorias', 'CategoriasController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/categoriaClientes', 'CategoriaClientesController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]); //
        Route::resource('/tiposClientes', 'TiposClientesController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);

        Route::resource('/historialCompra', 'HistorialCompraController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/vendedores', 'VendedoresController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/tiposProveedores', 'TiposProveedoresController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/proyectos', 'ProyectosController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/subproyectos', 'SubProyectosController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);
        Route::resource('/unidadesMedida', 'UnidadesMedidaController', ['only' => ['create', 'store', 'edit', 'update', 'delete']]);

        Route::post('/dashboard/listado', 'DashboardController@listado');
        Route::post('/dashboard/changebdd', 'DashboardController@changebdd');

        Route::get('/reportes/cotizaciones', 'ReportesController@cotizaciones');
        Route::post('/reportes/cotizaciones/pdf', 'ReportesController@cotizacionespdf');
        Route::post('/reportes/cotizaciones/excel', 'ReportesController@cotizacionesexcel');
        Route::get('/reportes/cobros', 'ReportesController@cobros');
        Route::post('/reportes/cobros/pdf', 'ReportesController@cobrospdf');
        Route::post('/reportes/cobros/excel', 'ReportesController@cobrosexcel');
        Route::get('/reportes/compras', 'ReportesController@compras');
        Route::post('/reportes/compras/pdf', 'ReportesController@compraspdf');
        Route::post('/reportes/compras/excel', 'ReportesController@comprasexcel');
        Route::get('/reportes/pagos', 'ReportesController@pagos');
        Route::post('/reportes/pagos/pdf', 'ReportesController@pagospdf');
        Route::post('/reportes/pagos/excel', 'ReportesController@pagosexcel');
        Route::get('/reportes/cuentaCliente', 'ReportesController@cuentaCliente');
        Route::post('/reportes/cuentaCliente/pdf', 'ReportesController@cuentapdf');
        Route::post('/reportes/cuentaCliente/excel', 'ReportesController@cuentaexcel');
        Route::get('/reportes/saldoProveedores', 'ReportesController@saldoProveedores');
        Route::post('/reportes/saldoProveedores/pdf', 'ReportesController@saldopdf');
        Route::post('/reportes/saldoProveedores/excel', 'ReportesController@saldoexcel');
        Route::get('/reportes/utilidades', 'ReportesController@utilidades');
        Route::post('/reportes/utilidades/pdf', 'ReportesController@utilidadespdf');
        Route::post('/reportes/utilidades/excel', 'ReportesController@utilidadesexcel');

        //Cotizar Direccto
        Route::get('/prospectos/cotizar-directo', 'ProspectosController@directo')->name('prospectos.directo');

        // Crud Notas
        Route::get('/notas', 'NotaController@index')->name('notas.index');
        Route::get('/notas/create', 'NotaController@create')->name('notas.create');
        Route::post('/notas', 'NotaController@store')->name('notas.store');
        Route::get('/notas/{id}', 'NotaController@show')->name('notas.show');
        Route::get('/notas/{id}/edit', 'NotaController@edit')->name('notas.edit');
        Route::post('/notas/{id}', 'NotaController@update')->name('notas.update');
        Route::delete('/notas/{id}', 'NotaController@destroy')->name('notas.destroy');
    });

    Route::get('/gethistorialtarea/{tarea}', 'TareasController@gethistorial')->name('tareas.gethistorial');
    //Prospectos
    Route::get('/prospectos/cotizaciones', 'ProspectosController@cotizaciones');
    Route::get('cotizacionesdirectas', 'ProspectosController@cotizacionesdirectas');
    Route::get('cotizacionesdirectas/create', 'ProspectosController@cotizacionesdirectas_create')->name('cotizacionesdirectas.create');

    Route::get('cotizacionesdirectas/{cotizacion}/edit', 'ProspectosController@cotizacionesdirectas_edit')->name('cotizacionesdirectas.edit');
    Route::post('/cotizacionesdirectas/store', 'ProspectosController@cotizacionesdirectas_store')->name('cotizacionesdirectas.store');
    Route::post('/cotizacionesdirectas/{cotizacion}/update', 'ProspectosController@cotizacionesdirectas_update')->name('cotizacionesdirectas.update');
    Route::get('/prospectos/prospectos', 'ProspectosController@prospectos')->name('prospectos.indexprospectos');
    //prospectos ver nuevo
    Route::get('/prospectos/{prospecto}/disenador/{disenador}/anio/{anio}', 'ProspectosController@prospectosver')->name('prospectos.prospectosver');
    //prospectos editar nuevo
    Route::get('/prospectos/{prospecto}/disenador/{disenador}/anio/{anio}/editar', 'ProspectosController@prospectoseditar')->name('prospectos.prospectoseditar');
    //prospectos index nuevo
    Route::get('/prospectos/{disenador}/anio/{anio}/index', 'ProspectosController@prospectosindex')->name('prospectos.prospectosindex');

    Route::get('/prospectos/{estatus}/indexprospectos', 'ProspectosController@prospectos'); //Ruta para los estatus de apartado//

    Route::get('/prospectos/create2', 'ProspectosController@create2')->name('prospectos.create2');
    Route::get('/productosmasivo', 'ProductosController@create2')->name('productos.create2');
    Route::post('/productosguardar', 'ProductosController@guardar');
    Route::get('prospectos/regeneratePDF', 'ProspectosController@regeneratePDF');
    Route::get('prospectos/actualizarActividades', 'ProspectosController@actualizarActividades');
    Route::post('/prospectos/{prospecto}/guardarActividades', 'ProspectosController@guardarActividades');
    Route::get('/prospectos/{prospecto}/cotizar', 'ProspectosController@cotizar')->name('prospectos.cotizar');
    Route::post('/prospectos/{prospecto}/cotizacion', 'ProspectosController@cotizacion');
    Route::post('/prospectos/{prospecto}/convertir', 'ProspectosController@convertir');
    Route::post('/prospectos/{prospecto}/cotizacion/{cotizacion}', 'ProspectosController@cotizacionUpdate');
    Route::post('/prospectos/{prospecto}/enviarCotizacion', 'ProspectosController@enviarCotizacion');
    Route::post('/prospectos/{prospecto}/aceptarCotizacion', 'ProspectosController@aceptarCotizacion');
    Route::post('/prospectos/{prospecto}/notasCotizacion', 'ProspectosController@notasCotizacion');
    Route::post('/prospectos/{prospecto}/copiarCotizacion', 'ProspectosController@copiarCotizacion');
    Route::delete('/prospectos/{prospecto}/cotizacion/{cotizacion}', 'ProspectosController@destroyCotizacion');
    Route::resource('/prospectos', 'ProspectosController');
    Route::resource('/tareas', 'TareasController');
    Route::post('/tareasactualizar', 'TareasController@actualizar');
    Route::post('/comentarios', 'TareasController@guardarcomentario');
    Route::post('/marcarleida', 'TareasController@marcarleida');

    //Proyectos Aprobados
    Route::get('/proyectos-aprobados', 'ProyectosAprobadosController@index')->name('proyectos-aprobados.index');
    Route::delete('/proyectos-aprobados/{proyecto}', 'ProyectosAprobadosController@destroy');
    Route::get('/proyectos-aprobados/{proyecto}/show', 'ProyectosAprobadosController@show');
    // Route::get('/proyectos-aprobados/{proyecto}/generarOrdenes', 'ProyectosAprobadosController@generarOrdenes');

    //Ordenes de Compra
    Route::get('ordenes-compra/checarDescripciones', 'OrdenesCompraController@checarDescripciones');
    Route::get('ordenes-compra/regeneratePDF', 'OrdenesCompraController@regeneratePDF');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/comprar', 'OrdenesCompraController@comprar');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/agregarArchivo', 'OrdenesCompraController@agregarArchivo');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/borrarArchivo', 'OrdenesCompraController@borrarArchivo');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/rechazar', 'OrdenesCompraController@rechazar');
    Route::get('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/aprobar', 'OrdenesCompraController@aprobar');
    Route::get('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/desaprobar', 'OrdenesCompraController@desaprobar');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/confirmar', 'OrdenesCompraController@confirmar');
    Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/actualizar', 'OrdenesCompraController@actualizar');
    Route::resource('/proyectos-aprobados.ordenes-compra', 'OrdenesCompraController', [
        'parameters' => ['proyectos-aprobados' => 'proyecto', 'ordenes-compra' => 'orden'],
    ]);

    //Cuentas cobrar
    Route::post('/cuentas-cobrar/{cuenta}/facturar', 'CuentasCobrarController@facturar');
    Route::post('/cuentas-cobrar/{cuenta}/deletefactura', 'CuentasCobrarController@deletefactura');
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

    //Cotizar Directo
    // Route::get('/prospectos/cotizar-directo', 'ProspectosController@directo')->name('prospectos.directo');

    // Crud Notas
    // Route::resource('/productos', 'ProductosController');
    Route::get('/notas', 'NotasController@index')->name('notas.index');
    Route::get('/notas/create', 'NotasController@create')->name('notas.create');
    Route::post('/notas', 'NotasController@store')->name('notas.store');
    Route::get('/notas/{id}', 'NotasController@show')->name('notas.show');
    Route::get('/notas/{id}/editar', 'NotasController@edit')->name('notas.edit');
    Route::post('/notas/{id}', 'NotasController@update')->name('notas.update');
    Route::delete('/notas/{id}', 'NotasController@destroy')->name('notas.destroy');

});