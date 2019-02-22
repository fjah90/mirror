<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = ['proveedor_id','categoria_id','nombre','foto'];

    /**
     * ---------------------------------------------------------------------------
     *                             Agregates
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

    public function categoria(){
      return $this->belongsTo('App\Models\Categoria', 'categoria_id', 'id');
    }

    public function descripciones(){
      return $this->hasMany('App\Models\ProductoDescripcion', 'producto_id', 'id')
        ->orderBy('categoria_descripcion_id', 'asc');
    }

}
