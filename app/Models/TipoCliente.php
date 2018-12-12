<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class TipoCliente extends Model
{
    protected $table = 'tipos_clientes';

    protected $fillable = ['nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

}
