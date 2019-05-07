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
     * Actualiza status y fecha estimada de status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenProceso  $orden
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, OrdenProceso $orden)
    {
      $validator = Validator::make($request->all(), [
        'fecha_estimada' => 'required|date_format:d/m/Y',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      if($orden->status == OrdenProceso::STATUS_FABRICACION){
        $update = ['fecha_estimada_fabricacion'=>$request->fecha_estimada];
      }
      else if($orden->status == OrdenProceso::STATUS_ADUANA){
        $update = [
          'status' => OrdenProceso::STATUS_IMPORTACION,
          'fecha_estimada_importacion'=>$request->fecha_estimada,
          'fecha_real_aduana' => date('Y-m-d')
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_IMPORTACION){
        $update = [
          'status' => OrdenProceso::STATUS_LIBERADO_ADUANA,
          'fecha_estimada_liberado_aduana'=>$request->fecha_estimada,
          'fecha_real_importacion' => date('Y-m-d')
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_LIBERADO_ADUANA){
        $update = [
          'status' => OrdenProceso::STATUS_EMBARCADO_FINAL,
          'fecha_estimada_embarque_final'=>$request->fecha_estimada,
          'fecha_real_liberado_aduana' => date('Y-m-d')
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_EMBARCADO_FINAL){
        $update = [
          'status' => OrdenProceso::STATUS_DESCARGA,
          'fecha_estimada_descarga'=>$request->fecha_estimada,
          'fecha_real_embarque_final' => date('Y-m-d')
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_DESCARGA){
        $update = [
          'status' => OrdenProceso::STATUS_ENTREGADO,
          'fecha_estimada_entrega'=>$request->fecha_estimada,
          'fecha_real_descarga' => date('Y-m-d')
        ];
      }
      else if($orden->status == OrdenProceso::STATUS_ENTREGADO){
        $update = [
          'fecha_estimada_instalacion'=>$request->fecha_estimada,
          'fecha_real_entrega' => date('Y-m-d')
        ];
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
        'bl' => 'required|file|mimes:jpeg,jpg,png,pdf',
        'fecha_estimada' => 'required|date_format:d/m/Y',
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
        'fecha_estimada_embarque' => $request->fecha_estimada
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
      $bl = Storage::putFileAs(
        'public/ordenes_proceso/'.$orden->id,
        $request->bl,
        'bl.'.$request->bl->guessExtension()
      );
      $bl = str_replace('public/', '', $bl);
      $update['bl'] = $bl;
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
      $orden->bl = asset('storage/'.$orden->bl);
      if($orden->certificado) $orden->certificado = asset('storage/'.$orden->certificado);

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
        'fecha_estimada' => 'required|date_format:d/m/Y',
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
        'status'=>OrdenProceso::STATUS_ADUANA,
        'fecha_real_embarque' => date('Y-m-d'),
        'fecha_estimada_aduana' => $request->fecha_estimada
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

    
}
