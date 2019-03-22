<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';

    protected $fillable = ['cliente_id','proyecto_id','proveedor_id',
      'orden_proceso_id','cliente_nombre','proyecto_nombre','proveedor_empresa',
      'status','orden_proceso_status','moneda','subtotal','iva','total',
      'motivo_rechazo'
    ];

    protected $casts = [
      'subtotal' => 'float',
      'iva' => 'float',
      'total' => 'float'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function proyecto(){
      return $this->belongsTo('App\Models\ProyectoAprobado', 'proyecto_id', 'id');
    }

    public function proveedor(){
      return $this->belongsTo('App\Models\Proveedor', 'proveedor_id', 'id');
    }

    public function entradas(){
      return $this->hasMany('App\Models\OrdenCompraEntrada', 'orden_id', 'id');
    }

}
