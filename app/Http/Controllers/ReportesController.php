<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CuentaCobrar;
use App\Models\OrdenCompra;
use App\Models\Pago;
use App\Models\PagoCuentaPagar;
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
            ->leftjoin('users', 'prospectos_cotizaciones.user_id', '=', 'users.id')
            ->select('prospectos_cotizaciones.*', 'users.name as user_name', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('reportes.cotizaciones', compact('cotizaciones'));
    }

    public function cobros()
    {
        $cobros = Pago::leftjoin('facturas', 'pagos.factura_id', '=', 'facturas.id')
            ->leftjoin('cuentas_cobrar', 'facturas.cuenta_id', '=', 'cuentas_cobrar.id')
            ->leftjoin('clientes', 'cuentas_cobrar.cliente_id', '=', 'clientes.id')
            ->select('cuentas_cobrar.*', 'clientes.nombre as cliente_nombre', 'facturas.documento', 'pagos.fecha as pago_fecha', 'pagos.monto as pago_monto')
            ->orderBy('pagos.fecha', 'desc')
            ->get();

        return view('reportes.cobros', compact('cobros'));
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

    public function pagos()
    {
        $pagos = PagoCuentaPagar::leftjoin('facturas_cuentas_pagar', 'pagos_cuentas_pagar.factura_id', '=', 'facturas_cuentas_pagar.id')
            ->leftjoin('cuentas_pagar', 'facturas_cuentas_pagar.cuenta_id', '=', 'cuentas_pagar.id')
            ->leftjoin('proveedores', 'cuentas_pagar.proveedor_id', '=', 'proveedores.id')
            ->leftjoin('ordenes_compra', 'cuentas_pagar.orden_compra_id', '=', 'ordenes_compra.id')
            ->leftjoin('clientes', 'ordenes_compra.cliente_id', '=', 'clientes.id')
            ->select('cuentas_pagar.*', 'clientes.nombre as cliente_nombre', 'proveedores.razon_social as proveedor_nombre', 'ordenes_compra.proyecto_nombre as proyecto_nombre',
                'facturas_cuentas_pagar.documento', 'pagos_cuentas_pagar.fecha as pago_fecha', 'pagos_cuentas_pagar.monto as pago_monto', 'ordenes_compra.numero as numero_compra')
            ->orderBy('pagos_cuentas_pagar.fecha', 'desc')
            ->get();

        return view('reportes.pagos', compact('pagos'));
    }

    public function saldoProveedores()
    {
        $saldos = PagoCuentaPagar::leftjoin('facturas_cuentas_pagar', 'pagos_cuentas_pagar.factura_id', '=', 'facturas_cuentas_pagar.id')
            ->leftjoin('cuentas_pagar', 'facturas_cuentas_pagar.cuenta_id', '=', 'cuentas_pagar.id')
            ->leftjoin('proveedores', 'cuentas_pagar.proveedor_id', '=', 'proveedores.id')
            ->leftjoin('ordenes_compra', 'cuentas_pagar.orden_compra_id', '=', 'ordenes_compra.id')
            ->leftjoin('clientes', 'ordenes_compra.cliente_id', '=', 'clientes.id')
            ->select('cuentas_pagar.*', 'clientes.nombre as cliente_nombre', 'proveedores.razon_social as proveedor_nombre', 'ordenes_compra.proyecto_nombre as proyecto_nombre',
                'facturas_cuentas_pagar.documento', 'facturas_cuentas_pagar.monto as facturas_monto', 'facturas_cuentas_pagar.vencimiento as facturas_fecha_vencimiento', 'pagos_cuentas_pagar.fecha as pago_fecha', 'pagos_cuentas_pagar.monto as pago_monto', 'ordenes_compra.numero as numero_compra')
            ->orderBy('facturas_cuentas_pagar.vencimiento', 'desc')
            ->get();

        return view('reportes.saldoProveedores', compact('saldos'));
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

    public function utilidades()
    {
        $datos = OrdenCompra::leftjoin('proyectos_aprobados', 'ordenes_compra.proyecto_id', '=', 'proyectos_aprobados.id')
            ->leftjoin('prospectos_cotizaciones', 'proyectos_aprobados.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->leftjoin('clientes', 'proyectos_aprobados.cliente_id', '=', 'clientes.id')
            ->select('ordenes_compra.*', 'proyectos_aprobados.proyecto as proyecto_nombre', 'clientes.nombre as cliente_nombre',
                'prospectos_cotizaciones.id as cotizaciones_id', 'prospectos_cotizaciones.moneda as cotizaciones_moneda', 'prospectos_cotizaciones.total as cotizaciones_total')
            ->orderBy('prospectos_cotizaciones.id', 'asc')
            ->get();

        return view('reportes.utilidades', compact('datos'));
    }
}
