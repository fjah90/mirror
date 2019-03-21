<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProyectoAprobado extends Model
{
    protected $table = 'proyectos_aprobados';

    protected $fillable = ['cliente_id','cotizacion_id','cliente_nombre',
      'proyecto','moneda','proveedores'
    ];

    /**
     * Para regresar proveedores como array.
     *
     * @param  string  $value
     * @return array
     */
    public function getProveedoresAttribute($value)
    {
        return explode(",",$value);
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function cotizacion(){
      return $this->belongsTo('App\Models\ProspectoCotizacion', 'cotizacion_id', 'id');
    }

}
