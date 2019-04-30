<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre','name'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function descripciones(){
      return $this->hasMany('App\Models\CategoriaDescripcion', 'categoria_id')
        ->orderBy('ordenamiento', 'asc');
    }

}
