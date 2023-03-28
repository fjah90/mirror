<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
