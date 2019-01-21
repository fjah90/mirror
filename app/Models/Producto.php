<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = ['proveedor_id','categoria_id','composicion','diseño',
    'coleccion','descripcion1','descripcion2','descripcion3','descripcion4',
    'descripcion5','descripcion6','foto'];

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

    public function categoria(){
      return $this->belongsTo('App\Models\Categoria', 'categoria_id', 'id');
    }

}
