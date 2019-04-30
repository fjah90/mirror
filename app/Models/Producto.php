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
     *                          Private Methods
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

    // Modelo duplicado, esta aqui para recordarme que laravel re-llama los metodos
    // de relaciones cuando parsea de eloquent a json
    // public function descripciones(){
    //   return $this->hasMany('App\Models\ProductoDescripcion', 'producto_id', 'id');
    // }

    public function descripciones(){
      return $this->hasMany('App\Models\ProductoDescripcion', 'producto_id', 'id')
      ->join('categorias_descripciones',
        'productos_descripciones.categoria_descripcion_id', '=', 'categorias_descripciones.id')
      ->orderBy('categorias_descripciones.ordenamiento','asc');
    }

}
