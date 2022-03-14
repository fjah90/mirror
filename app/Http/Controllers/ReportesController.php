<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CuentaCobrar;
use App\Models\OrdenCompra;
use App\Models\Pago;
use Carbon\Carbon;
use App\Models\PagoCuentaPagar;
use App\Models\ProspectoCotizacion;
use App\Models\ProspectoCotizacionEntrada;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cotizaciones()
    {
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');
        $cotizaciones = ProspectoCotizacion::with('prospecto:id,nombre,cliente_id', 'prospecto.cliente:id,nombre', 'user:id,name', 'entradas:id,cantidad,producto_id,cotizacion_id,importe', 'entradas.producto:id,proveedor_id', 'entradas.producto.proveedor:id,empresa','proyecto_aprobado')
            ->has('prospecto')
            ->has('entradas.producto')
            //->whereBetween('prospectos_cotizaciones.created_at', [$inicio, $fin])
            ->orderBy('fecha', 'desc')
            ->get();
        return view('reportes.cotizaciones', compact('cotizaciones'));
    }
    public function  cotizacionesexcel(Request $request){
        $datos = $request->datos;
        $dataF = [];
        foreach ($datos as $key => $dato) {

            $dato[5] = str_replace("<span>","",$dato[5]);
            $dato[5] = str_replace("</span><br>","",$dato[5]);
            $dato[5] = str_replace(" ","",$dato[5]);
            $dato[5] = str_replace("    ","",$dato[5]);
            $dato[6] = str_replace("<span>","",$dato[6]);
            $dato[6] = str_replace("</span><br>","",$dato[6]);
            $dato[6] = str_replace(" ","",$dato[6]);
            $dato[6] = str_replace("    ","",$dato[6]);

            if ($key == 0) {
                array_push($dataF, 
                    $data = array(
                        'FECHA ' => $dato[0],
                        'NÚMERO DE COTIZACIÓN' => $dato[1],
                        'FECHA DE APROBACIÓN' => $dato[2],
                        'CLIENTE' => $dato[3],
                        'PROYECTO' => $dato[4],
                        'MARCA' => $dato[5],
                        'PROVEEDORES' => $dato[6],
                        'IVA' => $dato[7],
                        'MONTO' => $dato[8],
                        'MONEDA' => $dato[9],
                        'USUARIO' => $dato[10],
                        'TOTAL MXN' => $request->totalMxn,
                        'TOTAL USD' => $request->totalUsd,
                    )
                );    
            }
            else{
                array_push($dataF, 
                    $data = array(
                        'FECHA ' => $dato[0],
                        'NÚMERO DE COTIZACIÓN' => $dato[1],
                        'FECHA DE APROBACIÓN' => $dato[2],
                        'CLIENTE' => $dato[3],
                        'PROYECTO' => $dato[4],
                        'MARCA' => $dato[5],
                        'PROVEEDORES' => $dato[6],
                        'IVA' => $dato[7],
                        'MONTO' => $dato[8],
                        'MONEDA' => $dato[9],
                        'USUARIO' => $dato[10],
                        'TOTAL MXN' => '',
                        'TOTAL USD' => '',
                    )
                );
            }
            
        }

        Excel::create('ReporteCotizaciones', function($excel) use($dataF) {
 
            $excel->sheet('Cotizaciones', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }

    public function  cotizacionespdf(Request $request){
        $datos = $request->datos;
        $totalMxn = $request->totalMxn;
        $totalUsd = $request->totalUsd;
        $url = $url = 'reportes/cotizaciones.pdf';

        foreach($datos as $key => $dato){
            $dato[5] = str_replace("<span>","",$dato[5]);
            $dato[5] = str_replace("</span><br>","",$dato[5]);
            $dato[6] = str_replace("<span>","",$dato[6]);
            $dato[6] = str_replace("</span><br>","",$dato[6]);
            $datos[$key][5] = $dato[5];
            $datos[$key][6] = $dato[6];
        }

        $reportePDF = PDF::loadView('reportes.cotizacionesPDF', compact('datos', 'totalUsd','totalMxn'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }

    public function  cobrosexcel(Request $request){
        $datos = $request->datos;
        $dataF = [];
        foreach ($datos as $key =>  $dato) {

            if ($key == 0) {
                array_push($dataF, 
                $data = array(
                    'FECHA DE APROBACIÓN' => $dato[0],
                    'NÚMERO DE COMPRA' => $dato[1],
                    'CLIENTE' => $dato[2],
                    'PROYECTO' => $dato[3],
                    'DOCUMENTO' => $dato[4],
                    'MONTO' => $dato[5],
                    'MONEDA' => $dato[6],
                    'TOTAL MXN' => $request->totalMxn,
                    'TOTAL USD' => $request->totalUsd,
                    )
                );
                
            }
            else{
                array_push($dataF, 
                    $data = array(
                        'FECHA DE APROBACIÓN' => $dato[0],
                        'NÚMERO DE COMPRA' => $dato[1],
                        'CLIENTE' => $dato[2],
                        'PROYECTO' => $dato[3],
                        'DOCUMENTO' => $dato[4],
                        'MONTO' => $dato[5],
                        'MONEDA' => $dato[6],
                        'TOTAL MXN' => '',
                        'TOTAL USD' => '',
                    )
                );
            }

            
        }

        Excel::create('ReporteCobros', function($excel) use($dataF) {
 
            $excel->sheet('Cobros', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }



    public function  cobrospdf(Request $request){
        $datos = $request->datos;
        $totalMxn = $request->totalMxn;
        $totalUsd = $request->totalUsd;
        $url = $url = 'reportes/cobros.pdf';
        $reportePDF = PDF::loadView('reportes.cobrosPDF', compact('datos', 'totalUsd','totalMxn'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }

    public function  comprasexcel(Request $request){
        $datos = $request->datos;
        $dataF = [];
        foreach ($datos as $key => $dato) {

            if ($key == 0) {
                array_push($dataF, 
                    $data = array(
                        'FECHA DE APROBACIÓN' => $dato[0],
                        'NÚMERO DE COMPRA' => $dato[1],
                        'COTIZACIÓN' => $dato[2],
                        'PROVEEDOR' => $dato[3],
                        'CLIENTE' => $dato[4],
                        'PROYECTO' => $dato[5],
                        'MONTO' => $dato[6],
                        'MONEDA' => $dato[7],
                        'TOTAL MXN' => $request->totalMxn,
                        'TOTAL USD' => $request->totalUsd,
                    )
                );    
            }
            else{
                array_push($dataF, 
                    $data = array(
                        'FECHA DE APROBACIÓN' => $dato[0],
                        'NÚMERO DE COMPRA' => $dato[1],
                        'COTIZACIÓN' => $dato[2],
                        'PROVEEDOR' => $dato[3],
                        'CLIENTE' => $dato[4],
                        'PROYECTO' => $dato[5],
                        'MONTO' => $dato[6],
                        'MONEDA' => $dato[7],
                        'TOTAL MXN' => '',
                        'TOTAL USD' => '',
                    )
                );
            }
            
        }

        Excel::create('ReporteCompras', function($excel) use($dataF) {
 
            $excel->sheet('Compras', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }

    public function  compraspdf(Request $request){
        $datos = $request->datos;
        $totalMxn = $request->totalMxn;
        $totalUsd = $request->totalUsd;
        
        $url = $url = 'reportes/compras.pdf';
        $reportePDF = PDF::loadView('reportes.comprasPDF', compact('datos', 'totalUsd','totalMxn'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }

    public function  pagosexcel(Request $request){
        $datos = $request->datos;
        $dataF = [];
        foreach ($datos as $key => $dato) {

            if ($key == 0) {
                array_push($dataF, 
                    $data = array(
                        'FECHA DE PAGO' => $dato[0],
                        'NÚMERO DE COMPRA' => $dato[1],
                        'PROVEEDOR' => $dato[2],
                        'CLIENTE' => $dato[3],
                        'PROYECTO' => $dato[4],
                        'DOCUMENTO' => $dato[5],
                        'MONTO' => $dato[6],
                        'MONEDA' => $dato[7],
                        'TOTAL MXN' => $request->totalMxn,
                        'TOTAL USD' => $request->totalUsd,

                    )
                );    
            }
            else{
                array_push($dataF, 
                    $data = array(
                        'FECHA DE PAGO' => $dato[0],
                        'NÚMERO DE COMPRA' => $dato[1],
                        'PROVEEDOR' => $dato[2],
                        'CLIENTE' => $dato[3],
                        'PROYECTO' => $dato[4],
                        'DOCUMENTO' => $dato[5],
                        'MONTO' => $dato[6],
                        'MONEDA' => $dato[7],
                        'TOTAL MXN' => '',
                        'TOTAL USD' => '',

                    )
                );
            }

        }

        Excel::create('ReportePagos', function($excel) use($dataF) {
 
            $excel->sheet('Pagos', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }

    public function  pagospdf(Request $request){
        $datos = $request->datos;
        $totalMxn = $request->totalMxn;
        $totalUsd = $request->totalUsd;
        $url = $url = 'reportes/pagos.pdf';
        $reportePDF = PDF::loadView('reportes.pagosPDF', compact('datos', 'totalUsd','totalMxn'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }

    public function  saldoexcel(Request $request){
        $datos = $request->datos;
        $dataF = [];
        foreach ($datos as $key => $dato) {

            if ($key == 0) {
                array_push($dataF, 
                    $data = array(
                        'NÚMERO DE COMPRA' => $dato[0],
                        'PROVEEDOR' => $dato[1],
                        'CLIENTE' => $dato[2],
                        'PROYECTO' => $dato[3],
                        'DOCUMENTO' => $dato[4],
                        'MONTO' => $dato[5],
                        'MONEDA' => $dato[6],
                        'FECHA DE FACTURA' => $dato[7],
                        'FECHA DE VENCIMIENTO' => $dato[8],
                        'DIAS A FAVOR O EN CONTRA' => $dato[9],
                        'TOTAL MXN' => $request->totalMxn,
                        'TOTAL USD' => $request->totalUsd,
                    )
                );    
            }

            else{
                array_push($dataF, 
                    $data = array(
                        'NÚMERO DE COMPRA' => $dato[0],
                        'PROVEEDOR' => $dato[1],
                        'CLIENTE' => $dato[2],
                        'PROYECTO' => $dato[3],
                        'DOCUMENTO' => $dato[4],
                        'MONTO' => $dato[5],
                        'MONEDA' => $dato[6],
                        'FECHA DE FACTURA' => $dato[7],
                        'FECHA DE VENCIMIENTO' => $dato[8],
                        'DIAS A FAVOR O EN CONTRA' => $dato[9],
                        'TOTAL MXN' => '',
                        'TOTAL USD' => '',
                    )
                );
            }
            
        }

        Excel::create('ReporteSaldo', function($excel) use($dataF) {
 
            $excel->sheet('Saldo', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }

    public function  saldopdf(Request $request){
        $datos = $request->datos;
        $totalMxn = $request->totalMxn;
        $totalUsd = $request->totalUsd;
        $url = $url = 'reportes/saldo.pdf';
        $reportePDF = PDF::loadView('reportes.saldoPDF', compact('datos', 'totalUsd','totalMxn'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }

    public function  cuentaexcel(Request $request){
        
        //datos
        $dataF = [];
        $datos    = [];
        $cliente  = [];
        
        $proyectos = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select('cuentas_cobrar.proyecto')
            ->where('cliente_id', "=", $request->cliente)
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
        


        foreach($datos as $pro){

            $totalMxnMonto = 0;
            $totalMxnFacturado = 0;
            $totalMxnPagado = 0;
            $totalMxnPorFacturar = 0;
            $totalMxnPendiente = 0;


            $totalUsdMonto = 0;
            $totalUsdFacturado = 0;
            $totalUsdPagado = 0;
            $totalUsdPorFacturar = 0;
            $totalUsdPendiente = 0;

            foreach($pro as $key => $cuen){
                if ($cuen->moneda == 'Dolares') {
                    $totalUsdMonto += floatval($cuen->total);
                    $totalUsdFacturado += floatval($cuen->facturado);
                    $totalUsdPagado += floatval($cuen->pagado);
                    $totalUsdPendiente += floatval($cuen->pendiente);

                }
                else{
                    $totalMxnMonto += floatval($cuen->total);
                    $totalMxnFacturado += floatval($cuen->facturado);
                    $totalMxnPagado += floatval($cuen->pagado);
                    $totalMxnPendiente += floatval($cuen->pendiente);

                }

                array_push($dataF, 
                    $data = array(
                        'FECHA' => $cuen->cotizacionFecha,
                        'NÚMERO DE COTIZACIÓN' => $cuen->cotizacion_id,
                        'FECHA DE APROBACIÓN' => $cuen->aprobadoEn ,
                        'MONEDA' => $cuen->moneda,
                        'MONTO' => number_format($cuen->total, 2, '.', ' '),
                        'FACTURADO' => number_format($cuen->facturado, 2, '.', ' '),
                        'POR FACTURAR' => number_format($cuen->total - $cuen->facturado, 2, '.', ' '),
                        'PAGADO' => number_format($cuen->pagado, 2, '.', ' '),
                        'PENDIENTE' => number_format($cuen->pendiente, 2, '.', ' '),
                        'TOTAL MXN MONTO' => '',
                        'TOTAL MXN FACTURADO' => '',
                        'TOTAL MXN POR FACTURAR' => '',
                        'TOTAL MXN PAGADO' => '' ,
                        'TOTAL MXN PENDIENTE' => '',

                        'TOTAL USD MONTO' => '',
                        'TOTAL USD FACTURADO' => '',
                        'TOTAL USD POR FACTURAR' => '',
                        'TOTAL USD PAGADO' => '',
                        'TOTAL USD PENDIENTE' => '',
                    )
                );
                
            }
            $totalMxnPorFacturar = $totalMxnMonto - $totalMxnFacturado;
            
            $totalUsdPorFacturar = $totalUsdMonto - $totalUsdFacturado;

            array_push($dataF, 
                $data = array(
                    'FECHA' => '',
                    'NÚMERO DE COTIZACIÓN' => '',
                    'FECHA DE APROBACIÓN' => '',
                    'MONEDA' => '',
                    'MONTO' => '',
                    'FACTURADO' => '',
                    'POR FACTURAR' => '',
                    'PAGADO' => '',
                    'PENDIENTE' => '',
                    'TOTAL MXN MONTO' => $totalMxnMonto,
                    'TOTAL MXN FACTURADO' => $totalMxnFacturado,
                    'TOTAL MXN POR FACTURAR' => $totalMxnPorFacturar,
                    'TOTAL MXN PAGADO' => $totalMxnPagado,
                    'TOTAL MXN PENDIENTE' => $totalMxnPendiente,

                    'TOTAL USD MONTO' => $totalUsdMonto,
                    'TOTAL USD FACTURADO' => $totalUsdFacturado,
                    'TOTAL USD POR FACTURAR' => $totalUsdPorFacturar,
                    'TOTAL USD PAGADO' => $totalUsdPagado,
                    'TOTAL USD PENDIENTE' => $totalUsdPendiente,
                )
            );
            
        }

        Excel::create('ReporteCuenta', function($excel) use($dataF) {
 
            $excel->sheet('CuentaCliente', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }

    public function  cuentapdf(Request $request){

        //datos
        $datos    = [];
        $cliente  = [];
        
        $proyectos = CuentaCobrar::leftjoin('prospectos_cotizaciones', 'cuentas_cobrar.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->select('cuentas_cobrar.proyecto')
            ->where('cliente_id', "=", $request->cliente)
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
        $totales = [];

        $totalMxnMonto = 0;
        $totalMxnFacturado= 0;
        $totalMxnPagado = 0;
        $totalMxnPendiente= 0;
        $totalMxnPorFacturar= 0;
        $totalUsdMonto= 0;
        $totalUsdFacturado= 0;
        $totalUsdPagado= 0;
        $totalUsdPendiente= 0;
        $totalUsdPorFacturar= 0;

        foreach($datos as $pro){
            $tot = [];
            foreach($pro as $cuen){
                if ($cuen->moneda == 'Dolares') {
                    $totalUsdMonto += $cuen->total;             
                    $totalUsdFacturado = $cuen->facturado;
                    $totalUsdPagado = $cuen->pagado;
                    $totalUsdPendiente = $cuen->pendiente;
                    

                }
                else{
                    $totalMxnMonto = $cuen->total;
                    $totalMxnFacturado = $cuen->facturado;
                    $totalMxnPagado = $cuen->pagado;
                    $totalMxnPendiente = $cuen->pendiente;
                }
            }
            $totalUsdPorFacturar = $totalUsdMonto - $totalUsdFacturado;
            $totalMxnPorFacturar = $totalMxnMonto - $totalMxnFacturado;
            array_push($tot, $totalMxnMonto);
            array_push($tot, $totalMxnFacturado);
            array_push($tot, $totalMxnPagado);
            array_push($tot, $totalMxnPendiente);
            array_push($tot, $totalMxnPorFacturar);
            array_push($tot, $totalUsdMonto);
            array_push($tot, $totalUsdFacturado);
            array_push($tot, $totalUsdPagado);
            array_push($tot, $totalUsdPendiente);
            array_push($tot, $totalUsdPorFacturar);
            array_push($totales, $tot);
        }

        $cliente = Cliente::find($request->id);
        
        $url = $url = 'reportes/cuenta.pdf';
        $reportePDF = PDF::loadView('reportes.cuentaPDF', compact('datos','totales' ,'totalUsdMonto', 'totalUsdFacturado', 'totalUsdPorFacturar', 'totalUsdPagado', 'totalUsdPendiente','totalMxnMonto' ,'totalMxnFacturado' ,'totalMxnPorFacturar' ,'totalMxnPendiente' ,'totalMxnPagado'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }

    public function  utilidadesexcel(Request $request){
        $datos = $request->datos;
        $dataF = [];
        foreach ($datos as $key => $dato) {
            if ($key == 0) {
                array_push($dataF, 
                    $data = array(
                        'NÚMERO DE COTIZACIÓN' => $dato[0],
                        'CLIENTE' => $dato[1],
                        'PROYECTO' => $dato[2],
                        'MONTO' => $dato[3],
                        'MONEDA' => $dato[4],
                        'NÚMERO DE COMPRA' => $dato[5],
                        'COSTO' => $dato[6],
                        'UTILIDAD' => $dato[7],
                        'TOTAL MXN VENTAS' => $request->totalMxnVentas,
                        'TOTAL MXN COSTO' => $request->totalMxnCosto,
                        'TOTAL MXN UTILIDAD' => $request->totalMxnUtilidad,
                        'TOTAL USD VENTAS' => $request->totalUsdVentas,
                        'TOTAL USD COSTO' => $request->totalUsdCosto,
                        'TOTAL USD UTILIDAD' => $request->totalUsdUtilidad,
                    )
                );    
            }
            else{
                array_push($dataF, 
                    $data = array(
                        'NÚMERO DE COTIZACIÓN' => $dato[0],
                        'CLIENTE' => $dato[1],
                        'PROYECTO' => $dato[2],
                        'MONTO' => $dato[3],
                        'MONEDA' => $dato[4],
                        'NÚMERO DE COMPRA' => $dato[5],
                        'COSTO' => $dato[6],
                        'UTILIDAD' => $dato[7],
                        'TOTAL MXN VENTAS' => '',
                        'TOTAL MXN COSTO' => '',
                        'TOTAL MXN UTILIDAD' => '',

                        'TOTAL USD VENTAS' => '',
                        'TOTAL USD COSTO' =>'',
                        'TOTAL USD UTILIDAD' =>'',
                    )
                );
            }
            
        }

        Excel::create('ReporteUtilidades', function($excel) use($dataF) {
 
            $excel->sheet('Utilidades', function($sheet) use($dataF){

                $sheet->fromArray($dataF);
 
            });
        })->store('xls',storage_path('app/public/reportes'));

    }

    public function  utilidadespdf(Request $request){
        $datos = $request->datos;
        $totalMxnVentas = $request->totalMxnVentas;
        $totalMxnCosto = $request->totalMxnCosto;
        $totalMxnUtilidad = $request->totalMxnUtilidad;
        $totalUsdVentas = $request->totalUsdVentas;
        $totalUsdCosto = $request->totalUsdCosto;
        $totalUsdUtilidad = $request->totalUsdUtilidad;
        $url = $url = 'reportes/utilidades.pdf';
        $reportePDF = PDF::loadView('reportes.utilidadesPDF', compact('datos', 'totalUsdVentas', 'totalUsdCosto', 'totalUsdUtilidad','totalMxnVentas','totalMxnCosto','totalMxnUtilidad'));
        Storage::disk('public')->put($url, $reportePDF->output());
    }


    public function cobros()
    {
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');

        $cobros = Pago::leftjoin('facturas', 'pagos.factura_id', '=', 'facturas.id')
            ->leftjoin('cuentas_cobrar', 'facturas.cuenta_id', '=', 'cuentas_cobrar.id')
            ->leftjoin('clientes', 'cuentas_cobrar.cliente_id', '=', 'clientes.id')
            ->select('cuentas_cobrar.*', 'clientes.nombre as cliente_nombre', 'facturas.documento', 'pagos.fecha as pago_fecha', 'pagos.monto as pago_monto','pagos.created_at')
            //->whereBetween('pagos.created_at', [$inicio, $fin])
            ->orderBy('pagos.fecha', 'desc')
            ->get();

        return view('reportes.cobros', compact('cobros'));
    }

    public function compras()
    {
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');

        $compras = OrdenCompra::leftjoin('proyectos_aprobados', 'ordenes_compra.proyecto_id', '=', 'proyectos_aprobados.id')
            ->leftjoin('clientes', 'ordenes_compra.cliente_id', '=', 'clientes.id')
            ->leftjoin('proveedores', 'ordenes_compra.proveedor_id', '=', 'proveedores.id')
            ->select('ordenes_compra.*', 'proyectos_aprobados.proyecto as proyecto_nombre', 'proyectos_aprobados.id as proyecto_id','proyectos_aprobados.cotizacion_id as cotizacion_id', 'clientes.nombre as cliente_nombre', 'proveedores.razon_social as proveedor_razon_social')
            ->where('ordenes_compra.status','Aprobada')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reportes.compras', compact('compras'));
    }

    public function pagos()
    {
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');

        $pagos = PagoCuentaPagar::leftjoin('facturas_cuentas_pagar', 'pagos_cuentas_pagar.factura_id', '=', 'facturas_cuentas_pagar.id')
            ->leftjoin('cuentas_pagar', 'facturas_cuentas_pagar.cuenta_id', '=', 'cuentas_pagar.id')
            ->leftjoin('proveedores', 'cuentas_pagar.proveedor_id', '=', 'proveedores.id')
            ->leftjoin('ordenes_compra', 'cuentas_pagar.orden_compra_id', '=', 'ordenes_compra.id')
            ->leftjoin('clientes', 'ordenes_compra.cliente_id', '=', 'clientes.id')
            ->select('cuentas_pagar.*', 'clientes.nombre as cliente_nombre', 'proveedores.razon_social as proveedor_nombre', 'ordenes_compra.proyecto_nombre as proyecto_nombre',
                'facturas_cuentas_pagar.documento', 'pagos_cuentas_pagar.fecha as pago_fecha', 'pagos_cuentas_pagar.monto as pago_monto', 'ordenes_compra.numero as numero_compra','pagos_cuentas_pagar.created_at')
            //->whereBetween('pagos_cuentas_pagar.created_at', [$inicio, $fin])
            ->orderBy('pagos_cuentas_pagar.fecha', 'desc')
            ->get();

        return view('reportes.pagos', compact('pagos'));
    }

    public function saldoProveedores()
    {
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');

        $saldos = PagoCuentaPagar::leftjoin('facturas_cuentas_pagar', 'pagos_cuentas_pagar.factura_id', '=', 'facturas_cuentas_pagar.id')
            ->leftjoin('cuentas_pagar', 'facturas_cuentas_pagar.cuenta_id', '=', 'cuentas_pagar.id')
            ->leftjoin('proveedores', 'cuentas_pagar.proveedor_id', '=', 'proveedores.id')
            ->leftjoin('ordenes_compra', 'cuentas_pagar.orden_compra_id', '=', 'ordenes_compra.id')
            ->leftjoin('clientes', 'ordenes_compra.cliente_id', '=', 'clientes.id')
            ->select('cuentas_pagar.*', 'clientes.nombre as cliente_nombre', 'proveedores.razon_social as proveedor_nombre', 'ordenes_compra.proyecto_nombre as proyecto_nombre',
                'facturas_cuentas_pagar.documento', 'facturas_cuentas_pagar.monto as facturas_monto', 'facturas_cuentas_pagar.vencimiento as facturas_fecha_vencimiento', 'pagos_cuentas_pagar.fecha as pago_fecha', 'pagos_cuentas_pagar.monto as pago_monto', 'ordenes_compra.numero as numero_compra','pagos_cuentas_pagar.created_at')
            //->whereBetween('pagos_cuentas_pagar.created_at', [$inicio, $fin])
            ->orderBy('facturas_cuentas_pagar.vencimiento', 'desc')
            ->get();

        return view('reportes.saldoProveedores', compact('saldos'));
    }

    public function cuentaCliente(Request $request)
    {
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');

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
        $inicio = Carbon::parse('2021-01-01');
        $fin = Carbon::parse('2022-12-31');

        $datos = OrdenCompra::leftjoin('proyectos_aprobados', 'ordenes_compra.proyecto_id', '=', 'proyectos_aprobados.id')
            ->leftjoin('prospectos_cotizaciones', 'proyectos_aprobados.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->leftjoin('clientes', 'proyectos_aprobados.cliente_id', '=', 'clientes.id')
            ->select('ordenes_compra.*', 'proyectos_aprobados.proyecto as proyecto_nombre', 'clientes.nombre as cliente_nombre',
                'prospectos_cotizaciones.id as cotizaciones_id', 'prospectos_cotizaciones.moneda as cotizaciones_moneda', 'prospectos_cotizaciones.total as cotizaciones_total')
            ->where('ordenes_compra.status','Confirmada')
            ->orWhere('ordenes_compra.status','Pendiente')
            //->whereBetween('ordenes_compra.created_at', [$inicio, $fin])
            ->orderBy('prospectos_cotizaciones.id', 'asc')
            ->get();

        return view('reportes.utilidades', compact('datos'));
    }
}
