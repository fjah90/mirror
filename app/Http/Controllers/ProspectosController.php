<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Prospecto;
use App\Models\ProspectoActividad;
use App\Models\ProspectoTipoActividad;
use App\Models\ProspectoCotizacion;
use App\Models\ProspectoCotizacionEntrada;
use App\Models\CondicionCotizacion;
use Validator;
use PDF;
use Mail;
use Storage;

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

    /**
     * Agrear cotizacion.
     *
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function cotizar(Prospecto $prospecto)
    {
      $prospecto->load('cliente','cotizaciones.entradas.producto');
      $productos = Producto::with('categoria','proveedor')->get();
      $condiciones = CondicionCotizacion::all();

      foreach ($prospecto->cotizaciones as $cotizacion) {
        if($cotizacion->archivo) $cotizacion->archivo = asset('storage/'.$cotizacion->archivo);
      }
      foreach ($productos as $producto) {
        if($producto->foto) $producto->foto = asset('storage/'.$producto->foto);
      }

      return view('prospectos.cotizar', compact('prospecto', 'productos', 'condiciones'));
    }

    /**
     * Guardar cotizacion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function cotizacion(Request $request, Prospecto $prospecto)
    {
      $validator = Validator::make($request->all(), [
        'prospecto_id' => 'required',
        'entrega' => 'required',
        'lugar' => 'required',
        'moneda' => 'required',
        'condicion' => 'required',
        'iva' => 'required',
        'entradas' => 'required|min:1',
        'entradas.foto' => 'image',
        'subtotal' => 'required',
        'total' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }
      if (is_null($request->entradas[0])) {
        return response()->json([
          "success" => false, "error" => true, "message" => "Debe Agregar al menos 1 producto"
        ], 422);
      }

      $create = $request->except('entradas', 'condicion');
      $create['fecha'] = date('Y-m-d');
      if($request->condicion['id']==0){//nueva condicion, dar de alta
        $condicion = CondicionCotizacion::create(['nombre'=>$request->condicion['nombre']]);
        $create['condicion_id'] = $condicion->id;
      }
      else $create['condicion_id'] = $request->condicion['id'];
      if($request->iva=="1"){
        $create['iva'] = bcmul($create['subtotal'], 0.16, 2);
        $create['total'] = bcmul($create['subtotal'], 1.16, 2);
      }
      else {
        $create['total'] = $create['subtotal'];
      }

      $cotizacion = ProspectoCotizacion::create($create);

      //guardar entradas
      foreach ($request->entradas as $index => $entrada) {
        $producto = Producto::find($entrada['producto_id']);

        if(isset($entrada['foto']) && $entrada['foto']){
          $foto = Storage::putFileAs(
            'public/cotizaciones/'.$cotizacion->id,
            $entrada['foto'],
            'entrada_'.($index+1).'.'.$entrada['foto']->guessExtension()
          );
          $foto = str_replace('public/', '', $foto);
          $entrada['foto'] = $foto;
        }
        else if($producto->foto){
          $extencion = pathinfo(asset($producto->foto), PATHINFO_EXTENSION);
          $path = "cotizaciones/".$cotizacion->id."/entrada_".($index+1).".".$extencion;
          Storage::copy("public/".$producto->foto, "public/$path");
          $entrada['foto'] = $path;
        }
        $entrada['cotizacion_id'] = $cotizacion->id;
        ProspectoCotizacionEntrada::create($entrada);
      }

      $cotizacion->load('prospecto.cliente', 'condiciones', 'entradas.producto.categoria');
      foreach ($cotizacion->entradas as $entrada) {
        if($entrada->foto) $entrada->foto = asset('storage/'.$entrada->foto);
      }

      //crear pdf de cotizacion
      $url = 'cotizaciones/'.$cotizacion->id.'/cotizacion_'.$cotizacion->id.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";

      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion'));
      Storage::disk('public')->put($url, $cotizacionPDF->output());

      unset($cotizacion->fechaPDF);
      $cotizacion->update(['archivo'=>$url]);
      $cotizacion->archivo = asset('storage/'.$cotizacion->archivo);

      return response()->json(['success'=>true,'error'=>false,'cotizacion'=>$cotizacion], 200);
    }

    /**
     * Enviar cotizacion por email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enviarCotizacion(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'cotizacion_id' => 'required',
        'email' => 'required|email|max:255',
        'mensaje' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $cotizacion = ProspectoCotizacion::findOrFail($request->cotizacion_id);
      $cotizacion_id = $cotizacion->id;
      $email = $request->email;
      $pdf = file_get_contents(asset('storage/'.$cotizacion->archivo));

      Mail::send('prospectos.enviarCotizacion', ['mensaje' => $request->mensaje], function ($message) use ($cotizacion_id, $email, $pdf){
        $message->to($email)->subject('Cotización Intercorp');
        $message->attachData($pdf, 'Cotizacion '.$cotizacion_id.'.pdf');
      });

      return response()->json(['success'=>true, 'error'=>false], 200);
    }

    public function pruebas(){
      $cotizacion = ProspectoCotizacion::with('prospecto.cliente', 'condiciones', 'entradas.producto.categoria')
      ->find(1);

      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";

      foreach ($cotizacion->entradas as $entrada) {
        if($entrada->foto) $entrada->foto = asset('storage/'.$entrada->foto);
      }

      // return view('prospectos.cotizacionPDF', compact('cotizacion'));
      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion'));
      return $cotizacionPDF->download('cotizacion.pdf');
    }

}
