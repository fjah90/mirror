<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class SubProyecto extends Model
{
    protected $table = 'subproyectos';

    protected $fillable = ['proyecto_id','nombre'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    /*public function proyecto(){
      return $this->belongsTo('App\Models\Proyecto', 'proyecto_id', 'id');
    }*/

}
