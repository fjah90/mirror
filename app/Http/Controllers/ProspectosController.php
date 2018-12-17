<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Prospecto;
use App\Models\ProspectoActividad;
use App\Models\ProspectoTipoActividad;

class ProspectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $prospectos = Prospecto::with('cliente', 'ultima_actividad.tipo', 'proxima_actividad.tipo')->get();

      return view('prospectos.index', compact('prospectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $clientes = Cliente::all();
      $productos = Producto::all();
      $tipos = ProspectoTipoActividad::all();
      return view('prospectos.create', compact('clientes', 'productos', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'cliente_id' => 'required',
        'descripcion' => 'required',
        'ultima_actividad.tipo_id' => 'required',
        'ultima_actividad.fecha' => 'required|date_format:d/m/Y',
        'ultima_actividad.descripcion' => 'required',
        'proxima_actividad.tipo_id' => 'required',
        'proxima_actividad.fecha' => 'required|date_format:d/m/Y',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $prospecto = Prospecto::create([
        'cliente_id' => $request->cliente_id,
        'descripcion' => $request->descripcion
      ]);

      //ultima actividad
      $create = [
        'prospecto_id' => $prospecto->id,
        'fecha' => $request->ultima_actividad['fecha'],
        'descripcion' => $request->ultima_actividad['descripcion'],
        'realizada' => 1
      ];
      if($request->ultima_actividad['tipo_id'] == 0){ //dar de alta nuevo tipo
        $tipo = ProspectoTipoActividad::create(['nombre' => $request->ultima_actividad['tipo']]);
        $create['tipo_id'] = $tipo->id;
      }
      else $create['tipo_id'] = $request->ultima_actividad['tipo_id'];
      $actividad = ProspectoActividad::create($create);

      //productos ofrecidos
      foreach ($request->ultima_actividad['ofrecidos'] as $ofrecido) {
        $actividad->productos_ofrecidos()->attach($ofrecido['id']);
      }

      //proxima actividad
      $create = [
        'prospecto_id' => $prospecto->id,
        'fecha' => $request->proxima_actividad['fecha'],
      ];
      if($request->proxima_actividad['tipo_id'] == 0){ //dar de alta nuevo tipo
        $tipo = ProspectoTipoActividad::create(['nombre' => $request->proxima_actividad['tipo']]);
        $create['tipo_id'] = $tipo->id;
      }
      else $create['tipo_id'] = $request->proxima_actividad['tipo_id'];
      $actividad2 = ProspectoActividad::create($create);

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function show(Prospecto $prospecto)
    {
      $prospecto->load(['cliente', 'actividades.tipo', 'actividades.productos_ofrecidos']);
      return view('prospectos.show', compact('prospecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Prospecto $prospecto)
    {
      $prospecto->load(['cliente', 'actividades.tipo', 'actividades.productos_ofrecidos',
      'proxima_actividad.tipo', 'proxima_actividad.productos_ofrecidos']);
      $prospecto->nueva_proxima_actividad = (object)[
        'fecha'=>'',
        'tipo_id'=>1,
        'tipo'=>''
      ];
      $productos = Producto::all();
      $tipos = ProspectoTipoActividad::all();
      return view('prospectos.edit', compact('prospecto' ,'productos', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prospecto $prospecto)
    {
      $validator = Validator::make($request->all(), [
        'proxima.descripcion' => 'required',
        'proxima.productos_ofrecidos' => 'present',
        'nueva.tipo_id' => 'required',
        'nueva.fecha' => 'required|date_format:d/m/Y',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $prospecto->load('proxima_actividad.tipo');
      $proxima = $prospecto->proxima_actividad;

      //actualizar proxima actividad
      $proxima->update([
        'descripcion' => $request->proxima['descripcion'],
        'realizada' => 1
      ]);

      //ingresar productos ofrecidos
      foreach ($request->proxima['productos_ofrecidos'] as $ofrecido) {
        $proxima->productos_ofrecidos()->attach($ofrecido['id']);
      }
      $proxima->load('productos_ofrecidos');

      //crear nueva proxima actividad
      $create = [
        'prospecto_id' => $prospecto->id,
        'fecha' => $request->nueva['fecha'],
      ];
      if($request->nueva['tipo_id'] == 0){ //dar de alta nuevo tipo
        $tipo = ProspectoTipoActividad::create(['nombre' => $request->nueva['tipo']]);
        $create['tipo_id'] = $tipo->id;
      }
      else $create['tipo_id'] = $request->nueva['tipo_id'];
      $nueva = ProspectoActividad::create($create);
      $nueva->load('tipo','productos_ofrecidos');

      //cargar tipos, por si se ingreso nuevo
      $tipos = ProspectoTipoActividad::all();

      return response()->json(['success' => true, "error" => false,
      'proxima'=>$proxima, 'nueva'=>$nueva, 'tipos'=>$tipos], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prospecto $prospecto)
    {
      $prospecto->delete();
      return response()->json(['success' => true, "error" => false], 200);
    }
}
