<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuentaPagar;
use App\Models\FacturaCuentaPagar;
use App\Models\PagoCuentaPagar;
use App\User;
use Carbon\Carbon;
use Validator;
use Storage;

class CuentasPagarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $usuarios = User::all();
      $cuentas = CuentaPagar::all();

      return view('cuentas-pagar.index', compact('cuentas','usuarios'));
    }

    public function listado(Request $request)
    {
      $usuario = $request->id;
      if ($request->anio == '2019-12-31') {
          $inicio = Carbon::parse('2019-01-01');    
      }
      elseif ($request->anio == '2020-12-31') {
          $inicio = Carbon::parse('2020-01-01');    
      }
      else{
          $inicio = Carbon::parse('2021-01-01');
      }

      if ($request->anio == 'Todos') {
          if ($usuario == 'Todos') {
            $cuentas = CuentaPagar::all();
          }
          else{
            $cuentas = CuentaPagar::with('orden')->whereHas('orden', function($q) use($usuario)
            {       
              $q->whereHas('proyecto', function($q) use($usuario)
              {
                $q->whereHas('cotizacion', function($q) use($usuario)
                {
                  $q->where('user_id', $usuario);
                
                });
              });
            })->get()
          }
          
      }
      else{
          $anio = Carbon::parse($request->anio);
          if ($usuario == 'Todos') {
            $cuentas = CuentaPagar::with('orden')->whereBetween('created_at', [$inicio, $anio])->whereHas('orden', function($q) use($usuario)
            {       
              $q->whereHas('proyecto', function($q) use($usuario)
              {
                $q->whereHas('cotizacion', function($q) use($usuario)
                {
                  $q->where('user_id', $usuario);
                
                });
              });
            })->get()
          }
          else{
            $cuentas = CuentaPagar::whereBetween('created_at', [$inicio, $anio])->get();
          }
      }

      return response()->json(['success' => true, "error" => false, 'cuentas' => $cuentas], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CuentaPagar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function show(CuentaPagar $cuenta)
    {
      $cuenta->load('facturas.pagos');
      foreach ($cuenta->facturas as $factura) {
        $factura->pdf = asset('storage/'.$factura->pdf);
        $factura->xml = asset('storage/'.$factura->xml);

        foreach ($factura->pagos as $pago) {
          if($pago->comprobante) $pago->comprobante = asset('storage/'.$pago->comprobante);
        }
      }

      return view('cuentas-pagar.show', compact('cuenta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CuentaPagar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function edit(CuentaPagar $cuenta)
    {
      $cuenta->load('facturas.pagos','orden.entradas');
      foreach ($cuenta->facturas as $factura) {
        $factura->pdf = ($factura->pdf)?asset('storage/'.$factura->pdf):"";
        $factura->xml = ($factura->xml)?asset('storage/'.$factura->xml):"";

        foreach ($factura->pagos as $pago) {
          if($pago->comprobante) $pago->comprobante = asset('storage/'.$pago->comprobante);
        }
      }

      return view('cuentas-pagar.edit', compact('cuenta'));
    }

    /**
     * Agrega una factura a cuenta por pagar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CuentaPagar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function facturar(Request $request, CuentaPagar $cuenta)
    {
      $validator = Validator::make($request->all(), [
        'cuenta_id' => 'required',
        'documento' => 'required',
        'monto' => 'required|numeric',
        'vencimiento' => 'required|date_format:d/m/Y',
        'pdf' => 'nullable|file|mimes:pdf',
        'xml' => 'nullable|file|mimes:xml',
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
      if(!is_null($request->pdf)){
        $pdf = Storage::putFileAs('public/cuentas-pagar/'.$cuenta->id,
          $request->pdf, $request->documento.'.pdf'
        );
        $pdf = str_replace('public/', '', $pdf);
        $create['pdf'] = $pdf;
      }
      if(!is_null($request->xml)){
        $xml = Storage::putFileAs('public/cuentas-pagar/'.$cuenta->id,
          $request->xml, $request->documento.'.xml'
        );
        $xml = str_replace('public/', '', $xml);
        $create['xml'] = $xml;
      }

      $factura = FacturaCuentaPagar::create($create);
      $factura->pagos = [];
      $factura->pdf = ($factura->pdf)?asset('storage/'.$factura->pdf):"";
      $factura->xml = ($factura->xml)?asset('storage/'.$factura->xml):"";
      $cuenta->facturado = bcadd($cuenta->facturado, $factura->monto, 2);
      $cuenta->save();

      return response()->json(['success'=>true, "error"=>false, 'factura'=>$factura], 200);
    }


    /**
     * Registrar pago a factura de cuenta por pagar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CuentaPagar  $cuenta
     * @return \Illuminate\Http\Response
     */
    public function pagar(Request $request, CuentaPagar $cuenta)
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

      $factura = FacturaCuentaPagar::findOrFail($request->factura_id);
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
          'public/cuentas-pagar/'.$cuenta->id,
          $request->comprobante,
          "Pago ".$factura->documento."_".$pago_numero.".".$request->comprobante->guessExtension()
        );
        $comprobante = str_replace('public/', '', $comprobante);
        $create['comprobante'] = $comprobante;
      }

      $pago = PagoCuentaPagar::create($create);
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
