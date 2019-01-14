<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoCotizacion extends Model
{
    protected $table = 'prospectos_cotizaciones';

    protected $fillable = ['prospecto_id','fecha','subtotal','iva','total',
    'observaciones','archivo','entrega','condiciones','precios'];

    protected $casts = [
      'subtotal' => 'float',
      'iva' => 'float',
      'total' => 'float'
    ];

    protected $appends = [
      'fecha_formated'
    ];

    public function getFechaFormatedAttribute(){
      return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function prospecto(){
      return $this->belongsTo('App\Models\Prospecto', 'prospecto_id', 'id');
    }

    public function entradas(){
      return $this->hasMany('App\Models\ProspectoCotizacionEntrada','cotizacion_id','id');
    }

}
