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
use App\Models\UnidadMedida;
use App\Models\CuentaCobrar;
use App\Models\ProyectoAprobado;
use Validator;
use PDF;
use PDFMerger;
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
      ->has('cliente')->get();

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
        'ultima_actividad.tipo_id' => 'required_with:ultima_actividad.fecha',
        'ultima_actividad.tipo' => 'required_if:ultima_actividad.tipo_id,0',
        'ultima_actividad.fecha' => 'required_with:ultima_actividad.tipo_id|date_format:d/m/Y',
        'proxima_actividad.tipo_id' =>
          'required_with_all:proxima_actividad.fecha,ultima_actividad.tipo_id,ultima_actividad.fecha',
        'proxima_actividad.tipo' => 'required_if:proxima_actividad.tipo_id,0',
        'proxima_actividad.fecha' =>
          'required_with_all:proxima_actividad.tipo_id,ultima_actividad.tipo_id,ultima_actividad.fecha|date_format:d/m/Y',
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
      if(isset($request->ultima_actividad)){
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
        if(isset($request->proxima_actividad)){
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
        }
      }

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

      if(is_null($prospecto->proxima_actividad)){
        $prospecto->proxima_actividad = false;
      }

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
        'nombre' => 'required',
        'descripcion' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $prospecto->update($request->all());

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospecto  $prospecto
     * @return \Illuminate\Http\Response
     */
    public function guardarActividades(Request $request, Prospecto $prospecto)
    {
      $validator = Validator::make($request->all(), [
        'proxima' => 'present',
        'nueva.tipo_id' => 'required',
        'nueva.fecha' => 'required|date_format:d/m/Y',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if(!is_null($request->proxima)){
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
      }
      else $proxima = false;

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
      $prospecto->load('cliente','cotizaciones.entradas.producto','cotizaciones.entradas.descripciones');
      $productos = Producto::with('categoria','proveedor','descripciones.descripcionNombre')
      ->has('categoria')->get();
      $condiciones = CondicionCotizacion::all();
      $unidades_medida = UnidadMedida::orderBy('simbolo')->get();

      foreach ($prospecto->cotizaciones as $cotizacion) {
        if($cotizacion->archivo) $cotizacion->archivo = asset('storage/'.$cotizacion->archivo);
      }
      foreach ($productos as $producto) {
        if($producto->foto) $producto->foto = asset('storage/'.$producto->foto);
      }

      return view('prospectos.cotizar', compact('prospecto', 'productos', 'condiciones','unidades_medida'));
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
      if(!$cotizacion->numero) $cotizacion->numero = $cotizacion->id;

      //guardar entradas
      $fichas = [];
      foreach ($request->entradas as $index => $entrada) {
        $producto = Producto::find($entrada['producto_id']);
        if($producto->ficha_tecnica) $fichas[] = storage_path('app/public/'.$producto->ficha_tecnica);

        if($entrada['fotos']){//hay fotos
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
        $entrada['orden'] = $index + 1;
        $modelo_entrada = ProspectoCotizacionEntrada::create($entrada);

        foreach ($entrada['descripciones'] as $descripcion) {
          $descripcion['entrada_id'] = $modelo_entrada->id;
          if(is_null($descripcion['nombre'])) $descripcion['nombre'] = $descripcion['name'];
          if(is_null($descripcion['name'])) $descripcion['name'] = $descripcion['nombre'];
          ProspectoCotizacionEntradaDescripcion::create($descripcion);
        }
      }

      $cotizacion->load('prospecto.cliente', 'condiciones', 'entradas.producto.categoria',
      'entradas.producto.proveedor', 'entradas.descripciones', 'user');
      if($cotizacion->user->firma) $cotizacion->user->firma = storage_path('app/public/'.$cotizacion->user->firma);
      else $cotizacion->user->firma = public_path('images/firma_vacia.png');

      //crear pdf de cotizacion
      $url = 'cotizaciones/'.$cotizacion->id.'/C '.$cotizacion->numero.' Intercorp '.$prospecto->nombre.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";
      $nombre = ($cotizacion->idioma=='español')?"nombre":"name";

      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
      Storage::disk('public')->put($url, $cotizacionPDF->output());

      $pdf = new PDFMerger();
      $pdf->addPDF(storage_path("app/public/$url"), 'all');
      $fichas = array_unique($fichas, SORT_STRING);
      foreach ($fichas as $ficha) $pdf->addPDF($ficha, 'all');
      $pdf->merge('file', storage_path("app/public/$url"));

      unset($cotizacion->fechaPDF);
      $cotizacion->update(['archivo'=>$url]);
      $cotizacion->archivo = asset('storage/'.$cotizacion->archivo);

      return response()->json(['success'=>true,'error'=>false,'cotizacion'=>$cotizacion], 200);
    }

    /**
     * Editar cotizacion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prospecto  $prospecto
     * @param  \App\Models\ProspectoCotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function cotizacionUpdate(Request $request, Prospecto $prospecto, ProspectoCotizacion $cotizacion)
    {
      $validator = Validator::make($request->all(), [
        'prospecto_id' => 'required',
        'cotizacion_id' => 'required',
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

      $user = auth()->user();

      $update = $request->except('entradas', 'condicion', 'observaciones','prospecto_id','cotizacion_id');
      $update['user_id'] = $user->id;
      $update['fecha'] = date('Y-m-d');
      if($request->condicion['id']==0){//nueva condicion, dar de alta
        $condicion = CondicionCotizacion::create(['nombre'=>$request->condicion['nombre']]);
        $update['condicion_id'] = $condicion->id;
      }
      else $update['condicion_id'] = $request->condicion['id'];
      if($request->iva=="1"){
        $update['iva'] = bcmul($update['subtotal'], 0.16, 2);
        $update['total'] = bcmul($update['subtotal'], 1.16, 2);
      }
      else {
        $update['total'] = $update['subtotal'];
      }
      $observaciones = "<ul>";
      foreach ($request->observaciones as $obs) {
        $observaciones.="<li>$obs</li>";
      }
      $observaciones.= "</ul>";
      $update['observaciones'] = $observaciones;

      if(!$update['numero']) $update['numero'] = $cotizacion->id;
      $cotizacion->update($update);

      //guardar entradas
      foreach ($request->entradas as $index => $entrada) {
        if(isset($entrada['borrar'])){
          ProspectoCotizacionEntrada::destroy($entrada['id']);
        }
        else if(isset($entrada['id']) && isset($entrada['actualizar'])){
          //recuperar entrada para actualizar
          $entradaGuardada = ProspectoCotizacionEntrada::find($entrada['id']);
          unset($entrada['id']);
        }
        else if(isset($entrada['id']) && !isset($entrada['actualizar'])) continue;
        else if(!isset($entrada['id'])) $entradaGuardada = false;

        $producto = Producto::find($entrada['producto_id']);
        if($entrada['fotos'] && !is_string($entrada['fotos'][0])){//hay fotos
          $fotos = ""; $separador = "";
          foreach ($entrada['fotos'] as $foto_index => $foto) {
            //borrar archivo actual, si existe
            Storage::disk('public')->delete('cotizaciones/'.$cotizacion->id.'/entrada_' .($index+1). '_foto_' .($foto_index+1). '.' .$foto->guessExtension());
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
        else if($entradaGuardada && $entradaGuardada->producto_id==$producto->id){
          unset($entrada['fotos']); //no se actualzan fotos
        }
        else if($producto->foto){
          $extencion = pathinfo(asset($producto->foto), PATHINFO_EXTENSION);
          $ruta = "cotizaciones/".$cotizacion->id."/entrada_".($index+1)."_foto_1.".$extencion;
          Storage::disk('public')->delete($ruta);//borrar archivo actual, si existe
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
        $entrada['observaciones'] = ($observaciones=="<ul></ul>")?"":$observaciones;

        if($entradaGuardada){
          if($entradaGuardada->producto_id==$producto->id){
            //mismo producto, solo actualizar descripciones
            foreach ($entrada['descripciones'] as $descripcion) {
              ProspectoCotizacionEntradaDescripcion::find($descripcion['id'])
              ->update(['valor'=>$descripcion['valor']]);
            }
          }
          else {
            $entradaGuardada->load('descripciones');
            foreach ($entradaGuardada->descripciones as $desc) {
              $desc->delete();
            }
            unset($entradaGuardada->descripciones);

            foreach ($entrada['descripciones'] as $descripcion) {
              $descripcion['entrada_id'] = $entradaGuardada->id;
              if(is_null($descripcion['nombre'])) $descripcion['nombre'] = $descripcion['name'];
              if(is_null($descripcion['name'])) $descripcion['name'] = $descripcion['nombre'];
              ProspectoCotizacionEntradaDescripcion::create($descripcion);
            }
          }
          $entradaGuardada->update($entrada);
        }
        else {
          $modelo_entrada = ProspectoCotizacionEntrada::create($entrada);
          foreach ($entrada['descripciones'] as $descripcion) {
            $descripcion['entrada_id'] = $modelo_entrada->id;
            if(is_null($descripcion['nombre'])) $descripcion['nombre'] = $descripcion['name'];
            if(is_null($descripcion['name'])) $descripcion['name'] = $descripcion['nombre'];
            ProspectoCotizacionEntradaDescripcion::create($descripcion);
          }
        }
      }//foreach $request->entradas

      $cotizacion->load('prospecto.cliente', 'condiciones', 'entradas.producto.categoria',
      'entradas.producto.proveedor', 'entradas.descripciones', 'user');
      if($cotizacion->user->firma) $cotizacion->user->firma = storage_path('app/public/'.$cotizacion->user->firma);
      else $cotizacion->user->firma = public_path('images/firma_vacia.png');

      //crear pdf de cotizacion
      $url = 'cotizaciones/'.$cotizacion->id.'/C '.$cotizacion->numero.' Intercorp '.$prospecto->nombre.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";
      $nombre = ($cotizacion->idioma=='español')?"nombre":"name";

      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
      Storage::disk('public')->put($url, $cotizacionPDF->output());

      $fichas = [];
      foreach ($cotizacion->entradas as $entrada) {
        if($entrada->producto->ficha_tecnica)
          $fichas[] = storage_path('app/public/'.$entrada->producto->ficha_tecnica);
      }
      $pdf = new PDFMerger();
      $pdf->addPDF(storage_path("app/public/$url"), 'all');
      $fichas = array_unique($fichas, SORT_STRING);
      foreach ($fichas as $ficha) $pdf->addPDF($ficha, 'all');
      $pdf->merge('file', storage_path("app/public/$url"));

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

      $cotizacion = ProspectoCotizacion::with('entradas','prospecto')->findOrFail($request->cotizacion_id);
      $email = $request->email;
      // $pdf_name = basename($cotizacion->archivo);
      $pdf_name = 'C '.$cotizacion->numero.' Intercorp '.$cotizacion->prospecto->nombre.'.pdf';
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
      $this->registrarActividadDeCotizacionEnviada($cotizacion);

      return response()->json(['success'=>true, 'error'=>false], 200);
    }

    private function registrarActividadDeCotizacionEnviada($cotizacion){
      $pdf_link = asset('storage/'.$cotizacion->archivo);
      $prospecto = Prospecto::with('proxima_actividad')->find($cotizacion->prospecto_id);

      if(!is_null($prospecto->proxima_actividad)){
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
      }
      else {
        $create = [
          'prospecto_id' => $prospecto->id,
          'tipo_id' => 4,//Cotización enviada
          'fecha' => date('d/m/Y'),
          'descripcion' => $pdf_link,
          'realizada' => 1
        ];
        $actividad = ProspectoActividad::create($create);

        //ingresar productos ofrecidos
        foreach ($cotizacion->entradas as $entrada) {
          $actividad->productos_ofrecidos()->attach($entrada->producto_id);
        }
      }
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

      $url = 'cotizaciones/'.$cotizacion->id.'/C '.$cotizacion->id.' Intercorp '.$cotizacion->prospecto->nombre.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', $cotizacion->fecha);
      $mes = $meses[+$mes-1];
      $cotizacion->fechaPDF = "$mes $dia, $ano";

      $nombre = ($cotizacion->idioma=='español')?"nombre":"name";

      // return view('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
      $cotizacionPDF = PDF::loadView('prospectos.cotizacionPDF', compact('cotizacion', 'nombre'));
      Storage::disk('public')->put($url, $cotizacionPDF->output());

      $fichas = [];
      foreach ($cotizacion->entradas as $entrada) {
        if($entrada->producto->ficha_tecnica)
          $fichas[] = storage_path('app/public/'.$entrada->producto->ficha_tecnica);
      }

      $pdf = new PDFMerger();
      $pdf->addPDF(storage_path('app/public/'.$url), 'all');
      $fichas = array_unique($fichas, SORT_STRING);
      foreach ($fichas as $ficha) $pdf->addPDF($ficha, 'all');
      $pdf->merge('file', storage_path("app/public/$url"));

      unset($cotizacion->fechaPDF);
      $cotizacion->update(['archivo'=>$url]);

      $pdf->merge('download', "cotizacion.pdf");
    }

}
