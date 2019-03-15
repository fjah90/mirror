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

  //Cuentas cobrar
  Route::post('/cuentas-cobrar/{cuenta}/facturar', 'CuentasCobrarController@facturar');
  Route::post('/cuentas-cobrar/{cuenta}/pagar', 'CuentasCobrarController@pagar');
  Route::resource('/cuentas-cobrar', 'CuentasCobrarController', [
    'only'=>['index','show','edit'],
    'parameters'=>['cuentas-cobrar'=>'cuenta']
  ]);

});
