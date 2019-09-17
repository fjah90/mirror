<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class AgenteAduanal extends Model
{
  protected $table = 'agentes_aduanales';

  protected $fillable = ['compañia','direccion','telefono','email','contacto'];

}
