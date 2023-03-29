<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class CategoriaCliente extends Model
{
    protected $table = 'categoria_cliente';
    protected $fillable = ['nombre'];


    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */
}
