<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyectoAprobado;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraEntrada;
use Validator;

class ProyectosAprobadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $proyectos = ProyectoAprobado::with('cotizacion')->get();
      foreach ($proyectos as $proyecto) {
        $proyecto->cotizacion->archivo = asset('storage/'.$proyecto->cotizacion->archivo);
      }

      return view('proyectos_aprobados.index', compact('proyectos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CuentaCobrar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function show(CuentaCobrar $cuenta)
    {
      $cuenta->load('facturas.pagos');
      foreach ($cuenta->facturas as $factura) {
        $factura->pdf = asset('storage/'.$factura->pdf);
        $factura->xml = asset('storage/'.$factura->xml);

        foreach ($factura->pagos as $pago) {
          if($pago->comprobante) $pago->comprobante = asset('storage/'.$pago->comprobante);
        }
      }

      return view('cuentas-cobrar.show', compact('cuenta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CuentaCobrar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function edit(CuentaCobrar $cuenta)
    {
      $cuenta->load('facturas.pagos');
      foreach ($cuenta->facturas as $factura) {
        $factura->pdf = asset('storage/'.$factura->pdf);
        $factura->xml = asset('storage/'.$factura->xml);

        foreach ($factura->pagos as $pago) {
          if($pago->comprobante) $pago->comprobante = asset('storage/'.$pago->comprobante);
        }
      }

      return view('cuentas-cobrar.edit', compact('cuenta'));
    }

    /**
     * Agrega una factura a cuenta por cobrar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CuentaCobrar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function facturar(Request $request, CuentaCobrar $cuenta)
    {
      $validator = Validator::make($request->all(), [
        'cuenta_id' => 'required',
        'documento' => 'required',
        'monto' => 'required|numeric',
        'vencimiento' => 'required|date_format:d/m/Y',
        'pdf' => 'required|file|mimes:pdf',
        'xml' => 'required|file|mimes:xml',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 400);
      }

      $facturado = $cuenta->facturado + $request->monto;
      if ($facturado > $cuenta->total) {
        return response()->json([
          "success" => false, "error" => true, "message" => "La factura supera el monto pendiente de facturar"
        ], 422);
      }

      $create = $request->except(['pdf','xml']);
      $create['pendiente'] = $create['monto'];
      $pdf = Storage::putFileAs(
        'public/cuentas-cobrar/'.$cuenta->id,
        $request->pdf,
        $request->documento.'.pdf'
      );
      $pdf = str_replace('public/', '', $pdf);
      $create['pdf'] = $pdf;
      $xml = Storage::putFileAs(
        'public/cuentas-cobrar/'.$cuenta->id,
        $request->xml,
        $request->documento.'.xml'
      );
      $xml = str_replace('public/', '', $xml);
      $create['xml'] = $xml;

      $factura = Factura::create($create);
      $factura->pagos = [];
      $factura->pdf = asset('storage/'.$factura->pdf);
      $factura->xml = asset('storage/'.$factura->xml);
      $cuenta->facturado = bcadd($cuenta->facturado, $factura->monto, 2);
      $cuenta->save();

      return response()->json(['success'=>true, "error"=>false, 'factura'=>$factura], 200);
    }


    /**
     * Registrar pago a factura de cuenta por cobrar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CuentaCobrar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function pagar(Request $request, CuentaCobrar $cuenta)
    {
      $validator = Validator::make($request->all(), [
        'factura_id' => 'required',
        'fecha' => 'required|date_format:d/m/Y',
        'monto' => 'required|numeric',
        'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 400);
      }

      $factura = Factura::findOrFail($request->factura_id);
      if ($request->monto > $factura->pendiente) {
        return response()->json([
          "success" => false, "error" => true, "message" => "El pago supera el monto pendiente de la factura"
        ], 422);
      }

      $create = $request->only(['factura_id','fecha','monto']);
      if($request->referencia) $create['referencia'] = $request->referencia;
      if($request->comprobante){
        $pago_numero = $factura->pagos->count() + 1;
        $comprobante = Storage::putFileAs(
          'public/cuentas-cobrar/'.$cuenta->id,
          $request->comprobante,
          "pago ".$factura->documento."_".$pago_numero.".".$request->comprobante->guessExtension()
        );
        $comprobante = str_replace('public/', '', $comprobante);
        $create['comprobante'] = $comprobante;
      }

      $pago = Pago::create($create);
      if($pago->comprobante){
        $pago->comprobante = asset('storage/'.$pago->comprobante);
      }
      $factura->pagado+= $pago->monto;
      $factura->pendiente-= $pago->monto;
      if($factura->pendiente<=0) $factura->pagada = true;
      $factura->save();

      $cuenta->pagado+= $pago->monto;
      $cuenta->pendiente-= $pago->monto;
      if($cuenta->pendiente<=0) $cuenta->pagada = true;
      $cuenta->save();

      return response()->json(['success'=>true, "error"=>false, 'pago'=>$pago], 200);
    }


    public function generarOrdenes(ProyectoAprobado $proyecto){
      $proyecto->load('cotizacion.entradas.producto.proveedor');

      //se va a crear una orden por cada proveedor involucrado en el proyecto
      $proveedores = $proyecto->cotizacion->entradas->groupBy(function($entrada){
        return $entrada->producto->proveedor->empresa;
      });

      foreach ($proveedores as $proveedor => $entradas) {
        $create = [
          'cliente_id'=>$proyecto->cliente_id,
          'proyecto_id'=>$proyecto->id,
          'proveedor_id'=>$entradas->first()->producto->proveedor_id,
          'cliente_nombre'=>$proyecto->cliente_nombre,
          'proyecto_nombre'=>$proyecto->proyecto,
          'proveedor_empresa'=>$proveedor,
          'moneda'=>$entradas->first()->producto->proveedor->moneda
        ];

        $orden = OrdenCompra::create($create);

        foreach ($entradas as $entrada) {
          OrdenCompraEntrada::create([
            'orden_id'=>$orden->id,
            'producto_id'=>$entrada->producto_id,
            'cantidad'=>$entrada->cantidad,
            'medida'=>$entrada->medida,
            'precio'=>$entrada->precio,
            'importe'=>$entrada->importe
          ]);

          $orden->subtotal = bcadd($orden->subtotal, $entrada->importe, 2);
        }

        if($proyecto->cotizacion->iva > 0){
          $orden->iva = bcmul($orden->subtotal, 0.16, 2);
        }
        else $orden->iva = 0;
        $orden->total = bcadd($orden->subtotal, $orden->iva, 2);
        $orden->save();
      }//foreach proveedores

      echo "Ordenes Creadas";
    }


}
