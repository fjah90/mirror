<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatFormaPago extends Model
{
    protected $table='cat_forma_pago';

    protected $fillable =[

     'id',
     'codigo',
     'nombre',
     'bancarizado',
     'cta_beneficiaria',
     'cta_ordenante'
    ];


    protected $date =['created_at','updated_at'];
}
