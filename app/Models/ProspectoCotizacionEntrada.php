<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoCotizacionEntrada extends Model
{
    protected $table = 'prospectos_cotizaciones_entradas';

    protected $fillable = ['cotizacion_id','producto_id','cantidad','medida',
    'precio','importe','foto','observacion'];

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

    public function descripciones(){
      return $this->hasMany('App\Models\ProspectoCotizacionEntradaDescripcion', 'entrada_id', 'id');
    }

}
