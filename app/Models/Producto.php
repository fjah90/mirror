<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = ['proveedor_id','material_id','composicion'];

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

    public function proveedor(){
      return $this->belongsTo('App\Models\Proveedor', 'proveedor_id', 'id');
    }

    public function material(){
      return $this->belongsTo('App\Models\Material', 'material_id', 'id');
    }

}
