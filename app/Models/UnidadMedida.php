<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class UnidadMedida extends Model
{
    protected $table = 'unidades_medida';

    protected $fillable = ['simbolo','nombre','simbolo_ingles','nombre_ingles'];

    public function conversiones(){
      return $this->hasMany('App\Models\UnidadMedidaConversion', 'unidad_medida_id', 'id');
    }

}
