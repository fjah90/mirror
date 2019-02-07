<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProductoDescripcion extends Model
{
    protected $table = 'productos_descripciones';

    protected $fillable = ['producto_id','categoria_descripcion_id','valor'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function descripcionNombre(){
      return $this->belongsTo('App\Models\CategoriaDescripcion', 'categoria_descripcion_id');
    }

    public function producto(){
      return $this->belongsTo('App\Models\Producto', 'producto_id');
    }

}
