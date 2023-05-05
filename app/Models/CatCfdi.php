<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatCfdi extends Model
{
    protected $table='cat_cfdi';

    protected $fillable =[

     'id',
     'clave',
     'descripcion'
    ];

    protected $date =['created_at','updated_at'];
}
