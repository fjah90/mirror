<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CuentaCobrar;
use App\Models\OrdenCompra;
use App\Models\OrdenProceso;
use App\Models\Prospecto;
use App\Models\ProspectoActividad;
use App\Models\ProspectoCotizacion;
use App\Models\ProyectoAprobado;
use App\User;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId             = auth()->user()->id;
        $clientesId         = auth()->user()->clientes()->get()->pluck('id');
        $clientes           = sizeof($clientesId);
        $proyectosAprobados = ProyectoAprobado::whereIn('cliente_id', $clientesId)->get()->count();
        $prospectosId       = auth()->user()->prospectos()->get()->pluck('id');
        $prospectos         = sizeof($prospectosId);
        $ordenesProceso     = OrdenProceso::whereIn('orden_compra_id', OrdenCompra::whereIn('cliente_id', $clientesId)->pluck('id'))->get()->count();

        $cotizaciones = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('users', 'prospectos_cotizaciones.user_id', '=', 'users.id')
            ->select('prospectos_cotizaciones.*', 'users.name as user_name', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->where('prospectos_cotizaciones.user_id', '=', $userId)->orderBy('fecha', 'desc')->get();

        $cotizacionesAceptadas = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('proyectos_aprobados', 'prospectos_cotizaciones.id', '=', 'proyectos_aprobados.cotizacion_id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre', 'proyectos_aprobados.id as id_aprobado')
            ->where('prospectos_cotizaciones.user_id', '=', $userId)
            ->where('aceptada', true)
            ->orderBy('fecha', 'desc')->get();

        $proximasActividades = ProspectoActividad::leftjoin('prospectos', 'prospectos_actividades.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('prospectos_tipos_actividades', 'prospectos_actividades.tipo_id', '=', 'prospectos_tipos_actividades.id')
            ->select('prospectos_actividades.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre', 'prospectos_tipos_actividades.nombre as tipo_actividad')
            ->whereIn('prospecto_id', $prospectosId)->where('realizada', false)->orderBy('fecha', 'desc')->get();

        $usuarios = User::select('id', 'name')->get();

        $cuentasCobrar = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select('cuentas_cobrar.total', 'cuentas_cobrar.facturado', 'cuentas_cobrar.pagado', 'cuentas_cobrar.pendiente', 'cuentas_cobrar.cotizacion_id', 'prospectos_cotizaciones.moneda')
            ->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId)
            ->get();
        $totalesCuentas = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select(DB::raw('SUM(cuentas_cobrar.total) as "total", SUM(cuentas_cobrar.facturado) as "facturado", SUM(cuentas_cobrar.pagado) as "pagado", prospectos_cotizaciones.moneda as "moneda"'))
            ->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId)
            ->groupBy('prospectos_cotizaciones.moneda')
            ->get();
        $compras = OrdenCompra::with('entradas.producto','cliente','proyecto','proyecto.cotizacion','proyecto.cotizacion.user')->where('status','Por Autorizar')->get();

        $data = [
            'bdd' => session('database_name'),
            'clientes'            => $clientes,
            'prospectos'          => $prospectos,
            'proyectosAprobados'  => $proyectosAprobados,
            'ordenesProceso'      => $ordenesProceso,
            'cotizaciones'        => $cotizaciones,
            'compras'             => $compras,
            'aceptadas'           => $cotizacionesAceptadas,
            'proximasActividades' => $proximasActividades,
            'usuarios'            => $usuarios,
            'cuentasCobrar'       => $cuentasCobrar,
            'totalCuentas'        => $totalesCuentas,
        ];

        return view('dashboard', compact('data'));
    }

    public function changebdd(Request $request)
    {
        session(['database_name' => $request->bdd]);

        return response()->json(['success' => true, "error" => false], 200);
    }

    public function listado(Request $request)
    {
        $usuario            = null;
        $clientesId         = null;
        $clientes           = null;
        $proyectosAprobados = null;
        $prospectosId       = null;
        $prospectos         = null;
        $ordenesProceso     = null;

        if ($request->anio == '2019-12-31') {
            $inicio = Carbon::parse('2019-01-01');    
        }
        elseif ($request->anio == '2020-12-31') {
            $inicio = Carbon::parse('2020-01-01');    
        }
        elseif ($request->anio == '2022-12-31') {
            $inicio = Carbon::parse('2022-01-01');    
        }
        else{
            $inicio = Carbon::parse('2021-01-01');
        }

        if ($request->id != "todos") {
            if ($request->anio == 'Todos') {
                $usuario            = User::find($request->id);
                $clientesId         = $usuario->clientes()->get()->pluck('id');
                $clientes           = sizeof($clientesId);
                $proyectosAprobados = ProyectoAprobado::whereIn('cliente_id', $clientesId)->get()->count();
                $prospectosId       = $usuario->prospectos()->get()->pluck('id');
                $prospectos         = sizeof($prospectosId);
                $ordenesProceso     = OrdenProceso::whereIn('orden_compra_id', OrdenCompra::whereIn('cliente_id', $clientesId)->pluck('id'))->get()->count();    
            }
            else{
                $anio = Carbon::parse($request->anio);
                $usuario            = User::find($request->id);
                $clientesId         = $usuario->clientes()->get()->pluck('id');
                $clientes           = sizeof($clientesId);
                $proyectosAprobados = ProyectoAprobado::whereIn('cliente_id', $clientesId)->whereBetween('proyectos_aprobados.created_at', [$inicio, $anio])->get()->count();
                $prospectosId       = $usuario->prospectos()->whereBetween('prospectos.created_at', [$inicio, $anio])->get()->pluck('id');
                $prospectos         = sizeof($prospectosId);
                $ordenesProceso     = OrdenProceso::whereIn('orden_compra_id', OrdenCompra::whereIn('cliente_id', $clientesId)->pluck('id'))->whereBetween('ordenes_proceso.created_at', [$inicio, $anio])->get()->count();
            }
            
        } else {
            if ($request->anio == 'Todos') {

                $clientesId         = Cliente::get();
                $clientes           = sizeof($clientesId);
                $proyectosAprobados = ProyectoAprobado::get()->count();
                $prospectosId       = Prospecto::get()->pluck('id');
                $prospectos         = sizeof($prospectosId);
                $ordenesProceso     = OrdenProceso::get()->count();
            }
            else{
                $anio = Carbon::parse($request->anio);
                $clientesId         = Cliente::get();
                $clientes           = sizeof($clientesId);
                $proyectosAprobados = ProyectoAprobado::whereBetween('created_at', [$inicio, $anio])->get()->count();
                $prospectosId       = Prospecto::whereBetween('created_at', [$inicio, $anio])->get()->pluck('id');
                $prospectos         = sizeof($prospectosId);
                $ordenesProceso     = OrdenProceso::whereBetween('created_at', [$inicio, $anio])->get()->count();
            }

        }

        $cotizaciones = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('users', 'prospectos_cotizaciones.user_id', '=', 'users.id')
            ->select('prospectos_cotizaciones.*', 'users.name as user_name', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre');
        if ($request->id != "todos") {
            $cotizaciones->where('prospectos_cotizaciones.user_id', '=', $request->id);
        }
        if ($request->anio != 'Todos') {
            $anio = Carbon::parse($request->anio);
            $cotizaciones->whereBetween('prospectos_cotizaciones.created_at', [$inicio, $anio]);
        }


        $cotizaciones = $cotizaciones->orderBy('fecha', 'desc')->get();

        $cotizacionesAceptadas = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('proyectos_aprobados', 'prospectos_cotizaciones.id', '=', 'proyectos_aprobados.cotizacion_id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre', 'proyectos_aprobados.id as id_aprobado');
        if ($request->id != "todos") {
            $cotizacionesAceptadas->where('prospectos_cotizaciones.user_id', '=', $request->id);
        }
        if ($request->anio != 'Todos') {
            $anio = Carbon::parse($request->anio);
            $cotizacionesAceptadas->whereBetween('prospectos_cotizaciones.created_at', [$inicio, $anio]);
        }



        $cotizacionesAceptadas = $cotizacionesAceptadas->where('aceptada', true)->orderBy('fecha', 'desc')->get();

        $proximasActividades = ProspectoActividad::leftjoin('prospectos', 'prospectos_actividades.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('prospectos_tipos_actividades', 'prospectos_actividades.tipo_id', '=', 'prospectos_tipos_actividades.id')
            ->select('prospectos_actividades.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre', 'prospectos_tipos_actividades.nombre as tipo_actividad');
        if ($request->id != "todos") {
            $proximasActividades->whereIn('prospecto_id', $prospectosId);
        }
        if ($request->anio != 'Todos') {
            $anio = Carbon::parse($request->anio);
            $proximasActividades->whereBetween('prospectos_actividades.created_at', [$inicio, $anio]);
        }
        $proximasActividades = $proximasActividades->where('realizada', false)->orderBy('fecha', 'desc')->get();

        $usuarios = User::select('id', 'name')->get();

        $cuentasCobrar = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select('cuentas_cobrar.total', 'cuentas_cobrar.facturado', 'cuentas_cobrar.pagado', 'cuentas_cobrar.pendiente', 'cuentas_cobrar.cotizacion_id', 'prospectos_cotizaciones.moneda');
        if ($request->id != "todos") {
            $cuentasCobrar->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId);
        }
        if ($request->anio != 'Todos') {
            $anio = Carbon::parse($request->anio);
            $cuentasCobrar->whereBetween('cuentas_cobrar.created_at', [$inicio, $anio]);
        }
        $cuentasCobrar = $cuentasCobrar->get();

        $totalesCuentas = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select(DB::raw('SUM(cuentas_cobrar.total) as "total", SUM(cuentas_cobrar.facturado) as "facturado", SUM(cuentas_cobrar.pagado) as "pagado", prospectos_cotizaciones.moneda as "moneda"'));
        if ($request->id != "todos") {
            $totalesCuentas->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId);
        }
        if ($request->anio != 'Todos') {
            $anio = Carbon::parse($request->anio);
            $totalesCuentas->whereBetween('cuentas_cobrar.created_at', [$inicio, $anio]);
        }
        $totalesCuentas = $totalesCuentas->groupBy('prospectos_cotizaciones.moneda')->get();
        
        if ($request->id != "todos") {
            if ($request->anio == 'Todos') {
                $compras = OrdenCompra::with('entradas.producto','cliente','proyecto','proyecto.cotizacion','proyecto.cotizacion.user')->where('status','Por Autorizar')->whereHas('proyecto', function($q) use($usuario)
                {       
                    $q->whereHas('cotizacion', function($q) use($usuario)
                    {
                        
                        $q->where('user_id', $usuario->id);
                    });
                });

                $compras = $compras->get();
            }
            else{
                $anio = Carbon::parse($request->anio);
                $compras = OrdenCompra::with('entradas.producto','cliente','proyecto','proyecto.cotizacion','proyecto.cotizacion.user')->where('status','Por Autorizar')->whereHas('proyecto', function($q) use($usuario)
                {       
                    $q->whereHas('cotizacion', function($q) use($usuario)
                    {
                        
                        $q->where('user_id', $usuario->id);
                    });
                });

                $compras = $compras->whereBetween('ordenes_compra.created_at', [$inicio, $anio])->get();
            }
            
        }  
        else{
            if ($request->anio =='Todos') {
                $compras = OrdenCompra::with('entradas.producto','cliente','proyecto','proyecto.cotizacion','proyecto.cotizacion.user')->where('status','Por Autorizar')->get();        
            }
            else{
                $anio = Carbon::parse($request->anio);
                $compras = OrdenCompra::with('entradas.producto','cliente','proyecto','proyecto.cotizacion','proyecto.cotizacion.user')->where('status','Por Autorizar')->whereBetween('ordenes_compra.created_at', [$inicio, $anio])->get();    
            }
            
        }   
        

        $data = [
            'clientes'            => $clientes,
            'prospectos'          => $prospectos,
            'proyectosAprobados'  => $proyectosAprobados,
            'ordenesProceso'      => $ordenesProceso,
            'cotizaciones'        => $cotizaciones,
            'compras'             => $compras,
            'aceptadas'           => $cotizacionesAceptadas,
            'proximasActividades' => $proximasActividades,
            'usuarios'            => $usuarios,
            'cuentasCobrar'       => $cuentasCobrar,
            'totalCuentas'        => $totalesCuentas,
        ];

        return response()->json(['success' => true, "error" => false, 'data' => $data], 200);
    }
}
