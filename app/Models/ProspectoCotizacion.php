<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoCotizacion extends Model
{
    protected $table = 'prospectos_cotizaciones';

    protected $fillable = ['prospecto_id','condicion_id','fecha','subtotal','iva',
      'total','observaciones','notas','archivo','entrega','lugar','moneda','facturar',
      'user_id','idioma','aceptada','notas2','numero'
    ];

    protected $casts = [
      'subtotal' => 'float',
      'iva' => 'float',
      'total' => 'float',
      'aceptada' => 'boolean'
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

    public function condiciones(){
      return $this->belongsTo('App\Models\CondicionCotizacion', 'condicion_id', 'id');
    }

    public function user(){
      return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function entradas(){
      return $this->hasMany('App\Models\ProspectoCotizacionEntrada','cotizacion_id','id');
    }

}
