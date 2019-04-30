<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class CategoriaDescripcion extends Model
{
    protected $table = 'categorias_descripciones';

    protected $fillable = ['categoria_id','nombre','name','ordenamiento'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function categoria(){
      return $this->belognsTo('App\Models\Categoria', 'categoria_id');
    }

}
