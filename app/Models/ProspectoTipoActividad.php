<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoTipoActividad extends Model
{
    protected $table = 'prospectos_tipos_actividades';

    protected $fillable = ['nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function actividades(){
      return $this->hasMany('App\Models\ProspectoActividad', 'tipo_id', 'id');
    }

}
