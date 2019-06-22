<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\ProyectoAprobado;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraEntrada;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\OrdenProceso;
use App\Models\CuentaPagar;
use App\User;
use Mail;
use PDF;
use Storage;

class OrdenesCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function index($proyecto)
    {
      $ordenes = OrdenCompra::with('entradas.producto')
        ->where('proyecto_id',$proyecto)
        ->get();

      foreach ($ordenes as $orden) {
        if($orden->archivo) $orden->archivo = asset('storage/'.$orden->archivo);
      }

      return view('ordenes-compra.index', compact('ordenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function create(ProyectoAprobado $proyecto)
    {
      $proveedores = Proveedor::all();
      $productos = Producto::with('categoria')->has('categoria')->get();

      return view('ordenes-compra.create', compact('proyecto','proveedores','productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProyectoAprobado $proyecto)
    {
      $validator = Validator::make($request->all(), [
        'proyecto_id' => 'required',
        'proveedor_id' => 'required',
        'moneda' => 'required',
        'subtotal' => 'required',
        'iva' => 'required',
        'total' => 'required',
        'entradas' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $create = $request->except('entradas');
      $create['cliente_id'] = $proyecto->cliente_id;
      $create['cliente_nombre'] = $proyecto->cliente_nombre;
      $create['proyecto_nombre'] = $proyecto->proyecto;

      if($request->iva=="1"){
        $create['iva'] = bcmul($create['subtotal'], 0.16, 2);
        $create['total'] = bcmul($create['subtotal'], 1.16, 2);
      }
      else {
        $create['total'] = $create['subtotal'];
      }

      $orden = OrdenCompra::create($create);

      //guardar entradas
      foreach ($request->entradas as $entrada) {
        $entrada['orden_id'] = $orden->id;
        OrdenCompraEntrada::create($entrada);
      }

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @param  \App\Models\OrdenCompra  $orden
     * @return \Illuminate\Http\Response
     */
    public function show(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
      $orden->load('proveedor', 'entradas.producto');

      return view('ordenes-compra.show', compact('proyecto','orden'));
    }

    /**
     * Cambia status a Por Autorizar.
     *
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @param  \App\Models\OrdenCompra  $orden
     * @return \Illuminate\Http\Response
     */
    public function comprar(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
      if($orden->status!=OrdenCompra::STATUS_PENDIENTE){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '.
            OrdenCompra::STATUS_PENDIENTE.
            ' para poder ser comprada'
        ], 400);
      }

      $this->avisarOrdenPorAprobar($orden);

      //generar PDF de orden
      $orden->load('proveedor.contactos', 'proyecto.cotizacion',
      'proyecto.cliente', 'entradas.producto.descripciones.descripcionNombre');
      $firmaAbraham = User::select('firma')->where('id',2)->first()->firma;
      if($firmaAbraham) $firmaAbraham = storage_path('app/public/'.$firmaAbraham);
      else $firmaAbraham = public_path('images/firma_vacia.png');
      $orden->firmaAbraham = $firmaAbraham;

      $url = 'ordenes_compra/'.$orden->id.'/orden_'.$orden->id.'.pdf';
      // $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      // 'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      $meses = ['JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY',
      'AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER'];
      list($ano,$mes,$dia) = explode('-', date('Y-m-d'));
      $mes = $meses[+$mes-1];
      // $orden->fechaPDF = "$dia DE $mes DEL $ano";
      $orden->fechaPDF = "$mes $dia, $ano";

      foreach ($orden->entradas as $entrada) {
        if($entrada->producto->foto) $entrada->producto->foto = asset('storage/'.$entrada->producto->foto);
      }

      $ordenPDF = PDF::loadView('ordenes-compra.ordenPDF', compact('orden'));
      Storage::disk('public')->put($url, $ordenPDF->output());
      unset($orden->fechaPDF);
      unset($orden->firmaAbraham);
      $orden->update(['status'=>'Por Autorizar', 'archivo'=>$url]);

      return response()->json(['success' => true, "error" => false], 200);
    }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Models\ProyectoAprobado  $proyecto
      * @param  \App\Models\OrdenCompra  $orden
      * @return \Illuminate\Http\Response
      */
     public function edit(ProyectoAprobado $proyecto, OrdenCompra $orden)
     {
       $productos = Producto::with('categoria')->has('categoria')->get();
       $orden->load('proveedor', 'entradas.producto');
       if($orden->iva>0) $orden->iva = 1;

       return view('ordenes-compra.edit', compact('proyecto','orden','productos'));
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @param  \App\Models\OrdenCompra  $orden
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
      $validator = Validator::make($request->all(), [
        'proyecto_id' => 'required',
        'proveedor_id' => 'required',
        'moneda' => 'required',
        'subtotal' => 'required',
        'iva' => 'required',
        'total' => 'required',
        'entradas' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $update = ['subtotal'=>$request->subtotal];
      if($request->iva=="1"){
        $update['iva'] = bcmul($update['subtotal'], 0.16, 2);
        $update['total'] = bcmul($update['subtotal'], 1.16, 2);
      }
      else {
        $update['iva'] = 0;
        $update['total'] = $update['subtotal'];
      }
      if($orden->status==OrdenCompra::STATUS_RECHAZADA){
        $update['status'] = OrdenCompra::STATUS_POR_AUTORIZAR;
      }

      $orden->update($update);

      //sincronizar entradas
      foreach ($request->entradas as $entrada) {
        if(isset($entrada['id'])){
          $ent = OrdenCompraEntrada::find($entrada['id']);

          if(isset($entrada['borrar'])){//borrar entrada
            $ent->delete();
            continue ;
          }

          //actualizar entrada ya guardada
          unset($entrada['id']);
          if($entrada['cantidad_convertida']==""){
            unset($entrada['conversion']);
            unset($entrada['cantidad_convertida']);
          }
          $ent->update($entrada);
          continue ;
        }

        //crear nueva entrada
        $entrada['orden_id'] = $orden->id;
        OrdenCompraEntrada::create($entrada);
      }

      if($orden->status==OrdenCompra::STATUS_RECHAZADA){
        $this->avisarOrdenPorAprobar($orden);
      }

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @param  \App\Models\OrdenCompra  $orden
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
      if($orden->status==OrdenCompra::STATUS_APROBADA){
        return response()->json(['success' => false, "error" => true,
          'message'=>'No se puede cancelar la orden porque esta en estatus '
            .OrdenCompra::STATUS_APROBADA
        ], 400);
      }
      $orden->update(['status'=>OrdenCompra::STATUS_CANCELADA]);

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Cambia status a Aprobada.
     *
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @param  \App\Models\OrdenCompra  $orden
     * @return \Illuminate\Http\Response
     */
    public function aprobar(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
      if($orden->status!=OrdenCompra::STATUS_POR_AUTORIZAR){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenCompra::STATUS_POR_AUTORIZAR
            .' para poder ser rechazada'
        ], 400);
      }

      //generar numero de orden (proceso)
      if($orden->proveedor->nacional){
        $hoy = date('d/m/Y');
        $hoy2 = date('Y-m-d');
        $numero = OrdenProceso::create([
          'orden_compra_id'=>$orden->id,
          'status' => OrdenProceso::STATUS_DESCARGA,
          'fecha_estimada_fabricacion' => $hoy, 'fecha_real_fabricacion' => $hoy2,
          'fecha_estimada_embarque' => $hoy, 'fecha_real_embarque' => $hoy2,
          'fecha_estimada_frontera' => $hoy, 'fecha_real_frontera' => $hoy2,
          'fecha_estimada_aduana' => $hoy, 'fecha_real_aduana' => $hoy2,
          'fecha_estimada_importacion' => $hoy, 'fecha_real_importacion' => $hoy2,
          'fecha_estimada_liberado_aduana' => $hoy,'fecha_real_liberado_aduana' => $hoy2,
          'fecha_estimada_embarque_final' => $hoy, 'fecha_real_embarque_final' => $hoy2,
        ]);
      }
      else {
        $numero = OrdenProceso::create(['orden_compra_id'=>$orden->id]);
      }

      //generar cuenta por pagar
      $create = [
        'proveedor_id' => $orden->proveedor_id,
        'orden_compra_id' => $orden->id,
        'proveedor_empresa' => $orden->proveedor_empresa,
        'proyecto_nombre' => $orden->proyecto_nombre,
        'moneda' => $orden->moneda,
        'dias_credito' => $orden->proveedor->dias_credito,
        'total' => $orden->total,
        'pendiente' => $orden->total,
      ];
      CuentaPagar::create($create);

      //actualizar orden
      $orden->update([
        'status'=>OrdenCompra::STATUS_APROBADA,
        'orden_proceso_id'=>$numero->id
      ]);

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Cambia status a Rechazada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoAprobado  $proyecto
     * @param  \App\Models\OrdenCompra  $orden
     * @return \Illuminate\Http\Response
     */
    public function rechazar(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
      $validator = Validator::make($request->all(), ['motivo' => 'required']);
      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status!=OrdenCompra::STATUS_POR_AUTORIZAR){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenCompra::STATUS_POR_AUTORIZAR
            .' para poder ser rechazada'
        ], 400);
      }
      $orden->update([
        'status'=>OrdenCompra::STATUS_RECHAZADA,
        'motivo_rechazo'=>$request->motivo
      ]);

      $this->avisarOrdenRechazada($orden);

      return response()->json(['success' => true, "error" => false], 200);
    }

    /*
     * Envia mensaje de aviso de nueva orden por aprobar por correo a
     * abraham@intercorp.mx
     * @param  \App\Models\OrdenCompra  $orden
     */
    public function avisarOrdenPorAprobar($orden){
      $mensaje = "Hay una nueva orden por autorizar de parte del usuario: ".auth()->user()->name;
      $mensaje.= ", para el proyecto ".$orden->proyecto_nombre;
      Mail::send('email', ['mensaje'=>$mensaje], function ($message){
        $message->to('abraham@intercorp.mx')
                ->cc('omar.herrera@tigears.com')
                ->cc('simonc@789.mx')
                ->subject('Nueva orden por autorizar');
      });
    }

    /*
     * Envia mensaje de aviso de orden rechazada por correo a usuario dueño del proyecto
     * @param  \App\Models\OrdenCompra  $orden
     */
    public function avisarOrdenRechazada($orden){
      $mensaje = "Abraham ha rechazado su orden para el proyecto ".$orden->proyecto_nombre;
      $mensaje.= "<br />Motivo: ".$orden->motivo_rechazo;
      $email = $orden->proyecto->cotizacion->user->email;
      Mail::send('email', ['mensaje'=>$mensaje], function ($message) use ($email){
        $message->to($email)
                ->cc('omar.herrera@tigears.com')
                ->cc('simonc@789.mx')
                ->subject('Su orden ha sido rechazada');
      });
    }

    /**
     * Para crear pdf de una orden a voluntad.
     *
     */
    public function regeneratePDF(Request $request)
    {
      $orden = OrdenCompra::with('proveedor.contactos', 'proyecto.cotizacion',
      'proyecto.cliente', 'entradas.producto.descripciones.descripcionNombre')
      ->where('id', $request->orden_id)->first();
      $firmaAbraham = User::select('firma')->where('id',2)->first()->firma;
      if($firmaAbraham) $firmaAbraham = storage_path('app/public/'.$firmaAbraham);
      else $firmaAbraham = public_path('images/firma_vacia.png');
      $orden->firmaAbraham = $firmaAbraham;

      $url = 'ordenes_compra/'.$orden->id.'/orden_'.$orden->id.'.pdf';
      $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO',
      'AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
      list($ano,$mes,$dia) = explode('-', date('Y-m-d'));
      $mes = $meses[+$mes-1];
      $orden->fechaPDF = "$dia DE $mes DEL $ano";

      foreach ($orden->entradas as $entrada) {
        if($entrada->producto->foto) $entrada->producto->foto = asset('storage/'.$entrada->producto->foto);
      }

      $ordenPDF = PDF::loadView('ordenes-compra.ordenPDF', compact('orden'));
      Storage::disk('public')->put($url, $ordenPDF->output());
      $orden->update(['archivo'=>$url]);

      return $ordenPDF->download('orden.pdf');
    }

}
