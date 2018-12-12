<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Material extends Model
{
    protected $table = 'materiales';

    protected $fillable = ['nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

}
