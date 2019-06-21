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
use Storage;

class OrdenesProcesoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $ordenes = OrdenProceso::with('ordenCompra')->get();

      foreach ($ordenes as $orden) {
        if($orden->ordenCompra->archivo)
          $orden->ordenCompra->archivo = asset('storage/'.$orden->ordenCompra->archivo);
        if($orden->factura){
          $orden->factura = asset('storage/'.$orden->factura);
          $orden->packing = asset('storage/'.$orden->packing);
          if($orden->bl) $orden->bl = asset('storage/'.$orden->bl);
          if($orden->certificado) $orden->certificado = asset('storage/'.$orden->certificado);
        }
        if($orden->deposito_warehouse){
          $orden->deposito_warehouse = asset('storage/'.$orden->deposito_warehouse);
        }
        if($orden->gastos){
          $orden->gastos = asset('storage/'.$orden->gastos);
          $orden->pago = asset('storage/'.$orden->pago);
        }
        if($orden->carta_entrega){
          $orden->carta_entrega = asset('storage/'.$orden->carta_entrega);
        }
      }

      return view('ordenes-proceso.index', compact('ordenes'));
    }


    /**
     * Actualiza status y fecha estimada de status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'status' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status == OrdenProceso::STATUS_ADUANA){
        $update = [
          'fecha_real_aduana' => date('Y-m-d'),
          'status' => OrdenProceso::STATUS_IMPORTACION
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_IMPORTACION){
        $update = [
          'fecha_real_importacion' => date('Y-m-d'),
          'status' => OrdenProceso::STATUS_LIBERADO_ADUANA
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_LIBERADO_ADUANA){
        $update = [
          'fecha_real_liberado_aduana' => date('Y-m-d'),
          'status' => OrdenProceso::STATUS_EMBARCADO_FINAL
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_EMBARCADO_FINAL){
        $update = [
          'fecha_real_embarque_final' => date('Y-m-d'),
          'status' => OrdenProceso::STATUS_DESCARGA
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_DESCARGA){
        $update = [
          'fecha_real_descarga' => date('Y-m-d'),
          'status' => OrdenProceso::STATUS_ENTREGADO
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_ENTREGADO){
        $update = [
          'fecha_real_entrega' => date('Y-m-d'),
        ];
      }

      //actualizar orden
      $orden->update($update);

      return response()->json([
        'success' => true, "error" => false, "actualizados"=>$update
      ], 200);
    }

    /**
     * Actualiza fechas estimadas de la orden.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function fijarFechasEstimadas(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'fecha_estimada_fabricacion' => 'present',
        'fecha_estimada_embarque' => 'present',
        'fecha_estimada_frontera' => 'present',
        'fecha_estimada_aduana' => 'present',
        'fecha_estimada_importacion' => 'present',
        'fecha_estimada_liberado_aduana' => 'present',
        'fecha_estimada_embarque_final' => 'present',
        'fecha_estimada_descarga' => 'present',
        'fecha_estimada_entrega' => 'present',
        'fecha_estimada_instalacion' => 'present'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $update = [];
      //filtrar fechas a actualizar, las que tienen formato dd/mm/yyyy
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_fabricacion)===1){
        $update['fecha_estimada_fabricacion'] = $request->fecha_estimada_fabricacion;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_embarque)===1){
        $update['fecha_estimada_embarque'] = $request->fecha_estimada_embarque;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_frontera)===1){
        $update['fecha_estimada_frontera'] = $request->fecha_estimada_frontera;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_aduana)===1){
        $update['fecha_estimada_aduana'] = $request->fecha_estimada_aduana;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_importacion)===1){
        $update['fecha_estimada_importacion'] = $request->fecha_estimada_importacion;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_liberado_aduana)===1){
        $update['fecha_estimada_liberado_aduana'] = $request->fecha_estimada_liberado_aduana;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_embarque_final)===1){
        $update['fecha_estimada_embarque_final'] = $request->fecha_estimada_embarque_final;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_descarga)===1){
        $update['fecha_estimada_descarga'] = $request->fecha_estimada_descarga;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_entrega)===1){
        $update['fecha_estimada_entrega'] = $request->fecha_estimada_entrega;
      }
      if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $request->fecha_estimada_instalacion)===1){
        $update['fecha_estimada_instalacion'] = $request->fecha_estimada_instalacion;
      }

      //actualizar orden
      $orden->update($update);

      return response()->json([
        'success' => true, "error" => false, "actualizados"=>$update
      ], 200);
    }

    /**
     * Cambia status a Embarcado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function embarcar(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'factura' => 'required|file|mimes:jpeg,jpg,png,pdf',
        'packing' => 'required|file|mimes:jpeg,jpg,png,pdf',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status!=OrdenProceso::STATUS_FABRICACION){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_FABRICACION
        ], 400);
      }

      $update = [
        'status'=>OrdenProceso::STATUS_EMBARCADO,
        'fecha_real_fabricacion' => date('Y-m-d'),
      ];

      $factura = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->factura,
        'factura.'.$request->factura->guessExtension()
      );
      $factura = str_replace('public/', '', $factura);
      $update['factura'] = $factura;
      $packing = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->packing,
        'packing.'.$request->packing->guessExtension()
      );
      $packing = str_replace('public/', '', $packing);
      $update['packing'] = $packing;
      if($request->bl){
        $bl = Storage::putFileAs(
          'public/ordenes_proceso/'.$orden->id,
          $request->bl,
          'bl.'.$request->bl->guessExtension()
        );
        $bl = str_replace('public/', '', $bl);
        $update['bl'] = $bl;
      }
      if($request->certificado){
        $certificado = Storage::putFileAs(
          'public/ordenes_proceso/'.$orden->id,
          $request->certificado,
          'certificado.'.$request->certificado->guessExtension()
        );
        $certificado = str_replace('public/', '', $certificado);
        $update['certificado'] = $certificado;
      }

      //actualizar orden
      $orden->update($update);
      $orden->factura = asset('storage/'.$orden->factura);
      $orden->packing = asset('storage/'.$orden->packing);
      if($orden->bl) $orden->bl = asset('storage/'.$orden->bl);
      if($orden->certificado) $orden->certificado = asset('storage/'.$orden->certificado);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a Frontera.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function frontera(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'deposito_warehouse' => 'required|file|mimes:jpeg,jpg,png,pdf'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status!=OrdenProceso::STATUS_EMBARCADO){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_EMBARCADO
        ], 400);
      }

      $update = [
        'status'=>OrdenProceso::STATUS_FRONTERA,
        'fecha_real_embarque' => date('Y-m-d'),
      ];

      $deposito_warehouse = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->deposito_warehouse,
        'deposito_warehouse.'.$request->deposito_warehouse->guessExtension()
      );
      $deposito_warehouse = str_replace('public/', '', $deposito_warehouse);
      $update['deposito_warehouse'] = $deposito_warehouse;

      //actualizar orden
      $orden->update($update);
      $orden->deposito_warehouse = asset('storage/'.$orden->deposito_warehouse);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a Aduana.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function aduana(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'gastos' => 'required|file|mimes:jpeg,jpg,png,pdf',
        'pago' => 'required|file|mimes:jpeg,jpg,png,pdf',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status!=OrdenProceso::STATUS_FRONTERA){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_FRONTERA
        ], 400);
      }

      $update = [
        'status'=>OrdenProceso::STATUS_ADUANA,
        'fecha_real_frontera' => date('Y-m-d'),
      ];

      $gastos = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->gastos,
        'gastos.'.$request->gastos->guessExtension()
      );
      $gastos = str_replace('public/', '', $gastos);
      $update['gastos'] = $gastos;
      $pago = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->pago,
        'pago.'.$request->pago->guessExtension()
      );
      $pago = str_replace('public/', '', $pago);
      $update['pago'] = $pago;

      //actualizar orden
      $orden->update($update);
      $orden->gastos = asset('storage/'.$orden->gastos);
      $orden->pago = asset('storage/'.$orden->pago);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a Entregado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function entrega(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'carta_entrega' => 'required|file|mimes:jpeg,jpg,png,pdf'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status!=OrdenProceso::STATUS_DESCARGA){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_DESCARGA
        ], 400);
      }

      $update = [
        'status'=>OrdenProceso::STATUS_ENTREGADO,
        'fecha_real_descarga' => date('Y-m-d'),
      ];

      $carta_entrega = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->carta_entrega,
        'carta_entrega.'.$request->carta_entrega->guessExtension()
      );
      $carta_entrega = str_replace('public/', '', $carta_entrega);
      $update['carta_entrega'] = $carta_entrega;

      //actualizar orden
      $orden->update($update);
      $orden->$carta_entrega = asset('storage/'.$orden->$carta_entrega);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

}
