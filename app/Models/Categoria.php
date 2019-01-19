<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

}
