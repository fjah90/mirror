<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CuentaCobrar;
use App\Models\OrdenCompra;
use App\Models\ProspectoCotizacion;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cotizaciones()
    {
        $cotizaciones = ProspectoCotizacion::leftjoin('prospectos', 'prospectos_cotizaciones.prospecto_id', '=', 'prospectos.id')
            ->leftjoin('clientes', 'prospectos.cliente_id', '=', 'clientes.id')
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('reportes.cotizaciones', compact('cotizaciones'));
    }

    public function compras()
    {
        $compras = OrdenCompra::leftjoin('proyectos', 'ordenes_compra.proyecto_id', '=', 'proyectos.id')
            ->leftjoin('clientes', 'ordenes_compra.cliente_id', '=', 'clientes.id')
            ->leftjoin('proveedores', 'ordenes_compra.proveedor_id', '=', 'proveedores.id')
            ->select('ordenes_compra.*', 'proyectos.nombre as proyecto_nombre', 'proyectos.id as proyecto_id', 'clientes.nombre as cliente_nombre', 'proveedores.razon_social as proveedor_razon_social')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reportes.compras', compact('compras'));
    }

    public function cuentaCliente(Request $request)
    {
        $datos    = [];
        $clientes = Cliente::get();
        $cliente  = [];
        if ($request->id) {
            $proyectos = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
                ->select('cuentas_cobrar.proyecto')
                ->where('cliente_id', "=", $request->id)
                ->groupBy('proyecto')
                ->get();

            foreach ($proyectos as $value) {
                $dato = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
                    ->leftjoin('proyectos_aprobados', 'cuentas_cobrar.cotizacion_id', '=', 'proyectos_aprobados.cotizacion_id')
                    ->select('cuentas_cobrar.*', 'prospectos_cotizaciones.moneda', 'proyectos_aprobados.created_at as aprobadoEn', 'prospectos_cotizaciones.fecha as cotizacionFecha')
                    ->where('cuentas_cobrar.proyecto', "=", $value->proyecto)
                    ->orderBy('prospectos_cotizaciones.fecha', 'desc')
                    ->get();
                array_push($datos, $dato);
            }

            $cliente = Cliente::find($request->id);
            $data    = [
                'cliente'   => $cliente,
                'proyectos' => $datos,
            ];

            return response()->json(['success' => true, "error" => false, 'data' => $data], 200);

        }

        $data = [
            'cliente'   => $cliente,
            'clientes'  => $clientes,
            'proyectos' => $datos,
        ];

        return view('reportes.cuentaCliente', compact('data'));

    }
}
