<?php

namespace App\Http\Controllers;

use App\Models\CuentaCobrar;
use App\Models\OrdenCompra;
use App\Models\OrdenProceso;
use App\Models\ProspectoActividad;
use App\Models\ProspectoCotizacion;
use App\Models\ProyectoAprobado;
use App\User;
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
        $clientesId         = auth()->user()->clientes()->get()->pluck('id');
        $clientes           = sizeof($clientesId);
        $proyectosAprobados = ProyectoAprobado::whereIn('cliente_id', $clientesId)->get()->count();
        $prospectosId       = auth()->user()->prospectos()->get()->pluck('id');
        $prospectos         = sizeof($prospectosId);
        $ordenesProceso     = OrdenProceso::whereIn('orden_compra_id', OrdenCompra::whereIn('cliente_id', $clientesId)->pluck('id'))->get()->count();

        $cotizaciones = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->whereIn('prospecto_id', $prospectosId)->orderBy('fecha', 'desc')->get();

        $cotizacionesAceptadas = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->whereIn('prospecto_id', $prospectosId)
            ->where('aceptada', true)
            ->orderBy('fecha', 'desc')->get();

        $proximasActividades = ProspectoActividad::leftjoin('prospectos', 'prospectos_actividades.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('prospectos_tipos_actividades', 'prospectos_actividades.tipo_id', '=', 'prospectos_tipos_actividades.id')
            ->select('prospectos_actividades.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre', 'prospectos_tipos_actividades.nombre as tipo_actividad')
            ->whereIn('prospecto_id', $prospectosId)->where('realizada', false)->orderBy('fecha', 'desc')->get();

        $usuarios = User::select('id', 'name')->get();

        $cuentasCobrar = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select('cuentas_cobrar.total', 'cuentas_cobrar.facturado', 'cuentas_cobrar.pagado', 'cuentas_cobrar.pendiente', 'cuentas_cobrar.cotizacion_id')
            ->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId)
            ->get();
        $totalesCuentas = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select(DB::raw('SUM(cuentas_cobrar.total) as "total", SUM(cuentas_cobrar.facturado) as "facturado", SUM(cuentas_cobrar.pagado) as "pagado"'))
            ->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId)
            ->get();

        $data = [
            'clientes'            => $clientes,
            'prospectos'          => $prospectos,
            'proyectosAprobados'  => $proyectosAprobados,
            'ordenesProceso'      => $ordenesProceso,
            'cotizaciones'        => $cotizaciones,
            'aceptadas'           => $cotizacionesAceptadas,
            'proximasActividades' => $proximasActividades,
            'usuarios'            => $usuarios,
            'cuentasCobrar'       => $cuentasCobrar,
            'totalCuentas'        => $totalesCuentas,
        ];

        return view('dashboard', compact('data'));
    }

    public function listado(Request $request)
    {
        $usuario            = User::find($request->id);
        $clientesId         = $usuario->clientes()->get()->pluck('id');
        $clientes           = sizeof($clientesId);
        $proyectosAprobados = ProyectoAprobado::whereIn('cliente_id', $clientesId)->get()->count();
        $prospectosId       = $usuario->prospectos()->get()->pluck('id');
        $prospectos         = sizeof($prospectosId);
        $ordenesProceso     = OrdenProceso::whereIn('orden_compra_id', OrdenCompra::whereIn('cliente_id', $clientesId)->pluck('id'))->get()->count();

        $cotizaciones = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->whereIn('prospecto_id', $prospectosId)->orderBy('fecha', 'desc')->get();

        $cotizacionesAceptadas = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->whereIn('prospecto_id', $prospectosId)
            ->where('aceptada', true)
            ->orderBy('fecha', 'desc')->get();

        $proximasActividades = ProspectoActividad::leftjoin('prospectos', 'prospectos_actividades.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->leftjoin('prospectos_tipos_actividades', 'prospectos_actividades.tipo_id', '=', 'prospectos_tipos_actividades.id')
            ->select('prospectos_actividades.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre', 'prospectos_tipos_actividades.nombre as tipo_actividad')
            ->whereIn('prospecto_id', $prospectosId)->where('realizada', false)->orderBy('fecha', 'desc')->get();

        $usuarios = User::select('id', 'name')->get();

        $cuentasCobrar = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select('cuentas_cobrar.total', 'cuentas_cobrar.facturado', 'cuentas_cobrar.pagado', 'cuentas_cobrar.pendiente', 'cuentas_cobrar.cotizacion_id')
            ->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId)
            ->get();

        $totalesCuentas = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select(DB::raw('SUM(cuentas_cobrar.total) as "total", SUM(cuentas_cobrar.facturado) as "facturado", SUM(cuentas_cobrar.pagado) as "pagado"'))
            ->whereIn('prospectos_cotizaciones.prospecto_id', $prospectosId)
            ->get();

        $data = [
            'clientes'            => $clientes,
            'prospectos'          => $prospectos,
            'proyectosAprobados'  => $proyectosAprobados,
            'ordenesProceso'      => $ordenesProceso,
            'cotizaciones'        => $cotizaciones,
            'aceptadas'           => $cotizacionesAceptadas,
            'proximasActividades' => $proximasActividades,
            'usuarios'            => $usuarios,
            'cuentasCobrar'       => $cuentasCobrar,
            'totalCuentas'        => $totalesCuentas,
        ];

        return response()->json(['success' => true, "error" => false, 'data' => $data], 200);
    }
}
