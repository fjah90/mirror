<?php

namespace App\Models;

use App\Model;

class DatoFacturacion extends Model
{
    protected $table = 'datos_facturacion';

    protected $fillable = ['rfc', 'razon_social', 'calle',
        'nexterior', 'ninterior', 'colonia', 'cp', 'ciudad', 'estado', 'cliente_id',
    ];

    protected $appends = ['direccion'];

    public function getDireccionAttribute()
    {
        return $this->calle . " #" . $this->nexterior . " " . $this->ciudad . " " . $this->estado;
    }
    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }
}
