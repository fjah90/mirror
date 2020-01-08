<?php

namespace App\Http\Controllers;

use App\Models\ProspectoCotizacion;

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
            ->select('prospectos_cotizaciones.*', 'prospectos.nombre as prospecto_nombre', 'prospectos.id as prospecto_id', 'clientes.nombre as cliente_nombre')->get();

        return view('reportes.cotizaciones', compact('cotizaciones'));
    }
}
