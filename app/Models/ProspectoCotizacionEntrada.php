<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoCotizacionEntrada extends Model
{
    protected $table = 'prospectos_cotizaciones_entradas';

    protected $fillable = ['cotizacion_id','producto_id','coleccion','diseno','color',
    'cantidad','precio','importe','observacion','foto','medida'];

    protected $casts = [
      'cantidad' => 'float',
      'precio' => 'float',
      'importe' => 'float'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cotizacion(){
      return $this->belongsTo('App\Models\ProspectoCotizacion', 'cotizacion_id', 'id');
    }

    public function producto(){
      return $this->belongsTo('App\Models\Producto', 'producto_id', 'id');
    }

}
