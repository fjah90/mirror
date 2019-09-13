<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoCotizacion extends Model
{
    protected $table = 'prospectos_cotizaciones';

    protected $fillable = ['prospecto_id','condicion_id','fecha','subtotal','iva',
      'total','observaciones','notas','archivo','entrega','lugar','moneda','facturar',
      'user_id','idioma','aceptada','notas2','numero','rfc','razon_social','calle',
      'nexterior','ninterior','colonia','cp','ciudad','estado'
    ];

    protected $casts = [
      'subtotal' => 'float',
      'iva' => 'float',
      'total' => 'float',
      'aceptada' => 'boolean',
      'facturar' => 'boolean'
    ];

    protected $appends = [
      'fecha_formated', 'direccion'
    ];

    public function getFechaFormatedAttribute(){
      return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    public function getDireccionAttribute(){
      return $this->calle." ".$this->nexterior.(($this->ninterior)?" Int. ".$this->ninterior:"")." "
      .$this->colonia." ".$this->cp." ".$this->ciudad." ".$this->estado." ".$this->pais;
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
      return $this->belongsTo('App\Models\CondicionCotizacion', 'condicion_id', 'id')
      ->withDefault(['nombre' => '']);
    }

    public function user(){
      return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function entradas(){
      return $this->hasMany('App\Models\ProspectoCotizacionEntrada','cotizacion_id','id')
      ->orderBy('orden','asc');
    }

}
