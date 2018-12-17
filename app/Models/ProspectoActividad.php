<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoActividad extends Model
{
    protected $table = 'prospectos_actividades';

    protected $fillable = ['prospecto_id','tipo_id','fecha','descripcion','realizada'];

    protected $casts = [
      'realizada' => 'bool'
    ];

    protected $appends = [
      'fecha_formated'
    ];

    public function setFechaAttribute($value){
      list($dia, $mes, $ano) = explode('/', $value);
      $this->attributes['fecha'] = "$ano-$mes-$dia";
    }

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

    public function tipo(){
      return $this->belongsTo('App\Models\ProspectoTipoActividad', 'tipo_id', 'id');
    }

    public function productos_ofrecidos(){
      return $this->belongsToMany(
        'App\Models\Producto',
        'prospectos_actividades_productos',
        'actividad_id',
        'producto_id'
      )->withTimeStamps();
    }

}
