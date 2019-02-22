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
  return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {

  Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

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
  Route::resource('/prospectos', 'ProspectosController');
});
