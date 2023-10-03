<?php

namespace App\Models;

use App\Model;

class ProspectoCotizacionEntradaDescripcion extends Model
{
    protected $table = 'prospectos_cotizaciones_entradas_descripciones';

    protected $fillable = ['entrada_id', 'nombre', 'name', 'valor', 'icono_visible', 'valor_ingles'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function entrada()
    {
        return $this->belongsTo('App\Models\ProspectoCotizacionEntrada', 'entrada_id', 'id');
    }

}
