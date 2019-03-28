<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class OrdenProceso extends Model
{
    protected $table = 'ordenes_proceso';

    protected $fillable = ['orden_compra_id'];

}
