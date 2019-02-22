<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Prospecto extends Model
{
    protected $table = 'prospectos';

    protected $fillable = ['cliente_id','nombre','descripcion'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function actividades(){
      return $this->hasMany('App\Models\ProspectoActividad', 'prospecto_id', 'id');
    }

    public function cotizaciones(){
      return $this->hasMany('App\Models\ProspectoCotizacion', 'prospecto_id', 'id');
    }

    public function ultima_actividad(){
      return $this->hasOne('App\Models\ProspectoActividad', 'prospecto_id', 'id')
        ->where('realizada', 1)->orderBy('id', 'desc');
    }

    public function proxima_actividad(){
      return $this->hasOne('App\Models\ProspectoActividad', 'prospecto_id', 'id')
        ->where('realizada', 0)->orderBy('id', 'desc');
    }

}
