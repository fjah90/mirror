<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyectoAprobado;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraEntrada;
use App\Models\ProspectoActividad;
use App\Models\ProspectoCotizacion;
use App\Models\CuentaCobrar;
use App\Models\Prospecto;
use App\Models\OrdenProceso;
use Carbon\Carbon;
use DateTime;
use App\User;
use Storage;

class ProyectosAprobadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //$proyectos = auth()->user()->proyectos_aprobados()->with('cotizacion.cuentaCobrar','cliente','cotizacion.user','ordenes')->get();

      
      $inicio = Carbon::parse('2021-01-01');
      $anio = Carbon::parse('2021-12-31');
      $proyectos = ProyectoAprobado::whereBetween('created_at', [$inicio, $anio])->with('cotizacion','cotizacion.cuenta_cobrar','cotizacion.user','ordenes')->get();

      foreach ($proyectos as $proyecto) {
        $proyecto->cotizacion->archivo = asset('storage/'.$proyecto->cotizacion->archivo);
      }

      if (auth()->user()->tipo == 'Administrador')
        $usuarios = User::all();
      else $usuarios = [];

      return view('proyectos_aprobados.index', compact('proyectos','usuarios'));
    }

    public function listado(Request $request)
    {

      if ($request->anio == '2019-12-31') {
          $inicio = Carbon::parse('2019-01-01');    
      }
      elseif ($request->anio == '2020-12-31') {
          $inicio = Carbon::parse('2020-01-01');    
      }
      else{
          $inicio = Carbon::parse('2021-01-01');
      }


      if ($request->id == 'Todos') {

        if ($request->anio == 'Todos') {
            $proyectos = ProyectoAprobado::with('cotizacion','cotizacion.cuenta_cobrar','cotizacion.user','ordenes')->get();
        }
        else{
            $anio = Carbon::parse($request->anio);
            $proyectos = ProyectoAprobado::whereBetween('created_at', [$inicio, $anio])->with('cotizacion','cotizacion.cuenta_cobrar','cotizacion.user','ordenes')->get();
        }

        
      } else {

        if ($request->anio == 'Todos') {
            $user = User::with('proyectos_aprobados.cotizacion','proyectos_aprobados.cotizacion.cuenta_cobrar','proyectos_aprobados.cotizacion.user')->find($request->id);
            if (is_null($user)) $proyectos = [];
            else $proyectos = $user->proyectos_aprobados;
        }
        else{
            $anio = Carbon::parse($request->anio);
            $user = User::with('proyectos_aprobados.cotizacion','proyectos_aprobados.cotizacion.cuenta_cobrar','proyectos_aprobados.cotizacion.user')->find($request->id);
            if (is_null($user)) $proyectos = [];
            else {

              $proyectos = $user->proyectos_aprobados->where('created_at','>',$inicio);

            }
        }
        
        
        
      }

      return response()->json(['success' => true, "error" => false, 'proyectos' => $proyectos], 200);
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

    public function show(ProyectoAprobado $proyecto)
    {
      $proyectos = Prospecto::all();
      $proyecto->load('cotizacion','ordenes','cliente','cotizacion.prospecto');

      $prospecto = $proyecto->cotizacion->prospecto;
      $prospecto->load('cotizaciones','cotizaciones.proyecto_aprobado','cotizaciones.entradas','cotizaciones.entradas.producto.proveedor','cotizaciones_aprobadas','cotizaciones_aprobadas.proyecto_aprobado','cotizaciones_aprobadas.entradas','cotizaciones_aprobadas.entradas.producto.proveedor');

      $ordenes = OrdenCompra::wherehas('proyecto.cotizacion', function($query) use ($prospecto) {
           $query->where('prospecto_id', $prospecto->id);
        })->with('entradas.producto')
            ->get();

      $proyect = ProyectoAprobado::findOrFail($proyecto->id);
      $cotizacion = ProspectoCotizacion::findOrFail($proyect->cotizacion_id);

      foreach ($ordenes as $orden) {
          if ($orden->archivo) {
              $orden->archivo = asset('storage/' . $orden->archivo);
          }

          $archivos_autorizacion = Storage::disk('public')->files('ordenes_compra/' . $orden->id . '/archivos_autorizacion');
          $archivos_autorizacion = array_map(function ($archivo) {
              return ['liga' => asset("storage/$archivo"), 'nombre' => basename($archivo)];
          }, $archivos_autorizacion);
          $orden->archivos_autorizacion = $archivos_autorizacion;
      }

      $cuentas = CuentaCobrar::whereHas('cotizacion', function ($query) use($prospecto) {
          return $query->where('prospecto_id', '=', $prospecto->id);
      })->with('cotizacion','cotizacion.user')->get();


      $ordenes_proceso = OrdenProceso::whereHas('ordenCompra', function ($query) use($proyecto) {
          return $query->where('proyecto_nombre', '=', $proyecto->proyecto);
      })->with('ordenCompra','ordenCompra.proyecto','ordenCompra.proyecto.cotizacion','ordenCompra.proyecto.cotizacion.user')->get();

      foreach ($ordenes_proceso as $orden) {
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
     

      return view('proyectos_aprobados.show', compact('proyecto','prospecto','ordenes','cuentas','ordenes_proceso','proyectos'));
    }

    public function destroy(ProyectoAprobado $proyecto)
    {
        $proyecto->load('cotizacion.cuenta_cobrar');
        $cuenta_cobrar = $proyecto->cotizacion->cuenta_cobrar;
        $today = new DateTime();
        $cotizacion = $proyecto->cotizacion;

        $cotizacion->aceptada = 0;
        $cotizacion->save();
        ProspectoActividad::create([
            'prospecto_id' => $proyecto->cotizacion->prospecto_id,
            'tipo_id'      => 7,
            'fecha'        => $today->format('d/m/Y'),
            'descripcion'  => 'Cotizacion realizada',
            'realizada'    => true,
        ]);

        $proyecto->delete();
        $cuenta_cobrar->delete();
        return response()->json(['success' => true, "error" => false], 200);
    }


}
