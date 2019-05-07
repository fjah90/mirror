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
use App\Models\ProspectoCotizacionEntradaDescripcion;
use App\Models\CondicionCotizacion;
use App\Models\CuentaCobrar;
use App\Models\ProyectoAprobado;
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
      $prospectos = Prospecto::with('cliente', 'ultima_actividad.tipo', 'proxima_actividad.tipo')
      ->whereHas('cliente')->get();

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
        'nombre' => 'required',
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
        'nombre' => $request->nombre,
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
      $productos = Producto::with('categoria','proveedor','descripciones.descripcionNombre')->get();
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
        'facturar' => 'required',
        'entrega' => 'required',
        'lugar' => 'required',
        'moneda' => 'required',
        'condicion' => 'required',
        'iva' => 'required',
        'entradas' => 'required|min:1',
        'entradas.fotos' => 'array',
        'entradas.fotos.*' => 'image|mimes:jpg,jpeg,png',
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

      $user = auth()->user();

      $create = $request->except('entradas', 'condicion', 'observaciones');
      $create['user_id'] = $user->id;
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
      $observaciones = "<ul>";
      foreach ($request->observaciones as $obs) {
        $observaciones.="<li>$obs</li>";
      }
      $observaciones.= "</ul>";
      $create['observaciones'] = $observaciones;

      $cotizacion = ProspectoCotizacion::create($create);

      //guardar entradas
      foreach ($request->entradas as $index => $entrada) {
        $producto = Producto::find($entrada['producto_id']);

        if(!is_null($entrada['fotos'][0])){//hay fotos
          $fotos = ""; $separador = "";
          foreach ($entrada['fotos'] as $foto_index => $foto) {
            $ruta = Storage::putFileAs(
              'public/cotizaciones/'.$cotizacion->id, $foto,
              'entrada_' .($index+1). '_foto_' .($foto_index+1). '.' .$foto->guessExtension()
            );
            $ruta = str_replace('public/', '', $ruta);
            $fotos.= $separador.$ruta;
            $separador = "|";
          }
          $entrada['fotos'] = $fotos;
        }
        else if($producto->foto){
          $extencion = pathinfo(asset($producto->foto), PATHINFO_EXTENSION);
          $ruta = "cotizaciones/".$cotizacion->id."/entrada_".($index+1)."_foto_1.".$extencion;
          Storage::copy("public/".$producto->foto, "public/$ruta");
          $entrada['fotos'] = $ruta;
        }
        else $entrada['fotos'] = "";
        $entrada['cotizacion_id'] = $cotizacion->id;
        $observaciones = "<ul>";
        foreach ($entrada['observaciones'] as $obs) {
          $observaciones.="<li>$obs</li>";
        }
        $observaciones.= "</ul>";
        $entrada['observaciones'] = ($observaciones=="<ul><li></li></ul>")?"":$observaciones;
        $modelo_entrada = ProspectoCotizacionEntrada::create($entrada);

        foreach ($entrada['descripciones'] as $descripcion) {
          if(!is_null($descripcion['valor'])){
            $descripcion['entrada_id'] = $modelo_entrada->id;
            if(is_null($descripcion['nombre'])) $descripcion['nombre'] = $descripcion['name'];
            if(is_null($descripcion['name'])) $descripcion['name'] = $descripcion['nombre'];
            ProspectoCotizacionEntradaDescripcion::create($descripcion);
          }
        }
      }

      $cotizacion->load('prospecto.cliente', 'condiciones', 'entradas.producto.categoria',
      'entradas.producto.proveedor', 'entradas.descripciones', 'user');
      if($cotizacion->user->firma) $cotizacion->user->firma = storage_path('app/public/'.$cotizacion->user->firma);
      else $cotizacion->user->firma = public_path('images/firma_vacia.png');

      //crear pdf de cotizacion
      $url = 'cotizaciones/'.$cotizacion->id.'/Cotizacion '.$cotizacion->id.' Intercorp '.$prospecto->nombre.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";
      $nombre = ($cotizacion->idioma=='español')?"nombre":"name";

      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
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
        'email' => 'required|array',
        'email.*' => 'email|max:255',
        'mensaje' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $cotizacion = ProspectoCotizacion::with('entradas')->findOrFail($request->cotizacion_id);
      $email = $request->email;
      $pdf_link = asset('storage/'.$cotizacion->archivo);
      $pdf_name = explode('/',$cotizacion->archivo);
      $pdf_name = end($pdf_name);
      $pdf = Storage::disk('public')->get($cotizacion->archivo);
      $user = auth()->user();

      Mail::send('email', ['mensaje' => $request->mensaje], function ($message)
      use ($email, $pdf, $pdf_name, $user){
        $message->to($email)
                ->cc('abraham@intercorp.mx')
                ->cc('omar.herrera@tigears.com')
                ->cc('simonc@789.mx')
                ->replyTo($user->email, $user->name)
                ->subject('Cotización Intercorp');
        $message->attachData($pdf, $pdf_name);
      });

      //generar actividad de envio de cotizacion
      $prospecto = Prospecto::with('proxima_actividad')->find($cotizacion->prospecto_id);
      if($prospecto->proxima_actividad->tipo_id == 4){//Cotización enviada
        $nueva = 3; //Email (Seguimiento)
      }
      else $nueva = $prospecto->proxima_actividad->tipo_id;

      //actualizar proxima actividad
      $prospecto->proxima_actividad->update([
        'tipo_id' => 4,
        'fecha' => date('d/m/Y'),
        'descripcion' => $pdf_link,
        'realizada' => 1
      ]);

      //ingresar productos ofrecidos
      foreach ($cotizacion->entradas as $entrada) {
        $prospecto->proxima_actividad->productos_ofrecidos()->attach($entrada->producto_id);
      }

      //crear nueva proxima actividad
      $create = [
        'prospecto_id' => $prospecto->id,
        'tipo_id' => $nueva,
        'fecha' => date('d/m/Y'),
      ];
      ProspectoActividad::create($create);

      return response()->json(['success'=>true, 'error'=>false], 200);
    }

    /**
     * Marca cotizacion como aceptada por el cliente y genera cuenta por cobrar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function aceptarCotizacion(Request $request, Prospecto $prospecto)
    {
      $validator = Validator::make($request->all(), [
        'cotizacion_id' => 'required',
        'comprobante' => 'required|file|mimes:jpeg,jpg,png,pdf',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $cotizacion = ProspectoCotizacion::with('condiciones', 'entradas.producto.proveedor')->findOrFail($request->cotizacion_id);
      $prospecto->load('cliente');

      //generar proyecto aprobado
      $proveedores = $cotizacion->entradas->groupBy(function($entrada){
        return $entrada->producto->proveedor->empresa;
      })->keys()->all();
      $proveedores = implode(",", $proveedores);

      $create = [
        'cliente_id' => $prospecto->cliente_id,
        'cotizacion_id' => $cotizacion->id,
        'cliente_nombre' => $prospecto->cliente->nombre,
        'proyecto' => $prospecto->nombre,
        'moneda' => $cotizacion->moneda,
        'proveedores' => $proveedores
      ];
      ProyectoAprobado::create($create);

      //generar cuenta por cobrar
      $create = [
        'cliente_id' => $prospecto->cliente_id,
        'cotizacion_id' => $cotizacion->id,
        'cliente' => $prospecto->cliente->nombre,
        'proyecto' => $prospecto->nombre,
        'condiciones' => $cotizacion->condiciones->nombre,
        'moneda' => $cotizacion->moneda,
        'total' => $cotizacion->total,
        'pendiente' => $cotizacion->total,
        'comprobante_confirmacion' => ''
      ];

      $comprobante = Storage::putFileAs(
        'public/cotizaciones/'.$cotizacion->id,
        $request->comprobante,
        'comprobante_confirmacion.'.$request->comprobante->guessExtension()
      );
      $comprobante = str_replace('public/', '', $comprobante);
      $create['comprobante_confirmacion'] = $comprobante;

      CuentaCobrar::create($create);
      $cotizacion->update(['aceptada'=>true]);

      return response()->json(['success'=>true, 'error'=>false], 200);
    }

    /**
     * Actualizar notas (internas) de cotizacion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function notasCotizacion(Request $request, Prospecto $prospecto)
    {
      $validator = Validator::make($request->all(), [
        'cotizacion_id' => 'required',
        'mensaje' => 'present',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $cotizacion = ProspectoCotizacion::findOrFail($request->cotizacion_id);
      $cotizacion->update(['notas2'=>$request->mensaje]);

      return response()->json(['success'=>true, 'error'=>false], 200);
    }

    public function regeneratePDF(Request $request){
      $cotizacion = ProspectoCotizacion::with('prospecto.cliente', 'condiciones',
      'entradas.producto.categoria', 'entradas.producto.proveedor',
      'entradas.descripciones', 'user')->find($request->cotizacion_id);
      if($cotizacion->user->firma)
        $cotizacion->user->firma = storage_path('app/public/'.$cotizacion->user->firma);
      else $cotizacion->user->firma = public_path('images/firma_vacia.png');

      $url = 'cotizaciones/'.$cotizacion->id.'/Cotizacion '.$cotizacion->id.' Intercorp '.$cotizacion->prospecto->nombre.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";

      $nombre = ($cotizacion->idioma=='español')?"nombre":"name";

      // return view('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
      Storage::disk('public')->put($url, $cotizacionPDF->output());
      return $cotizacionPDF->download('cotizacion.pdf');
    }

}
