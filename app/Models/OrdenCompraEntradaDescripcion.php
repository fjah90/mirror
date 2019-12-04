<?php

namespace App\Models;

use App\Model;

class OrdenCompraEntradaDescripcion extends Model
{
    protected $table = 'ordenes_compra_entradas_descripciones';

    protected $fillable = ['entrada_id', 'nombre', 'name', 'valor'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function entrada()
    {
        return $this->belongsTo('App\Models\OrdenCompraEntrada', 'entrada_id', 'id');
    }

}
