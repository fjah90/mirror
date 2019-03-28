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
      $productos = Producto::with('categoria')->get();

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
      if($orden->status!='Pendiente'){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus "Pendiente" para poder ser comprada'
        ], 400);
      }
      $orden->update(['status'=>'Por Autorizar']);

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
       $productos = Producto::with('categoria')->get();
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
      if($orden->status=='Rechazada'){
        $update['status'] = 'Por Autorizar';
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
      if($orden->status=='Aprobada'){
        return response()->json(['success' => false, "error" => true,
          'message'=>'No se puede cancelar la orden porque ya esta Aprobada'
        ], 400);
      }
      $orden->update(['status'=>'Cancelada']);

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
      if($orden->status!='Por Autorizar'){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus "Por Autorizar" para poder ser rechazada'
        ], 400);
      }

      //generar numero de orden (proceso)
      $numero = OrdenProceso::create(['orden_compra_id'=>$orden->id]);

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
        'status'=>'Aprobada',
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

      if($orden->status!='Por Autorizar'){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus "Por Autorizar" para poder ser rechazada'
        ], 400);
      }
      $orden->update([
        'status'=>'Rechazada',
        'motivo_rechazo'=>$request->motivo
      ]);

      return response()->json(['success' => true, "error" => false], 200);
    }

}
