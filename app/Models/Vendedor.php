<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
        'nombre', 'presupuesto_anual', 'comision_base', 'pago_comision'
    ];

}
