<?php

namespace App\Models;

use App\Model;
use App\User;

class Prospecto extends Model
{
    protected $table = 'prospectos';

    protected $fillable = ['cliente_id','nombre','descripcion', 'user_id'];

    protected $appends = [
        'num_cotizaciones','num_cotaprobadas'
    ];


    public function getNumCotizacionesAttribute()
    {
        return count($this->cotizaciones);
    }

    public function getNumCotaprobadasAttribute()
    {
        return count($this->cotizaciones_aprobadas);
    }

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

    public function cotizaciones_aprobadas(){
      return $this->hasMany('App\Models\ProspectoCotizacion', 'prospecto_id', 'id')->where('aceptada',1);
    }

    public function ultima_actividad(){
      return $this->hasOne('App\Models\ProspectoActividad', 'prospecto_id', 'id')
        ->where('realizada', 1)->orderBy('id', 'desc');
    }

    public function proxima_actividad(){
      return $this->hasOne('App\Models\ProspectoActividad', 'prospecto_id', 'id')
        ->where('realizada', 0)->orderBy('id', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
