<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;
use App\User;

class Prospecto extends Model
{
    protected $table = 'prospectos';

    protected $fillable = ['cliente_id','nombre','descripcion', 'user_id','es_prospecto','vendedor_id','proyeccion_venta','fecha_cierre','factibilidad','estatus'];

    protected $appends = [
        'num_cotizaciones','num_cotaprobadas','fecha_cierre_formated'
    ];


    public function getNumCotizacionesAttribute()
    {
        return count($this->cotizaciones);
    }

    public function getNumCotaprobadasAttribute()
    {
        return count($this->cotizaciones_aprobadas);
    }

    public function getFechaCierreFormatedAttribute()
    {
        return Carbon::parse($this->fecha_cierre)->format('d/m/Y');
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id')->withTrashed();
    }

    public function usuario(){
      return $this->belongsTo(User::class, 'user_id','id');
    }
    
    public function actividades(){
      return $this->hasMany('App\Models\ProspectoActividad', 'prospecto_id', 'id')->orderBy('fecha','asc');
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
        ->where('realizada', 0)->orderBy('id', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

}
