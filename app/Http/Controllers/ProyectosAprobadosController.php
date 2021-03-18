<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyectoAprobado;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraEntrada;
use App\Models\ProspectoActividad;
use Carbon\Carbon;
use DateTime;
use App\User;

class ProyectosAprobadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $proyectos = auth()->user()->proyectos_aprobados()->with('cotizacion.cuentaCobrar','cliente','cotizacion.user')->get();
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
            $proyectos = ProyectoAprobado::with('cotizacion','cotizacion.cuenta_cobrar','cotizacion.user')->get();
        }
        else{
            $anio = Carbon::parse($request->anio);
            $proyectos = ProyectoAprobado::whereBetween('created_at', [$inicio, $anio])->with('cotizacion','cotizacion.cuenta_cobrar','cotizacion.user')->get();
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
