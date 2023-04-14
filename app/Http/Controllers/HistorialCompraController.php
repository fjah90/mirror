<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CuentaCobrar;
use App\Models\OrdenCompra;
use App\Models\Pago;
use App\Models\PagoCuentaPagar;
use App\Models\ProspectoCotizacion;
use App\Models\Producto;
use App\Models\ProspectoCotizacionEntrada;
use Mail;
use App\User;
use Storage;
use DateTime;
class HistorialCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //$clientes = Cliente::orderBy('nombre','asc')->get();
        $productos = Producto::with('categoria')->get();
        //dd($productos);
        $clientes = OrdenCompra::join('proyectos_aprobados', 'ordenes_compra.proyecto_id', '=', 'proyectos_aprobados.id')
            ->join('prospectos_cotizaciones', 'proyectos_aprobados.cotizacion_id', '=', 'prospectos_cotizaciones.id')
            ->join('clientes', 'proyectos_aprobados.cliente_id', '=', 'clientes.id')
            ->join('users', 'prospectos_cotizaciones.user_id', '=', 'users.id')
            ->select('ordenes_compra.*', 'proyectos_aprobados.proyecto as proyecto_nombre', 'clientes.nombre as cliente_nombre',
                'prospectos_cotizaciones.id as cotizaciones_id', 'prospectos_cotizaciones.moneda as cotizaciones_moneda', 'prospectos_cotizaciones.total as cotizaciones_total','users.name as nombre_usuario')
            ->where('ordenes_compra.status','Confirmada')
            ->orWhere('ordenes_compra.status','Pendiente')
            ->orderBy('prospectos_cotizaciones.id', 'asc')
            ->get();
        //dd($clientes);

        return view ('catalogos.historialCompra.index',compact('clientes','productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $clientes = Cliente::orderBy('nombre','asc')->get();
        return view ('catalogos.historialCompra.create',compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
