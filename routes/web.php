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
  if(is_null($user)) return view('auth.login');
  return redirect('/dashboard');
});

Auth::routes();

Route::middleware('auth')->group(function () {
  Route::get('/500', function(){ return view('500'); });
  Route::get('/denied', function(){ return view('access_denied'); });

  //Dashboard
  Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

  //Mi Cuenta
  Route::get('/mi_cuenta', 'MiCuentaController@index')->name('mi_cuenta');
  Route::post('/mi_cuenta', 'MiCuentaController@update');

  //Administracion
  Route::middleware('role:Administrador')->group(function(){
    Route::post('/usuarios/{usuario}', 'UsuariosController@update');
    Route::resource('/usuarios', 'UsuariosController');
  });

  //Catalogos
  Route::resource('/tiposClientes', 'TiposClientesController', ['parameters' => [
    'tiposClientes' => 'tipo'
  ]]);
  Route::resource('/clientes', 'ClientesController');
  Route::resource('/proveedores', 'ProveedoresController', ['parameters'=>['proveedores'=>'proveedor']]);
  Route::resource('/proyectos', 'ProyectosController');
  Route::resource('/subproyectos', 'SubProyectosController');
  Route::resource('/categorias', 'CategoriasController');
  Route::post('/productos/{producto}', 'ProductosController@update');
  Route::resource('/productos', 'ProductosController');

  //Prospectos
  Route::get('prospectos/regeneratePDF', 'ProspectosController@regeneratePDF');
  Route::get('/prospectos/{prospecto}/cotizar', 'ProspectosController@cotizar')->name('prospectos.cotizar');
  Route::post('/prospectos/{prospecto}/cotizacion', 'ProspectosController@cotizacion');
  Route::post('/prospectos/{prospecto}/enviarCotizacion', 'ProspectosController@enviarCotizacion');
  Route::post('/prospectos/{prospecto}/aceptarCotizacion', 'ProspectosController@aceptarCotizacion');
  Route::post('/prospectos/{prospecto}/notasCotizacion', 'ProspectosController@notasCotizacion');
  Route::resource('/prospectos', 'ProspectosController');

  //Proyectos Aprobados
  Route::get('/proyectos-aprobados', 'ProyectosAprobadosController@index');
  // Route::get('/proyectos-aprobados/{proyecto}/generarOrdenes', 'ProyectosAprobadosController@generarOrdenes');

  //Ordenes de Compra
  Route::get('ordenes-compra/regeneratePDF', 'OrdenesCompraController@regeneratePDF');
  Route::get('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/comprar', 'OrdenesCompraController@comprar');
  Route::post('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/rechazar', 'OrdenesCompraController@rechazar');
  Route::get('/proyectos-aprobados/{proyecto}/ordenes-compra/{orden}/aprobar', 'OrdenesCompraController@aprobar');
  Route::resource('/proyectos-aprobados.ordenes-compra', 'OrdenesCompraController', [
    'parameters' => ['proyectos-aprobados'=>'proyecto', 'ordenes-compra'=>'orden']
  ]);

  //Cuentas cobrar
  Route::post('/cuentas-cobrar/{cuenta}/facturar', 'CuentasCobrarController@facturar');
  Route::post('/cuentas-cobrar/{cuenta}/pagar', 'CuentasCobrarController@pagar');
  Route::resource('/cuentas-cobrar', 'CuentasCobrarController', [
    'only'=>['index','show','edit'],
    'parameters'=>['cuentas-cobrar'=>'cuenta']
  ]);

  //Cuentas pagar
  Route::post('/cuentas-pagar/{cuenta}/facturar', 'CuentasPagarController@facturar');
  Route::post('/cuentas-pagar/{cuenta}/pagar', 'CuentasPagarController@pagar');
  Route::resource('/cuentas-pagar', 'CuentasPagarController', [
    'only'=>['index','show','edit'],
    'parameters'=>['cuentas-pagar'=>'cuenta']
  ]);

  //ordenes en proceso
  Route::get('/ordenes-proceso', 'OrdenesProcesoController@index');
  Route::post('/ordenes-proceso/{orden}/updateStatus', 'OrdenesProcesoController@updateStatus');
  Route::post('/ordenes-proceso/{orden}/embarcar', 'OrdenesProcesoController@embarcar');
  Route::post('/ordenes-proceso/{orden}/aduana', 'OrdenesProcesoController@aduana');
  
});
