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
          $orden->bl = asset('storage/'.$orden->bl);
          $orden->certificado = asset('storage/'.$orden->certificado);
        }
        if($orden->gastos){
          $orden->gastos = asset('storage/'.$orden->gastos);
          $orden->pago = asset('storage/'.$orden->pago);
        }
      }

      return view('ordenes-proceso.index', compact('ordenes'));
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
        'bl' => 'required|file|mimes:jpeg,jpg,png,pdf',
        'certificado' => 'required|file|mimes:jpeg,jpg,png,pdf',
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

      $update = ['status'=>OrdenProceso::STATUS_EMBARCADO];

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
      $bl = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->bl,
        'bl.'.$request->bl->guessExtension()
      );
      $bl = str_replace('public/', '', $bl);
      $update['bl'] = $bl;
      $certificado = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->certificado,
        'certificado.'.$request->certificado->guessExtension()
      );
      $certificado = str_replace('public/', '', $certificado);
      $update['certificado'] = $certificado;

      //actualizar orden
      $orden->update($update);
      $orden->factura = asset('storage/'.$orden->factura);
      $orden->packing = asset('storage/'.$orden->packing);
      $orden->bl = asset('storage/'.$orden->bl);
      $orden->certificado = asset('storage/'.$orden->certificado);

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

      if($orden->status!=OrdenProceso::STATUS_EMBARCADO){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_EMBARCADO
        ], 400);
      }

      $update = ['status'=>OrdenProceso::STATUS_ADUANA];

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
     * Cambia status a Proceso de Importacion.
     *
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function importar(OrdenProceso $orden)
    {
      if($orden->status!=OrdenProceso::STATUS_ADUANA){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_ADUANA
        ], 400);
      }

      //actualizar orden
      $orden->update(['status'=>OrdenProceso::STATUS_IMPORTACION]);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a Liberado de Aduana.
     *
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function liberar(OrdenProceso $orden)
    {
      if($orden->status!=OrdenProceso::STATUS_IMPORTACION){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_IMPORTACION
        ], 400);
      }

      //actualizar orden
      $orden->update(['status'=>OrdenProceso::STATUS_LIBERADO_ADUANA]);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a STATUS_EMBARCADO_FINAL.
     *
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function embarqueFinal(OrdenProceso $orden)
    {
      if($orden->status!=OrdenProceso::STATUS_LIBERADO_ADUANA){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_LIBERADO_ADUANA
        ], 400);
      }

      //actualizar orden
      $orden->update(['status'=>OrdenProceso::STATUS_EMBARCADO_FINAL]);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a STATUS_DESCARGA.
     *
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function descargar(OrdenProceso $orden)
    {
      if($orden->status!=OrdenProceso::STATUS_EMBARCADO_FINAL){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_EMBARCADO_FINAL
        ], 400);
      }

      //actualizar orden
      $orden->update(['status'=>OrdenProceso::STATUS_DESCARGA]);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }

    /**
     * Cambia status a STATUS_ENTREGADO.
     *
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function entregar(OrdenProceso $orden)
    {
      if($orden->status!=OrdenProceso::STATUS_DESCARGA){
        return response()->json(['success' => false, "error" => true,
          'message'=>'La orden debe estar en estatus '
            .OrdenProceso::STATUS_DESCARGA
        ], 400);
      }

      //actualizar orden
      $orden->update(['status'=>OrdenProceso::STATUS_ENTREGADO]);

      return response()->json([
        'success' => true, "error" => false, 'orden'=>$orden
      ], 200);
    }


}
