<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatRegimen extends Model
{
    protected $table='cat_regimen';

    protected $fillable =[

     'id',
     'clave',
     'descripcion',
     'persona'
    ];

    protected $date =['created_at','updated_at'];
}
