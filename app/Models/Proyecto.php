<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    protected $fillable = ['nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

}
