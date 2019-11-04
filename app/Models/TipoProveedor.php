<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class TipoProveedor extends Model
{
    protected $table = 'tipos_proveedores';

    protected $fillable = ['nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

}
