<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentaCobrar;
use App\Models\Factura;
use App\Models\Pago;
use Validator;
use Storage;

class CuentasCobrarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $cuentas = CuentaCobrar::all();

      return view('cuentas-cobrar.index', compact('cuentas'));
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



}
