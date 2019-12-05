<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class OrdenCompraEntrada extends Model
{
    protected $table = 'ordenes_compra_entradas';

    protected $fillable = ['orden_id','producto_id','cantidad','medida','conversion',
    'cantidad_convertida','precio','importe'];

    protected $casts = [
      'cantidad' => 'float',
      'cantidad_convertida' => 'float',
      'precio' => 'float',
      'importe' => 'float'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function orden(){
      return $this->belongsTo('App\Models\OrdenCompra', 'orden_id', 'id');
    }

    public function producto(){
      return $this->belongsTo('App\Models\Producto', 'producto_id', 'id');
    }

    public function descripciones(){
      return $this->hasMany('App\Models\OrdenCompraEntradaDescripcion', 'entrada_id', 'id');
    }
}
