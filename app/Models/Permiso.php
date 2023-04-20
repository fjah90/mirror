<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'rol_id', 'modulo', 'menu', 'crear','editar','ver','eliminar'
    ];    
  

}
