<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenStatus extends Model
{
    protected $table = 'gen_status';

    protected $fillable = [

        'nombre_status','descripcion_status'
    ];
}
