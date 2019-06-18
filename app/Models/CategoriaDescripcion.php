<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class CategoriaDescripcion extends Model
{
    protected $table = 'categorias_descripciones';

    protected $fillable = ['categoria_id','nombre','name','ordenamiento','no_alta_productos'];

    protected $casts = [
      'no_alta_productos' => 'boolean'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function categoria(){
      return $this->belognsTo('App\Models\Categoria', 'categoria_id');
    }

}
